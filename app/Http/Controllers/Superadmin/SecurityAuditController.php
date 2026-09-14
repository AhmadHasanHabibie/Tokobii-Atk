<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\IpBlacklist;
use App\Models\SecurityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityAuditController extends Controller
{
    /**
     * Display the Deep Forensics Security Audit Logs.
     */
    public function index(Request $request): View
    {
        $severity = $request->input('severity');
        $eventType = $request->input('event_type');
        $search = $request->input('q');

        $query = SecurityLog::with('user')->latest();

        if ($severity && in_array($severity, ['low', 'medium', 'high', 'critical'], true)) {
            $query->where('severity', $severity);
        }

        if ($eventType) {
            $query->where('event_type', $eventType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('endpoint', 'like', "%{$search}%")
                  ->orWhere('raw_payload', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        $eventTypes = SecurityLog::select('event_type')
            ->distinct()
            ->pluck('event_type');

        return view('superadmin.logs.audit', compact('logs', 'eventTypes', 'severity', 'eventType', 'search'));
    }

    /**
     * Return JSON payload details for forensic inspection modal.
     */
    public function showPayload(SecurityLog $securityLog): JsonResponse
    {
        return response()->json([
            'id' => $securityLog->id,
            'event_type' => $securityLog->event_type,
            'severity' => $securityLog->severity,
            'ip_address' => $securityLog->ip_address,
            'user_agent' => $securityLog->user_agent,
            'endpoint' => $securityLog->endpoint,
            'method' => $securityLog->method,
            'response_status' => $securityLog->response_status,
            'raw_payload' => $securityLog->raw_payload,
            'user' => $securityLog->user ? [
                'name' => $securityLog->user->name,
                'email' => $securityLog->user->email,
                'role' => $securityLog->user->role,
            ] : null,
            'created_at' => $securityLog->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Display the Active Defense IP Blacklist manager.
     */
    public function blacklistIndex(Request $request): View
    {
        $search = $request->input('q');

        $query = IpBlacklist::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $blacklists = $query->paginate(20)->withQueryString();

        return view('superadmin.blacklist.index', compact('blacklists', 'search'));
    }

    /**
     * Manually add an IP address to the blacklist.
     */
    public function storeBlacklist(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => ['required', 'ip', 'unique:ip_blacklists,ip_address'],
            'reason' => ['required', 'string', 'max:255'],
            'is_permanent' => ['nullable', 'boolean'],
        ]);

        IpBlacklist::create([
            'ip_address' => $validated['ip_address'],
            'reason' => $validated['reason'],
            'is_permanent' => $request->boolean('is_permanent', true),
            'blocked_by' => auth()->user()->email ?? 'superadmin',
            'hit_count' => 0,
        ]);

        SecurityLog::record(
            eventType: 'manual_ip_blacklisted',
            severity: 'high',
            endpoint: $request->path(),
            method: 'POST',
            payload: ['blacklisted_ip' => $validated['ip_address'], 'reason' => $validated['reason']],
            responseStatus: 200
        );

        return redirect()->route('superadmin.blacklist.index')->with('success', "IP {$validated['ip_address']} berhasil ditambahkan ke daftar blokir permanen.");
    }

    /**
     * Unblock / delete an IP from the blacklist.
     */
    public function destroyBlacklist(IpBlacklist $ipBlacklist): RedirectResponse
    {
        $ip = $ipBlacklist->ip_address;
        $ipBlacklist->delete();

        SecurityLog::record(
            eventType: 'ip_unblocked',
            severity: 'medium',
            endpoint: request()->path(),
            method: 'DELETE',
            payload: ['unblocked_ip' => $ip],
            responseStatus: 200
        );

        return redirect()->route('superadmin.blacklist.index')->with('success', "IP {$ip} berhasil dihapus dari daftar blokir.");
    }
}
