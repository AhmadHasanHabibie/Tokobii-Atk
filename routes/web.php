<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route dipisahkan berdasarkan role agar struktur project
| lebih rapi dan mudah dikembangkan.
|
*/

require __DIR__ . '/guest.php';
require __DIR__ . '/auth.php';

require __DIR__ . '/admin.php';
require __DIR__ . '/owner.php';
require __DIR__ . '/customer.php';