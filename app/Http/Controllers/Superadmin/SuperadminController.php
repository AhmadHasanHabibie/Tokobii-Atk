<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use App\Models\LoginHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuperadminController extends Controller
{
    /**
     * Display the main Superadmin Dashboard.
     */
    public function index(): View
    {
        $isDown = app()->isDownForMaintenance();
        $blockedIps = BlockedIp::latest()->paginate(10, ['*'], 'blocked_page');
        $recentLogins = LoginHistory::with('user')->latest()->take(5)->get();

        $stats = [
            'is_maintenance' => $isDown,
            'total_blocked_ips' => BlockedIp::count(),
            'total_logins_today' => LoginHistory::whereDate('created_at', today())->count(),
            'successful_logins_today' => LoginHistory::whereDate('created_at', today())->where('status', 'sukses')->count(),
            'failed_logins_today' => LoginHistory::whereDate('created_at', today())->where('status', 'gagal')->count(),
        ];

        return view('superadmin.dashboard', compact('stats', 'blockedIps', 'recentLogins'));
    }

    /**
     * Toggle Maintenance Mode (On/Off).
     */
    public function toggleMaintenance(): RedirectResponse
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');

            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Mode pemeliharaan berhasil dinonaktifkan. Sistem Tokobii kini kembali aktif untuk semua pengguna.');
        }

        Artisan::call('down');

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Mode pemeliharaan berhasil diaktifkan. Pengunjung umum dialihkan ke halaman pemeliharaan.');
    }

    /**
     * Store a new IP in the Blocklist.
     */
    public function storeBlockedIp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => ['required', 'ip', 'unique:blocked_ips,ip_address'],
            'reason' => ['nullable', 'string', 'max:255'],
        ], [
            'ip_address.required' => 'Alamat IP wajib diisi.',
            'ip_address.ip' => 'Format alamat IP tidak valid.',
            'ip_address.unique' => 'Alamat IP ini sudah ada di dalam daftar blokir.',
            'reason.max' => 'Alasan blokir maksimal 255 karakter.',
        ]);

        // Safety check: Prevent admin from blocking their current IP
        if ($validated['ip_address'] === $request->ip()) {
            return back()->with('error', 'Tindakan dibatalkan: Anda tidak dapat memblokir alamat IP yang sedang Anda gunakan.');
        }

        BlockedIp::create([
            'ip_address' => $validated['ip_address'],
            'reason' => $validated['reason'] ?: 'Diblokir manual oleh Superadmin',
        ]);

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Alamat IP {$validated['ip_address']} berhasil ditambahkan ke daftar blokir.");
    }

    /**
     * Remove an IP from the Blocklist.
     */
    public function destroyBlockedIp(BlockedIp $blockedIp): RedirectResponse
    {
        $ip = $blockedIp->ip_address;
        $blockedIp->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Alamat IP {$ip} berhasil dihapus dari daftar blokir.");
    }

    /**
     * Export and download MySQL/SQLite database backup as .sql.
     * Engineered specifically for hosting environments (cPanel, LiteSpeed, Nginx, Apache)
     * without relying on disabled CLI commands (exec/mysqldump) or disk write permissions.
     */
    public function downloadBackup(): StreamedResponse|RedirectResponse
    {
        try {
            // Extend execution time and memory limits for reliable backup export
            @ini_set('max_execution_time', '300');
            @set_time_limit(300);
            @ini_set('memory_limit', '512M');

            $connection = config('database.default');
            $pdo = DB::connection($connection)->getPdo();
            $driver = DB::connection($connection)->getDriverName();
            $database = DB::connection($connection)->getDatabaseName();

            // Quick connectivity ping
            $pdo->query('SELECT 1');
        } catch (\Throwable $e) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Gagal menghubungkan ke basis data: ' . $e->getMessage());
        }

        $filename = 'tokobii_backup_' . date('Y-m-d_His') . '.sql';

        return response()->streamDownload(function () use ($pdo, $driver, $database) {
            // Discard any existing output buffers to ensure clean, valid SQL output
            while (ob_get_level() > 0) {
                @ob_end_clean();
            }

            try {
                echo "-- =======================================================\n";
                echo "-- Tokobii E-Commerce Database Backup\n";
                echo "-- Generated at: " . date('Y-m-d H:i:s') . "\n";
                echo "-- Database: {$database}\n";
                echo "-- Driver: {$driver}\n";
                echo "-- Server: " . (request()->getHost() ?: 'localhost') . "\n";
                echo "-- =======================================================\n\n";

                if ($driver === 'sqlite') {
                    $tableStmt = $pdo->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    $tables = [];
                    while ($row = $tableStmt->fetch(\PDO::FETCH_ASSOC)) {
                        $tables[$row['name']] = $row['sql'];
                    }

                    foreach ($tables as $tableName => $createStatement) {
                        echo "--\n-- Table structure for table `{$tableName}`\n--\n\n";
                        echo "DROP TABLE IF EXISTS `{$tableName}`;\n";
                        echo $createStatement . ";\n\n";

                        echo "--\n-- Dumping data for table `{$tableName}`\n--\n\n";
                        $dataStmt = $pdo->query("SELECT * FROM \"{$tableName}\"");
                        $batch = [];
                        $columns = null;
                        $batchSize = 100;

                        while ($row = $dataStmt->fetch(\PDO::FETCH_ASSOC)) {
                            if ($columns === null) {
                                $columns = array_map(fn($col) => "`{$col}`", array_keys($row));
                            }

                            $escaped = [];
                            foreach ($row as $val) {
                                $escaped[] = is_null($val) ? 'NULL' : $pdo->quote((string) $val);
                            }
                            $batch[] = '(' . implode(', ', $escaped) . ')';

                            if (count($batch) >= $batchSize) {
                                echo "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES\n"
                                    . implode(",\n", $batch) . ";\n";
                                $batch = [];
                                if (function_exists('flush')) {
                                    @flush();
                                }
                            }
                        }

                        if (! empty($batch)) {
                            echo "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES\n"
                                . implode(",\n", $batch) . ";\n\n";
                            $batch = [];
                            if (function_exists('flush')) {
                                @flush();
                            }
                        } else {
                            echo "\n";
                        }
                    }
                } else {
                    // MySQL / MariaDB Export
                    echo "SET FOREIGN_KEY_CHECKS=0;\n";
                    echo "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n";
                    echo "SET AUTOCOMMIT=0;\n";
                    echo "START TRANSACTION;\n";
                    echo "SET time_zone = \"+00:00\";\n\n";

                    // Retrieve list of BASE TABLES (avoids views)
                    $tables = [];
                    try {
                        $tableStmt = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
                        while ($row = $tableStmt->fetch(\PDO::FETCH_NUM)) {
                            if (! empty($row[0])) {
                                $tables[] = $row[0];
                            }
                        }
                    } catch (\Throwable $e) {
                        $tables = [];
                    }

                    if (empty($tables)) {
                        $tableStmt = $pdo->query("SHOW TABLES");
                        while ($row = $tableStmt->fetch(\PDO::FETCH_NUM)) {
                            if (! empty($row[0])) {
                                $tables[] = $row[0];
                            }
                        }
                    }

                    foreach ($tables as $tableName) {
                        echo "--\n-- Table structure for table `{$tableName}`\n--\n\n";
                        echo "DROP TABLE IF EXISTS `{$tableName}`;\n";

                        try {
                            $createStmt = $pdo->query("SHOW CREATE TABLE `{$tableName}`");
                            $createRow = $createStmt->fetch(\PDO::FETCH_NUM);
                            $createStatement = $createRow[1] ?? null;
                            if ($createStatement) {
                                echo $createStatement . ";\n\n";
                            }
                        } catch (\Throwable $e) {
                            echo "-- Failed to get CREATE TABLE for `{$tableName}`: " . addslashes($e->getMessage()) . "\n\n";
                        }

                        echo "--\n-- Dumping data for table `{$tableName}`\n--\n\n";

                        try {
                            $dataStmt = $pdo->query("SELECT * FROM `{$tableName}`");
                            $batch = [];
                            $columns = null;
                            $batchSize = 100;

                            while ($row = $dataStmt->fetch(\PDO::FETCH_ASSOC)) {
                                if ($columns === null) {
                                    $columns = array_map(fn($col) => "`{$col}`", array_keys($row));
                                }

                                $escaped = [];
                                foreach ($row as $val) {
                                    $escaped[] = is_null($val) ? 'NULL' : $pdo->quote((string) $val);
                                }
                                $batch[] = '(' . implode(', ', $escaped) . ')';

                                if (count($batch) >= $batchSize) {
                                    echo "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES\n"
                                        . implode(",\n", $batch) . ";\n";
                                    $batch = [];
                                    if (function_exists('flush')) {
                                        @flush();
                                    }
                                }
                            }

                            if (! empty($batch)) {
                                echo "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES\n"
                                    . implode(",\n", $batch) . ";\n\n";
                                $batch = [];
                                if (function_exists('flush')) {
                                    @flush();
                                }
                            } else {
                                echo "\n";
                            }
                        } catch (\Throwable $e) {
                            echo "-- Failed to dump rows for `{$tableName}`: " . addslashes($e->getMessage()) . "\n\n";
                        }
                    }

                    echo "COMMIT;\n";
                    echo "SET FOREIGN_KEY_CHECKS=1;\n\n";
                }

                echo "-- Dump completed on " . date('Y-m-d H:i:s') . "\n";

                if (function_exists('flush')) {
                    @flush();
                }
            } catch (\Throwable $e) {
                echo "\n\n-- ERROR DURING BACKUP EXPORT: " . addslashes($e->getMessage()) . "\n";
                if ($driver !== 'sqlite') {
                    echo "ROLLBACK;\nSET FOREIGN_KEY_CHECKS=1;\n";
                }
                if (function_exists('flush')) {
                    @flush();
                }
            }
        }, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Display the full Login History page.
     */
    public function loginHistories(Request $request): View
    {
        $query = LoginHistory::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $histories = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => LoginHistory::count(),
            'success' => LoginHistory::where('status', 'sukses')->count(),
            'failed' => LoginHistory::where('status', 'gagal')->count(),
        ];

        return view('superadmin.login-histories', compact('histories', 'stats'));
    }

    /**
     * Display the dedicated Maintenance Mode view/popup.
     */
    public function maintenancePage(Request $request): View
    {
        return view('errors.maintenance', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display Face Biometric Management for Admin & Owner accounts.
     */
    public function faceManagement(Request $request): View
    {
        $adminsAndOwners = \App\Models\User::whereIn('role', ['admin', 'owner'])
            ->with(['faceProfile', 'faceVerification'])
            ->get();

        return view('superadmin.face-management', compact('adminsAndOwners'));
    }

    /**
     * Reset / Force Re-enroll face biometrics for an Admin or Owner.
     */
    public function resetFaceBiometrics(\App\Models\User $user): RedirectResponse
    {
        if (!$user->isAdmin() && !$user->isOwner()) {
            return back()->with('error', 'Hanya akun Admin dan Owner yang dapat direset biometrik wajahnya.');
        }

        $profile = \App\Models\FaceProfile::firstOrNew(['user_id' => $user->id]);
        $profile->is_active = true;
        $profile->needs_re_enroll = true;
        $profile->failed_attempts = 0;
        $profile->locked_until = null;
        $profile->save();

        if ($user->faceVerification) {
            $user->faceVerification->update([
                'failed_attempts' => 0,
                'locked_until' => null,
            ]);
        }

        return back()->with('success', "Biometrik wajah untuk pengguna {$user->name} ({$user->email}) berhasil direset. Pengguna akan diminta mendaftar ulang saat login/masuk ke profil.");
    }

    /**
     * Disable face verification completely for an Admin or Owner.
     */
    public function disableFaceBiometrics(\App\Models\User $user): RedirectResponse
    {
        if (!$user->isAdmin() && !$user->isOwner()) {
            return back()->with('error', 'Hanya akun Admin dan Owner yang dapat dinonaktifkan.');
        }

        $user->update(['face_verification_enabled' => false]);

        \App\Models\FaceProfile::where('user_id', $user->id)->update(['is_active' => false]);
        \App\Models\FaceVerification::where('user_id', $user->id)->update(['is_active' => false]);

        return back()->with('success', "Verifikasi wajah untuk {$user->name} berhasil dinonaktifkan sepenuhnya.");
    }

    /**
     * Generate a one-time emergency recovery code for an Admin or Owner.
     */
    public function generateEmergencyRecoveryCode(\App\Models\User $user): RedirectResponse
    {
        if (!$user->isAdmin() && !$user->isOwner()) {
            return back()->with('error', 'Hanya akun Admin dan Owner yang dapat diberikan kode pemulihan.');
        }

        $profile = \App\Models\FaceProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'engine_version' => config('face.engine_version', 'human-v3'),
                'is_active' => true,
                'needs_re_enroll' => false,
            ]
        );

        $recoveryCode = $profile->generateRecoveryCode();

        return back()->with([
            'success' => "Kode pemulihan darurat berhasil dibuat untuk {$user->name}.",
            'generated_recovery_code' => $recoveryCode,
            'recovery_user_name' => $user->name,
            'recovery_user_email' => $user->email,
        ]);
    }
}
