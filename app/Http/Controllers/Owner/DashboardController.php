<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the owner dashboard with comprehensive sales reporting by period.
     */
    public function index(Request $request): View
    {
        // 1. All-time macro metrics
        $totalRevenue = (float) (Order::where(function ($q) {
            $q->where('order_status', 'completed')
              ->orWhere('payment_status', 'paid');
        })->sum('grand_total') ?? 0);

        $totalOrders = Order::count();
        $completedOrders = Order::where('order_status', 'completed')->count();
        $pendingOrders = Order::where(function ($q) {
            $q->where('order_status', 'pending')
              ->orWhere('payment_status', 'pending');
        })->count();

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // 2. Period filtering for sales report (Daily, Weekly, Monthly)
        $period = $request->input('period', 'daily');
        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            $period = 'daily';
        }

        $currentDate = now();
        $dateInput = $request->input('date', $currentDate->toDateString());
        $weekDateInput = $request->input('week_date', $currentDate->toDateString());
        $monthInput = $request->input('month', $currentDate->format('Y-m'));

        switch ($period) {
            case 'weekly':
                $refDate = Carbon::parse($weekDateInput);
                $startDate = $refDate->copy()->startOfWeek();
                $endDate = $refDate->copy()->endOfWeek();
                $periodTitle = 'Laporan Penjualan Mingguan';
                $periodLabel = 'Minggu: ' . $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
                break;

            case 'monthly':
                $refMonth = Carbon::parse($monthInput . '-01');
                $startDate = $refMonth->copy()->startOfMonth();
                $endDate = $refMonth->copy()->endOfMonth();
                $periodTitle = 'Laporan Penjualan Bulanan';
                $periodLabel = 'Bulan: ' . $refMonth->translatedFormat('F Y');
                break;

            case 'daily':
            default:
                $refDay = Carbon::parse($dateInput);
                $startDate = $refDay->copy()->startOfDay();
                $endDate = $refDay->copy()->endOfDay();
                $periodTitle = 'Laporan Penjualan Harian';
                $periodLabel = 'Hari/Tanggal: ' . $refDay->translatedFormat('l, d F Y');
                break;
        }

        // Base query for valid period sales (paid or completed)
        $periodOrdersQuery = Order::with(['user', 'items.product.category'])
            ->where(function ($q) {
                $q->where('order_status', 'completed')
                  ->orWhere('payment_status', 'paid');
            })
            ->whereBetween('created_at', [$startDate, $endDate]);

        $periodRevenue = (float) ((clone $periodOrdersQuery)->sum('grand_total') ?? 0);
        $periodOrdersCount = (int) (clone $periodOrdersQuery)->count();

        $periodOrderIds = (clone $periodOrdersQuery)->pluck('id');
        $periodItemsSold = (int) (OrderItem::whereIn('order_id', $periodOrderIds)->sum('qty') ?? 0);
        $averageOrderValue = $periodOrdersCount > 0 ? round($periodRevenue / $periodOrdersCount) : 0;

        // Payment breakdown in period
        $periodQrisRevenue = (float) ((clone $periodOrdersQuery)->where('payment_method', 'qris')->sum('grand_total') ?? 0);
        $periodQrisCount = (int) (clone $periodOrdersQuery)->where('payment_method', 'qris')->count();
        $periodCashRevenue = (float) ((clone $periodOrdersQuery)->where('payment_method', 'cash')->sum('grand_total') ?? 0);
        $periodCashCount = (int) (clone $periodOrdersQuery)->where('payment_method', 'cash')->count();

        // Top products in period
        $periodTopProducts = OrderItem::whereIn('order_id', $periodOrderIds)
            ->select('product_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Recent orders in period
        $periodOrdersList = (clone $periodOrdersQuery)
            ->latest()
            ->take(8)
            ->get();

        // Macro recent orders
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(6)
            ->get();

        // Top products all-time
        $topProducts = Product::with('category')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'totalProducts',
            'totalCategories',
            'totalCustomers',
            'recentOrders',
            'topProducts',
            'period',
            'periodTitle',
            'periodLabel',
            'startDate',
            'endDate',
            'dateInput',
            'weekDateInput',
            'monthInput',
            'periodRevenue',
            'periodOrdersCount',
            'periodItemsSold',
            'averageOrderValue',
            'periodQrisRevenue',
            'periodQrisCount',
            'periodCashRevenue',
            'periodCashCount',
            'periodTopProducts',
            'periodOrdersList'
        ));
    }
}