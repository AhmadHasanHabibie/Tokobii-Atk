<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\IpBlacklist;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Superadmin IT Security & SOC Dashboard.
     */
    public function index(): View
    {
        $totalBlockedIps = IpBlacklist::count();
        $totalSecurityLogs = SecurityLog::count();
        $criticalThreatsCount = SecurityLog::whereIn('severity', ['high', 'critical'])->count();
        $recentHoneypotHits = SecurityLog::where('event_type', 'honeypot_trap_triggered')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $isMaintenanceMode = App::isDownForMaintenance();

        // Recent security event logs
        $recentLogs = SecurityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Top attacked targets / honeypots
        $topAttackedEndpoints = SecurityLog::selectRaw('endpoint, count(*) as total')
            ->groupBy('endpoint')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Environment health summary
        $envAlerts = [];
        if (config('app.debug') === true && App::environment('production')) {
            $envAlerts[] = [
                'type' => 'critical',
                'title' => 'APP_DEBUG Aktif di Lingkungan Production!',
                'desc' => 'Mode debug mengekspos stack trace, kredensial database, dan variabel .env kepada publik.',
            ];
        }

        if (!is_writable(storage_path())) {
            $envAlerts[] = [
                'type' => 'warning',
                'title' => 'Perizinan Storage Tidak Terbuka',
                'desc' => 'Direktori storage/ tidak dapat ditulis oleh web server.',
            ];
        }

        return view('superadmin.dashboard.index', compact(
            'totalBlockedIps',
            'totalSecurityLogs',
            'criticalThreatsCount',
            'recentHoneypotHits',
            'isMaintenanceMode',
            'recentLogs',
            'topAttackedEndpoints',
            'envAlerts'
        ));
    }
}
