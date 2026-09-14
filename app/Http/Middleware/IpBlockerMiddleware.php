<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpBlockerMiddleware
{
    /**
     * Handle an incoming request.
     * Check if the request's IP address is in blocked_ips.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if ($ip && BlockedIp::isBlocked($ip)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses Ditolak: Alamat IP Anda telah diblokir.',
                    'ip' => $ip,
                ], 403);
            }

            abort(403, 'Akses Ditolak: Alamat IP Anda (' . $ip . ') telah diblokir.');
        }

        return $next($request);
    }
}
