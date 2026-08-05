<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Shop & Catalog
        |--------------------------------------------------------------------------
        */

        Route::get('/shop', [ShopController::class, 'index'])
            ->name('shop.index');

        Route::get('/shop/category/{slug}', [ShopController::class, 'category'])
            ->name('shop.category');

        Route::get('/shop/product/{slug}', [ShopController::class, 'show'])
            ->name('shop.show');

        /*
        |--------------------------------------------------------------------------
        | Cart (Module 1)
        |--------------------------------------------------------------------------
        */

        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart.index');

        Route::post('/cart/add', [CartController::class, 'add'])
            ->name('cart.add');

        Route::post('/cart/update', [CartController::class, 'update'])
            ->name('cart.update');

        Route::post('/cart/remove', [CartController::class, 'remove'])
            ->name('cart.remove');

        Route::post('/cart/clear', [CartController::class, 'clear'])
            ->name('cart.clear');

        /*
        |--------------------------------------------------------------------------
        | Checkout (Module 2)
        |--------------------------------------------------------------------------
        */

        Route::get('/checkout', [CheckoutController::class, 'index'])
            ->name('checkout.index');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');

        /*
        |--------------------------------------------------------------------------
        | Orders History & Pay & Pickup Receipt & Review & Proof Upload (Modules 3, 4, 5)
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::get('/orders/{order}/pay', [OrderController::class, 'pay'])
            ->name('orders.pay');

        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])
            ->name('orders.receipt');

        Route::post('/orders/{order}/upload-proof', [OrderController::class, 'uploadProof'])
            ->name('orders.upload-proof');

        Route::post('/orders/{order}/review', [OrderController::class, 'review'])
            ->name('orders.review');

        /*
        |--------------------------------------------------------------------------
        | Profile (Module 6)
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile.index');

        Route::get('/profile/edit', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

    });