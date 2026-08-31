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

class ReportWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private User $otherCustomer;
    private Category $category;
    private Product $product;
    private Order $order;
    private OrderItem $orderItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->otherCustomer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'description' => 'Kategori produk elektronik',
            'status' => 'active',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'TWS Bluetooth 5.3',
            'slug' => 'tws-bluetooth-53',
            'sku' => 'TWS-53',
            'price' => 150000,
            'stock' => 25,
            'description' => 'Earphone TWS jernih',
            'status' => 'active',
        ]);

        $this->order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_date' => now(),
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'subtotal' => 150000,
            'shipping_cost' => 0,
            'grand_total' => 150000,
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'qty' => 1,
            'price' => 150000,
            'subtotal' => 150000,
        ]);
    }

    private function createReport(string $status = 'pending', ?string $reply = null): Report
    {
        return Report::create([
            'user_id' => $this->customer->id,
            'order_id' => $this->order->id,
            'order_item_id' => $this->orderItem->id,
            'product_id' => $this->product->id,
            'report_type' => 'damaged',
            'description' => 'Earphone sebelah kiri mati total dan tidak bisa mengisi daya.',
            'status' => $status,
            'admin_reply' => $reply,
            'replied_by' => $reply ? $this->admin->id : null,
            'replied_at' => $reply ? now() : null,
        ]);
    }

    public function test_scenario_a_new_report_has_pending_status_and_reply_form_without_status_dropdown(): void
    {
        $report = $this->createReport('pending');

        $response = $this->actingAs($this->admin)->get(route('admin.reports.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Menunggu Tanggapan');
        $response->assertSee('Tulis Tanggapan:');
        $response->assertSee('Kirim Tanggapan');
        $response->assertDontSee('<select name="status"', false);
        $response->assertDontSee('Status Laporan:');
    }

    public function test_scenario_b_admin_submitting_reply_transitions_status_automatically_to_replied(): void
    {
        $report = $this->createReport('pending');

        // Admin submits reply (supports both POST and PUT methods seamlessly)
        $response = $this->actingAs($this->admin)->put(route('admin.reports.reply', $report), [
            'admin_reply' => 'Silakan datang kembali ke toko Tokobii dengan membawa struk untuk proses penukaran unit baru.',
        ]);

        $response->assertRedirect(route('admin.reports.show', $report));
        $response->assertSessionHas('success', 'Tanggapan keluhan berhasil dikirim.');

        $report->refresh();
        $this->assertEquals('replied', $report->status);
        $this->assertEquals('Sudah Dibalas', $report->status_label);
        $this->assertEquals('Silakan datang kembali ke toko Tokobii dengan membawa struk untuk proses penukaran unit baru.', $report->admin_reply);
        $this->assertEquals($this->admin->id, $report->replied_by);
        $this->assertNotNull($report->replied_at);

        // Admin show view now shows "Sudah Dibalas" and "Selesaikan Laporan" button
        $adminShow = $this->actingAs($this->admin)->get(route('admin.reports.show', $report));
        $adminShow->assertStatus(200);
        $adminShow->assertSee('Sudah Dibalas');
        $adminShow->assertSee('Selesaikan Laporan');
        $adminShow->assertDontSee('<textarea name="admin_reply"', false);

        // Admin index list shows "Sudah Dibalas"
        $adminIndex = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $adminIndex->assertStatus(200);
        $adminIndex->assertSee('Sudah Dibalas');

        // Customer sees identical status "Sudah Dibalas" and the reply text
        $customerIndex = $this->actingAs($this->customer)->get(route('customer.reports.index'));
        $customerIndex->assertStatus(200);
        $customerIndex->assertSee('Sudah Dibalas');
        $customerIndex->assertSee('Silakan datang kembali ke toko Tokobii dengan membawa struk untuk proses penukaran unit baru.');
    }

    public function test_scenario_c_admin_resolves_report_transitions_status_to_resolved_without_action_buttons(): void
    {
        $report = $this->createReport('replied', 'Unit pengganti telah disiapkan.');

        $response = $this->actingAs($this->admin)->put(route('admin.reports.resolve', $report));

        $response->assertRedirect(route('admin.reports.show', $report));
        $response->assertSessionHas('success', 'Laporan masalah berhasil diselesaikan.');

        $report->refresh();
        $this->assertEquals('resolved', $report->status);
        $this->assertEquals('Selesai', $report->status_label);

        // Admin show view now shows "Selesai" and no more action buttons
        $adminShow = $this->actingAs($this->admin)->get(route('admin.reports.show', $report));
        $adminShow->assertStatus(200);
        $adminShow->assertSee('Selesai');
        $adminShow->assertSee('Laporan Telah Diselesaikan');
        $adminShow->assertDontSee('Selesaikan Laporan');
        $adminShow->assertDontSee('Kirim Tanggapan');

        // Customer sees "Selesai"
        $customerIndex = $this->actingAs($this->customer)->get(route('customer.reports.index'));
        $customerIndex->assertStatus(200);
        $customerIndex->assertSee('Selesai');
    }

    public function test_scenario_d_empty_reply_fails_validation_and_retains_pending_status(): void
    {
        $report = $this->createReport('pending');

        $response = $this->actingAs($this->admin)->put(route('admin.reports.reply', $report), [
            'admin_reply' => '',
        ]);

        $response->assertSessionHasErrors([
            'admin_reply' => 'Tanggapan wajib diisi.',
        ]);

        $report->refresh();
        $this->assertEquals('pending', $report->status);
        $this->assertEquals('Menunggu Tanggapan', $report->status_label);
    }

    public function test_scenario_e_customer_cannot_access_admin_reply_or_resolve_endpoints(): void
    {
        $report = $this->createReport('pending');

        // Customer cannot access admin report routes
        $responseReply = $this->actingAs($this->customer)->put(route('admin.reports.reply', $report), [
            'admin_reply' => 'Hacker reply attempt',
        ]);
        $responseReply->assertStatus(403);

        $responseResolve = $this->actingAs($this->customer)->put(route('admin.reports.resolve', $report));
        $responseResolve->assertStatus(403);

        $report->refresh();
        $this->assertEquals('pending', $report->status);
    }
}
