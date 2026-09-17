<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    use HasFactory;

    protected $table = 'blocked_ips';

    protected $fillable = [
        'ip_address',
        'reason',
    ];

    /**
     * Check if a given IP is blocked.
     */
    public static function isBlocked(?string $ip): bool
    {
        if (empty($ip)) {
            return false;
        }

        try {
            return static::where('ip_address', $ip)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
