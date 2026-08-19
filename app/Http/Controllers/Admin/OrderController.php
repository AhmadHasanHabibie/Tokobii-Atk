<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with combined payment & pickup stats, search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        // Combined Order & Payment Realtime Statistics
        $totalOrders = Order::count();
        $waitingVerificationOrders = Order::where('payment_status', 'waiting_verification')->count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $readyForPickupOrders = Order::where('order_status', 'ready_for_pickup')->count();
        $completedOrders = Order::where('order_status', 'completed')->count();
        $rejectedOrders = Order::where('payment_status', 'rejected')->count();

        // Optimized Query with Eager Loading (prevents N+1)
        $query = Order::with(['user', 'items.product', 'payment']);

        // Search by Invoice Number, Customer Name, or Username
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Payment Method (Cash, QRIS)
        if ($request->filled('payment_method') && in_array($request->input('payment_method'), ['cash', 'qris'])) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // Filter by Order / Payment Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if (in_array($status, ['pending', 'processing', 'ready_for_pickup', 'completed', 'cancelled'])) {
                $query->where('order_status', $status);
            } elseif (in_array($status, ['paid', 'rejected', 'waiting_verification'])) {
                $query->where('payment_status', $status);
            }
        }

        // Whitelisted Sorting Options
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'invoice_asc':
                $query->orderBy('invoice_number', 'asc');
                break;
            case 'invoice_desc':
                $query->orderBy('invoice_number', 'desc');
                break;
            case 'total_desc':
                $query->orderBy('grand_total', 'desc');
                break;
            case 'total_asc':
                $query->orderBy('grand_total', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'waitingVerificationOrders',
            'paidOrders',
            'readyForPickupOrders',
            'completedOrders',
            'rejectedOrders'
        ));
    }

    /**
     * Display the specified order details with combined Payment & Pickup Information.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'payment.verifiedByAdmin']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the camera-based scanner for existing pickup receipt QR codes.
     */
    public function scan(): View
    {
        return view('admin.orders.scan');
    }

    /**
     * Resolve the invoice number encoded in an existing pickup receipt QR code.
     */
    public function lookupByInvoice(Request $request): JsonResponse
    {
        $payload = trim((string) $request->input('payload'));

        if (!preg_match('/^INV-\d{8}-\d{4}$/', $payload)) {
            return response()->json([
                'message' => 'QR Code tidak valid.',
            ], 422);
        }

        $order = Order::where('invoice_number', $payload)->first();

        if (!$order) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'redirect_url' => route('admin.orders.show', $order),
        ]);
    }

    /**
     * Display dedicated standalone printable Pickup Receipt for Admin.
     */
    public function receipt(Order $order): View
    {
        $order->load(['user', 'items.product', 'payment.verifiedByAdmin']);

        return view('admin.orders.receipt', compact('order'));
    }

    /**
     * Update order and payment status for Verification and Pickup Workflow.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $action = $request->input('action');

        if ($action === 'start_processing') {
            if ($order->payment_method !== 'cash' || $order->order_status !== 'pending' || $order->payment_status !== 'pending') {
                return back()->with('error', 'Hanya pesanan tunai yang masih menunggu pembayaran dapat mulai diproses.');
            }

            $order->update(['order_status' => 'processing']);

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pesanan tunai mulai diproses. Status pembayaran tetap menunggu pembayaran tunai.');
        }

        if ($action === 'confirm_cash_payment') {
            if ($order->payment_method !== 'cash' || $order->order_status !== 'ready_for_pickup' || $order->payment_status !== 'pending') {
                return back()->with('error', 'Pembayaran tunai hanya dapat dikonfirmasi saat pesanan Cash sudah Ready for Pickup.');
            }

            $cashTotal = $order->grand_total_in_rupiah;
            $data = $request->validate([
                'received_amount' => ['required', 'integer', 'min:' . $cashTotal],
            ], [
                'received_amount.required' => 'Uang diterima wajib diisi.',
                'received_amount.integer' => 'Uang diterima harus berupa nominal rupiah utuh.',
                'received_amount.min' => 'Uang diterima kurang dari total pembayaran.',
            ]);

            $receivedAmount = (int) $data['received_amount'];
            $change = $receivedAmount - $cashTotal;
            $payment = $order->payment;

            if (!$payment) {
                return back()->with('error', 'Data pembayaran pesanan tidak ditemukan.');
            }

            $payment->update([
                'payment_status' => 'paid',
                'received_amount' => $receivedAmount,
                'change_amount' => $change,
                'verified_by_admin_id' => auth()->id(),
                'verified_at' => now(),
                'reject_reason' => null,
            ]);
            $order->update(['payment_status' => 'paid']);

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pembayaran tunai dikonfirmasi. Kembalian: Rp ' . number_format($change, 0, ',', '.') . '.');
        }

        if ($action === 'approve_payment') {
            if ($order->payment_method !== 'qris' || $order->payment_status !== 'waiting_verification') {
                return back()->with('error', 'Hanya pembayaran berstatus Waiting Verification yang dapat disetujui.');
            }

            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'paid',
                    'verified_by_admin_id' => auth()->id(),
                    'verified_at' => now(),
                    'reject_reason' => null,
                ]);
            }

            $guidance = [
                'type' => 'success',
                'title' => 'Pembayaran Berhasil Diverifikasi!',
                'message' => 'Pembayaran QRIS untuk pesanan ' . $order->invoice_number . ' telah diverifikasi lunas.',
                'invoice' => $order->invoice_number,
                'amount' => $order->grand_total,
                'steps' => [
                    'Status pesanan saat ini berubah menjadi <strong>Diproses</strong>.',
                    'Silakan ambil dan siapkan produk yang dipesan pelanggan dari stok toko.',
                    'Setelah produk siap di kasir, klik tombol <strong>Set Siap Diambil</strong>.',
                ],
                'primary_btn_text' => 'Tutup & Siapkan Pesanan',
            ];

            return redirect()->route('admin.orders.show', $order)
                ->with('guidance', $guidance)
                ->with('success', 'Pembayaran pesanan ' . $order->invoice_number . ' telah disetujui & mulai diproses.');
        }

        if ($action === 'reject_payment') {
            if ($order->payment_method !== 'qris' || $order->payment_status !== 'waiting_verification') {
                return back()->with('error', 'Hanya pembayaran berstatus Waiting Verification yang dapat ditolak.');
            }

            $request->validate([
                'reject_reason' => 'required|string|max:500',
            ], [
                'reject_reason.required' => 'Alasan penolakan pembayaran wajib diisi.',
                'reject_reason.max' => 'Alasan penolakan maksimal 500 karakter.',
            ]);

            $order->update([
                'payment_status' => 'rejected',
                'order_status' => 'cancelled',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'rejected',
                    'verified_by_admin_id' => auth()->id(),
                    'verified_at' => now(),
                    'reject_reason' => $request->input('reject_reason'),
                ]);
            }

            $guidance = [
                'type' => 'warning',
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran untuk pesanan ' . $order->invoice_number . ' telah ditolak.',
                'invoice' => $order->invoice_number,
                'steps' => [
                    'Alasan penolakan: <em>' . e($request->input('reject_reason')) . '</em>',
                    'Pelanggan dapat melihat alasan penolakan dan mengunggah ulang bukti transfer valid jika diperlukan.',
                ],
                'primary_btn_text' => 'Mengerti',
            ];

            return redirect()->route('admin.orders.show', $order)
                ->with('guidance', $guidance)
                ->with('warning', 'Pembayaran pesanan ' . $order->invoice_number . ' ditolak dan pesanan dibatalkan.');
        }

        if ($action === 'ready_for_pickup') {
            $canBeReady = $order->payment_method === 'cash'
                ? $order->order_status === 'processing' && $order->payment_status === 'pending'
                : $order->payment_status === 'paid' && $order->order_status === 'processing';

            if (!$canBeReady) {
                return back()->with('error', 'Status pesanan belum memenuhi syarat untuk Ready for Pickup.');
            }

            $order->update(['order_status' => 'ready_for_pickup']);

            $guidance = [
                'type' => 'info',
                'title' => 'Pesanan Siap Diambil!',
                'message' => 'Pesanan ' . $order->invoice_number . ' kini berstatus Siap Diambil (Ready for Pickup).',
                'invoice' => $order->invoice_number,
                'steps' => [
                    'Struk pengambilan dapat dicetak melalui tombol <strong>Cetak Struk</strong>.',
                    'Saat pelanggan datang, cocokkan kode QR atau nomor invoice pesanan.',
                    'Setelah produk diserahkan ke pelanggan, klik tombol <strong>Selesaikan Pesanan</strong>.',
                ],
                'primary_btn_text' => 'Mengerti',
            ];

            return redirect()->route('admin.orders.show', $order)
                ->with('guidance', $guidance)
                ->with('success', 'Pesanan ' . $order->invoice_number . ' kini Siap Diambil di toko (Ready for Pickup).');
        }

        if ($action === 'complete') {
            if ($order->order_status !== 'ready_for_pickup' || $order->payment_status !== 'paid') {
                return back()->with('error', 'Pesanan harus Ready for Pickup dan pembayarannya Paid sebelum diselesaikan.');
            }

            $order->update(['order_status' => 'completed']);

            $guidance = [
                'type' => 'success',
                'title' => 'Pesanan Telah Selesai!',
                'message' => 'Pesanan ' . $order->invoice_number . ' telah berhasil diselesaikan dan diserahkan kepada pelanggan.',
                'invoice' => $order->invoice_number,
                'steps' => [
                    'Transaksi tuntas dan tercatat rapi di laporan pendapatan toko.',
                    'Pelanggan sekarang dapat memberikan rating serta ulasan kepuasan produk.',
                ],
                'primary_btn_text' => 'Kembali ke Daftar Pesanan',
                'primary_btn_url' => route('admin.orders.index'),
                'secondary_btn_text' => 'Tetap di Halaman Ini',
            ];

            return redirect()->route('admin.orders.show', $order)
                ->with('guidance', $guidance)
                ->with('success', 'Pesanan ' . $order->invoice_number . ' telah Selesai (Completed).');
        }

        return back()->with('error', 'Tindakan update status tidak valid.');
    }
}
