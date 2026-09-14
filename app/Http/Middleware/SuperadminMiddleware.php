<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
{
    /**
     * Handle an incoming request.
     * Ensure the authenticated user has the Superadmin role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isSuperadmin()) {
            abort(403, 'Akses Ditolak: Halaman ini hanya dapat diakses oleh Superadmin.');
        }

        if ($user->isBanned() || $user->isInactive()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Superadmin Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
