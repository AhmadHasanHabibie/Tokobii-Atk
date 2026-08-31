<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceVerification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'face_verifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'face_descriptor',
        'is_active',
        'enrolled_at',
        'last_verified_at',
        'failed_attempts',
        'locked_until',
        'device_info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'face_descriptor' => 'array',
        'is_active' => 'boolean',
        'enrolled_at' => 'datetime',
        'last_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'failed_attempts' => 'integer',
    ];

    /**
     * Relationship to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the verification is currently locked due to too many failed attempts.
     */
    public function isLocked(): bool
    {
        return !is_null($this->locked_until) && now()->isBefore($this->locked_until);
    }

    /**
     * Get remaining lockout duration in minutes.
     */
    public function remainingLockoutMinutes(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }

        return (int) ceil(now()->diffInSeconds($this->locked_until) / 60);
    }

    /**
     * Record a failed verification attempt and apply lockout if limit reached.
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
     * Reset failed attempts and clear lockout upon successful verification.
     */
    public function resetLockout(): void
    {
        $this->update([
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_verified_at' => now(),
        ]);
    }
}
