<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Handle the Login event.
     */
    public function handle(Login $event): void
    {
        try {
            LoginHistory::create([
                'user_id' => $event->user?->id,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent(),
                'status' => 'sukses',
            ]);
        } catch (\Throwable $e) {
            // Failsafe: logging should never prevent user login
            report($e);
        }
    }
}
