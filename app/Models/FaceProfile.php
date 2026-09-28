<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class FaceProfile extends Model
{
    use HasFactory;

    protected $table = 'face_profiles';

    protected $fillable = [
        'user_id',
        'embedding',
        'samples',
        'engine_version',
        'is_active',
        'needs_re_enroll',
        'enrolled_at',
        'last_verified_at',
        'failed_attempts',
        'locked_until',
        'device_info',
        'recovery_code',
        'recovery_code_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'needs_re_enroll' => 'boolean',
        'enrolled_at' => 'datetime',
        'last_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'recovery_code_used_at' => 'datetime',
        'failed_attempts' => 'integer',
    ];

    /**
     * Hidden sensitive attributes from JSON output.
     */
    protected $hidden = [
        'embedding',
        'samples',
        'recovery_code',
    ];

    /**
     * Relationship to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve decrypted embedding vector as an array of floats.
     *
     * @return array<float>|null
     */
    public function getDecryptedEmbedding(): ?array
    {
        if (empty($this->embedding)) {
            return null;
        }

        try {
            $json = Crypt::decryptString($this->embedding);
            $array = json_decode($json, true);
            return is_array($array) ? array_map('floatval', $array) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Store encrypted embedding vector.
     *
     * @param array<float> $vector
     */
    public function setEncryptedEmbedding(array $vector): void
    {
        $normalized = array_values(array_map('floatval', $vector));
        $this->attributes['embedding'] = Crypt::encryptString(json_encode($normalized));
    }

    /**
     * Store encrypted multiple samples.
     *
     * @param array<array<float>> $samples
     */
    public function setEncryptedSamples(array $samples): void
    {
        $normalized = array_map(function ($s) {
            return array_values(array_map('floatval', $s));
        }, $samples);

        $this->attributes['samples'] = Crypt::encryptString(json_encode($normalized));
    }

    /**
     * Retrieve decrypted multi-samples.
     *
     * @return array<array<float>>|null
     */
    public function getDecryptedSamples(): ?array
    {
        if (empty($this->samples)) {
            return null;
        }

        try {
            $json = Crypt::decryptString($this->samples);
            return json_decode($json, true);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Check if profile is temporarily locked out due to excessive failed attempts.
     */
    public function isLocked(): bool
    {
        return !is_null($this->locked_until) && now()->isBefore($this->locked_until);
    }

    /**
     * Remaining lockout duration in minutes.
     */
    public function remainingLockoutMinutes(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($this->locked_until) / 60);
    }

    /**
     * Increment failed attempt count and lock if limit is reached.
     */
    public function recordFailedAttempt(int $maxAttempts = 5, int $lockoutMinutes = 15): void
    {
        $this->increment('failed_attempts');

        if ($this->failed_attempts >= $maxAttempts) {
            $this->update([
                'locked_until' => now()->addMinutes($lockoutMinutes),
            ]);
        }
    }

    /**
     * Reset failed attempts and clear lockout on successful match.
     */
    public function resetLockout(): void
    {
        $this->update([
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_verified_at' => now(),
        ]);
    }

    /**
     * Generate an 8-character alphanumeric one-time emergency recovery code.
     */
    public function generateRecoveryCode(): string
    {
        $code = strtoupper(Str::random(4) . '-' . Str::random(4));
        $this->update([
            'recovery_code' => hash('sha256', $code),
            'recovery_code_used_at' => null,
        ]);

        return $code;
    }

    /**
     * Verify and consume a one-time emergency recovery code.
     */
    public function verifyAndConsumeRecoveryCode(string $plainCode): bool
    {
        $clean = strtoupper(trim($plainCode));
        $hashed = hash('sha256', $clean);

        if ($this->recovery_code && hash_equals($this->recovery_code, $hashed) && is_null($this->recovery_code_used_at)) {
            $this->update([
                'recovery_code_used_at' => now(),
                'failed_attempts' => 0,
                'locked_until' => null,
                'last_verified_at' => now(),
            ]);
            return true;
        }

        return false;
    }
}
