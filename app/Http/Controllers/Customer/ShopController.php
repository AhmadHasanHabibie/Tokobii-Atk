<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Display a listing of products in the shop with search, category filter, sorting, and pagination.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        // Search by Product Name or SKU
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category Filter
        $selectedCategory = null;
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $selectedCategory = Category::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::latest()->get();

        return view('customer.shop.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'sort'
        ));
    }

    /**
     * Display products for a specific category.
     */
    public function category(string $slug, Request $request): View
    {
        $selectedCategory = Category::where('slug', $slug)->firstOrFail();
        $query = Product::with('category')->where('category_id', $selectedCategory->id);

        // Search by Product Name or SKU
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::latest()->get();

        return view('customer.shop.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'sort'
        ));
    }

    /**
     * Display the specified product detail.
     */
    public function show(string $slug): View
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();

        // Related Products (Same category max 4, fallback to latest if empty)
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $excludeIds = $relatedProducts->pluck('id')->push($product->id);
            $additionalProducts = Product::with('category')
                ->whereNotIn('id', $excludeIds)
                ->latest()
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($additionalProducts);
        }

        return view('customer.shop.show', compact('product', 'relatedProducts'));
    }
}
