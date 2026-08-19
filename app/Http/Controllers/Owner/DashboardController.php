<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the owner dashboard.
     */
    public function index(): View
    {
        // Metric counts
        $totalRevenue = Order::where(function ($q) {
            $q->where('order_status', 'completed')
              ->orWhere('payment_status', 'paid');
        })->sum('grand_total');

        $totalOrders = Order::count();
        $completedOrders = Order::where('order_status', 'completed')->count();
        $pendingOrders = Order::where(function ($q) {
            $q->where('order_status', 'pending')
              ->orWhere('payment_status', 'pending');
        })->count();

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(6)
            ->get();

        // Top products
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
            'topProducts'
        ));
    }
}