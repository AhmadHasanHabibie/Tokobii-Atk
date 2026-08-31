<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportAndAlertHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->owner = User::factory()->create([
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_reports_index_renders_successfully_on_empty_database(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHasAll(['reports', 'stats', 'categories', 'products', 'totalReports', 'pendingReports', 'repliedReports']);
        $response->assertSee('Laporan &amp; Komplain Pelanggan', false);
        $response->assertSee('Total Omset');
        $response->assertSee('Rp 0');
    }

    public function test_admin_reports_index_renders_with_real_statistics_and_data(): void
    {
        $category = Category::create([
            'name' => 'Buku Tulis',
            'slug' => 'buku-tulis',
            'description' => 'Kategori buku tulis',
            'status' => 'active',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Buku Sinar Dunia 38 Lembar',
            'slug' => 'buku-sinar-dunia-38-lembar',
            'sku' => 'BK-SIDU-38',
            'price' => 5000,
            'stock' => 100,
            'description' => 'Buku tulis berkualitas',
            'status' => 'active',
        ]);

        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => 'INV-20260831-0001',
            'order_date' => now(),
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => 10,
            'price' => 5000,
            'subtotal' => 50000,
        ]);

        $report = Report::create([
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $product->id,
            'report_type' => 'damaged',
            'description' => 'Ada 2 buku yang sampulnya robek.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Rp 50.000');
        $response->assertSee('Ada 2 buku yang sampulnya robek.');
        $response->assertSee('Buku Sinar Dunia 38 Lembar');
        $response->assertSee($this->customer->name);
    }

    public function test_admin_reports_category_and_product_views_render(): void
    {
        $category = Category::create([
            'name' => 'Alat Tulis',
            'slug' => 'alat-tulis',
            'status' => 'active',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Pulpen Gel Hitam',
            'slug' => 'pulpen-gel-hitam',
            'sku' => 'PL-GEL-01',
            'price' => 3000,
            'stock' => 50,
            'status' => 'active',
        ]);

        $categoryResponse = $this->actingAs($this->admin)->get(route('admin.reports.categories.show', $category));
        $categoryResponse->assertStatus(200);
        $categoryResponse->assertSee('Laporan Kategori: Alat Tulis');
        $categoryResponse->assertSee('Pulpen Gel Hitam');

        $aliasResponse = $this->actingAs($this->admin)->get(route('admin.reports.category', $category));
        $aliasResponse->assertStatus(200);

        $productResponse = $this->actingAs($this->admin)->get(route('admin.reports.products.show', $product));
        $productResponse->assertStatus(200);
        $productResponse->assertSee('Laporan Produk: Pulpen Gel Hitam');
    }

    public function test_admin_can_reply_and_resolve_report(): void
    {
        $category = Category::create([
            'name' => 'Kertas',
            'slug' => 'kertas',
            'status' => 'active',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kertas HVS A4 70gr',
            'slug' => 'kertas-hvs-a4-70gr',
            'sku' => 'KRT-HVS-A4',
            'price' => 45000,
            'stock' => 20,
            'status' => 'active',
        ]);

        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => 'INV-20260831-0002',
            'order_date' => now(),
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'subtotal' => 45000,
            'grand_total' => 45000,
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => 1,
            'price' => 45000,
            'subtotal' => 45000,
        ]);

        $report = Report::create([
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $product->id,
            'report_type' => 'wrong_item',
            'description' => 'Ukuran kertas yang diterima F4 bukan A4.',
            'status' => 'pending',
        ]);

        $detailResponse = $this->actingAs($this->admin)->get(route('admin.reports.show', $report));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Detail Tiket Laporan Masalah');

        $replyResponse = $this->actingAs($this->admin)->put(route('admin.reports.reply', $report), [
            'admin_reply' => 'Silakan bawa barang ke toko untuk penukaran unit baru.',
            'status' => 'replied',
        ]);

        $replyResponse->assertRedirect(route('admin.reports.show', $report));
        $replyResponse->assertSessionHas('success', 'Tanggapan keluhan berhasil dikirim.');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'replied',
            'admin_reply' => 'Silakan bawa barang ke toko untuk penukaran unit baru.',
            'replied_by' => $this->admin->id,
        ]);

        $resolveResponse = $this->actingAs($this->admin)->put(route('admin.reports.resolve', $report));
        $resolveResponse->assertRedirect(route('admin.reports.show', $report));
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'resolved',
        ]);
    }

    public function test_flash_messages_include_dismiss_close_button_in_layouts(): void
    {
        // Admin layout with session success
        $adminResponse = $this->actingAs($this->admin)
            ->withSession(['success' => 'Kategori berhasil ditambahkan.'])
            ->get(route('admin.categories.index'));

        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Kategori berhasil ditambahkan.');
        $adminResponse->assertSee('data-bs-dismiss="alert"', false);
        $adminResponse->assertSee('btn-close', false);

        // Customer layout with session error
        $customerResponse = $this->actingAs($this->customer)
            ->withSession(['error' => 'Gagal memproses permintaan.'])
            ->get(route('customer.dashboard'));

        $customerResponse->assertStatus(200);
        $customerResponse->assertSee('Gagal memproses permintaan.');
        $customerResponse->assertSee('data-bs-dismiss="alert"', false);

        // Owner layout with session info
        $ownerResponse = $this->actingAs($this->owner)
            ->withSession(['info' => 'Pemberitahuan sistem.'])
            ->get(route('owner.dashboard'));

        $ownerResponse->assertStatus(200);
        $ownerResponse->assertSee('Pemberitahuan sistem.');
        $ownerResponse->assertSee('data-bs-dismiss="alert"', false);
    }
}
