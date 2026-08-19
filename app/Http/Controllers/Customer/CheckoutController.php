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

            // Cash orders can be prepared immediately, while QRIS orders remain
            // pending until the customer submits proof of payment. Both payment
            // records use the existing `pending` enum value for Waiting Payment.
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_number' => $invoiceNumber,
                'order_date' => now(),
                'order_status' => $paymentMethod === 'cash' ? 'processing' : 'pending',
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

        // Set rich guidance data for post-action interactive modal
        if ($order->payment_method === 'qris') {
            $guidance = [
                'type' => 'info',
                'title' => 'Pesanan Berhasil Dibuat!',
                'message' => 'Pesanan Anda telah tercatat dengan invoice ' . $order->invoice_number . '. Silakan selesaikan pembayaran QRIS untuk proses verifikasi.',
                'invoice' => $order->invoice_number,
                'amount' => $order->grand_total,
                'method' => 'qris',
                'steps' => [
                    'Pindai (scan) kode QRIS resmi Tokobii pada bagian informasi pembayaran di bawah.',
                    'Transfer tepat sejumlah <strong>Rp ' . number_format($order->grand_total, 0, ',', '.') . '</strong> via E-Wallet atau Mobile Banking.',
                    'Unggah foto bukti transfer pada kartu <strong>Unggah Bukti Transfer QRIS</strong> agar admin dapat memverifikasi pesanan Anda.',
                ],
                'primary_btn_text' => 'Mengerti & Unggah Bukti',
                'primary_btn_url' => null,
                'secondary_btn_text' => 'Lihat Daftar Pesanan',
                'secondary_btn_url' => route('customer.orders.index'),
            ];
        } else {
            $guidance = [
                'type' => 'success',
                'title' => 'Pesanan Berhasil Dibuat!',
                'message' => 'Pesanan Anda dengan invoice ' . $order->invoice_number . ' sedang disiapkan oleh tim Tokobii.',
                'invoice' => $order->invoice_number,
                'amount' => $order->grand_total,
                'method' => 'cash',
                'steps' => [
                    'Staf toko sedang menyiapkan produk yang Anda pesan.',
                    'Kunjungi gerai Tokobii dan tunjukkan <strong>Kode QR / No. Invoice</strong> pada halaman ini kepada kasir.',
                    'Lakukan pembayaran tunai pas sebesar <strong>Rp ' . number_format($order->grand_total, 0, ',', '.') . '</strong> saat serah terima barang.',
                ],
                'primary_btn_text' => 'Lihat Detail Pesanan',
                'primary_btn_url' => null,
                'secondary_btn_text' => 'Katalog Produk',
                'secondary_btn_url' => route('customer.shop.index'),
            ];
        }

        return redirect()->route('customer.orders.show', $order)
            ->with('order_guidance', $guidance)
            ->with('success', 'Pesanan ' . $order->invoice_number . ' berhasil dibuat.');
    }
}
