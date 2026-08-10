@extends('layouts.admin.app')

@section('title', 'Payment & Pickup Details - Tokobii')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden !important;
        }
        #printableReceiptArea, #printableReceiptArea * {
            visibility: visible !important;
        }
        #printableReceiptArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 20px !important;
            background: #fff !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3 d-print-none">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.payments.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Payment Management</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Details</li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3 d-print-none" role="alert">
            <strong>✅ Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3 d-print-none" role="alert">
            <strong>⚠️ Notice:</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3 d-print-none" role="alert">
            <strong>❌ Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3 d-print-none" role="alert">
            <strong>❌ Form Error:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header & Actions --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Payment <span class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</span></h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Payment verification data, pickup receipts, and transaction status.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            {{-- Action Buttons --}}
            @if($payment->payment_status === 'waiting_verification')
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#approvePaymentModal">
                    Approve Payment
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                    Reject Payment
                </button>
            @elseif($payment->payment_status === 'paid')
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#readyForPickupModal">
                    Mark Ready for Pickup
                </button>
            @elseif($payment->payment_status === 'ready_for_pickup')
                <button type="button" class="btn btn-tokobii-secondary" data-bs-toggle="modal" data-bs-target="#printReceiptModal">
                    Print Receipt
                </button>
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#completePaymentModal">
                    Mark Completed
                </button>
            @endif

            <button type="button" onclick="window.location.reload();" class="btn btn-tokobii-secondary" title="Refresh">
                🔄
            </button>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary">
                Back to Payments
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-12 col-md-5">
            
            {{-- Section 1: Payment Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">1. Payment Information</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 40%;">Invoice Number</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $payment->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Payment Method</th>
                                    <td>: 
                                        @if($payment->payment_method === 'qris')
                                            <span class="tokobii-badge tokobii-badge-info">📱 QRIS</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-neutral">💵 Cash</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Payment Status</th>
                                    <td>: 
                                        @if($payment->payment_status === 'paid')
                                            <span class="tokobii-badge tokobii-badge-success">Paid</span>
                                        @elseif($payment->payment_status === 'ready_for_pickup')
                                            <span class="tokobii-badge tokobii-badge-info">Ready for Pickup</span>
                                        @elseif($payment->payment_status === 'completed')
                                            <span class="tokobii-badge tokobii-badge-success">Completed</span>
                                        @elseif($payment->payment_status === 'waiting_verification')
                                            <span class="tokobii-badge tokobii-badge-warning">Verification</span>
                                        @elseif($payment->payment_status === 'rejected')
                                            <span class="tokobii-badge tokobii-badge-danger">Rejected</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Payment Date</th>
                                    <td class="text-slate-800">: {{ $payment->payment_date ? $payment->payment_date->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Verified By</th>
                                    <td class="text-slate-800">: {{ $payment->verifiedByAdmin->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Verified At</th>
                                    <td class="text-slate-400">: {{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section 2: Customer Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">2. Customer Overview</h5>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-blue-50 text-blue-600 fw-bold d-inline-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; font-size: 1.1rem; background-color: #eff6ff; color: #2563eb;">
                            {{ strtoupper(substr($payment->order->user->name ?? 'G', 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-900 mb-0">{{ $payment->order->user->name ?? '-' }}</h6>
                            <span class="text-slate-400 font-monospace d-block" style="font-size: 0.8125rem;">{{ $payment->order->user->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 4: Pickup Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">4. Pickup Details</h5>
                    <span class="tokobii-badge tokobii-badge-info">Main Counter</span>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-slate-100">
                        <div>
                            <span class="text-slate-400 d-block mb-1" style="font-size: 0.75rem;">QR Invoice:</span>
                            <code class="text-blue-600 fw-bold font-monospace">{{ $payment->invoice_number }}</code>
                        </div>
                        <div class="bg-white p-1 rounded-3 border border-slate-200 shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($payment->invoice_number) }}" 
                                 alt="QR Code {{ $payment->invoice_number }}" 
                                 style="width: 72px; height: 72px;">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 40%;">Pickup Status</th>
                                    <td>: 
                                        @if($payment->payment_status === 'completed')
                                            <span class="tokobii-badge tokobii-badge-success">Completed</span>
                                        @elseif($payment->payment_status === 'ready_for_pickup')
                                            <span class="tokobii-badge tokobii-badge-info">Ready for Pickup</span>
                                        @elseif($payment->payment_status === 'paid')
                                            <span class="tokobii-badge tokobii-badge-info">Preparing</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Verification</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Pickup Receipt</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $payment->pickup_receipt_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Pickup Location</th>
                                    <td class="text-slate-800">: Tokobii Physical Store</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section 7: Payment Timeline Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">7. Payment Timeline</h5>
                </div>
                <div class="p-4">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex align-items-start gap-3">
                            <span class="rounded-circle bg-emerald-100 text-emerald-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem; background-color: #f0fdf4; color: #16a34a;">✓</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">1. Order Created</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">{{ $payment->created_at ? $payment->created_at->format('d M Y, H:i') : '-' }}</span>
                            </div>
                        </li>

                        <li class="d-flex align-items-start gap-3 {{ $payment->payment_status !== 'pending' ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">💳</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">2. Waiting Payment</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Method: {{ strtoupper($payment->payment_method) }}</span>
                            </div>
                        </li>

                        <li class="d-flex align-items-start gap-3 {{ in_array($payment->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">🔍</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">3. Verification Queue</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Admin verification</span>
                            </div>
                        </li>

                        <li class="d-flex align-items-start gap-3 {{ in_array($payment->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">🟢</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">4. Paid & Confirmed</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Payment completed</span>
                            </div>
                        </li>

                        <li class="d-flex align-items-start gap-3 {{ in_array($payment->payment_status, ['ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">📦</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">5. Ready for Pickup</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Receipt issued</span>
                            </div>
                        </li>

                        @if($payment->payment_status === 'rejected')
                            <li class="d-flex align-items-start gap-3">
                                <span class="rounded-circle bg-rose-100 text-rose-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem; background-color: #ffe4e6; color: #e11d48;">🔴</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-rose-600" style="font-size: 0.875rem;">Rejected</h6>
                                    <span class="text-slate-400" style="font-size: 0.75rem;">Payment rejected</span>
                                </div>
                            </li>
                        @else
                            <li class="d-flex align-items-start gap-3 {{ $payment->payment_status === 'completed' ? '' : 'opacity-50' }}">
                                <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">🏁</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">6. Completed</h6>
                                    <span class="text-slate-400" style="font-size: 0.75rem;">Order completed</span>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Section 6: Verification Notes Card --}}
            <div class="tokobii-card">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">6. Verification Notes</h5>
                </div>
                <div class="p-4">
                    @if($payment->reject_reason)
                        <div class="p-3 bg-rose-50 text-rose-700 rounded-3 border border-rose-200" style="font-size: 0.875rem; background-color: #fff1f2; color: #be123c;">
                            ⚠️ <strong>Rejection Reason:</strong> {{ $payment->reject_reason }}
                        </div>
                    @else
                        <div class="text-center py-2 text-slate-400" style="font-size: 0.875rem;">
                            No rejection notes.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-12 col-md-7">
            
            {{-- Section 3: Order Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">3. Linked Order</h5>
                    @if($payment->order)
                        <a href="{{ route('admin.orders.show', $payment->order) }}" class="btn btn-tokobii-secondary btn-sm">
                            👁 Order Details
                        </a>
                    @endif
                </div>
                <div class="p-4">
                    @if($payment->order)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Order Invoice</th>
                                        <td class="text-slate-900 fw-bold">: <code class="text-blue-600 font-monospace">{{ $payment->order->invoice_number }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Order Date</th>
                                        <td class="text-slate-800">: {{ $payment->order->order_date ? $payment->order->order_date->format('d M Y, H:i') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Total Items</th>
                                        <td class="text-slate-800 fw-bold">: {{ $payment->order->items ? $payment->order->items->sum('qty') : 0 }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Order Grand Total</th>
                                        <td class="text-slate-900 font-monospace fw-bold">: Rp {{ number_format($payment->order->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-slate-400 mb-0" style="font-size: 0.875rem;">Linked order not found.</p>
                    @endif
                </div>
            </div>

            {{-- Section 5: Payment Summary Card --}}
            <div class="tokobii-card">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">5. Financial Summary</h5>
                </div>
                <div class="p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Items Subtotal</th>
                                            <td class="text-end font-monospace text-slate-800 fw-semibold">: Rp {{ number_format($payment->order->subtotal ?? $payment->amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Discount</th>
                                            <td class="text-end font-monospace text-slate-400">: Rp 0</td>
                                        </tr>
                                        <tr class="border-top border-slate-200">
                                            <th class="ps-0 text-slate-900 fw-bold pt-3 fs-6">Grand Total</th>
                                            <td class="text-end font-monospace text-blue-600 fw-bold pt-3 fs-5">: Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Confirmation Modals --}}
@if($payment->payment_status === 'waiting_verification')
    <div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="approvePaymentModalLabel">Approve Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="approveForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="approve">

                    <div class="modal-body p-4 text-center">
                        <h5 class="fw-bold text-slate-900 mb-2">Approve this payment?</h5>
                        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</strong> (Rp {{ number_format($payment->amount, 0, ',', '.') }}) will be marked as <span class="tokobii-badge tokobii-badge-success">Paid</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-tokobii-primary" id="approveBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="approveSpinner" role="status" aria-hidden="true"></span>
                            <span id="approveBtnText">Yes, Approve Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="rejectPaymentModalLabel">Reject Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="rejectForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject">

                    <div class="modal-body p-4">
                        <h5 class="fw-bold text-slate-900 text-center mb-3">Reject Payment Reason</h5>
                        
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="reject_reason" 
                                      id="reject_reason" 
                                      rows="3" 
                                      class="tokobii-input w-100 @error('reject_reason') is-invalid @enderror" 
                                      placeholder="State reason clearly..." 
                                      required></textarea>
                            @error('reject_reason')
                                <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="rejectBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="rejectSpinner" role="status" aria-hidden="true"></span>
                            <span id="rejectBtnText">Yes, Reject Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($payment->payment_status === 'paid')
    <div class="modal fade" id="readyForPickupModal" tabindex="-1" aria-labelledby="readyForPickupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="readyForPickupModalLabel">Set Ready for Pickup</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="readyForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="ready_for_pickup">

                    <div class="modal-body p-4 text-center">
                        <h5 class="fw-bold text-slate-900 mb-2">Mark Ready for Pickup?</h5>
                        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</strong> will be marked as Ready for Pickup.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-tokobii-primary" id="readyBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="readySpinner" role="status" aria-hidden="true"></span>
                            <span id="readyBtnText">Yes, Ready for Pickup</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($payment->payment_status === 'ready_for_pickup')
    <div class="modal fade" id="completePaymentModal" tabindex="-1" aria-labelledby="completePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="completePaymentModalLabel">Mark as Completed</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="completeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="complete">

                    <div class="modal-body p-4 text-center">
                        <h5 class="fw-bold text-slate-900 mb-2">Complete this transaction?</h5>
                        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</strong> will be marked as <span class="tokobii-badge tokobii-badge-success">Completed</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-tokobii-primary" id="completeBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="completeSpinner" role="status" aria-hidden="true"></span>
                            <span id="completeBtnText">Yes, Mark Completed</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- Printable Pickup Receipt Modal UI --}}
<div class="modal fade" id="printReceiptModal" tabindex="-1" aria-labelledby="printReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-white border-bottom border-slate-200 d-print-none">
                <h5 class="modal-title fw-bold text-slate-900" id="printReceiptModalLabel">🖨 Order Pickup Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="printableReceiptArea">
                <div class="text-center border-bottom border-slate-200 pb-3 mb-3">
                    <h3 class="fw-bold text-slate-900 mb-1">TOKOBII STORE</h3>
                    <p class="text-slate-500 small mb-0">Official Order Pickup Receipt</p>
                </div>

                <div class="row g-3 mb-3 text-slate-600" style="font-size: 0.875rem;">
                    <div class="col-6">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Invoice / Receipt:</span>
                        <strong class="text-blue-600 font-monospace fs-6">{{ $payment->pickup_receipt_number }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Receipt Date:</span>
                        <strong class="text-slate-800">{{ date('d M Y, H:i') }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Customer Name:</span>
                        <strong class="text-slate-800">{{ $payment->order->user->name ?? '-' }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Payment Method:</span>
                        <strong class="text-uppercase text-slate-800">{{ $payment->payment_method }} ({{ ucfirst($payment->payment_status) }})</strong>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered border-slate-200 align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th>No</th>
                                <th>Item Name</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($payment->order && $payment->order->items)
                                @foreach($payment->order->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-slate-900">{{ $item->product_name }}</td>
                                        <td class="text-center font-monospace text-slate-500">{{ $item->product->sku ?? '-' }}</td>
                                        <td class="text-center text-slate-900 fw-bold">{{ $item->qty }}</td>
                                        <td class="text-end font-monospace text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end font-monospace fw-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50">
                                <td colspan="5" class="text-end fw-bold text-slate-800">Grand Total:</td>
                                <td class="text-end font-monospace fw-bold text-blue-600 fs-6">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top border-slate-200 pt-3">
                    <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-500 me-3" style="max-width: 70%; font-size: 0.8125rem;">
                        <strong>Note:</strong> Present this receipt when collecting your order at the counter.
                    </div>
                    <div class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($payment->invoice_number) }}" 
                             alt="QR Code {{ $payment->invoice_number }}" 
                             class="border border-slate-200 rounded p-1 shadow-sm mb-1" 
                             style="width: 72px; height: 72px;">
                        <span class="d-block text-slate-400 font-monospace" style="font-size: 0.6875rem;">{{ $payment->invoice_number }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-slate-50 border-top border-slate-100 py-2 d-print-none">
                <button type="button" onclick="window.print();" class="btn btn-tokobii-primary">
                    🖨 Print Receipt
                </button>
                <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupSubmitHandler(formId, btnId, spinnerId, btnTextId, text) {
            const form = document.getElementById(formId);
            const btn = document.getElementById(btnId);
            const spinner = document.getElementById(spinnerId);
            const btnText = document.getElementById(btnTextId);

            if (form && btn) {
                form.addEventListener('submit', function () {
                    btn.disabled = true;
                    if (spinner) spinner.classList.remove('d-none');
                    if (btnText) btnText.textContent = text;
                });
            }
        }

        setupSubmitHandler('approveForm', 'approveBtn', 'approveSpinner', 'approveBtnText', 'Processing...');
        setupSubmitHandler('rejectForm', 'rejectBtn', 'rejectSpinner', 'rejectBtnText', 'Processing...');
        setupSubmitHandler('readyForm', 'readyBtn', 'readySpinner', 'readyBtnText', 'Processing...');
        setupSubmitHandler('completeForm', 'completeBtn', 'completeSpinner', 'completeBtnText', 'Processing...');
    });
</script>
@endpush
