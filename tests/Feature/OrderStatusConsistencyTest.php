<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusConsistencyTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_test_1_order_created_without_payment_shows_menunggu_pembayaran_for_both_roles(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $this->assertEquals('Menunggu Pembayaran', $order->status_label);

        // Assert Customer UI shows "Menunggu Pembayaran"
        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertSee('Menunggu Pembayaran');
        $responseCust->assertDontSee('Waiting_verification');
        $responseCust->assertDontSee('Pending');

        // Assert Admin UI shows "Menunggu Pembayaran"
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $responseAdmin->assertSee('Menunggu Pembayaran');
    }

    public function test_test_2_order_waiting_verification_shows_menunggu_verifikasi_for_both_roles(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_status' => 'pending',
            'payment_status' => 'waiting_verification',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $this->assertEquals('waiting_verification', $order->status);
        $this->assertEquals('Menunggu Verifikasi', $order->status_label);

        // Assert Customer UI shows "Menunggu Verifikasi"
        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertSee('Menunggu Verifikasi');
        $responseCust->assertDontSee('Waiting_verification');
        $responseCust->assertDontSee('Verifikasi Kasir');

        // Assert Admin UI shows "Menunggu Verifikasi"
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $responseAdmin->assertSee('Menunggu Verifikasi');
    }

    public function test_test_3_order_processing_paid_shows_sedang_diproses_for_both_roles(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_status' => 'processing',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $this->assertEquals('Sedang Diproses', $order->status_label);

        // Assert Customer UI shows "Sedang Diproses"
        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertSee('Sedang Diproses');

        // Assert Admin UI shows "Sedang Diproses"
        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $responseAdmin->assertSee('Sedang Diproses');
    }

    public function test_test_4_order_ready_for_pickup_shows_siap_diambil_for_both_roles(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_status' => 'ready_for_pickup',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $this->assertEquals('Siap Diambil', $order->status_label);

        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertSee('Siap Diambil');
        $responseCust->assertDontSee('Ready_for_pickup');

        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $responseAdmin->assertSee('Siap Diambil');
    }

    public function test_test_5_order_completed_shows_selesai_for_both_roles(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $this->assertEquals('Selesai', $order->status_label);

        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertSee('Selesai');

        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $responseAdmin->assertSee('Selesai');
    }

    public function test_test_6_order_cancelled_shows_dibatalkan_for_both_roles(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_status' => 'cancelled',
            'payment_status' => 'rejected',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        $this->assertEquals('Dibatalkan', $order->status_label);

        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertSee('Dibatalkan');

        $responseAdmin = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $responseAdmin->assertSee('Dibatalkan');
    }
}
