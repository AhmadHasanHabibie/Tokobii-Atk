<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Semua route khusus Admin Tokobii.
|
*/

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
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
        | Category Management
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategoryController::class);

        /*
        |--------------------------------------------------------------------------
        | Product Management
        |--------------------------------------------------------------------------
        */

        Route::resource('products', ProductController::class);

        /*
        |--------------------------------------------------------------------------
        | Customer Monitoring
        |--------------------------------------------------------------------------
        */

        Route::resource('customers', CustomerController::class)
            ->only([
                'index',
                'show',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Order Management (Unified Transaction & Pickup Center)
        |--------------------------------------------------------------------------
        */

        Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])
            ->name('orders.receipt');

        Route::resource('orders', OrderController::class)
            ->only([
                'index',
                'show',
                'update',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::controller(ProfileController::class)
            ->prefix('profile')
            ->name('profile.')
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/edit', 'edit')
                    ->name('edit');

                Route::put('/', 'update')
                    ->name('update');

                Route::delete('/', 'destroy')
                    ->name('destroy');

            });

    });