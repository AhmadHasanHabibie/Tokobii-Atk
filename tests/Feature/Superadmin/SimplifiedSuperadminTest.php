<?php

namespace Tests\Feature\Superadmin;

use App\Models\BlockedIp;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SimplifiedSuperadminTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->create([
            'email' => 'superadmin@tokobii.test',
            'role' => 'superadmin',
            'password' => Hash::make('SuperadminPass123'),
            'status' => 'active',
        ]);

        $this->customer = User::factory()->create([
            'email' => 'customer@tokobii.test',
            'role' => 'customer',
            'password' => Hash::make('CustomerPass123'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        Artisan::call('up');
        parent::tearDown();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('superadmin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_receives_403_forbidden_on_superadmin_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get(route('superadmin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_superadmin_can_view_dashboard_and_login_histories(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dasbor Kontrol Sistem');
        $response->assertSee('Mode Pemeliharaan Toko');

        $response2 = $this->actingAs($this->superadmin)->get(route('superadmin.login-histories'));
        $response2->assertStatus(200);
        $response2->assertSee('Riwayat Login Pengguna');
    }

    public function test_maintenance_mode_toggle(): void
    {
        $this->assertFalse(app()->isDownForMaintenance());

        // Toggle ON
        $response = $this->actingAs($this->superadmin)->post(route('superadmin.maintenance.toggle'));
        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertTrue(app()->isDownForMaintenance());

        // Toggle OFF
        $response2 = $this->actingAs($this->superadmin)->post(route('superadmin.maintenance.toggle'));
        $response2->assertRedirect(route('superadmin.dashboard'));
        $this->assertFalse(app()->isDownForMaintenance());
    }

    public function test_ip_blocker_workflow(): void
    {
        $ip = '198.51.100.42';

        // Add IP to blocklist
        $response = $this->actingAs($this->superadmin)->post(route('superadmin.ip-blocker.store'), [
            'ip_address' => $ip,
            'reason' => 'Spamming bot',
        ]);
        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseHas('blocked_ips', ['ip_address' => $ip]);

        // Attempting to visit any page with this IP should return 403
        $blockedResponse = $this->withServerVariables(['REMOTE_ADDR' => $ip])->get('/shop');
        $blockedResponse->assertStatus(403);

        // Reset server variables to local IP
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);

        // Delete IP from blocklist
        $blockedIp = BlockedIp::where('ip_address', $ip)->firstOrFail();
        $deleteResponse = $this->actingAs($this->superadmin)->delete(route('superadmin.ip-blocker.destroy', $blockedIp));
        $deleteResponse->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseMissing('blocked_ips', ['ip_address' => $ip]);

        // Access unblocked
        $unblockedResponse = $this->withServerVariables(['REMOTE_ADDR' => $ip])->get('/shop');
        $unblockedResponse->assertStatus(200);
    }

    public function test_login_history_is_automatically_recorded_via_event_listeners(): void
    {
        // Successful login
        $this->post(route('login'), [
            'email' => 'customer@tokobii.test',
            'password' => 'CustomerPass123',
        ]);

        $this->assertDatabaseHas('login_histories', [
            'user_id' => $this->customer->id,
            'status' => 'sukses',
        ]);

        // Logout via route so session cookie is cleared
        $this->post(route('logout'));

        // Failed login
        $this->post(route('login'), [
            'email' => 'customer@tokobii.test',
            'password' => 'WrongPassword!',
        ]);

        $this->assertDatabaseHas('login_histories', [
            'user_id' => $this->customer->id,
            'status' => 'gagal',
        ]);
    }

    public function test_database_backup_download(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.backup.download'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/sql');
    }

    public function test_anti_ddos_login_rate_limiting_returns_429_too_many_requests(): void
    {
        $ip = '203.0.113.88';

        // Perform 5 failed attempts from the same IP
        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])
                ->post(route('login'), [
                    'email' => "attempt{$i}@tokobii.test",
                    'password' => 'WrongPassword',
                ]);
        }

        // 6th attempt should be blocked with 429 Too Many Requests
        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post(route('login'), [
                'email' => 'attempt6@tokobii.test',
                'password' => 'WrongPassword',
            ]);

        $response->assertStatus(429);
        $response->assertSee('Terlalu banyak percobaan login');
    }

    public function test_during_maintenance_only_superadmin_can_login(): void
    {
        Artisan::call('down', ['--secret' => 'superadmin-bypass']);

        // 1. Check login page shows maintenance warning
        $loginPage = $this->get(route('login'));
        $loginPage->assertStatus(200);
        $loginPage->assertSee('Mode Pemeliharaan Sedang Aktif');

        // 2. Customer login during maintenance is redirected to maintenance popup
        $customerLogin = $this->post(route('login'), [
            'email' => 'customer@tokobii.test',
            'password' => 'CustomerPass123',
        ]);
        $customerLogin->assertRedirect(route('maintenance.page'));
        $this->assertGuest();

        // 3. Superadmin login during maintenance succeeds
        $superadminLogin = $this->post(route('login'), [
            'email' => 'superadmin@tokobii.test',
            'password' => 'SuperadminPass123',
        ]);
        $superadminLogin->assertRedirect(route('superadmin.dashboard'));
        $this->assertAuthenticatedAs($this->superadmin);
    }

    public function test_logged_in_user_sees_custom_maintenance_popup_with_logout_button(): void
    {
        Artisan::call('down', ['--secret' => 'superadmin-bypass']);

        // Logged-in customer visits shop / dashboard
        $response = $this->actingAs($this->customer)->get('/shop');
        $response->assertStatus(503);
        $response->assertSee('Mode Pemeliharaan Aktif');
        $response->assertSee('Kembali ke Halaman Login');

        // Customer uses the logout button from the maintenance popup
        $logoutResponse = $this->actingAs($this->customer)->post(route('logout'));
        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
