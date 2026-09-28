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
     * Export and download MySQL database backup as .sql.
     */
    public function downloadBackup(): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $backupDir = storage_path('app/backups');
        if (! File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $filename = 'tokobii_backup_' . date('Y-m-d_His') . '.sql';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Retrieve database credentials
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        $host = $dbConfig['host'] ?? '127.0.0.1';
        $port = $dbConfig['port'] ?? '3306';
        $database = $dbConfig['database'] ?? 'tokobii';
        $username = $dbConfig['username'] ?? 'root';
        $password = $dbConfig['password'] ?? '';

        $dumpSuccessful = false;

        // 1. Try mysqldump command line first
        $mysqldumpPath = 'mysqldump';
        if (file_exists('C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe')) {
            $mysqldumpPath = '"C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe"';
        }

        $pwdArg = $password !== '' ? '--password=' . escapeshellarg($password) : '';
        $cmd = sprintf(
            '%s --host=%s --port=%s --user=%s %s %s > %s 2>&1',
            $mysqldumpPath,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $pwdArg,
            escapeshellarg($database),
            escapeshellarg($filePath)
        );

        @exec($cmd, $output, $returnCode);

        if ($returnCode === 0 && file_exists($filePath) && filesize($filePath) > 0) {
            $dumpSuccessful = true;
        }

        // 2. Failsafe: Pure PHP SQL Export if mysqldump is unavailable or errored
        if (! $dumpSuccessful) {
            try {
                $handle = fopen($filePath, 'w');
                fwrite($handle, "-- Tokobii Database Backup\n");
                fwrite($handle, "-- Generated at: " . date('Y-m-d H:i:s') . "\n");
                fwrite($handle, "-- Database: {$database}\n\n");
                fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

                $driver = DB::getDriverName();
                $tables = [];

                if ($driver === 'sqlite') {
                    $sqliteTables = DB::select("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    foreach ($sqliteTables as $st) {
                        $tables[$st->name] = $st->sql;
                    }
                } else {
                    $mysqlTables = DB::select('SHOW TABLES');
                    foreach ($mysqlTables as $tableObj) {
                        $tableName = reset((array) $tableObj);
                        $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                        $createStatement = ((array) $createTable[0])['Create Table'] ?? null;
                        $tables[$tableName] = $createStatement;
                    }
                }

                foreach ($tables as $tableName => $createStatement) {
                    if ($createStatement) {
                        fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                        fwrite($handle, $createStatement . ";\n\n");
                    }

                    // Dump rows
                    $rows = DB::table($tableName)->get();
                    if ($rows->count() > 0) {
                        foreach ($rows as $row) {
                            $rowArray = (array) $row;
                            $columns = array_map(fn($col) => "`{$col}`", array_keys($rowArray));
                            $values = array_map(function ($val) {
                                if (is_null($val)) {
                                    return 'NULL';
                                }
                                return "'" . addslashes((string) $val) . "'";
                            }, array_values($rowArray));

                            fwrite($handle, "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n");
                        }
                        fwrite($handle, "\n");
                    }
                }

                fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
                fclose($handle);

                $dumpSuccessful = true;
            } catch (\Throwable $e) {
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }

                return redirect()->route('superadmin.dashboard')
                    ->with('error', 'Gagal membuat file backup database: ' . $e->getMessage());
            }
        }

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/sql',
        ])->deleteFileAfterSend(true);
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
