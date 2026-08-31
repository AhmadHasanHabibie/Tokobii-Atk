<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderActionsTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $otherCustomer;
    private Product $productA;
    private Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->otherCustomer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $this->productA = Product::create([
            'category_id' => $category->id,
            'name' => 'TWS Bluetooth Earphone',
            'slug' => 'tws-bluetooth-earphone',
            'sku' => 'TWS-001',
            'price' => 50000,
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->productB = Product::create([
            'category_id' => $category->id,
            'name' => 'Mouse Wireless Ergonomic',
            'slug' => 'mouse-wireless-ergonomic',
            'sku' => 'MOU-002',
            'price' => 75000,
            'stock' => 15,
            'is_active' => true,
        ]);
    }

    private function createOrderForCustomer(User $user, string $orderStatus = 'completed', string $paymentStatus = 'paid'): Order
    {
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_date' => now(),
            'order_status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'shipping_cost' => 0,
            'grand_total' => 50000,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'invoice_number' => $order->invoice_number,
            'payment_method' => 'qris',
            'payment_status' => $paymentStatus,
            'amount' => 50000,
            'payment_date' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->productA->id,
            'product_name' => $this->productA->name,
            'qty' => 1,
            'price' => 50000,
            'subtotal' => 50000,
        ]);

        return $order;
    }

    public function test_1_completed_order_shows_review_and_report_buttons_on_orders_index(): void
    {
        $order = $this->createOrderForCustomer($this->customer, 'completed', 'paid');

        $response = $this->actingAs($this->customer)->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Berikan Ulasan');
        $response->assertSee('Laporkan Masalah');
        $response->assertSee('Detail');
        $response->assertSee($order->invoice_number);
    }

    public function test_2_non_completed_orders_do_not_show_review_or_report_buttons(): void
    {
        $statuses = [
            ['order_status' => 'pending', 'payment_status' => 'pending'],
            ['order_status' => 'pending', 'payment_status' => 'waiting_verification'],
            ['order_status' => 'processing', 'payment_status' => 'paid'],
            ['order_status' => 'ready_for_pickup', 'payment_status' => 'paid'],
            ['order_status' => 'cancelled', 'payment_status' => 'rejected'],
        ];

        foreach ($statuses as $status) {
            $order = $this->createOrderForCustomer($this->customer, $status['order_status'], $status['payment_status']);

            $response = $this->actingAs($this->customer)->get(route('customer.orders.index'));
            $response->assertStatus(200);
            $response->assertDontSee('Berikan Ulasan');
            $response->assertDontSee('Laporkan Masalah');
        }
    }

    public function test_3_review_button_navigates_to_review_form_with_correct_order_and_item(): void
    {
        $order = $this->createOrderForCustomer($this->customer, 'completed', 'paid');
        $item = $order->items->first();

        $response = $this->actingAs($this->customer)->get(route('customer.orders.reviews.create', [$order, $item]));

        $response->assertStatus(200);
        $response->assertSee($order->invoice_number);
        $response->assertSee($item->product_name);
        $response->assertSee('Beri Ulasan Produk');
    }

    public function test_4_report_button_navigates_to_report_form_with_correct_order_and_item(): void
    {
        $order = $this->createOrderForCustomer($this->customer, 'completed', 'paid');
        $item = $order->items->first();

        $response = $this->actingAs($this->customer)->get(route('customer.orders.reports.create', [$order, $item]));

        $response->assertStatus(200);
        $response->assertSee($order->invoice_number);
        $response->assertSee($item->product_name);
        $response->assertSee('Laporkan Kendala Produk');
    }

    public function test_5_customer_cannot_review_or_report_another_customer_order(): void
    {
        $order = $this->createOrderForCustomer($this->otherCustomer, 'completed', 'paid');
        $item = $order->items->first();

        // Attempt to access review form
        $responseReview = $this->actingAs($this->customer)->get(route('customer.orders.reviews.create', [$order, $item]));
        $responseReview->assertStatus(403);

        // Attempt to submit review
        $responseSubmitReview = $this->actingAs($this->customer)->post(route('customer.orders.reviews.store', [$order, $item]), [
            'rating' => 5,
            'comment' => 'Kualitas sangat baik',
        ]);
        $responseSubmitReview->assertStatus(403);

        // Attempt to access report form
        $responseReport = $this->actingAs($this->customer)->get(route('customer.orders.reports.create', [$order, $item]));
        $responseReport->assertStatus(403);

        // Attempt to submit report
        $responseSubmitReport = $this->actingAs($this->customer)->post(route('customer.orders.reports.store', [$order, $item]), [
            'report_type' => 'damaged',
            'description' => 'Produk mengalami cacat fisik',
        ]);
        $responseSubmitReport->assertStatus(403);
    }

    public function test_6_after_review_submitted_button_state_changes_to_view_review(): void
    {
        $order = $this->createOrderForCustomer($this->customer, 'completed', 'paid');
        $item = $order->items->first();

        // Submit review
        $response = $this->actingAs($this->customer)->post(route('customer.orders.reviews.store', [$order, $item]), [
            'rating' => 5,
            'comment' => 'Produk sangat memuaskan dan original!',
        ]);
        $response->assertRedirect(route('customer.orders.show', $order));

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'product_id' => $item->product_id,
            'rating' => 5,
        ]);

        // On orders index, button now shows "Lihat Ulasan"
        $indexResponse = $this->actingAs($this->customer)->get(route('customer.orders.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Lihat Ulasan');
        $indexResponse->assertDontSee('Berikan Ulasan');

        // Cannot create duplicate review
        $createAgain = $this->actingAs($this->customer)->get(route('customer.orders.reviews.create', [$order, $item]));
        $createAgain->assertRedirect(route('customer.orders.show', $order));
        $createAgain->assertSessionHas('warning', 'Produk ini sudah direview.');
    }

    public function test_7_after_report_submitted_button_state_changes_to_view_report(): void
    {
        $order = $this->createOrderForCustomer($this->customer, 'completed', 'paid');
        $item = $order->items->first();

        // Submit report
        $response = $this->actingAs($this->customer)->post(route('customer.orders.reports.store', [$order, $item]), [
            'report_type' => 'damaged',
            'description' => 'Dus kemasan penyok dan barang tidak bisa menyala.',
        ]);
        $response->assertRedirect(route('customer.reports.index'));

        $this->assertDatabaseHas('reports', [
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'product_id' => $item->product_id,
            'report_type' => 'damaged',
        ]);

        // On orders index, button now shows "Lihat Laporan"
        $indexResponse = $this->actingAs($this->customer)->get(route('customer.orders.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Lihat Laporan');
        $indexResponse->assertDontSee('Laporkan Masalah');

        // Cannot create duplicate report
        $createAgain = $this->actingAs($this->customer)->get(route('customer.orders.reports.create', [$order, $item]));
        $createAgain->assertRedirect(route('customer.orders.show', $order));
        $createAgain->assertSessionHas('warning', 'Produk ini sudah dilaporkan untuk pesanan tersebut.');
    }

    public function test_8_multi_item_order_actions_on_show_page(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'invoice_number' => Order::generateInvoiceNumber(),
            'order_date' => now(),
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'subtotal' => 125000,
            'shipping_cost' => 0,
            'grand_total' => 125000,
        ]);

        $item1 = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->productA->id,
            'product_name' => $this->productA->name,
            'qty' => 1,
            'price' => 50000,
            'subtotal' => 50000,
        ]);

        $item2 = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->productB->id,
            'product_name' => $this->productB->name,
            'qty' => 1,
            'price' => 75000,
            'subtotal' => 75000,
        ]);

        // Review item 1 only
        Review::create([
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
            'order_item_id' => $item1->id,
            'product_id' => $item1->product_id,
            'rating' => 4,
            'comment' => 'Bagus',
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee('Ulasan Diberikan');
        $response->assertSee('Beri Ulasan');
        $response->assertSee('Laporkan');

        // On index, because item 2 is not yet reviewed, it still offers "Berikan Ulasan"
        $indexResponse = $this->actingAs($this->customer)->get(route('customer.orders.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Berikan Ulasan');
    }
}
