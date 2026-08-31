<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FaceVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FaceVerificationLoginController extends Controller
{
    /**
     * Sesi temporary Face Auth berlaku maksimal 10 menit (600 detik).
     */
    protected const SESSION_LIFETIME_SECONDS = 600;

    /**
     * Display the Face Verification Challenge view for Admin / Owner.
     */
    public function showChallenge(Request $request): View|RedirectResponse
    {
        // 1. Jika user sudah login dan face verification sudah terverifikasi di session, arahkan ke dashboard
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

        // 2. Validasi sesi temporary face auth
        $userId = $request->session()->get('face_auth:user_id');
        $authTime = $request->session()->get('face_auth:auth_time');

        if (!$userId || !$authTime || (now()->timestamp - $authTime > self::SESSION_LIFETIME_SECONDS)) {
            $this->clearFaceAuthSession($request);

            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi wajah telah berakhir. Silakan masuk kembali.');
        }

        $user = User::find($userId);

        if (!$user || !($user->isAdmin() || $user->isOwner())) {
            $this->clearFaceAuthSession($request);

            return redirect()->route('login')
                ->with('error', 'Pengguna tidak ditemukan atau tidak memiliki hak akses verifikasi wajah.');
        }

        $isLocked = false;
        $remainingLockout = 0;

        if ($user->faceVerification && $user->faceVerification->isLocked()) {
            $isLocked = true;
            $remainingLockout = $user->faceVerification->remainingLockoutMinutes();
        }

        return view('auth.face-verification-challenge', [
            'user' => $user,
            'isLocked' => $isLocked,
            'remainingLockout' => $remainingLockout,
        ]);
    }

    /**
     * Process face matching verification from camera descriptor vector.
     */
    public function verify(Request $request, FaceVerificationService $service): JsonResponse
    {
        $userId = $request->session()->get('face_auth:user_id');
        $authTime = $request->session()->get('face_auth:auth_time');

        if (!$userId || !$authTime || (now()->timestamp - $authTime > self::SESSION_LIFETIME_SECONDS)) {
            $this->clearFaceAuthSession($request);

            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi wajah telah berakhir. Silakan masuk kembali.',
                'redirect_url' => route('login'),
            ], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            $this->clearFaceAuthSession($request);

            return response()->json([
                'success' => false,
                'message' => 'Akun pengguna tidak ditemukan.',
                'redirect_url' => route('login'),
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'descriptor' => ['required', 'array', 'size:128'],
            'descriptor.*' => ['required', 'numeric'],
        ], [
            'descriptor.required' => 'Data biometrik wajah wajib disertakan.',
            'descriptor.array' => 'Format biometrik wajah tidak valid.',
            'descriptor.size' => 'Vektor biometrik wajah harus tepat 128 dimensi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $result = $service->verify($user, $request->input('descriptor'));

        if (!$result['status']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'reason' => $result['reason'] ?? 'mismatch',
            ], 422);
        }

        // Verification successful -> Finalize authentication
        $remember = (bool) $request->session()->get('face_auth:remember', false);
        $this->clearFaceAuthSession($request);

        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('face_verified_at', now()->timestamp);

        $targetUrl = $user->isAdmin()
            ? route('admin.dashboard')
            : route('owner.dashboard');

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi wajah berhasil. Selamat datang kembali!',
            'redirect_url' => $targetUrl,
        ]);
    }

    /**
     * Cancel the face verification challenge and return to login.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $this->clearFaceAuthSession($request);

        return redirect()->route('login')
            ->with('info', 'Proses verifikasi wajah dibatalkan.');
    }

    /**
     * Clear temporary face auth session variables.
     */
    protected function clearFaceAuthSession(Request $request): void
    {
        $request->session()->forget([
            'face_auth:user_id',
            'face_auth:remember',
            'face_auth:auth_time',
            'face_auth:role',
        ]);
    }
}
