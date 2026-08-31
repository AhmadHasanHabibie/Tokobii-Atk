<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwoFactorSecurityController extends Controller
{
    /**
     * Request activation of 2FA by validating current password and sending OTP.
     */
    public function requestEnable(Request $request, TwoFactorService $twoFactorService): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi akun wajib diisi untuk mengaktifkan Verifikasi 2 Langkah.',
            'password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('customer.profile.index')
                ->with('info', 'Verifikasi 2 langkah sudah dalam keadaan aktif.');
        }

        $sent = $twoFactorService->sendOtp($user, 'enable_2fa');

        if (!$sent) {
            return redirect()->route('customer.profile.index')
                ->with('error', 'Gagal mengirim kode konfirmasi ke email. Silakan coba beberapa saat lagi.');
        }

        return redirect()->route('customer.profile.index')
            ->with('two_factor_enabling', true)
            ->with('success', 'Kode konfirmasi telah dikirim ke email terdaftar Anda. Masukkan 6 digit kode untuk mengaktifkan.');
    }

    /**
     * Confirm 2FA activation with the provided OTP code.
     */
    public function confirmEnable(Request $request, TwoFactorService $twoFactorService): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ], [
            'code.required' => 'Kode konfirmasi wajib diisi.',
            'code.string' => 'Kode konfirmasi harus berupa teks.',
            'code.digits' => 'Kode konfirmasi harus terdiri dari 6 digit angka.',
        ]);

        $user = $request->user();

        $result = $twoFactorService->verifyOtp($user, (string) $request->code, 'enable_2fa');

        if (!$result['status']) {
            return redirect()->route('customer.profile.index')
                ->with('two_factor_enabling', true)
                ->with('error', $result['message']);
        }

        $user->two_factor_enabled = true;
        $user->save();

        Log::info("2FA berhasil diaktifkan untuk user_id: {$user->id}");

        return redirect()->route('customer.profile.index')
            ->with('success', 'Verifikasi 2 langkah berhasil diaktifkan. Akun Anda kini lebih aman.');
    }

    /**
     * Resend 2FA activation confirmation OTP.
     */
    public function resendEnable(Request $request, TwoFactorService $twoFactorService): RedirectResponse
    {
        $user = $request->user();

        if (!$twoFactorService->canResend($user, 'enable_2fa')) {
            $cooldown = $twoFactorService->getResendCooldownSeconds($user, 'enable_2fa');

            return redirect()->route('customer.profile.index')
                ->with('two_factor_enabling', true)
                ->with('error', "Silakan tunggu {$cooldown} detik sebelum meminta kode baru.");
        }

        $sent = $twoFactorService->sendOtp($user, 'enable_2fa');

        if (!$sent) {
            return redirect()->route('customer.profile.index')
                ->with('two_factor_enabling', true)
                ->with('error', 'Gagal mengirim ulang kode konfirmasi. Silakan coba lagi.');
        }

        return redirect()->route('customer.profile.index')
            ->with('two_factor_enabling', true)
            ->with('success', 'Kode konfirmasi baru telah dikirim ke email Anda.');
    }

    /**
     * Disable 2FA after validating current account password.
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi akun wajib diisi untuk menonaktifkan Verifikasi 2 Langkah.',
            'password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        $user = $request->user();

        $user->two_factor_enabled = false;
        $user->save();

        Log::info("2FA berhasil dinonaktifkan untuk user_id: {$user->id}");

        return redirect()->route('customer.profile.index')
            ->with('success', 'Verifikasi 2 langkah berhasil dinonaktifkan.');
    }
}
