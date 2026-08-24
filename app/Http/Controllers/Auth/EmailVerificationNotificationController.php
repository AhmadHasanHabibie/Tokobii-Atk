<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('customer.dashboard'))
                ->with('info', 'Email akun Anda sudah terverifikasi.');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage());

            return back()->with('error', 'Email verifikasi gagal dikirim. Silakan periksa konfigurasi mail atau coba lagi nanti.');
        }

        return back()->with('status', 'verification-link-sent')
            ->with('success', 'Email verifikasi berhasil dikirim. Silakan cek inbox Anda.');
    }
}
