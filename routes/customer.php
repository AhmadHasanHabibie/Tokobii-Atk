<?php

use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\ReportController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\TwoFactorSecurityController;
use App\Http\Controllers\Customer\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes (All Secured by Auth, Email Verification, & Customer Role)
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
        | Orders History & Pay & Pickup Receipt & Review & Proof Upload
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

        Route::get('/orders/{order}/items/{item}/review', [ReviewController::class, 'create'])->name('orders.reviews.create');
        Route::post('/orders/{order}/items/{item}/review', [ReviewController::class, 'store'])->name('orders.reviews.store');
        Route::post('/orders/{order}/review', [OrderController::class, 'review'])->name('orders.review');
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/orders/{order}/items/{item}/report', [ReportController::class, 'create'])->name('orders.reports.create');
        Route::post('/orders/{order}/items/{item}/report', [ReportController::class, 'store'])->name('orders.reports.store');
        Route::post('/reports/{report}/reply', [ReportController::class, 'reply'])->name('reports.reply');
        Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');

        /*
        |--------------------------------------------------------------------------
        | Profile & Account Security (2FA)
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

        Route::post('/profile/security/2fa/request-enable', [TwoFactorSecurityController::class, 'requestEnable'])
            ->name('profile.security.2fa.request-enable');

        Route::post('/profile/security/2fa/confirm-enable', [TwoFactorSecurityController::class, 'confirmEnable'])
            ->middleware('throttle:10,1')
            ->name('profile.security.2fa.confirm-enable');

        Route::post('/profile/security/2fa/resend-enable', [TwoFactorSecurityController::class, 'resendEnable'])
            ->middleware('throttle:6,1')
            ->name('profile.security.2fa.resend-enable');

        Route::post('/profile/security/2fa/disable', [TwoFactorSecurityController::class, 'disable'])
            ->name('profile.security.2fa.disable');

    });
