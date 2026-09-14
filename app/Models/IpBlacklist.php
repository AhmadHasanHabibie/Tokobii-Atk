<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpBlacklist extends Model
{
    use HasFactory;

    protected $table = 'ip_blacklists';

    protected $fillable = [
        'ip_address',
        'reason',
        'user_agent',
        'hit_count',
        'is_permanent',
        'blocked_until',
        'blocked_by',
    ];

    protected $casts = [
        'is_permanent' => 'boolean',
        'blocked_until' => 'datetime',
        'hit_count' => 'integer',
    ];

    /**
     * Check if a given IP address is currently blocked.
     */
    public static function isBlocked(?string $ip): bool
    {
        if (empty($ip)) {
            return false;
        }

        $entry = static::where('ip_address', $ip)->first();

        if (!$entry) {
            return false;
        }

        if ($entry->is_permanent) {
            return true;
        }

        if ($entry->blocked_until && $entry->blocked_until->isFuture()) {
            return true;
        }

        return false;
    }

    /**
     * Block an IP address or increment hit count if already recorded.
     */
    public static function blockIp(
        string $ip,
        string $reason = 'Suspicious Security Activity',
        ?string $userAgent = null,
        bool $permanent = true,
        ?string $blockedBy = 'honeypot_system',
        ?\DateTimeInterface $blockedUntil = null
    ): self {
        $entry = static::where('ip_address', $ip)->first();

        if ($entry) {
            $entry->increment('hit_count');
            $entry->update([
                'reason' => $reason,
                'user_agent' => $userAgent ?? $entry->user_agent,
                'is_permanent' => $permanent,
                'blocked_until' => $blockedUntil,
                'blocked_by' => $blockedBy ?? $entry->blocked_by,
            ]);
            return $entry;
        }

        return static::create([
            'ip_address' => $ip,
            'reason' => $reason,
            'user_agent' => $userAgent,
            'hit_count' => 1,
            'is_permanent' => $permanent,
            'blocked_until' => $blockedUntil,
            'blocked_by' => $blockedBy ?? 'honeypot_system',
        ]);
    }
}
