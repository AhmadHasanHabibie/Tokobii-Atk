<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Illuminate\Support\Facades\Auth;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'superadmin',
        'superadmin/*',
        'login',
        'logout',
        'maintenance',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            $data = json_decode(@file_get_contents($this->app->storagePath() . '/framework/down'), true) ?: [];

            // 1. Secret bypass endpoint
            if (isset($data['secret']) && $request->path() === $data['secret']) {
                return $this->bypassResponse($data['secret']);
            }

            // 2. Valid cookie bypass
            if ($this->hasValidBypassCookie($request, $data)) {
                return $next($request);
            }

            // 3. Superadmin authenticated session always bypasses
            if ($request->user() && $request->user()->isSuperadmin()) {
                return $next($request);
            }

            // 4. Allowed routes (login, logout, maintenance, superadmin)
            if ($this->inExceptArray($request)) {
                return $next($request);
            }

            // 5. JSON Response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Layanan Tokobii sedang dalam pemeliharaan.',
                    'status' => 503,
                ], 503);
            }

            // 6. Custom Interactive Maintenance View / Popup
            return response()->view('errors.maintenance', [
                'user' => $request->user(),
            ], 503);
        }

        return $next($request);
    }
}
