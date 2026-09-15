<?php

use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ProfileController;
use App\Http\Controllers\Owner\SalesReportController;
use App\Http\Controllers\Security\FaceVerificationProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'owner', 'face.verified'])
    ->prefix('owner')
    ->name('owner.')
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
        | Laporan Penjualan (Harian, Mingguan, Bulanan & Download PDF)
        |--------------------------------------------------------------------------
        */

        Route::get('/sales', [SalesReportController::class, 'index'])
            ->name('sales.index');

        Route::get('/sales/pdf', [SalesReportController::class, 'exportPdf'])
            ->name('sales.pdf');

        /*
        |--------------------------------------------------------------------------
        | Profile
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

        Route::post('profile/face-verification/enroll', [FaceVerificationProfileController::class, 'enroll'])
            ->name('profile.face-verification.enroll');

        Route::post('profile/face-verification/disable', [FaceVerificationProfileController::class, 'disable'])
            ->name('profile.face-verification.disable');

    });