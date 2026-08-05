<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments with search, filters, sorting, stats, and pagination.
     */
    public function index(Request $request): View
    {
        // Realtime Payment Statistics (including Pickup Workflow)
        $totalPayments = Payment::count();
        $pendingPayments = Payment::where('payment_status', 'pending')->count();
        $waitingVerificationPayments = Payment::where('payment_status', 'waiting_verification')->count();
        $paidPayments = Payment::where('payment_status', 'paid')->count();
        $readyForPickupPayments = Payment::where('payment_status', 'ready_for_pickup')->count();
        $completedPayments = Payment::where('payment_status', 'completed')->count();
        $rejectedPayments = Payment::where('payment_status', 'rejected')->count();

        $query = Payment::with(['order.user', 'verifiedByAdmin']);

        // Search by invoice number or customer name/email
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Payment Method (Cash, QRIS)
        if ($request->filled('payment_method') && in_array($request->input('payment_method'), ['cash', 'qris'])) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // Filter by Payment Status (Pending, Waiting Verification, Paid, Ready for Pickup, Completed, Rejected)
        if ($request->filled('payment_status') && in_array($request->input('payment_status'), ['pending', 'waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected'])) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Whitelisted Sorting
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
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $payments = $query->paginate(10)->withQueryString();

        return view('admin.payments.index', compact(
            'payments',
            'totalPayments',
            'pendingPayments',
            'waitingVerificationPayments',
            'paidPayments',
            'readyForPickupPayments',
            'completedPayments',
            'rejectedPayments'
        ));
    }

    /**
     * Display the specified payment details.
     */
    public function show(Payment $payment): View
    {
        $payment->load(['order.user', 'order.items.product', 'verifiedByAdmin']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Process payment status updates (Verification & Pickup Workflow).
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $action = $request->input('action');

        if ($action === 'approve') {
            if ($payment->payment_status !== 'waiting_verification') {
                return back()->with('error', 'Hanya pembayaran dengan status Waiting Verification yang dapat disetujui.');
            }

            $payment->update([
                'payment_status' => 'paid',
                'verified_by_admin_id' => auth()->id(),
                'verified_at' => now(),
                'reject_reason' => null,
            ]);

            if ($payment->order) {
                $payment->order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'processing',
                ]);
            }

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Pembayaran ' . $payment->invoice_number . ' berhasil diverifikasi dan disetujui.');
        }

        if ($action === 'reject') {
            if ($payment->payment_status !== 'waiting_verification') {
                return back()->with('error', 'Hanya pembayaran dengan status Waiting Verification yang dapat ditolak.');
            }

            $request->validate([
                'reject_reason' => 'required|string|max:500',
            ], [
                'reject_reason.required' => 'Alasan penolakan wajib diisi.',
                'reject_reason.max' => 'Alasan penolakan maksimal 500 karakter.',
            ]);

            $payment->update([
                'payment_status' => 'rejected',
                'verified_by_admin_id' => auth()->id(),
                'verified_at' => now(),
                'reject_reason' => $request->input('reject_reason'),
            ]);

            if ($payment->order) {
                $payment->order->update([
                    'payment_status' => 'rejected',
                ]);
            }

            return redirect()->route('admin.payments.show', $payment)
                ->with('warning', 'Pembayaran ' . $payment->invoice_number . ' telah ditolak.');
        }

        if ($action === 'ready_for_pickup') {
            if ($payment->payment_status !== 'paid') {
                return back()->with('error', 'Hanya pembayaran berstatus Paid yang dapat diubah ke Ready for Pickup.');
            }

            $payment->update([
                'payment_status' => 'ready_for_pickup',
            ]);

            if ($payment->order) {
                $payment->order->update([
                    'order_status' => 'ready_for_pickup',
                ]);
            }

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Pesanan ' . $payment->invoice_number . ' kini Siap Diambil (Ready for Pickup).');
        }

        if ($action === 'complete') {
            if (!in_array($payment->payment_status, ['ready_for_pickup', 'paid'])) {
                return back()->with('error', 'Status pembayaran tidak memenuhi syarat untuk diselesaikan.');
            }

            $payment->update([
                'payment_status' => 'completed',
            ]);

            if ($payment->order) {
                $payment->order->update([
                    'order_status' => 'completed',
                ]);
            }

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Pesanan ' . $payment->invoice_number . ' telah Selesai (Completed).');
        }

        return back()->with('error', 'Tindakan verifikasi tidak valid.');
    }
}
