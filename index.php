<?php

/**
 * Tokobii - Production Root Index Controller
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * File ini berfungsi sebagai entry point aplikasi ketika seluruh proyek Laravel
 * di-deploy langsung ke dalam folder document root hosting (/home/tokobii1/public_html).
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Cek apakah aplikasi sedang dalam maintenance mode
if (file_exists($maintenance = __DIR__ . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer Autoloader
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel Application Kernel
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
