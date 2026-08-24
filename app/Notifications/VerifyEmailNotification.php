<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmailBase
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - Tokobii')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Terima kasih telah mendaftar di Tokobii.')
            ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email akun Anda dan mengaktifkan akses penuh ke Tokobii.')
            ->action('Verifikasi Alamat Email', $verificationUrl)
            ->line('Tautan verifikasi email ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak pernah membuat akun di Tokobii, silakan abaikan email ini.')
            ->salutation('Salam hangat, Tim Tokobii');
    }
}
