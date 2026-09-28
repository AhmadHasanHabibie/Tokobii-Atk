<?php

namespace App\Services;

use App\Models\FaceProfile;
use App\Models\FaceVerification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class FaceVerificationService
{
    /**
     * Standard Euclidean distance threshold for legacy 128-D Face Recognition vector.
     */
    public const LEGACY_MATCH_THRESHOLD = 0.45;

    /**
     * Default cosine similarity threshold for @vladmandic/human (1024-D).
     * Value >= 0.70 represents the same person with high confidence.
     */
    public const DEFAULT_COSINE_THRESHOLD = 0.70;

    /**
     * Maximum allowed failed face verification attempts before lockout.
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Temporary lockout duration in minutes after exceeding max failed attempts.
     */
    public const LOCKOUT_MINUTES = 15;

    /**
     * Enroll or update face biometrics for a user using the new Human engine (encrypted).
     *
     * @param array<float> $embedding Centroid embedding vector (e.g. 1024-D)
     * @param array<array<float>> $samples Optional 5 sample vectors
     */
    public function enrollHuman(User $user, array $embedding, array $samples = [], ?string $deviceInfo = null): FaceProfile
    {
        $this->assertValidVectorArray($embedding);

        $engineVersion = config('face.engine_version', 'human-v3');

        $profile = FaceProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'engine_version' => $engineVersion,
                'is_active' => true,
                'needs_re_enroll' => false,
                'enrolled_at' => now(),
                'last_verified_at' => null,
                'failed_attempts' => 0,
                'locked_until' => null,
                'device_info' => $deviceInfo,
            ]
        );

        $profile->setEncryptedEmbedding($embedding);
        if (!empty($samples)) {
            $profile->setEncryptedSamples($samples);
        }
        $profile->save();

        // Also sync legacy FaceVerification record for backward compatibility
        try {
            FaceVerification::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'face_descriptor' => count($embedding) === 128 ? $embedding : array_slice($embedding, 0, 128),
                    'is_active' => true,
                    'enrolled_at' => now(),
                    'failed_attempts' => 0,
                    'locked_until' => null,
                    'device_info' => $deviceInfo,
                ]
            );
        } catch (\Throwable $e) {}

        $user->update([
            'face_verification_enabled' => true,
        ]);

        Log::info("Biometrik wajah (@vladmandic/human) berhasil didaftarkan untuk user_id: {$user->id}, role: {$user->role}");

        return $profile;
    }

    /**
     * Legacy enrollment method kept for backward compatibility with 128-D descriptors.
     */
    public function enroll(User $user, array $descriptor, ?string $deviceInfo = null): FaceVerification
    {
        $this->assertValidVectorArray($descriptor);

        // Also create/update FaceProfile
        $profile = FaceProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'engine_version' => count($descriptor) === 128 ? 'legacy-face-api-128' : config('face.engine_version', 'human-v3'),
                'is_active' => true,
                'needs_re_enroll' => false,
                'enrolled_at' => now(),
                'last_verified_at' => null,
                'failed_attempts' => 0,
                'locked_until' => null,
                'device_info' => $deviceInfo,
            ]
        );
        $profile->setEncryptedEmbedding($descriptor);
        $profile->save();

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
     * Comprehensive Biometric Verification for Human Engine.
     * Validates anti-spoofing, liveness, and embedding similarity on the server.
     *
     * @return array{status: bool, reason: string, message: string, similarity?: float, antispoof?: float, liveness?: float, distance?: float}
     */
    public function verifyBiometrics(
        User $user,
        array $embedding,
        float $antispoofScore = 1.0,
        float $livenessScore = 1.0,
        ?float $threshold = null
    ): array {
        $this->assertValidVectorArray($embedding);

        $maxAttempts = config('face.max_attempts', self::MAX_ATTEMPTS);
        $lockMinutes = config('face.lock_minutes', self::LOCKOUT_MINUTES);
        $antispoofThreshold = config('face.antispoof_threshold', 0.40);
        $livenessThreshold = config('face.liveness_threshold', 0.40);
        $matchThreshold = $threshold ?? config('face.match_threshold', self::DEFAULT_COSINE_THRESHOLD);

        // 1. Ambil data FaceProfile terbaru
        $profile = FaceProfile::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        // Fallback ke legacy FaceVerification jika FaceProfile belum ada
        if (!$profile) {
            $legacy = FaceVerification::where('user_id', $user->id)->where('is_active', true)->first();
            if ($legacy && !empty($legacy->face_descriptor)) {
                return $this->verify($user, $embedding, $threshold);
            }

            Log::warning("Verifikasi wajah gagal: data biometrik tidak ditemukan untuk user_id: {$user->id}");
            return [
                'status' => false,
                'reason' => 'not_enrolled',
                'message' => 'Akun ini belum memiliki data biometrik wajah terdaftar.',
            ];
        }

        // 2. Cek apakah user perlu daftar ulang (Migrasi dari model lama)
        if ($profile->needs_re_enroll) {
            Log::info("Verifikasi wajah ditolak: akun user_id: {$user->id} memerlukan pendaftaran ulang biometrik.");
            return [
                'status' => false,
                'reason' => 'needs_re_enroll',
                'message' => 'Sistem pengenalan wajah telah ditingkatkan untuk keamanan lebih tinggi. Silakan lakukan pendaftaran ulang biometrik wajah di profil Anda.',
            ];
        }

        // 3. Cek Lockout
        if ($profile->isLocked()) {
            $minutes = $profile->remainingLockoutMinutes();
            Log::warning("Verifikasi wajah ditolak: akun terkunci sementara untuk user_id: {$user->id}, sisa {$minutes} menit");
            return [
                'status' => false,
                'reason' => 'locked',
                'message' => "Terlalu banyak percobaan verifikasi yang salah. Akun Anda terkunci sementara selama {$minutes} menit.",
            ];
        }

        // 4. Validasi Anti-Spoofing & Liveness
        if ($antispoofScore < $antispoofThreshold) {
            $profile->recordFailedAttempt($maxAttempts, $lockMinutes);
            Log::warning("Anti-Spoofing TRIGGERED: Terdeteksi foto/layar palsu untuk user_id: {$user->id}, score: {$antispoofScore}, threshold: {$antispoofThreshold}");
            return [
                'status' => false,
                'reason' => 'spoof_detected',
                'message' => 'Keamanan: Wajah tidak terdeteksi sebagai objek nyata (terindikasi foto/layar digital). Silakan hadapkan wajah asli Anda ke kamera.',
                'antispoof' => round($antispoofScore, 4),
            ];
        }

        if ($livenessScore < $livenessThreshold) {
            $profile->recordFailedAttempt($maxAttempts, $lockMinutes);
            Log::warning("Liveness TRIGGERED: Tidak ada interaksi aktif untuk user_id: {$user->id}, score: {$livenessScore}, threshold: {$livenessThreshold}");
            return [
                'status' => false,
                'reason' => 'liveness_failed',
                'message' => 'Keamanan: Uji keaktifan wajah (liveness) gagal. Pastikan pencahayaan cukup dan lakukan gerakan yang diminta.',
                'liveness' => round($livenessScore, 4),
            ];
        }

        // 5. Dekripsi Stored Embedding
        $storedEmbedding = $profile->getDecryptedEmbedding();
        if (!$storedEmbedding) {
            return [
                'status' => false,
                'reason' => 'corrupt_data',
                'message' => 'Data biometrik tersimpan tidak dapat dibaca. Silakan hubungi Superadmin.',
            ];
        }

        // 6. Hitung Kemiripan (Cosine Similarity)
        $similarity = $this->computeCosineSimilarity($storedEmbedding, $embedding);
        $distance = round(1.0 - $similarity, 4);

        $telemetry = [
            'similarity' => round($similarity, 4),
            'distance' => $distance,
            'antispoof' => round($antispoofScore, 4),
            'liveness' => round($livenessScore, 4),
        ];

        // 7. Evaluasi Kesesuaian Wajah
        if ($similarity >= $matchThreshold) {
            $profile->resetLockout();

            // Sync legacy table if present
            FaceVerification::where('user_id', $user->id)->update([
                'failed_attempts' => 0,
                'locked_until' => null,
                'last_verified_at' => now(),
            ]);

            Log::info("Verifikasi wajah BERHASIL (@vladmandic/human) untuk user_id: {$user->id}, similarity: {$similarity}, threshold: {$matchThreshold}");

            return array_merge([
                'status' => true,
                'reason' => 'matched',
                'message' => 'Verifikasi wajah berhasil. Identitas Anda terkonfirmasi.',
            ], $telemetry);
        }

        // 8. Jika Tidak Cocok -> Catat Percobaan Gagal
        $profile->recordFailedAttempt($maxAttempts, $lockMinutes);
        $attempts = $profile->fresh()->failed_attempts;
        $isNowLocked = $profile->fresh()->isLocked();

        Log::warning("Verifikasi wajah GAGAL (tidak cocok) untuk user_id: {$user->id}, similarity: {$similarity}, threshold: {$matchThreshold}, percobaan ke-{$attempts}");

        if ($isNowLocked) {
            return array_merge([
                'status' => false,
                'reason' => 'locked',
                'message' => "Terlalu banyak percobaan verifikasi yang salah. Akun Anda terkunci sementara selama {$lockMinutes} menit.",
            ], $telemetry);
        }

        $remainingAttempts = max(0, $maxAttempts - $attempts);

        return array_merge([
            'status' => false,
            'reason' => 'mismatch',
            'message' => "Wajah tidak cocok dengan data biometrik akun ini. Sisa kesempatan: {$remainingAttempts} kali.",
        ], $telemetry);
    }

    /**
     * Legacy verify method for 128-D Euclidean distance.
     * Kept fully functional for backward compatibility and existing tests.
     */
    public function verify(User $user, array $descriptor, ?float $threshold = null): array
    {
        $this->assertValidVectorArray($descriptor);

        // Jika user memiliki FaceProfile dengan human-v3, arahkan ke verifyBiometrics
        $profile = FaceProfile::where('user_id', $user->id)->where('is_active', true)->first();
        if ($profile && $profile->embedding && $profile->engine_version === config('face.engine_version', 'human-v3')) {
            return $this->verifyBiometrics($user, $descriptor, 1.0, 1.0, $threshold);
        }

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
            return [
                'status' => false,
                'reason' => 'locked',
                'message' => "Terlalu banyak percobaan verifikasi yang salah. Akun Anda terkunci sementara selama {$minutes} menit.",
            ];
        }

        $threshold = $threshold ?? self::LEGACY_MATCH_THRESHOLD;
        $distance = $this->computeEuclideanDistance($record->face_descriptor, $descriptor);

        if ($distance <= $threshold) {
            $record->resetLockout();
            return [
                'status' => true,
                'reason' => 'matched',
                'message' => 'Verifikasi wajah berhasil.',
                'distance' => round($distance, 4),
            ];
        }

        $record->recordFailedAttempt(self::MAX_ATTEMPTS, self::LOCKOUT_MINUTES);
        $attempts = $record->fresh()->failed_attempts;
        $isNowLocked = $record->fresh()->isLocked();

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

        FaceProfile::where('user_id', $user->id)->update([
            'is_active' => false,
        ]);

        FaceVerification::where('user_id', $user->id)->update([
            'is_active' => false,
        ]);

        Log::info("Verifikasi wajah dinonaktifkan untuk user_id: {$user->id}");
    }

    /**
     * Compute Cosine Similarity between two floating point vectors.
     * Returns a float between -1.0 and 1.0 (1.0 = identical direction).
     */
    public function computeCosineSimilarity(array $vectorA, array $vectorB): float
    {
        $lenA = count($vectorA);
        $lenB = count($vectorB);

        if ($lenA === 0 || $lenB === 0) {
            return 0.0;
        }

        $dim = min($lenA, $lenB);

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < $dim; $i++) {
            $valA = (float) $vectorA[$i];
            $valB = (float) $vectorB[$i];

            $dotProduct += $valA * $valB;
            $normA += $valA * $valA;
            $normB += $valB * $valB;
        }

        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }

        $similarity = $dotProduct / (sqrt($normA) * sqrt($normB));

        return max(-1.0, min(1.0, $similarity));
    }

    /**
     * Compute Euclidean Distance between two vectors.
     */
    public function computeEuclideanDistance(array $vectorA, array $vectorB): float
    {
        $dim = min(count($vectorA), count($vectorB));
        if ($dim === 0) {
            throw new InvalidArgumentException("Vektor biometrik wajah tidak boleh kosong.");
        }

        $sum = 0.0;
        for ($i = 0; $i < $dim; $i++) {
            $diff = (float) $vectorA[$i] - (float) $vectorB[$i];
            $sum += $diff * $diff;
        }

        return sqrt($sum);
    }

    /**
     * Validate that descriptor is an array of numeric values.
     */
    public function assertValidVectorArray(array $vector): void
    {
        if (empty($vector) || count($vector) < 16) {
            throw new InvalidArgumentException("Format data biometrik wajah tidak valid (vektor terlalu pendek).");
        }

        foreach ($vector as $val) {
            if (!is_numeric($val)) {
                throw new InvalidArgumentException("Nilai koordinat biometrik wajah harus berupa angka numerik.");
            }
        }
    }

    /**
     * Backward-compatible validator for 128-D vectors.
     */
    public function assertValidVector(array $vector): void
    {
        $this->assertValidVectorArray($vector);
    }
}
