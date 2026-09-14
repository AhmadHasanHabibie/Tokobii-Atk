<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminReauthMiddleware
{
    /**
     * Lifetime of re-authentication in seconds (15 minutes).
     */
    protected const REAUTH_TIMEOUT = 900;

    /**
     * Handle an incoming request.
     * Requires Superadmin to confirm password before accessing critical incident response / maintenance endpoints.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lastAuth = $request->session()->get('superadmin_reauth_at');

        if (!$lastAuth || (time() - $lastAuth) > self::REAUTH_TIMEOUT) {
            // Save current target URL in session for seamless return
            $request->session()->put('superadmin_reauth_target', $request->fullUrl());

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Re-autentikasi password diperlukan untuk tindakan kritikal ini.',
                    'reauth_required' => true,
                    'redirect_url' => route('superadmin.reauth'),
                ], 403);
            }

            return redirect()->route('superadmin.reauth')->with('warning', 'Otorisasi Keamanan: Harap masukkan ulang kata sandi Anda untuk melanjutkan ke menu kritikal.');
        }

        return $next($request);
    }
}
