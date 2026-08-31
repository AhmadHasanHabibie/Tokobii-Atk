<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashPaymentWorkflowTest extends TestCase
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

    private function createCashOrder(int $grandTotal = 20200, string $orderStatus = 'ready_for_pickup', string $paymentStatus = 'pending'): Order
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_date' => now(),
            'order_status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'payment_method' => 'cash',
            'subtotal' => $grandTotal,
            'shipping_cost' => 0,
            'grand_total' => $grandTotal,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'payment_method' => 'cash',
            'payment_status' => $paymentStatus,
            'amount' => $grandTotal,
            'payment_date' => $paymentStatus === 'paid' ? now() : null,
        ]);

        return $order;
    }

    public function test_1_cash_order_at_ready_for_pickup_shows_confirm_payment_button_not_complete_button(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Konfirmasi Pembayaran');
        $response->assertDontSee('#completeOrderModal');
        $response->assertSee('Menunggu Pembayaran');
        $response->assertSee('Siap Diambil');
    }

    public function test_2_cannot_complete_cash_order_if_payment_is_unpaid(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'complete',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pembayaran tunai belum dikonfirmasi. Konfirmasikan pembayaran terlebih dahulu.');

        $order->refresh();
        $this->assertEquals('ready_for_pickup', $order->order_status);
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_3_confirm_cash_payment_requires_received_amount(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'confirm_cash_payment',
            'received_amount' => '',
        ]);

        $response->assertSessionHasErrors([
            'received_amount' => 'Jumlah uang yang diterima wajib diisi.',
        ]);

        $order->refresh();
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_4_confirm_cash_payment_rejects_amount_less_than_total(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'confirm_cash_payment',
            'received_amount' => 20000,
        ]);

        $response->assertSessionHasErrors([
            'received_amount' => 'Uang yang diterima kurang dari total tagihan.',
        ]);

        $order->refresh();
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_5_confirm_cash_payment_exact_amount_succeeds_with_zero_change(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'confirm_cash_payment',
            'received_amount' => 20200,
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');

        $order->refresh();
        $payment = $order->payment;

        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('paid', $payment->payment_status);
        $this->assertEquals(20200, $payment->received_amount);
        $this->assertEquals(0, $payment->change_amount);
        $this->assertEquals($this->admin->id, $payment->verified_by_admin_id);
        $this->assertNotNull($payment->verified_at);
        $this->assertNotNull($payment->payment_date);
    }

    public function test_6_confirm_cash_payment_greater_amount_succeeds_with_correct_change(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'confirm_cash_payment',
            'received_amount' => '25.000', // Test formatted string handling
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');

        $order->refresh();
        $payment = $order->payment;

        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('paid', $payment->payment_status);
        $this->assertEquals(25000, $payment->received_amount);
        $this->assertEquals(4800, $payment->change_amount);

        // Verify that after payment is paid, the button becomes "Selesaikan Pesanan"
        $showResponse = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Selesaikan Pesanan');
        $showResponse->assertSee('#completeOrderModal');
        $showResponse->assertDontSee('#confirmCashPaymentModal');
        $showResponse->assertSee('Lunas');
        $showResponse->assertSee('25.000');
        $showResponse->assertSee('4.800');
    }

    public function test_7_cannot_confirm_cash_payment_twice(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'paid');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'confirm_cash_payment',
            'received_amount' => 25000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pembayaran pesanan ini sudah dikonfirmasi.');
    }

    public function test_8_complete_cash_order_after_payment_paid_succeeds(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'paid');

        $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'complete',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('completed', $order->order_status);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('Selesai', $order->status_label);
    }

    public function test_9_qris_workflow_is_not_affected(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_date' => now(),
            'order_status' => 'pending',
            'payment_status' => 'waiting_verification',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'payment_method' => 'qris',
            'payment_status' => 'waiting_verification',
            'amount' => 50000,
        ]);

        // Step 1: Admin verifies QRIS payment
        $responseApprove = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'approve_payment',
        ]);
        $responseApprove->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();
        $this->assertEquals('processing', $order->order_status);
        $this->assertEquals('paid', $order->payment_status);

        // Step 2: Set ready for pickup
        $responseReady = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'ready_for_pickup',
        ]);
        $responseReady->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();
        $this->assertEquals('ready_for_pickup', $order->order_status);
        $this->assertEquals('paid', $order->payment_status);

        // Step 3: Complete order
        $responseComplete = $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'complete',
        ]);
        $responseComplete->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();
        $this->assertEquals('completed', $order->order_status);
        $this->assertEquals('paid', $order->payment_status);
    }

    public function test_10_customer_sees_consistent_status_for_cash_order(): void
    {
        $order = $this->createCashOrder(20200, 'ready_for_pickup', 'pending');

        // Customer sees Menunggu Pembayaran
        $responseCust = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCust->assertStatus(200);
        $responseCust->assertSee('Menunggu Pembayaran');
        $responseCust->assertSee('Siap Diambil');

        // Admin confirms cash payment
        $this->actingAs($this->admin)->put(route('admin.orders.update', $order), [
            'action' => 'confirm_cash_payment',
            'received_amount' => 25000,
        ]);

        // Customer now sees Lunas and change amount
        $responseCustPaid = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $responseCustPaid->assertStatus(200);
        $responseCustPaid->assertSee('Lunas');
        $responseCustPaid->assertSee('25.000');
        $responseCustPaid->assertSee('4.800');
    }
}
