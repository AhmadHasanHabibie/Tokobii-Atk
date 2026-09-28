<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureFaceVerified
{
    /**
     * Handle an incoming request.
     * Ensure Admin & Owner with active Face Verification have completed biometric challenge.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (($user->isAdmin() || $user->isOwner()) && $user->hasFaceVerificationEnabled()) {
                $verifiedAt = $request->session()->get('face_verified_at');
                $maxTtl = (int) config('face.session_hours', 8) * 3600;

                if (!$verifiedAt || (now()->timestamp - (int) $verifiedAt > $maxTtl)) {
                    $userId = $user->id;
                    $role = $user->role;

                    // Clear authenticated session and establish temporary face challenge state
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    $request->session()->put('face_auth:user_id', $userId);
                    $request->session()->put('face_auth:auth_time', now()->timestamp);
                    $request->session()->put('face_auth:role', $role);

                    $msg = $verifiedAt
                        ? 'Sesi verifikasi biometrik wajah Anda telah kedaluwarsa. Silakan verifikasi ulang.'
                        : 'Verifikasi wajah diperlukan untuk melanjutkan.';

                    return redirect()->route('face-verification.challenge')
                        ->with('error', $msg);
                }
            }
        }

        return $next($request);
    }
}
