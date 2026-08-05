<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        if ($action === 'approve_payment') {
            if ($order->payment_status !== 'waiting_verification') {
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

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pembayaran pesanan ' . $order->invoice_number . ' telah disetujui & mulai diproses.');
        }

        if ($action === 'reject_payment') {
            if ($order->payment_status !== 'waiting_verification') {
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

            return redirect()->route('admin.orders.show', $order)
                ->with('warning', 'Pembayaran pesanan ' . $order->invoice_number . ' ditolak dan pesanan dibatalkan.');
        }

        if ($action === 'ready_for_pickup') {
            if ($order->payment_status !== 'paid' && $order->order_status !== 'processing') {
                return back()->with('error', 'Hanya pesanan yang sudah dibayar (Paid) yang dapat diubah menjadi Ready for Pickup.');
            }

            $order->update([
                'order_status' => 'ready_for_pickup',
                'payment_status' => 'ready_for_pickup',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'ready_for_pickup',
                ]);
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pesanan ' . $order->invoice_number . ' kini Siap Diambil di toko (Ready for Pickup).');
        }

        if ($action === 'complete') {
            if (!in_array($order->order_status, ['ready_for_pickup', 'processing']) && !in_array($order->payment_status, ['paid', 'ready_for_pickup'])) {
                return back()->with('error', 'Status pesanan tidak memenuhi syarat untuk diselesaikan.');
            }

            $order->update([
                'order_status' => 'completed',
                'payment_status' => 'completed',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'completed',
                ]);
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pesanan ' . $order->invoice_number . ' telah Selesai (Completed).');
        }

        return back()->with('error', 'Tindakan update status tidak valid.');
    }
}
