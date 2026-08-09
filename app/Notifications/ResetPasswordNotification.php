<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi Tokobii')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Tokobii Anda.')
            ->line('Tombol di bawah akan membawa Anda ke halaman untuk membuat kata sandi baru.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line('Tautan ini berlaku selama 60 menit.')
            ->line('Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini.')
            ->salutation('Salam, Tim Tokobii');
    }
}
