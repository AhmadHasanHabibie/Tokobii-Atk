<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FaceProfile;
use App\Models\User;
use App\Services\FaceVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        $needsReEnroll = false;

        $profile = FaceProfile::where('user_id', $user->id)->first();
        if ($profile) {
            $isLocked = $profile->isLocked();
            $remainingLockout = $profile->remainingLockoutMinutes();
            $needsReEnroll = (bool) $profile->needs_re_enroll;
        } elseif ($user->faceVerification) {
            $isLocked = $user->faceVerification->isLocked();
            $remainingLockout = $user->faceVerification->remainingLockoutMinutes();
        }

        return view('auth.face-verification-challenge', [
            'user' => $user,
            'isLocked' => $isLocked,
            'remainingLockout' => $remainingLockout,
            'needsReEnroll' => $needsReEnroll,
        ]);
    }

    /**
     * Generate dynamic challenge nonce and random action prompt (single-use, 60s TTL).
     */
    public function getChallenge(Request $request): JsonResponse
    {
        $userId = $request->session()->get('face_auth:user_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak valid.',
            ], 401);
        }

        $nonce = Str::random(32);
        $actions = [
            'blink'      => 'Kedipkan kedua mata Anda perlahan',
            'turn_left'  => 'Tengok kepala ke kiri sedikit',
            'turn_right' => 'Tengok kepala ke kanan sedikit',
            'smile'      => 'Tersenyumlah ke arah kamera',
        ];

        $keys = array_keys($actions);
        $action = $keys[array_rand($keys)];
        $prompt = $actions[$action];
        $ttl = config('face.challenge_ttl_seconds', 60);

        $request->session()->put('face_auth:challenge_nonce', $nonce);
        $request->session()->put('face_auth:challenge_action', $action);
        $request->session()->put('face_auth:challenge_expires_at', now()->addSeconds($ttl)->timestamp);

        return response()->json([
            'success' => true,
            'nonce' => $nonce,
            'action' => $action,
            'prompt' => $prompt,
            'expires_in' => $ttl,
        ]);
    }

    /**
     * Process face matching verification from Human engine descriptor vector.
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

        // Validate nonce if provided (Single-use challenge token)
        if ($request->filled('nonce')) {
            $expectedNonce = $request->session()->get('face_auth:challenge_nonce');
            $expiresAt = $request->session()->get('face_auth:challenge_expires_at', 0);

            // Invalidate immediately to prevent replay attacks
            $request->session()->forget([
                'face_auth:challenge_nonce',
                'face_auth:challenge_action',
                'face_auth:challenge_expires_at',
            ]);

            if (!$expectedNonce || $expectedNonce !== $request->input('nonce') || now()->timestamp > $expiresAt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tantangan keamanan (challenge nonce) telah kedaluwarsa. Silakan ulangi pemindaian.',
                    'reason' => 'nonce_expired',
                ], 422);
            }
        }

        $validator = Validator::make($request->all(), [
            'descriptor' => ['required', 'array', 'min:16'],
            'descriptor.*' => ['required', 'numeric'],
            'antispoof_score' => ['nullable', 'numeric'],
            'liveness_score' => ['nullable', 'numeric'],
            'challenge_result' => ['nullable', 'boolean'],
        ], [
            'descriptor.required' => 'Data biometrik wajah wajib disertakan.',
            'descriptor.array' => 'Format biometrik wajah tidak valid.',
            'descriptor.min' => 'Data biometrik wajah tidak lengkap.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Check interactive challenge result if provided
        if ($request->has('challenge_result') && !$request->boolean('challenge_result')) {
            return response()->json([
                'success' => false,
                'message' => 'Tantangan interaksi wajah belum terpenuhi. Silakan ikuti instruksi yang ditampilkan.',
                'reason' => 'challenge_failed',
            ], 422);
        }

        $descriptor = $request->input('descriptor');
        $antispoofScore = (float) $request->input('antispoof_score', 1.0);
        $livenessScore = (float) $request->input('liveness_score', 1.0);

        // Run server-side biometric verification
        $result = $service->verifyBiometrics(
            $user,
            $descriptor,
            $antispoofScore,
            $livenessScore
        );

        if (!$result['status']) {
            $response = [
                'success' => false,
                'message' => $result['message'],
                'reason' => $result['reason'] ?? 'mismatch',
            ];

            // If calibration/debug mode is on, expose telemetry
            if (config('face.debug_mode')) {
                $response['telemetry'] = [
                    'similarity' => $result['similarity'] ?? null,
                    'antispoof'  => $result['antispoof'] ?? null,
                    'liveness'   => $result['liveness'] ?? null,
                    'distance'   => $result['distance'] ?? null,
                ];
            }

            return response()->json($response, 422);
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

        $response = [
            'success' => true,
            'message' => 'Verifikasi biometrik berhasil. Selamat datang kembali!',
            'redirect_url' => $targetUrl,
        ];

        if (config('face.debug_mode')) {
            $response['telemetry'] = [
                'similarity' => $result['similarity'] ?? null,
                'antispoof'  => $result['antispoof'] ?? null,
                'liveness'   => $result['liveness'] ?? null,
            ];
        }

        return response()->json($response);
    }

    /**
     * Emergency One-Time Recovery Code Verification.
     */
    public function verifyRecoveryCode(Request $request): JsonResponse
    {
        $userId = $request->session()->get('face_auth:user_id');
        $authTime = $request->session()->get('face_auth:auth_time');

        if (!$userId || !$authTime || (now()->timestamp - $authTime > self::SESSION_LIFETIME_SECONDS)) {
            $this->clearFaceAuthSession($request);
            return response()->json([
                'success' => false,
                'message' => 'Sesi telah berakhir. Silakan masuk kembali.',
                'redirect_url' => route('login'),
            ], 401);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'recovery_code' => ['required', 'string', 'min:8', 'max:16'],
        ], [
            'recovery_code.required' => 'Kode pemulihan darurat wajib diisi.',
            'recovery_code.min' => 'Format kode pemulihan darurat tidak valid.',
        ]);

        $profile = FaceProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profil biometrik tidak ditemukan.',
            ], 404);
        }

        $code = trim((string) $request->input('recovery_code'));
        if (!$profile->verifyAndConsumeRecoveryCode($code)) {
            Log::warning("Gagal verifikasi recovery code biometrik untuk user_id: {$user->id}, IP: {$request->ip()}");
            return response()->json([
                'success' => false,
                'message' => 'Kode pemulihan darurat salah atau sudah pernah digunakan sebelumnya.',
            ], 422);
        }

        Log::info("Recovery code biometrik BERHASIL digunakan untuk user_id: {$user->id}");

        $remember = (bool) $request->session()->get('face_auth:remember', false);
        $this->clearFaceAuthSession($request);

        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->put('face_verified_at', now()->timestamp);

        $targetUrl = $user->isAdmin() ? route('admin.dashboard') : route('owner.dashboard');

        return response()->json([
            'success' => true,
            'message' => 'Kode pemulihan darurat berhasil diverifikasi. Selamat datang kembali!',
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
            'face_auth:challenge_nonce',
            'face_auth:challenge_action',
            'face_auth:challenge_expires_at',
        ]);
    }
}
