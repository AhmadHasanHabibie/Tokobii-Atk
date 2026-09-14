<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/customer/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put('/customer/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('customer.profile.index'));

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put('/customer/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('customer.profile.index'));

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/customer/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/customer/profile')
            ->delete('/customer/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/customer/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_customer_can_update_password_directly_with_new_password_and_confirmation(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/customer/profile/edit')
            ->put('/customer/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'new-password123',
                'password_confirmation' => 'new-password123',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success')
            ->assertRedirect(route('customer.profile.index'));

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password123', $user->fresh()->password));
    }

    public function test_customer_cannot_update_password_with_short_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/customer/profile/edit')
            ->put('/customer/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/customer/profile/edit');

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('old-password123', $user->fresh()->password));
    }

    public function test_customer_cannot_update_password_with_mismatched_confirmation(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/customer/profile/edit')
            ->put('/customer/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'new-password123',
                'password_confirmation' => 'different-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/customer/profile/edit');
    }
}
