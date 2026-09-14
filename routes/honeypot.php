<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Active Defense Honeypot Decoy Traps
|--------------------------------------------------------------------------
| Any access to these fake endpoints triggers instant permanent IP blacklisting
| and deep forensics logging.
*/

Route::middleware(['honeypot'])->group(function () {
    Route::any('/wp-admin{fallback?}', fn() => '')->where('fallback', '.*');
    Route::any('/wp-login.php', fn() => '');
    Route::any('/admin.php', fn() => '');
    Route::any('/xmlrpc.php', fn() => '');
    Route::any('/.env{fallback?}', fn() => '')->where('fallback', '.*');
    Route::any('/phpmyadmin{fallback?}', fn() => '')->where('fallback', '.*');
    Route::any('/pma{fallback?}', fn() => '')->where('fallback', '.*');
    Route::any('/config.php', fn() => '');
    Route::any('/actuator{fallback?}', fn() => '')->where('fallback', '.*');
});
