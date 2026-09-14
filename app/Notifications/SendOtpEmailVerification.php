<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendOtpEmailVerification extends Notification
{
    use Queueable;

    public string $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode OTP Verifikasi Email - Tokobii')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah mendaftar akun di Tokobii.')
            ->line('Silakan masukkan 6 digit kode OTP verifikasi berikut pada halaman verifikasi akun Anda:')
            ->line(new HtmlString('<div style="text-align: center; margin: 28px 0;"><span style="display: inline-block; font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #2563eb; background-color: #eff6ff; padding: 14px 28px; border-radius: 12px; border: 2px dashed #93c5fd; font-family: monospace;">' . $this->otp . '</span></div>'))
            ->line('Kode OTP ini bersifat rahasia dan berlaku selama **15 menit**.')
            ->line('Jangan bagikan kode verifikasi ini kepada siapapun demi keamanan akun Anda.')
            ->line('Jika Anda tidak pernah merasa mendaftar di Tokobii, silakan abaikan email ini.')
            ->salutation('Salam hangat, Tim Tokobii');
    }
}
