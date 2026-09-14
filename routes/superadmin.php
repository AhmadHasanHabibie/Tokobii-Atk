<?php

use App\Http\Controllers\Superadmin\SuperadminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Superadmin Routes
|--------------------------------------------------------------------------
| Praktis & terpusat: Pemeliharaan Sistem, IP Blocker, Backup Database,
| dan Pemantauan Riwayat Login.
*/

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {

    // 1. Dashboard Utama (Kontrol Pemeliharaan, Backup, & IP Blocker)
    Route::get('/', [SuperadminController::class, 'index'])->name('dashboard');

    // 2. Toggle Mode Pemeliharaan (Maintenance On/Off)
    Route::post('/maintenance/toggle', [SuperadminController::class, 'toggleMaintenance'])->name('maintenance.toggle');

    // 3. IP Blocker (Tambah & Hapus IP)
    Route::post('/ip-blocker', [SuperadminController::class, 'storeBlockedIp'])->name('ip-blocker.store');
    Route::delete('/ip-blocker/{blockedIp}', [SuperadminController::class, 'destroyBlockedIp'])->name('ip-blocker.destroy');

    // 4. Unduh Cadangan Database (.sql)
    Route::get('/backup-database', [SuperadminController::class, 'downloadBackup'])->name('backup.download');

    // 5. Riwayat Login Pengguna
    Route::get('/login-histories', [SuperadminController::class, 'loginHistories'])->name('login-histories');
});
