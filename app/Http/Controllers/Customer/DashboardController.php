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

        // Real categories data from Admin (max 8)
        $categories = Category::latest()->take(8)->get();
        $totalCategories = Category::count();

        // Real products data from Admin (latest & popular placeholder)
        $newProducts = Product::with('category')->withAvg('reviews', 'rating')->withCount('reviews')->latest()->take(8)->get();
        $popularProducts = Product::withAvg('reviews', 'rating')->withCount('reviews')->latest()->take(4)->get();

        return view('customer.dashboard.index', compact(
            'greeting',
            'categories',
            'totalCategories',
            'newProducts',
            'popularProducts'
        ));
    }
}
