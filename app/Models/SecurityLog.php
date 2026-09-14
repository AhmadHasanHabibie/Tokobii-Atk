<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    use HasFactory;

    protected $table = 'security_logs';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'event_type',
        'severity',
        'endpoint',
        'method',
        'raw_payload',
        'response_status',
    ];

    protected $casts = [
        'response_status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to log a security event directly.
     */
    public static function record(
        string $eventType,
        string $severity = 'medium',
        ?string $endpoint = null,
        ?string $method = 'GET',
        $payload = null,
        ?int $responseStatus = 200,
        ?int $userId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        $sanitizedPayload = is_array($payload) || is_object($payload)
            ? json_encode(static::sanitizePayload($payload), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            : (is_string($payload) ? $payload : null);

        return static::create([
            'user_id' => $userId ?? (auth()->check() ? auth()->id() : null),
            'ip_address' => $ipAddress ?? request()->ip() ?? '127.0.0.1',
            'user_agent' => $userAgent ?? request()->userAgent(),
            'event_type' => $eventType,
            'severity' => $severity,
            'endpoint' => $endpoint ?? request()->path(),
            'method' => strtoupper($method ?? request()->method()),
            'raw_payload' => $sanitizedPayload,
            'response_status' => $responseStatus,
        ]);
    }

    /**
     * Recursively sanitize sensitive fields from payload before storage.
     */
    public static function sanitizePayload($data)
    {
        if (is_object($data)) {
            $data = (array) $data;
        }

        if (!is_array($data)) {
            return $data;
        }

        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'new_password_confirmation',
            'token',
            '_token',
            'secret',
            'otp',
            'email_verification_otp',
            'two_factor_code',
            'credit_card',
            'cvv',
            'pin',
            'api_key',
            'authorization',
        ];

        $sanitized = [];
        foreach ($data as $key => $value) {
            $lowerKey = strtolower((string) $key);
            if (in_array($lowerKey, $sensitiveKeys, true)) {
                $sanitized[$key] = '[REDACTED_SEC_PAYLOAD]';
            } elseif (is_array($value) || is_object($value)) {
                $sanitized[$key] = static::sanitizePayload($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }
}
