<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_creation_with_duplicate_name_shows_custom_indonesian_validation_message(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        Category::create([
            'name' => 'Buku Tulis',
            'slug' => 'buku-tulis',
            'description' => 'Kategori buku tulis',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Buku Tulis',
            'description' => 'Deskripsi baru',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Nama kategori tersebut sudah digunakan. Silakan gunakan nama kategori lain.',
        ]);
    }

    public function test_category_creation_with_missing_name_shows_custom_indonesian_validation_message(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => '',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'Nama kategori wajib diisi.',
        ]);
    }
}
