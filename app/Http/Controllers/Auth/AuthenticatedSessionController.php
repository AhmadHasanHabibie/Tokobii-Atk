<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, TwoFactorService $twoFactorService): RedirectResponse
    {
        $user = $request->validateCredentials();

        // 1. Admin Login
        if ($user->isAdmin()) {
            if ($user->hasFaceVerificationEnabled()) {
                $request->session()->put('face_auth:user_id', $user->id);
                $request->session()->put('face_auth:remember', $request->boolean('remember'));
                $request->session()->put('face_auth:auth_time', now()->timestamp);
                $request->session()->put('face_auth:role', 'admin');

                return redirect()->route('face-verification.challenge');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->put('face_verified_at', now()->timestamp);

            return redirect()->route('admin.dashboard');
        }

        // 2. Owner Login
        if ($user->isOwner()) {
            if ($user->hasFaceVerificationEnabled()) {
                $request->session()->put('face_auth:user_id', $user->id);
                $request->session()->put('face_auth:remember', $request->boolean('remember'));
                $request->session()->put('face_auth:auth_time', now()->timestamp);
                $request->session()->put('face_auth:role', 'owner');

                return redirect()->route('face-verification.challenge');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->put('face_verified_at', now()->timestamp);

            return redirect()->route('owner.dashboard');
        }

        // 3. Customer belum verifikasi email -> Arahkan ke verifikasi email
        if (!$user->hasVerifiedEmail()) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('verification.notice');
        }

        // 4. Customer dengan 2FA Aktif -> Tahan authenticated session, mulai challenge OTP
        if ($user->hasTwoFactorEnabled()) {
            $request->session()->put('two_factor:user_id', $user->id);
            $request->session()->put('two_factor:remember', $request->boolean('remember'));
            $request->session()->put('two_factor:auth_time', now()->timestamp);

            if ($intended = $request->session()->get('url.intended')) {
                $request->session()->put('two_factor:intended_url', $intended);
            }

            $twoFactorService->sendOtp($user, 'login');

            return redirect()->route('two-factor.login');
        }

        // 5. Customer dengan 2FA Nonaktif -> Login normal
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}