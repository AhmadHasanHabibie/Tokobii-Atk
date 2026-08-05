<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GuestController extends Controller
{
    /**
     * Display Home Page.
     */
    public function home(): View
    {
        return view('guest.home.index');
    }

    /**
     * Display Shop Page.
     */
    public function shop(): View
    {
        return view('guest.shop.index');
    }

    /**
     * Display Product Detail.
     */
    public function product(string $slug): View
    {
        return view('guest.product.show', [
            'slug' => $slug,
        ]);
    }

    /**
     * Display Category Page.
     */
    public function category(string $slug): View
    {
        return view('guest.category.index', [
            'slug' => $slug,
        ]);
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