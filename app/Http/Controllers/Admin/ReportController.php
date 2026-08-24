<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');
        $query = Report::with(['user', 'product', 'order'])->latest();

        if (in_array($statusFilter, ['pending', 'replied'])) {
            $query->where('status', $statusFilter);
        }

        $reports = $query->paginate(15)->withQueryString();
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $repliedReports = Report::where('status', 'replied')->count();

        return view('admin.reports.index', compact('reports', 'totalReports', 'pendingReports', 'repliedReports', 'statusFilter'));
    }

    public function product(Product $product): View
    {
        $reports = $product->reports()->with(['user', 'order'])->latest()->paginate(10);
        return view('admin.reports.product', compact('product', 'reports'));
    }

    public function show(Report $report): View
    {
        $report->load(['user', 'order', 'orderItem', 'product.category', 'repliedBy']);
        return view('admin.reports.show', compact('report'));
    }

    public function reply(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ], [
            'admin_reply.required' => 'Tanggapan atau balasan admin wajib diisi.',
            'admin_reply.string' => 'Tanggapan atau balasan admin harus berupa teks.',
            'admin_reply.max' => 'Tanggapan atau balasan admin maksimal 2000 karakter.',
        ]);

        $replied = DB::transaction(function () use ($report, $data) {
            $report = Report::lockForUpdate()->findOrFail($report->id);
            if ($report->admin_reply !== null || $report->replied_at !== null) {
                return false;
            }
            $report->update($data + [
                'status' => 'replied',
                'replied_by' => auth()->id(),
                'replied_at' => now(),
            ]);
            return true;
        });

        if (!$replied) {
            return redirect()->route('admin.reports.show', $report)
                ->with('error', 'Laporan keluhan ini sudah ditanggapi sebelumnya.');
        }

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Tanggapan keluhan berhasil dikirim.');
    }
}
