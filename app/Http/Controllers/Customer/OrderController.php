<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of customer order history.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['items.product', 'items.review', 'items.reports', 'payment'])
            ->where('user_id', auth()->id());

        // Search by Invoice Number or Product Name
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('items', function ($itemQuery) use ($search) {
                      $itemQuery->where('product_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Order / Payment Status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            if (in_array($status, ['pending', 'processing', 'ready_for_pickup', 'completed', 'cancelled'])) {
                $query->where('order_status', $status);
            } elseif (in_array($status, ['paid', 'rejected', 'waiting_verification'])) {
                $query->where('payment_status', $status);
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details for the authenticated customer (Payment Verification & Pickup Receipt page).
     */
    public function show(Order $order): View
    {
        // Security Authorization Check: Only owner can view their order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pesanan ini.');
        }

        $order->load(['items.product', 'items.review', 'items.reports', 'payment', 'user']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Display the dedicated "Lakukan Pembayaran" page for QRIS orders (before uploading proof of payment).
     */
    public function pay(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke halaman pembayaran pesanan ini.');
        }

        if ($order->payment_method !== 'qris') {
            return redirect()->route('customer.orders.show', $order);
        }

        // If proof has already been uploaded and status is waiting_verification, paid, ready_for_pickup, completed
        if (!in_array($order->payment_status, ['pending', 'rejected'])) {
            return redirect()->route('customer.orders.show', $order)
                ->with('info', 'Bukti pembayaran pesanan ini sudah diunggah. Silakan lihat status pada halaman Payment Verification.');
        }

        $order->load(['items.product', 'payment', 'user']);

        return view('customer.orders.pay', compact('order'));
    }

    /**
     * Display the standalone printable pickup receipt for the order.
     */
    public function receipt(Order $order): View|RedirectResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke resi pengambilan ini.');
        }

        if (!in_array($order->order_status, ['ready_for_pickup', 'completed']) && !in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed'])) {
            return back()->with('error', 'Struk pengambilan belum tersedia untuk pesanan ini.');
        }

        $order->load(['items.product', 'payment', 'user']);

        return view('customer.orders.receipt', compact('order'));
    }

    /**
     * Upload or re-upload QRIS proof of payment for customer order.
     */
    public function uploadProof(Request $request, Order $order): RedirectResponse
    {
        // Security Authorization Check: Only owner can upload proof for their order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunggah bukti pembayaran pesanan ini.');
        }

        if ($order->payment_method !== 'qris') {
            return back()->with('error', 'Unggah bukti pembayaran hanya untuk transaksi metode QRIS.');
        }

        if (!in_array($order->payment_status, ['pending', 'rejected'])) {
            return back()->with('error', 'Status pesanan saat ini tidak memungkinkan mengunggah ulang bukti pembayaran.');
        }

        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'proof_of_payment.required' => 'Pilih file bukti pembayaran QRIS Anda.',
            'proof_of_payment.file' => 'File bukti pembayaran tidak valid.',
            'proof_of_payment.mimes' => 'Format file bukti pembayaran harus berupa JPG, JPEG, PNG, atau PDF.',
            'proof_of_payment.max' => 'Ukuran file bukti pembayaran maksimal 2MB.',
        ]);

        if ($request->hasFile('proof_of_payment')) {
            // Delete old file if exists in storage
            if ($order->payment && $order->payment->proof_of_payment && Storage::disk('public')->exists($order->payment->proof_of_payment)) {
                Storage::disk('public')->delete($order->payment->proof_of_payment);
            }

            $path = $request->file('proof_of_payment')->store('proofs', 'public');

            if ($order->payment) {
                $order->payment->update([
                    'proof_of_payment' => $path,
                    'payment_date' => now(),
                    'payment_status' => 'waiting_verification',
                    'reject_reason' => null,
                    'verified_at' => null,
                    'verified_by_admin_id' => null,
                ]);
            } else {
                Payment::create([
                    'order_id' => $order->id,
                    'invoice_number' => $order->invoice_number,
                    'payment_method' => 'qris',
                    'payment_status' => 'waiting_verification',
                    'amount' => $order->grand_total,
                    'proof_of_payment' => $path,
                    'payment_date' => now(),
                ]);
            }

            $order->update([
                'payment_status' => 'waiting_verification',
                'order_status' => 'pending',
            ]);

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Bukti pembayaran QRIS berhasil diunggah. Menunggu verifikasi dari Admin Tokobii.');
        }

        return back()->with('error', 'Gagal mengunggah file bukti pembayaran.');
    }

    /**
     * Submit a rating and review for a completed order.
     */
    public function review(Request $request, Order $order): RedirectResponse
    {
        return redirect()->route('customer.orders.show', $order)
            ->with('info', 'Pilih tombol Review Produk pada item pesanan untuk memberikan ulasan.');

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak diizinkan memberikan ulasan untuk pesanan ini.');
        }

        if ($order->order_status !== 'completed') {
            return back()->with('error', 'Ulasan hanya dapat diberikan untuk pesanan yang telah Selesai (Completed).');
        }

        // Check if review has already been submitted
        if (str_contains($order->notes ?? '', '[RATING_REVIEW]')) {
            return back()->with('warning', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Pilih rating bintang 1 hingga 5.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'review.required' => 'Tuliskan teks ulasan Anda.',
            'review.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        $stars = str_repeat('★', $request->rating) . str_repeat('☆', 5 - $request->rating);
        $reviewContent = "[RATING_REVIEW] Rating: {$request->rating}/5 {$stars} | Review: {$request->review}";

        $updatedNotes = $order->notes ? $order->notes . "\n" . $reviewContent : $reviewContent;

        $order->update([
            'notes' => $updatedNotes,
        ]);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Terima kasih! Ulasan dan rating Anda telah berhasil disimpan.');
    }
}
