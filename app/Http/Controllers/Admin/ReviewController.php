<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews.index', ['categories' => Category::withCount('products')->orderBy('name')->get()]);
    }

    public function category(Category $category): View
    {
        $products = $category->products()->withAvg('reviews', 'rating')->withCount('reviews')->orderBy('name')->paginate(12);
        return view('admin.reviews.category', compact('category', 'products'));
    }

    public function product(Request $request, Product $product): View
    {
        $query = $product->reviews()->with(['user', 'order'])->latest();
        if (in_array($request->rating, ['1', '2', '3', '4', '5'])) $query->where('rating', $request->rating);
        if ($request->sort === 'oldest') $query->oldest();
        $reviews = $query->paginate(10)->withQueryString();
        $product->load('category')->loadCount('reviews')->loadAvg('reviews', 'rating');
        return view('admin.reviews.product', compact('product', 'reviews'));
    }
}
