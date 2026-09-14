<?php

namespace Tests\Feature\Superadmin;

use App\Models\IpBlacklist;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperadminSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;
    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->create([
            'email' => 'superadmin@tokobii.test',
            'role' => 'superadmin',
            'password' => Hash::make('SuperadminSecret123!'),
            'status' => 'active',
        ]);

        $this->admin = User::factory()->create([
            'email' => 'admin@tokobii.test',
            'role' => 'admin',
            'password' => Hash::make('AdminSecret123!'),
            'status' => 'active',
        ]);

        $this->customer = User::factory()->create([
            'email' => 'customer@tokobii.test',
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        Artisan::call('up');
        parent::tearDown();
    }

    /**
     * Test 1: Zero Trust Access & Strict Role Isolation.
     */
    public function test_guest_is_redirected_from_superadmin_dashboard(): void
    {
        $response = $this->get(route('superadmin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_and_customer_cannot_access_superadmin_dashboard(): void
    {
        // Admin
        $responseAdmin = $this->actingAs($this->admin)->get(route('superadmin.dashboard'));
        $responseAdmin->assertStatus(403);

        // Customer
        $responseCustomer = $this->actingAs($this->customer)->get(route('superadmin.dashboard'));
        $responseCustomer->assertStatus(403);
    }

    public function test_superadmin_can_access_superadmin_dashboard(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Security Operations Center');
    }

    /**
     * Test 2: Active Defense Honeypot Trap & Auto IP Blacklist.
     */
    public function test_honeypot_trap_blocks_and_blacklists_ip_permanently(): void
    {
        $attackerIp = '198.51.100.42';

        $response = $this->withServerVariables(['REMOTE_ADDR' => $attackerIp])
            ->get('/wp-admin');

        $response->assertStatus(403);

        // Verify IP is in ip_blacklists
        $this->assertDatabaseHas('ip_blacklists', [
            'ip_address' => $attackerIp,
            'is_permanent' => true,
        ]);

        // Verify security log recorded
        $this->assertDatabaseHas('security_logs', [
            'ip_address' => $attackerIp,
            'event_type' => 'honeypot_trap_triggered',
            'severity' => 'critical',
        ]);

        // Subsequent requests from this blacklisted IP must receive 403 Forbidden
        $subsequentResponse = $this->withServerVariables(['REMOTE_ADDR' => $attackerIp])
            ->get(route('login'));

        $subsequentResponse->assertStatus(403);
    }

    /**
     * Test 3: Deep Forensics Sanitized Raw Payload Logger.
     */
    public function test_sensitive_payload_is_sanitized_in_security_logs(): void
    {
        $sanitized = SecurityLog::sanitizePayload([
            'email' => 'attacker@test.com',
            'password' => 'UltraSecret123!',
            'token' => 'jwt_secret_token_value',
            'details' => [
                'current_password' => 'OldSecret!',
                'safe_info' => 'Normal Text',
            ],
        ]);

        $this->assertEquals('[REDACTED_SEC_PAYLOAD]', $sanitized['password']);
        $this->assertEquals('[REDACTED_SEC_PAYLOAD]', $sanitized['token']);
        $this->assertEquals('[REDACTED_SEC_PAYLOAD]', $sanitized['details']['current_password']);
        $this->assertEquals('Normal Text', $sanitized['details']['safe_info']);
        $this->assertEquals('attacker@test.com', $sanitized['email']);
    }

    /**
     * Test 4: Zero Trust Password Re-Authentication.
     */
    public function test_reauth_middleware_redirects_when_not_reauthenticated(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->get(route('superadmin.maintenance.index'));

        $response->assertRedirect(route('superadmin.reauth'));
    }

    public function test_superadmin_reauth_fails_with_invalid_password(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->post(route('superadmin.reauth.confirm'), [
                'password' => 'WrongPassword!',
            ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertNull(session('superadmin_reauth_at'));
    }

    public function test_superadmin_reauth_succeeds_with_correct_password(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->post(route('superadmin.reauth.confirm'), [
                'password' => 'SuperadminSecret123!',
            ]);

        $response->assertRedirect(route('superadmin.maintenance.index'));
        $this->assertNotNull(session('superadmin_reauth_at'));

        // Accessing maintenance now succeeds
        $maintenanceResponse = $this->actingAs($this->superadmin)
            ->withSession(['superadmin_reauth_at' => time()])
            ->get(route('superadmin.maintenance.index'));

        $maintenanceResponse->assertStatus(200);
        $maintenanceResponse->assertSee('System Maintenance', false);
    }

    /**
     * Test 5: Cache Manager Execution.
     */
    public function test_cache_manager_clears_application_cache(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->withSession(['superadmin_reauth_at' => time()])
            ->post(route('superadmin.maintenance.cache'), [
                'type' => 'optimize',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'cache_cleared',
            'severity' => 'medium',
        ]);
    }

    /**
     * Test 6: DEFCON 1 Global Panic Button & Stand-Down.
     */
    public function test_panic_button_requires_valid_password(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->withSession(['superadmin_reauth_at' => time()])
            ->post(route('superadmin.maintenance.panic'), [
                'password' => 'WrongPassword',
                'reason' => 'Testing panic trigger',
            ]);

        $response->assertSessionHas('error');
    }

    public function test_panic_button_triggers_emergency_protocol_and_stand_down_restores(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->withSession(['superadmin_reauth_at' => time()])
            ->post(route('superadmin.maintenance.panic'), [
                'password' => 'SuperadminSecret123!',
                'reason' => 'Simulated Defcon 1 Security Drill',
            ]);

        $this->assertNotNull(session('emergency_bypass_secret'));
        $response->assertRedirect();

        // Verify security log recorded
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'defcon1_panic_button_activated',
            'severity' => 'critical',
        ]);

        // Stand-down / lift maintenance
        $standDownResponse = $this->actingAs($this->superadmin)
            ->withSession(['superadmin_reauth_at' => time()])
            ->post(route('superadmin.maintenance.stand-down'));

        $standDownResponse->assertRedirect(route('superadmin.maintenance.index'));
        $this->assertDatabaseHas('security_logs', [
            'event_type' => 'panic_button_stand_down',
        ]);
    }

    /**
     * Test 7: IP Blacklist Management CRUD.
     */
    public function test_superadmin_can_add_and_remove_blacklisted_ip(): void
    {
        // Add manual blacklist
        $storeResponse = $this->actingAs($this->superadmin)
            ->post(route('superadmin.blacklist.store'), [
                'ip_address' => '203.0.113.88',
                'reason' => 'Manual Suspicious Activity Ban',
                'is_permanent' => 1,
            ]);

        $storeResponse->assertRedirect(route('superadmin.blacklist.index'));
        $this->assertDatabaseHas('ip_blacklists', [
            'ip_address' => '203.0.113.88',
        ]);

        $entry = IpBlacklist::where('ip_address', '203.0.113.88')->first();

        // Remove from blacklist
        $destroyResponse = $this->actingAs($this->superadmin)
            ->delete(route('superadmin.blacklist.destroy', $entry));

        $destroyResponse->assertRedirect(route('superadmin.blacklist.index'));
        $this->assertDatabaseMissing('ip_blacklists', [
            'id' => $entry->id,
        ]);
    }
}
