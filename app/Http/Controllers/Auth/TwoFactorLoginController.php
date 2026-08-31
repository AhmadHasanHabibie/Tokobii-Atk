<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorLoginController extends Controller
{
    /**
     * Sesi temporary 2FA berlaku maksimal 10 menit (600 detik).
     */
    protected const SESSION_LIFETIME_SECONDS = 600;

    /**
     * Display the 2FA OTP challenge view.
     */
    public function create(Request $request, TwoFactorService $twoFactorService): View|RedirectResponse
    {
        // 1. Jika user sudah login penuh, arahkan ke dashboard yang sesuai
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isOwner()) {
                return redirect()->route('owner.dashboard');
            }
            return redirect()->route('customer.dashboard');
        }

        // 2. Validasi keberadaan dan masa berlaku sesi temporary 2FA
        $userId = $request->session()->get('two_factor:user_id');
        $authTime = $request->session()->get('two_factor:auth_time');

        if (!$userId || !$authTime || (now()->timestamp - $authTime > self::SESSION_LIFETIME_SECONDS)) {
            $this->clearTwoFactorSession($request);

            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan masuk kembali.');
        }

        $user = User::find($userId);

        if (!$user) {
            $this->clearTwoFactorSession($request);

            return redirect()->route('login')
                ->with('error', 'Akun pengguna tidak ditemukan. Silakan masuk kembali.');
        }

        $maskedEmail = $this->maskEmail($user->email);
        $cooldown = $twoFactorService->getResendCooldownSeconds($user, 'login');

        return view('auth.two-factor-challenge', [
            'user' => $user,
            'maskedEmail' => $maskedEmail,
            'cooldown' => $cooldown,
        ]);
    }

    /**
     * Verify the incoming OTP code and complete login.
     */
    public function store(Request $request, TwoFactorService $twoFactorService): RedirectResponse
    {
        $userId = $request->session()->get('two_factor:user_id');
        $authTime = $request->session()->get('two_factor:auth_time');

        if (!$userId || !$authTime || (now()->timestamp - $authTime > self::SESSION_LIFETIME_SECONDS)) {
            $this->clearTwoFactorSession($request);

            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan masuk kembali.');
        }

        $user = User::find($userId);

        if (!$user) {
            $this->clearTwoFactorSession($request);

            return redirect()->route('login')
                ->with('error', 'Akun pengguna tidak ditemukan. Silakan masuk kembali.');
        }

        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.string' => 'Kode verifikasi harus berupa teks.',
            'code.digits' => 'Kode verifikasi harus terdiri dari 6 digit.',
        ]);

        $result = $twoFactorService->verifyOtp($user, (string) $request->code, 'login');

        if (!$result['status']) {
            return back()->withInput()->with('error', $result['message']);
        }

        $remember = $request->session()->get('two_factor:remember', false);
        $intendedUrl = $request->session()->get('two_factor:intended_url');

        $this->clearTwoFactorSession($request);

        Auth::login($user, (bool) $remember);
        $request->session()->regenerate();

        if ($intendedUrl) {
            return redirect()->to($intendedUrl)
                ->with('success', 'Verifikasi dua langkah berhasil. Selamat datang kembali!');
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Verifikasi dua langkah berhasil. Selamat datang kembali!');
    }

    /**
     * Resend a new OTP to the user's email.
     */
    public function resend(Request $request, TwoFactorService $twoFactorService): RedirectResponse
    {
        $userId = $request->session()->get('two_factor:user_id');
        $authTime = $request->session()->get('two_factor:auth_time');

        if (!$userId || !$authTime || (now()->timestamp - $authTime > self::SESSION_LIFETIME_SECONDS)) {
            $this->clearTwoFactorSession($request);

            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan masuk kembali.');
        }

        $user = User::find($userId);

        if (!$user) {
            $this->clearTwoFactorSession($request);

            return redirect()->route('login')
                ->with('error', 'Akun pengguna tidak ditemukan. Silakan masuk kembali.');
        }

        if (!$twoFactorService->canResend($user, 'login')) {
            $cooldown = $twoFactorService->getResendCooldownSeconds($user, 'login');

            return back()->with('error', "Silakan tunggu {$cooldown} detik sebelum meminta kode baru.");
        }

        $sent = $twoFactorService->sendOtp($user, 'login');

        if (!$sent) {
            return back()->with('error', 'Gagal mengirimkan kode verifikasi. Silakan periksa koneksi atau coba lagi nanti.');
        }

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Cancel the 2FA challenge and return to login.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $this->clearTwoFactorSession($request);

        return redirect()->route('login')
            ->with('info', 'Proses verifikasi dua langkah dibatalkan.');
    }

    /**
     * Clear all temporary 2FA session variables.
     */
    protected function clearTwoFactorSession(Request $request): void
    {
        $request->session()->forget([
            'two_factor:user_id',
            'two_factor:remember',
            'two_factor:auth_time',
            'two_factor:intended_url',
        ]);
    }

    /**
     * Mask email address for user privacy (e.g. j***@example.com).
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        $length = strlen($name);
        if ($length <= 2) {
            $maskedName = substr($name, 0, 1) . '***';
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', min(4, $length - 2));
        }

        return $maskedName . '@' . $domain;
    }
}
