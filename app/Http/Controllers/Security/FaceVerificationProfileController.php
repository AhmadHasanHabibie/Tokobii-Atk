<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Services\FaceVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaceVerificationProfileController extends Controller
{
    /**
     * Enroll or re-enroll face biometric data for Admin/Owner.
     */
    public function enroll(Request $request, FaceVerificationService $service): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'current_password'],
            'descriptor' => ['required', 'array', 'size:128'],
            'descriptor.*' => ['required', 'numeric'],
        ], [
            'password.required' => 'Kata sandi saat ini wajib diisi untuk konfirmasi keamanan.',
            'password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
            'descriptor.required' => 'Data pemindaian wajah wajib disertakan.',
            'descriptor.array' => 'Format biometrik wajah tidak valid.',
            'descriptor.size' => 'Vektor biometrik wajah harus tepat 128 dimensi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();

        if (!$user->isAdmin() && !$user->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur Verifikasi Wajah hanya tersedia untuk Administrator dan Owner.',
            ], 403);
        }

        $service->enroll($user, $request->input('descriptor'), $request->userAgent());

        $request->session()->put('face_verified_at', now()->timestamp);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi wajah berhasil didaftarkan dan diaktifkan. Akun Anda kini terlindungi.',
        ]);
    }

    /**
     * Disable face biometric verification for Admin/Owner.
     */
    public function disable(Request $request, FaceVerificationService $service): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi saat ini wajib diisi untuk menonaktifkan verifikasi wajah.',
            'password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        $user = $request->user();

        $service->disable($user);

        $request->session()->forget('face_verified_at');

        $redirectRoute = $user->isAdmin() ? 'admin.profile.index' : 'owner.profile.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Verifikasi wajah berhasil dinonaktifkan.');
    }
}
