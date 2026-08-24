<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified (LAN & Cross-Device Compatible).
     */
    public function __invoke(Request $request, string $id, string $hash)
    {
        // 1. Validasi Cryptographic Signed URL (Mendukung Host LAN & Absolute/Relative URL)
        if (!$request->hasValidSignature() && !$request->hasValidSignature(false)) {
            return response()->view('auth.verify-invalid', [
                'title' => 'Link Verifikasi Tidak Valid atau Kedaluwarsa',
                'message' => 'Link verifikasi ini sudah tidak dapat digunakan. Silakan kirim ulang email verifikasi untuk mendapatkan link baru.',
            ], 403);
        }

        // 2. Cari User Berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return response()->view('auth.verify-invalid', [
                'title' => 'Link Verifikasi Tidak Valid',
                'message' => 'Tautan verifikasi ini tidak sesuai dengan data akun pengguna Tokobii.',
            ], 404);
        }

        // 3. Validasi Hash Email
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->view('auth.verify-invalid', [
                'title' => 'Link Verifikasi Tidak Valid',
                'message' => 'Tautan verifikasi ini tidak cocok dengan alamat email pengguna.',
            ], 403);
        }

        // Cek status verifikasi sebelum update
        $alreadyVerified = $user->hasVerifiedEmail();

        // 4. Tandai Email Terverifikasi jika belum terisi
        if (!$alreadyVerified) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        // 5. CASE 1 & CASE 4: Jika Session Authenticated Masih Valid untuk User ini (Laptop/Browser Asal)
        if (Auth::check() && Auth::user()->id === $user->id) {
            if (Auth::user()->isCustomer()) {
                return redirect()->route('customer.dashboard')
                    ->with('success', $alreadyVerified ? 'Email akun Anda sudah terverifikasi.' : 'Email berhasil diverifikasi. Selamat datang di Tokobii!');
            }
        }

        // 6. CASE 3 & FALLBACK: Tampilkan Halaman Sukses Verifikasi (HP / Device Lain)
        return view('auth.verify-success', [
            'alreadyVerified' => $alreadyVerified,
            'user' => $user,
        ]);
    }
}
