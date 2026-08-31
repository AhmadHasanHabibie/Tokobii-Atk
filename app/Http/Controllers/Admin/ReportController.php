<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of reports with real statistics, search, category & status filters.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');
        $query = Report::with(['user', 'product.category', 'order'])->latest();

        // Global Search across Customer, Invoice, Product, and Issue description
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('invoice_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $categoryId = $request->input('category');
            $query->whereHas('product', function ($pq) use ($categoryId) {
                $pq->where('category_id', $categoryId);
            });
        }

        // Filter by Product
        if ($request->filled('product')) {
            $query->where('product_id', $request->input('product'));
        }

        // Filter by Status (pending, replied, resolved)
        if ($request->filled('status') && in_array($statusFilter, ['pending', 'replied', 'resolved'])) {
            $query->where('status', $statusFilter);
        }

        $reports = $query->paginate(15)->withQueryString();

        // Real Database Statistics (Safe on empty DB)
        $totalSales = (int) (Order::where(function ($q) {
            $q->where('order_status', 'completed')
              ->orWhere('payment_status', 'paid');
        })->sum('grand_total') ?? 0);

        $stats = [
            'sales' => $totalSales,
            'products' => Product::count(),
            'customers' => User::where('role', 'customer')->count(),
            'orders' => Order::count(),
            'reports' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'replied' => Report::where('status', 'replied')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
        ];

        $totalReports = $stats['reports'];
        $pendingReports = $stats['pending'];
        $repliedReports = $stats['replied'];
        $resolvedReports = $stats['resolved'];

        $categories = Category::withCount('reports')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.reports.index', compact(
            'reports',
            'stats',
            'categories',
            'products',
            'totalReports',
            'pendingReports',
            'repliedReports',
            'resolvedReports',
            'statusFilter'
        ));
    }

    /**
     * Display reports grouped by Category.
     */
    public function category(Category $category): View
    {
        $products = $category->products()->withCount('reports')->orderBy('name')->paginate(12);
        return view('admin.reports.category', compact('category', 'products'));
    }

    /**
     * Display reports for a specific Product.
     */
    public function product(Product $product): View
    {
        $reports = $product->reports()->with(['user', 'order'])->latest()->paginate(10);
        $product->load('category')->loadCount('reports');
        return view('admin.reports.product', compact('product', 'reports'));
    }

    /**
     * Display the specified report detail ticket.
     */
    public function show(Report $report): View
    {
        $report->load(['user', 'order', 'orderItem', 'product.category', 'repliedBy']);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Reply to a report ticket and automatically update its status to 'replied' (Sudah Dibalas).
     */
    public function reply(Request $request, Report $report): RedirectResponse
    {
        $data = $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ], [
            'admin_reply.required' => 'Tanggapan wajib diisi.',
            'admin_reply.string' => 'Tanggapan harus berupa teks.',
            'admin_reply.max' => 'Tanggapan maksimal 2000 karakter.',
        ]);

        $report->update([
            'admin_reply' => $data['admin_reply'],
            'status' => 'replied',
            'replied_by' => auth()->id(),
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Tanggapan keluhan berhasil dikirim.');
    }

    /**
     * Mark a report ticket as resolved (Selesai).
     */
    public function resolve(Report $report): RedirectResponse
    {
        $report->update([
            'status' => 'resolved',
        ]);

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Laporan masalah berhasil diselesaikan.');
    }
}
