<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification
{
    use Queueable;

    /**
     * The 6-digit OTP code.
     *
     * @var string
     */
    public string $code;

    /**
     * The action purpose (e.g. 'login' or 'enable_2fa').
     *
     * @var string
     */
    public string $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $code, string $action = 'login')
    {
        $this->code = $code;
        $this->action = $action;
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
     * Build the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $actionText = $this->action === 'enable_2fa'
            ? 'mengaktifkan Verifikasi 2 Langkah'
            : 'masuk ke akun Tokobii Anda';

        $subject = $this->action === 'enable_2fa'
            ? 'Kode Konfirmasi Aktivasi Verifikasi 2 Langkah - Tokobii'
            : 'Kode Verifikasi 2 Langkah (2FA) - Tokobii';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami menerima permintaan untuk ' . $actionText . '.')
            ->line('Gunakan kode verifikasi berikut untuk menyelesaikan proses:')
            ->line('**' . $this->code . '**')
            ->line('Kode verifikasi ini **hanya berlaku selama 5 menit** dan **hanya dapat digunakan satu kali**.')
            ->line('Demi keamanan akun Anda, **jangan pernah membagikan kode ini kepada siapapun**, termasuk pihak yang mengaku dari Tokobii.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dan segera ubah kata sandi akun Anda untuk menjaga keamanan.')
            ->salutation('Salam hangat, Tim Tokobii');
    }
}
