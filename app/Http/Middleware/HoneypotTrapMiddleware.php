<?php

namespace App\Http\Middleware;

use App\Models\IpBlacklist;
use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotTrapMiddleware
{
    /**
     * Handle an incoming request.
     * Traps malicious scanners/bots accessing decoy routes, blacklists IP, logs raw payload forensics, and blocks immediately.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip() ?? '127.0.0.1';
        $path = '/' . ltrim($request->path(), '/');
        $userAgent = $request->userAgent() ?? 'Unknown Agent';

        // 1. Blacklist IP Permanently
        $blacklist = IpBlacklist::blockIp(
            ip: $ip,
            reason: "Honeypot Decoy Trap Triggered: Access to {$path}",
            userAgent: $userAgent,
            permanent: true,
            blockedBy: 'honeypot_active_defense'
        );

        // 2. Deep Forensics Audit Log
        SecurityLog::record(
            eventType: 'honeypot_trap_triggered',
            severity: 'critical',
            endpoint: $path,
            method: $request->method(),
            payload: [
                'headers' => [
                    'user_agent' => $userAgent,
                    'referer' => $request->header('referer'),
                    'accept' => $request->header('accept'),
                    'host' => $request->header('host'),
                ],
                'query' => $request->query(),
                'body' => $request->all(),
            ],
            responseStatus: 403,
            userId: auth()->check() ? auth()->id() : null,
            ipAddress: $ip,
            userAgent: $userAgent
        );

        if ($request->expectsJson()) {
            return response()->json([
                'error' => '403 Forbidden',
                'message' => 'Aktivitas berbahaya terdeteksi. Alamat IP Anda telah diblokir secara permanen.',
                'ip' => $ip,
            ], 403);
        }

        return response()->view('errors.403_blacklisted', [
            'ip' => $ip,
            'reason' => "Percobaan akses ke endpoint terlarang: {$path}",
        ], 403);
    }
}
