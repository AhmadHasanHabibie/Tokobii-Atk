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
    public function index(Request $request): View
    {
        $categories = Category::withCount('reports')->orderBy('name')->get();
        $products = Product::orderBy('name')->get(['id', 'name']);
        $query = Report::with(['user', 'product.category', 'order'])->latest();

        if ($request->filled('category')) $query->whereHas('product', fn ($q) => $q->where('category_id', $request->category));
        if ($request->filled('product')) $query->where('product_id', $request->product);
        if (in_array($request->status, ['pending', 'replied', 'resolved'])) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('order', fn ($order) => $order->where('invoice_number', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn ($product) => $product->where('name', 'like', "%{$search}%"));
            });
        }

        $stats = [
            'sales' => Order::where('order_status', 'completed')->sum('grand_total'),
            'products' => Product::count(), 'customers' => User::where('role', 'customer')->count(),
            'orders' => Order::count(), 'reports' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(), 'replied' => Report::where('status', 'replied')->count(),
        ];
        $reports = $query->paginate(10)->withQueryString();
        return view('admin.reports.index', compact('categories', 'products', 'reports', 'stats'));
    }

    public function category(Category $category): View
    {
        $products = $category->products()->withCount('reports')->orderBy('name')->paginate(12);
        return view('admin.reports.category', compact('category', 'products'));
    }

    public function product(Product $product): View
    {
        $product->load('category')->loadCount('reports');
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
        $data = $request->validate(['admin_reply' => 'required|string|max:2000']);
        $replied = DB::transaction(function () use ($report, $data) {
            $report = Report::lockForUpdate()->findOrFail($report->id);
            if ($report->admin_reply !== null || $report->replied_at !== null) {
                return false;
            }
            $report->update($data + [
                'status' => 'replied', 'replied_by' => auth()->id(), 'replied_at' => now(),
            ]);
            return true;
        });

        if (! $replied) {
            return redirect()->route('admin.reports.show', $report)
                ->with('warning', 'Balasan Admin untuk laporan ini sudah pernah dikirim.');
        }
        return redirect()->route('admin.reports.show', $report)->with('success', 'Balasan Admin berhasil dikirim.');
    }

    public function resolve(Report $report): RedirectResponse
    {
        $report->update(['status' => 'resolved']);
        return redirect()->route('admin.reports.show', $report)->with('success', 'Laporan ditandai selesai.');
    }
}
