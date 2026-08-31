<?php

namespace App\Services;

use App\Models\TwoFactorCode;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TwoFactorService
{
    /**
     * OTP validity duration in minutes.
     */
    public const OTP_EXPIRY_MINUTES = 5;

    /**
     * Resend cooldown duration in seconds.
     */
    public const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * Maximum allowed verification attempts per OTP.
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Generate a new cryptographically secure 6-digit OTP for the user.
     */
    public function generateOtp(User $user, string $action = 'login'): string
    {
        // 1. Invalidate any existing active OTP for this user and action
        TwoFactorCode::where('user_id', $user->id)
            ->where('action', $action)
            ->whereNull('used_at')
            ->update([
                'expires_at' => now(),
            ]);

        // 2. Generate random 6-digit numeric string
        $code = (string) random_int(100000, 999999);

        // 3. Store hash securely in database
        TwoFactorCode::create([
            'user_id' => $user->id,
            'action' => $action,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'used_at' => null,
            'attempts' => 0,
        ]);

        Log::info("2FA OTP dibuat untuk user_id: {$user->id}, action: {$action}");

        return $code;
    }

    /**
     * Send OTP notification to the user's email.
     */
    public function sendOtp(User $user, string $action = 'login'): bool
    {
        try {
            $code = $this->generateOtp($user, $action);
            $user->notify(new TwoFactorCodeNotification($code, $action));

            Log::info("2FA OTP berhasil dikirimkan ke email untuk user_id: {$user->id}, action: {$action}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal mengirimkan 2FA OTP untuk user_id: {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify the provided OTP code for the user.
     *
     * @return array{status: bool, reason: string, message: string}
     */
    public function verifyOtp(User $user, string $code, string $action = 'login'): array
    {
        $record = TwoFactorCode::where('user_id', $user->id)
            ->where('action', $action)
            ->latest('id')
            ->first();

        if (!$record || $record->isUsed()) {
            Log::warning("2FA verifikasi gagal: kode tidak ditemukan atau sudah digunakan untuk user_id: {$user->id}, action: {$action}");
            return [
                'status' => false,
                'reason' => 'invalid',
                'message' => 'Kode verifikasi salah atau sudah tidak berlaku.',
            ];
        }

        if ($record->hasExceededMaxAttempts(self::MAX_ATTEMPTS)) {
            $record->update(['expires_at' => now()]);
            Log::warning("2FA verifikasi gagal: batas maksimal percobaan terlampaui untuk user_id: {$user->id}");
            return [
                'status' => false,
                'reason' => 'max_attempts',
                'message' => 'Terlalu banyak percobaan. Silakan minta kode verifikasi baru.',
            ];
        }

        // Increment attempts count
        $record->increment('attempts');

        if ($record->isExpired()) {
            Log::warning("2FA verifikasi gagal: kode telah kedaluwarsa untuk user_id: {$user->id}");
            return [
                'status' => false,
                'reason' => 'expired',
                'message' => 'Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.',
            ];
        }

        // Verify hash
        if (Hash::check($code, $record->code_hash)) {
            $record->update(['used_at' => now()]);
            Log::info("2FA verifikasi berhasil untuk user_id: {$user->id}, action: {$action}");
            return [
                'status' => true,
                'reason' => 'success',
                'message' => 'Verifikasi berhasil.',
            ];
        }

        if ($record->attempts >= self::MAX_ATTEMPTS) {
            $record->update(['expires_at' => now()]);
            Log::warning("2FA verifikasi gagal: batas maksimal 5x tercapai untuk user_id: {$user->id}");
            return [
                'status' => false,
                'reason' => 'max_attempts',
                'message' => 'Terlalu banyak percobaan. Silakan minta kode verifikasi baru.',
            ];
        }

        Log::warning("2FA verifikasi gagal: kode salah (percobaan ke-{$record->attempts}) untuk user_id: {$user->id}");
        return [
            'status' => false,
            'reason' => 'incorrect',
            'message' => 'Kode verifikasi yang Anda masukkan salah.',
        ];
    }

    /**
     * Check if user can request a resend (cooldown check).
     */
    public function canResend(User $user, string $action = 'login'): bool
    {
        $latest = TwoFactorCode::where('user_id', $user->id)
            ->where('action', $action)
            ->latest('id')
            ->first();

        if (!$latest) {
            return true;
        }

        return now()->diffInSeconds($latest->created_at) >= self::RESEND_COOLDOWN_SECONDS;
    }

    /**
     * Get remaining cooldown seconds before next allowed resend.
     */
    public function getResendCooldownSeconds(User $user, string $action = 'login'): int
    {
        $latest = TwoFactorCode::where('user_id', $user->id)
            ->where('action', $action)
            ->latest('id')
            ->first();

        if (!$latest) {
            return 0;
        }

        $elapsed = now()->diffInSeconds($latest->created_at);
        return max(0, self::RESEND_COOLDOWN_SECONDS - $elapsed);
    }
}
