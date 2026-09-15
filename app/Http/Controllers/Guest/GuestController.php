<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuestController extends Controller
{
    /**
     * Redirect Root / to /shop as main shop entrance.
     */
    public function home(): RedirectResponse
    {
        return redirect()->route('shop');
    }

    /**
     * Display Shop Page with real database products, search, category filter, and sorting.
     */
    public function shop(Request $request): View
    {
        $query = Product::active()->with('category')->withAvg('reviews', 'rating')->withCount('reviews');

        // Search by Product Name or SKU
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category Filter (only active categories)
        $selectedCategory = null;
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $selectedCategory = Category::where('slug', $categorySlug)->where('status', 'active')->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Sorting Options
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 'active')->latest()->get();

        return view('guest.shop.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'sort'
        ));
    }

    /**
     * Display Product Detail with real database product & related items.
     */
    public function product(string $slug)
    {
        $product = Product::active()->with('category')->withAvg('reviews', 'rating')->withCount('reviews')->where('slug', $slug)->first();

        if (!$product) {
            return redirect()->route('shop')
                ->with('warning', 'Produk tidak ditemukan atau kategori produk sedang dinonaktifkan.');
        }

        $reviews = $product->reviews()->with('user')->latest()->paginate(10);

        // Rating breakdown statistics
        $totalReviews = $product->reviews_count ?? $product->reviews()->count();
        $ratingStats = [
            5 => $totalReviews > 0 ? $product->reviews()->where('rating', 5)->count() : 0,
            4 => $totalReviews > 0 ? $product->reviews()->where('rating', 4)->count() : 0,
            3 => $totalReviews > 0 ? $product->reviews()->where('rating', 3)->count() : 0,
            2 => $totalReviews > 0 ? $product->reviews()->where('rating', 2)->count() : 0,
            1 => $totalReviews > 0 ? $product->reviews()->where('rating', 1)->count() : 0,
        ];

        $relatedProducts = Product::active()->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('guest.product.show', compact('product', 'relatedProducts', 'reviews', 'ratingStats', 'totalReviews'));
    }

    /**
     * Display Category Page by redirecting to shop with category filter.
     */
    public function category(string $slug): RedirectResponse
    {
        $selectedCategory = Category::where('slug', $slug)->where('status', 'active')->first();
        if (!$selectedCategory) {
            return redirect()->route('shop')->with('warning', 'Kategori tidak ditemukan atau sedang dinonaktifkan.');
        }

        return redirect()->route('shop', ['category' => $slug]);
    }

    /**
     * Display About Page.
     */
    public function about(): View
    {
        return view('guest.about.index');
    }

    /**
     * Display Contact Page.
     */
    public function contact(): View
    {
        return view('guest.contact.index');
    }
}