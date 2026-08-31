<?php

namespace App\Services;

use App\Models\FaceVerification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class FaceVerificationService
{
    /**
     * Standard Euclidean distance threshold for 128-D Face Recognition vector.
     * Distance <= 0.48 represents the exact same person.
     * Distance > 0.48 represents a different person or mismatch.
     */
    public const MATCH_THRESHOLD = 0.48;

    /**
     * Maximum allowed failed face verification attempts before lockout.
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Temporary lockout duration in minutes after exceeding max failed attempts.
     */
    public const LOCKOUT_MINUTES = 15;

    /**
     * Required dimensionality of the face descriptor embedding vector.
     */
    public const VECTOR_DIMENSIONS = 128;

    /**
     * Enroll or update face biometrics for a user.
     */
    public function enroll(User $user, array $descriptor, ?string $deviceInfo = null): FaceVerification
    {
        $this->assertValidVector($descriptor);

        $faceVerification = FaceVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'face_descriptor' => array_values(array_map('floatval', $descriptor)),
                'is_active' => true,
                'enrolled_at' => now(),
                'last_verified_at' => null,
                'failed_attempts' => 0,
                'locked_until' => null,
                'device_info' => $deviceInfo,
            ]
        );

        $user->update([
            'face_verification_enabled' => true,
        ]);

        Log::info("Biometrik wajah berhasil didaftarkan untuk user_id: {$user->id}, role: {$user->role}");

        return $faceVerification;
    }

    /**
     * Verify a challenge face descriptor against the stored biometric enrollment.
     *
     * @return array{status: bool, reason: string, message: string, distance?: float}
     */
    public function verify(User $user, array $descriptor, ?float $threshold = null): array
    {
        $this->assertValidVector($descriptor);

        $record = FaceVerification::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$record || empty($record->face_descriptor)) {
            Log::warning("Verifikasi wajah gagal: data biometrik tidak ditemukan untuk user_id: {$user->id}");
            return [
                'status' => false,
                'reason' => 'not_enrolled',
                'message' => 'Akun ini belum memiliki data biometrik wajah terdaftar.',
            ];
        }

        if ($record->isLocked()) {
            $minutes = $record->remainingLockoutMinutes();
            Log::warning("Verifikasi wajah ditolak: akun terkunci sementara untuk user_id: {$user->id}, sisa {$minutes} menit");
            return [
                'status' => false,
                'reason' => 'locked',
                'message' => "Terlalu banyak percobaan verifikasi yang salah. Akun Anda terkunci sementara selama {$minutes} menit.",
            ];
        }

        $threshold = $threshold ?? self::MATCH_THRESHOLD;
        $distance = $this->computeEuclideanDistance($record->face_descriptor, $descriptor);

        if ($distance <= $threshold) {
            $record->resetLockout();

            Log::info("Verifikasi wajah BERHASIL untuk user_id: {$user->id}, distance: {$distance}, threshold: {$threshold}");

            return [
                'status' => true,
                'reason' => 'matched',
                'message' => 'Verifikasi wajah berhasil.',
                'distance' => round($distance, 4),
            ];
        }

        // Distance exceeds threshold -> Mismatch
        $record->recordFailedAttempt(self::MAX_ATTEMPTS, self::LOCKOUT_MINUTES);

        $attempts = $record->fresh()->failed_attempts;
        $isNowLocked = $record->fresh()->isLocked();

        Log::warning("Verifikasi wajah GAGAL (tidak cocok) untuk user_id: {$user->id}, distance: {$distance}, threshold: {$threshold}, percobaan ke-{$attempts}");

        if ($isNowLocked) {
            return [
                'status' => false,
                'reason' => 'locked',
                'message' => "Terlalu banyak percobaan verifikasi yang salah. Akun Anda terkunci sementara selama " . self::LOCKOUT_MINUTES . " menit.",
                'distance' => round($distance, 4),
            ];
        }

        $remainingAttempts = max(0, self::MAX_ATTEMPTS - $attempts);

        return [
            'status' => false,
            'reason' => 'mismatch',
            'message' => "Wajah tidak cocok dengan data biometrik akun ini. Sisa kesempatan: {$remainingAttempts} kali.",
            'distance' => round($distance, 4),
        ];
    }

    /**
     * Disable face verification for a user.
     */
    public function disable(User $user): void
    {
        $user->update([
            'face_verification_enabled' => false,
        ]);

        FaceVerification::where('user_id', $user->id)->update([
            'is_active' => false,
        ]);

        Log::info("Verifikasi wajah dinonaktifkan untuk user_id: {$user->id}");
    }

    /**
     * Compute Euclidean Distance between two 128-dimensional floating point vectors.
     */
    public function computeEuclideanDistance(array $vectorA, array $vectorB): float
    {
        if (count($vectorA) !== self::VECTOR_DIMENSIONS || count($vectorB) !== self::VECTOR_DIMENSIONS) {
            throw new InvalidArgumentException("Vektor biometrik wajah harus memiliki tepat " . self::VECTOR_DIMENSIONS . " dimensi.");
        }

        $sum = 0.0;
        for ($i = 0; $i < self::VECTOR_DIMENSIONS; $i++) {
            $diff = (float) $vectorA[$i] - (float) $vectorB[$i];
            $sum += $diff * $diff;
        }

        return sqrt($sum);
    }

    /**
     * Validate that descriptor is an array of exactly 128 floats.
     */
    public function assertValidVector(array $vector): void
    {
        if (count($vector) !== self::VECTOR_DIMENSIONS) {
            throw new InvalidArgumentException("Format data biometrik wajah tidak valid (harus 128 dimensi).");
        }

        foreach ($vector as $val) {
            if (!is_numeric($val)) {
                throw new InvalidArgumentException("Nilai koordinat biometrik wajah harus berupa angka numerik.");
            }
        }
    }
}
