<?php

namespace Tests\Feature\Auth;

use App\Models\FaceVerification;
use App\Models\User;
use App\Services\FaceVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaceVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Generate a deterministic 128-D vector.
     */
    protected function generateVector(float $baseVal = 0.1): array
    {
        return array_fill(0, 128, $baseVal);
    }

    /**
     * Generate a matching 128-D vector (Euclidean distance < 0.2).
     */
    protected function generateMatchingVector(float $baseVal = 0.1): array
    {
        $vec = [];
        for ($i = 0; $i < 128; $i++) {
            $vec[] = $baseVal + 0.005 * ($i % 2 === 0 ? 1 : -1);
        }
        return $vec;
    }

    /**
     * Generate a mismatching 128-D vector (Euclidean distance > 0.8).
     */
    protected function generateMismatchingVector(): array
    {
        $vec = [];
        for ($i = 0; $i < 128; $i++) {
            $vec[] = ($i % 2 === 0) ? 0.9 : -0.9;
        }
        return $vec;
    }

    public function test_admin_login_without_face_verification_goes_directly_to_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password123'),
            'face_verification_enabled' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->assertTrue(session()->has('face_verified_at'));
    }

    public function test_owner_login_without_face_verification_goes_directly_to_dashboard(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'password' => bcrypt('password123'),
            'face_verification_enabled' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $owner->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('owner.dashboard'));
        $this->assertAuthenticatedAs($owner);
        $this->assertTrue(session()->has('face_verified_at'));
    }

    public function test_customer_login_is_not_affected_by_face_verification(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'face_verification_enabled' => false,
            'two_factor_enabled' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticatedAs($customer);
    }

    public function test_admin_login_with_face_verification_enabled_redirects_to_face_challenge(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password123'),
            'face_verification_enabled' => true,
        ]);

        FaceVerification::create([
            'user_id' => $admin->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('face-verification.challenge'));
        $this->assertGuest();
        $this->assertEquals($admin->id, session('face_auth:user_id'));
    }

    public function test_owner_login_with_face_verification_enabled_redirects_to_face_challenge(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'password' => bcrypt('password123'),
            'face_verification_enabled' => true,
        ]);

        FaceVerification::create([
            'user_id' => $owner->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $owner->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('face-verification.challenge'));
        $this->assertGuest();
        $this->assertEquals($owner->id, session('face_auth:user_id'));
    }

    public function test_cannot_access_admin_dashboard_without_completing_face_verification(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'face_verification_enabled' => true,
        ]);

        FaceVerification::create([
            'user_id' => $admin->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        // Scenario 1: Guest trying direct URL
        $response1 = $this->get('/admin/dashboard');
        $response1->assertRedirect(route('login'));

        // Scenario 2: Authenticated but without session('face_verified_at')
        $this->actingAs($admin);
        session()->forget('face_verified_at');

        $response2 = $this->get('/admin/dashboard');
        $response2->assertRedirect(route('face-verification.challenge'));
        $this->assertGuest();
    }

    public function test_cannot_access_owner_dashboard_without_completing_face_verification(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'face_verification_enabled' => true,
        ]);

        FaceVerification::create([
            'user_id' => $owner->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        // Scenario 1: Guest trying direct URL
        $response1 = $this->get('/owner/dashboard');
        $response1->assertRedirect(route('login'));

        // Scenario 2: Authenticated but without session('face_verified_at')
        $this->actingAs($owner);
        session()->forget('face_verified_at');

        $response2 = $this->get('/owner/dashboard');
        $response2->assertRedirect(route('face-verification.challenge'));
        $this->assertGuest();
    }

    public function test_face_verification_succeeds_with_matching_euclidean_distance(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'face_verification_enabled' => true,
        ]);

        FaceVerification::create([
            'user_id' => $admin->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        $this->withSession([
            'face_auth:user_id' => $admin->id,
            'face_auth:auth_time' => now()->timestamp,
            'face_auth:role' => 'admin',
            'face_auth:remember' => false,
        ]);

        $response = $this->postJson(route('face-verification.verify'), [
            'descriptor' => $this->generateMatchingVector(0.1),
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'redirect_url' => route('admin.dashboard'),
            ]);

        $this->assertAuthenticatedAs($admin);
        $this->assertTrue(session()->has('face_verified_at'));
        $this->assertNull(session('face_auth:user_id'));
    }

    public function test_face_verification_fails_with_mismatching_descriptor(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'face_verification_enabled' => true,
        ]);

        $record = FaceVerification::create([
            'user_id' => $admin->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        $this->withSession([
            'face_auth:user_id' => $admin->id,
            'face_auth:auth_time' => now()->timestamp,
            'face_auth:role' => 'admin',
        ]);

        $response = $this->postJson(route('face-verification.verify'), [
            'descriptor' => $this->generateMismatchingVector(),
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'reason' => 'mismatch',
            ]);

        $this->assertGuest();
        $this->assertEquals(1, $record->fresh()->failed_attempts);
    }

    public function test_five_consecutive_failed_face_attempts_locks_out_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'face_verification_enabled' => true,
        ]);

        $record = FaceVerification::create([
            'user_id' => $admin->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
            'failed_attempts' => 4,
        ]);

        $this->withSession([
            'face_auth:user_id' => $admin->id,
            'face_auth:auth_time' => now()->timestamp,
            'face_auth:role' => 'admin',
        ]);

        $response = $this->postJson(route('face-verification.verify'), [
            'descriptor' => $this->generateMismatchingVector(),
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'reason' => 'locked',
            ]);

        $this->assertTrue($record->fresh()->isLocked());
        $this->assertGuest();
    }

    public function test_admin_can_enroll_face_verification_with_valid_password_and_128d_vector(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('adminsecret123'),
            'face_verification_enabled' => false,
        ]);

        $vector = $this->generateVector(0.25);

        $response = $this->actingAs($admin)->postJson(route('admin.profile.face-verification.enroll'), [
            'password' => 'adminsecret123',
            'descriptor' => $vector,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertTrue($admin->fresh()->face_verification_enabled);
        $this->assertDatabaseHas('face_verifications', [
            'user_id' => $admin->id,
            'is_active' => true,
        ]);
    }

    public function test_enrollment_fails_with_wrong_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('adminsecret123'),
            'face_verification_enabled' => false,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.profile.face-verification.enroll'), [
            'password' => 'wrongpassword',
            'descriptor' => $this->generateVector(0.25),
        ]);

        $response->assertStatus(422);
        $this->assertFalse($admin->fresh()->face_verification_enabled);
    }

    public function test_enrollment_fails_with_invalid_vector_size(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('adminsecret123'),
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.profile.face-verification.enroll'), [
            'password' => 'adminsecret123',
            'descriptor' => [0.1, 0.2], // only 2 dimensions
        ]);

        $response->assertStatus(422);
    }

    public function test_owner_can_disable_face_verification_with_valid_password(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'password' => bcrypt('ownersecret123'),
            'face_verification_enabled' => true,
        ]);

        FaceVerification::create([
            'user_id' => $owner->id,
            'face_descriptor' => $this->generateVector(0.1),
            'is_active' => true,
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($owner)
            ->withSession(['face_verified_at' => now()->timestamp])
            ->post(route('owner.profile.face-verification.disable'), [
                'password' => 'ownersecret123',
            ]);

        $response->assertRedirect(route('owner.profile.index'));
        $this->assertFalse($owner->fresh()->face_verification_enabled);
        $this->assertDatabaseHas('face_verifications', [
            'user_id' => $owner->id,
            'is_active' => false,
        ]);
    }

    public function test_disable_fails_with_wrong_password(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'password' => bcrypt('ownersecret123'),
            'face_verification_enabled' => true,
        ]);

        $response = $this->actingAs($owner)
            ->withSession(['face_verified_at' => now()->timestamp])
            ->post(route('owner.profile.face-verification.disable'), [
                'password' => 'wrongpassword',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue($owner->fresh()->face_verification_enabled);
    }

    public function test_logout_clears_face_verified_session_state(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'face_verification_enabled' => true,
        ]);

        $this->actingAs($admin);
        session(['face_verified_at' => now()->timestamp]);

        $response = $this->post('/logout');

        $response->assertRedirect(route('shop'));
        $this->assertGuest();
        $this->assertFalse(session()->has('face_verified_at'));
    }

    public function test_face_challenge_page_redirects_to_login_if_session_missing(): void
    {
        $response = $this->get(route('face-verification.challenge'));
        $response->assertRedirect(route('login'));
    }

    public function test_cancel_face_verification_clears_session_and_redirects_to_login(): void
    {
        $this->withSession([
            'face_auth:user_id' => 999,
            'face_auth:auth_time' => now()->timestamp,
        ]);

        $response = $this->post(route('face-verification.cancel'));

        $response->assertRedirect(route('login'));
        $this->assertNull(session('face_auth:user_id'));
    }
}
