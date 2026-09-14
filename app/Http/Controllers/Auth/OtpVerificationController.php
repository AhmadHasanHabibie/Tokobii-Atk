<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Display the OTP email verification view.
     */
    public function notice(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            return $this->redirectVerifiedUser($user);
        }

        // Auto-generate OTP jika belum ada atau sudah kedaluwarsa
        if ($user && (empty($user->email_verification_otp) || ($user->email_verification_otp_expires_at && $user->email_verification_otp_expires_at->isPast()))) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                Log::warning('Auto send email verification OTP error: ' . $e->getMessage());
            }
        }

        return view('auth.verify-email');
    }

    /**
     * Verify the 6-digit OTP code submitted by the user.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.required' => 'Kode OTP 6 digit wajib diisi.',
            'otp.size' => 'Kode OTP harus tepat 6 digit angka.',
            'otp.regex' => 'Kode OTP hanya boleh berisi angka numerik.',
        ]);

        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectVerifiedUser($user)
                ->with('info', 'Email Anda sudah terverifikasi sebelumnya.');
        }

        // 1. Cek ketersediaan OTP di database
        if (empty($user->email_verification_otp)) {
            return back()
                ->with('error', 'Kode OTP tidak ditemukan atau belum digenerate. Silakan klik "Kirim Ulang Kode".')
                ->withInput();
        }

        // 2. Cek apakah OTP sudah kedaluwarsa (15 menit)
        if ($user->email_verification_otp_expires_at && $user->email_verification_otp_expires_at->isPast()) {
            return back()
                ->with('error', 'Kode OTP telah kedaluwarsa (masa aktif 15 menit). Silakan klik "Kirim Ulang Kode".')
                ->withInput();
        }

        // 3. Validasi kesesuaian OTP dengan timing attack safe comparison
        if (!hash_equals((string) $user->email_verification_otp, (string) $request->input('otp'))) {
            return back()
                ->with('error', 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali email Anda.')
                ->withInput();
        }

        // 4. Update status verifikasi email dan bersihkan OTP
        $user->markEmailAsVerified();
        $user->clearEmailVerificationOtp();

        event(new Verified($user));

        return $this->redirectVerifiedUser($user)
            ->with('success', 'Email akun Anda berhasil diverifikasi! Selamat datang di Tokobii.');
    }

    /**
     * Resend a fresh 6-digit OTP verification email.
     */
    public function resend(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectVerifiedUser($user)
                ->with('info', 'Email akun Anda sudah terverifikasi.');
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang email OTP verifikasi: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengirimkan kode OTP. Silakan periksa koneksi atau coba beberapa saat lagi.');
        }

        return back()
            ->with('status', 'verification-link-sent')
            ->with('success', 'Kode OTP baru 6 digit telah berhasil dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
    }

    /**
     * Redirect user according to role after verification.
     */
    protected function redirectVerifiedUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isOwner()) {
            return redirect()->intended(route('owner.dashboard'));
        }

        return redirect()->intended(route('customer.dashboard'));
    }
}
