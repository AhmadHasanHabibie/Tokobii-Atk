<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')
                ->with('warning', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        return view('customer.checkout.index', compact('cart', 'subtotal'));
    }

    /**
     * Store a newly created order and payment from checkout.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang belanja kosong. Transaksi tidak dapat diproses.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,qris',
            'notes' => 'nullable|string|max:500',
        ], [
            'payment_method.required' => 'Pilih metode pembayaran (Cash atau QRIS).',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'notes.max' => 'Catatan pesanan maksimal 500 karakter.',
        ]);

        // Stock validation check
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['qty']) {
                return back()->with('error', 'Stok produk "' . $item['name'] . '" tidak mencukupi saat ini.');
            }
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }

        $paymentMethod = $request->input('payment_method');
        $notes = $request->input('notes');

        $order = DB::transaction(function () use ($cart, $subtotal, $paymentMethod, $notes) {
            $invoiceNumber = Order::generateInvoiceNumber();

            // Initial status for both Cash & QRIS is pending (Waiting Payment / Waiting Upload)
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_number' => $invoiceNumber,
                'order_date' => now(),
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'grand_total' => $subtotal,
                'notes' => $notes,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Decrement product stock
                Product::where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            Payment::create([
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'amount' => $subtotal,
                'payment_date' => now(),
            ]);

            session()->forget('cart');

            return $order;
        });

        // Revised Workflow: QRIS redirects to Lakukan Pembayaran page, CASH redirects to Order History / Detail Order
        if ($order->payment_method === 'qris') {
            return redirect()->route('customer.orders.pay', $order)
                ->with('info', 'Pesanan ' . $order->invoice_number . ' berhasil dibuat. Silakan selesaikan pembayaran QRIS di bawah ini.');
        }

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pesanan ' . $order->invoice_number . ' berhasil dibuat. Pembayaran secara tunai dilakukan di kasir toko saat mengambil pesanan.');
    }
}
