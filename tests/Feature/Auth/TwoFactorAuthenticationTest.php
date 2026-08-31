<?php

namespace Tests\Feature\Auth;

use App\Models\TwoFactorCode;
use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_2fa_disabled_logs_in_immediately(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => false,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('customer.dashboard'));
    }

    public function test_login_with_2fa_enabled_redirects_to_challenge_and_sends_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
        $response->assertSessionHas('two_factor:user_id', $user->id);

        Notification::assertSentTo($user, TwoFactorCodeNotification::class);
    }

    public function test_unverified_customer_is_redirected_to_email_verification_notice_not_2fa(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => null,
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('verification.notice'));
        Notification::assertNothingSent();
    }

    public function test_login_with_wrong_password_does_not_send_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        Notification::assertNothingSent();
    }

    public function test_cannot_access_customer_dashboard_without_completing_2fa(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        // Simulasikan hanya memiliki session temporary 2FA (belum login resmi)
        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->get(route('customer.dashboard'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_valid_otp_authenticates_user_and_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $service = app(TwoFactorService::class);
        $code = $service->generateOtp($user, 'login');

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->post(route('two-factor.verify'), [
            'code' => $code,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('customer.dashboard'));
        $response->assertSessionMissing('two_factor:user_id');

        $this->assertDatabaseHas('two_factor_codes', [
            'user_id' => $user->id,
            'action' => 'login',
        ]);
        $record = TwoFactorCode::where('user_id', $user->id)->first();
        $this->assertNotNull($record->used_at);
    }

    public function test_invalid_otp_fails_authentication(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $service = app(TwoFactorService::class);
        $service->generateOtp($user, 'login');

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->from(route('two-factor.login'))->post(route('two-factor.verify'), [
            'code' => '000000',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
        $response->assertSessionHas('error', 'Kode verifikasi yang Anda masukkan salah.');
    }

    public function test_expired_otp_is_rejected(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        TwoFactorCode::create([
            'user_id' => $user->id,
            'action' => 'login',
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->subMinute(),
            'attempts' => 0,
        ]);

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->from(route('two-factor.login'))->post(route('two-factor.verify'), [
            'code' => '123456',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error', 'Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.');
    }

    public function test_used_otp_cannot_be_reused(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        TwoFactorCode::create([
            'user_id' => $user->id,
            'action' => 'login',
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'used_at' => now()->subMinute(),
            'attempts' => 0,
        ]);

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->from(route('two-factor.login'))->post(route('two-factor.verify'), [
            'code' => '123456',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error', 'Kode verifikasi salah atau sudah tidak berlaku.');
    }

    public function test_exceeding_max_attempts_invalidates_otp(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $service = app(TwoFactorService::class);
        $service->generateOtp($user, 'login');

        // Coba 5 kali salah
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->withSession([
                'two_factor:user_id' => $user->id,
                'two_factor:auth_time' => now()->timestamp,
            ])->from(route('two-factor.login'))->post(route('two-factor.verify'), [
                'code' => '00000' . $i,
            ]);
            $this->assertGuest();
        }

        $response->assertSessionHas('error', 'Terlalu banyak percobaan. Silakan minta kode verifikasi baru.');
    }

    public function test_resending_otp_invalidates_previous_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $service = app(TwoFactorService::class);
        $service->generateOtp($user, 'login');

        $oldRecord = TwoFactorCode::where('user_id', $user->id)->first();

        // Majukan waktu 65 detik untuk melewati cooldown 60s
        $this->travel(65)->seconds();

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->from(route('two-factor.login'))->post(route('two-factor.resend'));

        $response->assertRedirect(route('two-factor.login'));
        $response->assertSessionHas('success', 'Kode verifikasi baru telah dikirim ke email Anda.');

        $this->assertTrue($oldRecord->fresh()->isExpired());
        Notification::assertSentTo($user, TwoFactorCodeNotification::class);
    }

    public function test_resend_cooldown_is_enforced(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        TwoFactorCode::create([
            'user_id' => $user->id,
            'action' => 'login',
            'code_hash' => Hash::make('111111'),
            'expires_at' => now()->addMinutes(5),
            'created_at' => now()->subSeconds(10), // Baru 10 detik lalu
            'attempts' => 0,
        ]);

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->from(route('two-factor.login'))->post(route('two-factor.resend'));

        $response->assertRedirect(route('two-factor.login'));
        $response->assertSessionHas('error');
    }

    public function test_challenge_page_redirects_to_login_if_no_session(): void
    {
        $response = $this->get(route('two-factor.login'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Sesi verifikasi telah berakhir. Silakan masuk kembali.');
    }

    public function test_customer_can_enable_2fa_from_profile_with_password_and_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => false,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        // Step 1: Request enable dengan validasi password
        $response = $this->actingAs($user)->post(route('customer.profile.security.2fa.request-enable'), [
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.profile.index'));
        $response->assertSessionHas('two_factor_enabling', true);
        Notification::assertSentTo($user, TwoFactorCodeNotification::class);

        // Ambil code dari database
        $record = TwoFactorCode::where('user_id', $user->id)->where('action', 'enable_2fa')->first();
        $this->assertNotNull($record);

        // Buat kode verifikasi yang cocok untuk simulasi OTP masuk
        $rawOtp = '654321';
        $record->update(['code_hash' => Hash::make($rawOtp)]);

        // Step 2: Konfirmasi OTP
        $response = $this->actingAs($user)->post(route('customer.profile.security.2fa.confirm-enable'), [
            'code' => $rawOtp,
        ]);

        $response->assertRedirect(route('customer.profile.index'));
        $response->assertSessionHas('success', 'Verifikasi 2 langkah berhasil diaktifkan. Akun Anda kini lebih aman.');
        $this->assertTrue($user->fresh()->two_factor_enabled);
    }

    public function test_customer_can_disable_2fa_from_profile_with_password(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->post(route('customer.profile.security.2fa.disable'), [
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.profile.index'));
        $response->assertSessionHas('success', 'Verifikasi 2 langkah berhasil dinonaktifkan.');
        $this->assertFalse($user->fresh()->two_factor_enabled);
    }

    public function test_logout_destroys_session_and_next_login_requires_new_otp(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        // Login pertama kali
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        $service = app(TwoFactorService::class);
        $code1 = TwoFactorCode::where('user_id', $user->id)->latest('id')->first();

        // Verifikasi OTP
        $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->post(route('two-factor.verify'), [
            'code' => $service->generateOtp($user, 'login'),
        ]);
        $this->assertAuthenticatedAs($user);

        // Logout
        $this->post('/logout');
        $this->assertGuest();

        // Login kedua kali harus trigger 2FA kembali
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        $this->assertGuest();
        $response->assertRedirect(route('two-factor.login'));
    }

    public function test_cancel_two_factor_clears_session_and_redirects_to_login(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->withSession([
            'two_factor:user_id' => $user->id,
            'two_factor:auth_time' => now()->timestamp,
        ])->post(route('two-factor.cancel'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionMissing('two_factor:user_id');
    }

    public function test_admin_and_owner_login_flow_remains_unaffected(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        $owner = User::factory()->create([
            'role' => 'owner',
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        $responseAdmin = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);
        $this->assertAuthenticatedAs($admin);
        $responseAdmin->assertRedirect(route('admin.dashboard'));

        $this->post('/logout');
        $this->assertGuest();

        $responseOwner = $this->post('/login', [
            'email' => $owner->email,
            'password' => 'password123',
        ]);
        $this->assertAuthenticatedAs($owner);
        $responseOwner->assertRedirect(route('owner.dashboard'));
    }
}
