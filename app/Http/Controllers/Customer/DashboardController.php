<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard main page.
     */
    public function index(): View
    {
        // Dynamic server-time greeting
        $hour = (int) date('H');
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        // Real active categories data (max 8)
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->latest()
            ->take(8)
            ->get();
        $totalCategories = Category::where('status', 'active')->count();

        // Real active products data (latest & popular)
        $newProducts = Product::active()->with('category')->withAvg('reviews', 'rating')->withCount('reviews')->latest()->take(8)->get();
        $popularProducts = Product::active()->with('category')->withAvg('reviews', 'rating')->withCount('reviews')->latest()->take(4)->get();

        return view('customer.dashboard.index', compact(
            'greeting',
            'categories',
            'totalCategories',
            'newProducts',
            'popularProducts'
        ));
    }
}
