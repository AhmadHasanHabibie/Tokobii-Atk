<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Handle the Failed login event.
     */
    public function handle(Failed $event): void
    {
        try {
            $userId = $event->user?->id;

            // If user is null, try resolving existing user id by email from credentials
            if (! $userId && ! empty($event->credentials['email'])) {
                $userId = User::where('email', $event->credentials['email'])->value('id');
            }

            LoginHistory::create([
                'user_id' => $userId,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent(),
                'status' => 'gagal',
            ]);
        } catch (\Throwable $e) {
            // Failsafe: logging should never prevent application execution
            report($e);
        }
    }
}
