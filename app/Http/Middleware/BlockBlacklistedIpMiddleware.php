<?php

namespace App\Http\Middleware;

use App\Models\IpBlacklist;
use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBlacklistedIpMiddleware
{
    /**
     * Handle an incoming request.
     * Blocks any request originating from blacklisted IPs.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        try {
            if ($ip && IpBlacklist::isBlocked($ip)) {
                // Increment hit counter silently
                IpBlacklist::where('ip_address', $ip)->increment('hit_count');

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => '403 Forbidden',
                        'message' => 'Akses ditolak secara permanen oleh sistem keamanan Tokobii Threat Defense.',
                        'ip' => $ip,
                    ], 403);
                }

                return response()->view('errors.403_blacklisted', [
                    'ip' => $ip,
                ], 403);
            }
        } catch (\Throwable $e) {
            // Failsafe: avoid crashing if DB is unavailable or unmigrated
        }

        return $next($request);
    }
}
