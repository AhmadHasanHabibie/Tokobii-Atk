<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPriceSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->category = Category::create([
            'name' => 'Alat Tulis Kantor',
            'slug' => 'atk',
            'status' => 'active',
        ]);
    }

    public function test_case_1_and_case_2_valid_integer_price(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Buku Tulis A5',
            'sku' => 'PRD-001',
            'price' => 10000,
            'stock' => 50,
            'status' => 'active',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', [
            'sku' => 'PRD-001',
            'price' => 10000,
        ]);
    }

    public function test_case_3_and_case_4_decimal_price_rejected(): void
    {
        // CASE 3: 9799.82
        $response1 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Produk Desimal 1',
            'sku' => 'PRD-002',
            'price' => '9799.82',
            'stock' => 10,
            'status' => 'active',
        ]);

        $response1->assertSessionHasErrors([
            'price' => 'Harga produk harus berupa bilangan Rupiah tanpa desimal.',
        ]);

        // CASE 4: 10000.50
        $response2 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Produk Desimal 2',
            'sku' => 'PRD-003',
            'price' => '10000.50',
            'stock' => 10,
            'status' => 'active',
        ]);

        $response2->assertSessionHasErrors([
            'price' => 'Harga produk harus berupa bilangan Rupiah tanpa desimal.',
        ]);
    }

    public function test_case_5_and_case_6_zero_and_negative_price_rejected(): void
    {
        // CASE 5: 0
        $response1 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Produk Nol',
            'sku' => 'PRD-004',
            'price' => 0,
            'stock' => 10,
            'status' => 'active',
        ]);

        $response1->assertSessionHasErrors(['price']);

        // CASE 6: -1000
        $response2 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Produk Negatif',
            'sku' => 'PRD-005',
            'price' => -1000,
            'stock' => 10,
            'status' => 'active',
        ]);

        $response2->assertSessionHasErrors(['price']);
    }

    public function test_case_7_and_case_8_non_integer_string_and_exponent_rejected(): void
    {
        // CASE 7: abc
        $response1 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Produk String',
            'sku' => 'PRD-006',
            'price' => 'abc',
            'stock' => 10,
            'status' => 'active',
        ]);

        $response1->assertSessionHasErrors(['price']);

        // CASE 8: 1e5
        $response2 = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'Produk Eksponen',
            'sku' => 'PRD-007',
            'price' => '1e5',
            'stock' => 10,
            'status' => 'active',
        ]);

        $response2->assertSessionHasErrors(['price']);
    }

    public function test_case_9_and_case_10_update_manual_request_decimal_vs_integer(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Pensil 2B',
            'slug' => 'pensil-2b',
            'sku' => 'PRD-008',
            'price' => 5000,
            'stock' => 20,
            'status' => 'active',
        ]);

        // CASE 9: Manual request with "10000.50" on update
        $response1 = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'category_id' => $this->category->id,
            'name' => 'Pensil 2B',
            'sku' => 'PRD-008',
            'price' => '10000.50',
            'stock' => 20,
            'status' => 'active',
        ]);

        $response1->assertSessionHasErrors([
            'price' => 'Harga produk harus berupa bilangan Rupiah tanpa desimal.',
        ]);

        // CASE 10: Manual request with "10000" on update
        $response2 = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'category_id' => $this->category->id,
            'name' => 'Pensil 2B Super',
            'sku' => 'PRD-008',
            'price' => '10000',
            'stock' => 20,
            'status' => 'active',
        ]);

        $response2->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 10000,
        ]);
    }
}
