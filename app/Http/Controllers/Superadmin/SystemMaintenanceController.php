<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SystemMaintenanceController extends Controller
{
    /**
     * Display the System Maintenance, Environment Audit & Health Center.
     */
    public function index(): View
    {
        $isMaintenanceMode = App::isDownForMaintenance();
        $activeBypassSecret = session('emergency_bypass_secret');

        // 1. Environment & Security Health Matrix
        $envAudit = [
            'app_env' => [
                'label' => 'Environment (APP_ENV)',
                'value' => config('app.env'),
                'status' => config('app.env') === 'production' ? 'secure' : 'warning',
                'message' => config('app.env') === 'production'
                    ? 'Server berjalan di mode Production.'
                    : 'Server berjalan di lingkungan non-production (' . config('app.env') . ').',
            ],
            'app_debug' => [
                'label' => 'Debug Mode (APP_DEBUG)',
                'value' => config('app.debug') ? 'TRUE (ENABLED)' : 'FALSE (DISABLED)',
                'status' => (config('app.debug') && config('app.env') === 'production') ? 'danger' : (config('app.debug') ? 'warning' : 'secure'),
                'message' => config('app.debug')
                    ? (config('app.env') === 'production'
                        ? 'BAHAYA KRITIKAL: APP_DEBUG aktif di production! Kredensial dan stack trace terekspos.'
                        : 'Debug mode aktif (wajar untuk lingkungan pengembangan/lokal).')
                    : 'Debug mode aman dan dinonaktifkan.',
            ],
            'https_status' => [
                'label' => 'SSL / HTTPS Enforcement',
                'value' => request()->secure() ? 'HTTPS SECURE' : 'HTTP INSECURE',
                'status' => request()->secure() ? 'secure' : 'warning',
                'message' => request()->secure()
                    ? 'Koneksi terenkripsi dengan protokol HTTPS.'
                    : 'Koneksi tidak menggunakan HTTPS. Data transit berisiko disadap (Man-in-the-Middle).',
            ],
            'storage_permissions' => [
                'label' => 'Storage Directory Writable',
                'value' => is_writable(storage_path()) ? 'WRITABLE' : 'READ-ONLY',
                'status' => is_writable(storage_path()) ? 'secure' : 'danger',
                'message' => is_writable(storage_path())
                    ? 'Direktori storage/ memiliki izin tulis yang sesuai.'
                    : 'Direktori storage/ tidak dapat ditulis oleh sistem.',
            ],
            'bootstrap_permissions' => [
                'label' => 'Bootstrap Cache Writable',
                'value' => is_writable(base_path('bootstrap/cache')) ? 'WRITABLE' : 'READ-ONLY',
                'status' => is_writable(base_path('bootstrap/cache')) ? 'secure' : 'danger',
                'message' => is_writable(base_path('bootstrap/cache'))
                    ? 'Direktori bootstrap/cache/ siap untuk caching sistem.'
                    : 'Direktori bootstrap/cache/ tidak memiliki izin tulis.',
            ],
            'session_driver' => [
                'label' => 'Session Storage Driver',
                'value' => strtoupper((string) config('session.driver')),
                'status' => in_array(config('session.driver'), ['database', 'redis', 'file'], true) ? 'secure' : 'warning',
                'message' => 'Driver sesi saat ini menggunakan ' . config('session.driver'),
            ],
            'db_connection' => [
                'label' => 'Database Health',
                'value' => 'CONNECTED',
                'status' => 'secure',
                'message' => 'Koneksi database aktif dan responsif.',
            ],
            'php_version' => [
                'label' => 'PHP Runtime Version',
                'value' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.2.0', '>=') ? 'secure' : 'warning',
                'message' => 'Versi PHP yang sedang aktif.',
            ],
        ];

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $envAudit['db_connection']['value'] = 'FAILED';
            $envAudit['db_connection']['status'] = 'danger';
            $envAudit['db_connection']['message'] = 'Koneksi database gagal: ' . $e->getMessage();
        }

        // 2. Storage Disk Statistics
        $diskTotal = @disk_total_space(base_path()) ?: 1;
        $diskFree = @disk_free_space(base_path()) ?: 0;
        $diskUsed = $diskTotal - $diskFree;
        $diskUsagePercent = round(($diskUsed / $diskTotal) * 100, 1);

        $systemStats = [
            'disk_total_gb' => round($diskTotal / (1024 * 1024 * 1024), 2),
            'disk_free_gb' => round($diskFree / (1024 * 1024 * 1024), 2),
            'disk_used_gb' => round($diskUsed / (1024 * 1024 * 1024), 2),
            'disk_usage_percent' => $diskUsagePercent,
            'laravel_version' => App::version(),
        ];

        return view('superadmin.maintenance.index', compact(
            'isMaintenanceMode',
            'activeBypassSecret',
            'envAudit',
            'systemStats'
        ));
    }

    /**
     * Cache Manager: Execute artisan clear commands.
     */
    public function clearCache(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'all');
        $output = '';

        switch ($type) {
            case 'optimize':
                Artisan::call('optimize:clear');
                $output = Artisan::output();
                $message = 'Sistem Optimize Cache (config, route, view, cache) berhasil dibersihkan.';
                break;
            case 'config':
                Artisan::call('config:clear');
                $output = Artisan::output();
                $message = 'Konfigurasi cache berhasil dibersihkan.';
                break;
            case 'route':
                Artisan::call('route:clear');
                $output = Artisan::output();
                $message = 'Route cache berhasil dibersihkan.';
                break;
            case 'view':
                Artisan::call('view:clear');
                $output = Artisan::output();
                $message = 'Compiled Blade views cache berhasil dibersihkan.';
                break;
            case 'cache':
                Artisan::call('cache:clear');
                $output = Artisan::output();
                $message = 'Application data cache berhasil dibersihkan.';
                break;
            default:
                Artisan::call('optimize:clear');
                $output = Artisan::output();
                $message = 'Semua cache aplikasi berhasil dibersihkan.';
                break;
        }

        SecurityLog::record(
            eventType: 'cache_cleared',
            severity: 'medium',
            endpoint: $request->path(),
            method: 'POST',
            payload: ['type' => $type, 'output' => trim($output)],
            responseStatus: 200
        );

        return redirect()->back()->with('success', $message);
    }

    /**
     * Incident Response: DEFCON 1 Global Panic Button.
     * Force logouts all users, terminates sessions, and activates maintenance mode with emergency secret bypass.
     */
    public function triggerPanic(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        // 1. Re-validate Superadmin password
        if (!Hash::check($request->password, $user->password)) {
            SecurityLog::record(
                eventType: 'panic_button_auth_failed',
                severity: 'critical',
                endpoint: $request->path(),
                method: 'POST',
                payload: ['reason' => $request->input('reason')],
                responseStatus: 401
            );

            return redirect()->back()->with('error', 'Otorisasi Gagal: Kata sandi yang dimasukkan salah.');
        }

        // 2. Generate Emergency Bypass Secret
        $bypassSecret = 'panic_' . Str::random(32);
        $currentSessionId = $request->session()->getId();

        // 3. Force Logout & Clear All Active Sessions (Except current superadmin)
        $clearedSessionsCount = 0;

        // Session driver: database
        if (Schema::hasTable('sessions')) {
            $clearedSessionsCount += DB::table('sessions')
                ->where('id', '!=', $currentSessionId)
                ->delete();
        }

        // Session driver: file
        $sessionPath = storage_path('framework/sessions');
        if (File::exists($sessionPath)) {
            $files = File::files($sessionPath);
            foreach ($files as $file) {
                if ($file->getFilename() !== $currentSessionId && $file->getFilename() !== '.gitignore') {
                    File::delete($file->getPathname());
                    $clearedSessionsCount++;
                }
            }
        }

        // Clear API / Sanctum Personal Access Tokens
        if (Schema::hasTable('personal_access_tokens')) {
            DB::table('personal_access_tokens')->delete();
        }

        // 4. Activate Maintenance Mode with Secret Bypass Token
        Artisan::call('down', [
            '--secret' => $bypassSecret,
            '--render' => 'errors.503',
        ]);

        // Save bypass info to current superadmin session
        session(['emergency_bypass_secret' => $bypassSecret]);

        // 5. Deep Security Audit Log
        SecurityLog::record(
            eventType: 'defcon1_panic_button_activated',
            severity: 'critical',
            endpoint: $request->path(),
            method: 'POST',
            payload: [
                'triggered_by' => $user->email,
                'reason' => $request->input('reason', 'Security Incident Emergency Protocol'),
                'sessions_cleared' => $clearedSessionsCount,
                'bypass_secret_hash' => substr($bypassSecret, 0, 10) . '...',
            ],
            responseStatus: 200
        );

        $bypassUrl = url('/' . $bypassSecret);

        return redirect()->to($bypassUrl)->with(
            'warning',
            "DEFCON 1 DIAKTIFKAN: Sistem telah masuk ke Maintenance Mode. Seluruh sesi pengguna aktif ({$clearedSessionsCount}) telah diputus paksa. Anda telah dialihkan ke rute bypass darurat."
        );
    }

    /**
     * Incident Response: Stand-Down / Lift Maintenance Mode.
     */
    public function standDown(Request $request): RedirectResponse
    {
        Artisan::call('up');

        session()->forget('emergency_bypass_secret');

        SecurityLog::record(
            eventType: 'panic_button_stand_down',
            severity: 'high',
            endpoint: $request->path(),
            method: 'POST',
            payload: ['action' => 'maintenance_mode_lifted'],
            responseStatus: 200
        );

        return redirect()->route('superadmin.maintenance.index')->with('success', 'DEFCON STAND-DOWN: Maintenance Mode telah dinonaktifkan. Sistem Tokobii kembali beroperasi normal.');
    }
}
