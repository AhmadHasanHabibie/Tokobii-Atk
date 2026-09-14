<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\SendOtpEmailVerification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
        $response->assertSee('Verifikasi Alamat Email');
        $response->assertSee('Masukkan 6 Digit Kode OTP');
    }

    public function test_email_can_be_verified_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'email_verification_otp' => '123456',
            'email_verification_otp_expires_at' => now()->addMinutes(15),
        ]);

        Event::fake();

        $response = $this->actingAs($user)->post('/verify-email', [
            'otp' => '123456',
        ]);

        Event::assertDispatched(Verified::class);
        $user->refresh();
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNull($user->email_verification_otp);
        $this->assertNull($user->email_verification_otp_expires_at);
        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_email_cannot_be_verified_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'email_verification_otp' => '123456',
            'email_verification_otp_expires_at' => now()->addMinutes(15),
        ]);

        Event::fake();

        $response = $this->actingAs($user)->post('/verify-email', [
            'otp' => '654321',
        ]);

        Event::assertNotDispatched(Verified::class);
        $user->refresh();
        $this->assertFalse($user->hasVerifiedEmail());
        $response->assertSessionHas('error', 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali email Anda.');
    }

    public function test_email_cannot_be_verified_with_expired_otp(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'email_verification_otp' => '123456',
            'email_verification_otp_expires_at' => now()->subMinute(),
        ]);

        Event::fake();

        $response = $this->actingAs($user)->post('/verify-email', [
            'otp' => '123456',
        ]);

        Event::assertNotDispatched(Verified::class);
        $user->refresh();
        $this->assertFalse($user->hasVerifiedEmail());
        $response->assertSessionHas('error', 'Kode OTP telah kedaluwarsa (masa aktif 15 menit). Silakan klik "Kirim Ulang Kode".');
    }

    public function test_otp_can_be_resent(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
            'email_verification_otp' => '111111',
            'email_verification_otp_expires_at' => now()->subMinute(),
        ]);

        $response = $this->actingAs($user)->post('/email/verification-notification');

        $response->assertSessionHas('status', 'verification-link-sent');
        $user->refresh();
        $this->assertNotNull($user->email_verification_otp);
        $this->assertNotEquals('111111', $user->email_verification_otp);
        $this->assertTrue($user->email_verification_otp_expires_at->isFuture());

        Notification::assertSentTo($user, SendOtpEmailVerification::class);
    }
}
