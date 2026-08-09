<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real operational statistics from the database.
     */
    public function index(): View
    {
        // Real Counts from Database
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();

        // Status Breakdown Stats
        $waitingVerificationOrders = Order::where('payment_status', 'waiting_verification')->count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $readyForPickupOrders = Order::where('order_status', 'ready_for_pickup')->count();
        $completedOrders = Order::where('order_status', 'completed')->count();

        // Real Revenue calculated from Completed & Paid Orders
        $totalRevenue = Order::where(function ($q) {
            $q->where('order_status', 'completed')
              ->orWhere('payment_status', 'paid');
        })->sum('grand_total');

        // Recent 5 Orders
        $recentOrders = Order::with(['user', 'items.product', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        // Low Stock Alert Products (Stock <= 10)
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalCategories',
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'waitingVerificationOrders',
            'paidOrders',
            'readyForPickupOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}