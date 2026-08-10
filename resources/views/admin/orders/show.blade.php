@extends('layouts.admin.app')

@section('title', 'Order Details - Tokobii')

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
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Orders</a>
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
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Order <span class="text-blue-600 font-monospace">{{ $order->invoice_number }}</span></h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Payment verification, item breakdown, and pickup status management.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            {{-- Workflow Action Buttons --}}
            @if($order->payment_method === 'cash' && $order->order_status === 'pending' && $order->payment_status === 'pending')
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="start_processing">
                    <button type="submit" class="btn btn-tokobii-primary">Start Processing</button>
                </form>
            @elseif($order->payment_status === 'waiting_verification')
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#approvePaymentModal">
                    Approve Payment
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                    Reject Payment
                </button>
            @elseif($order->order_status === 'processing')
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#readyForPickupModal">
                    Mark Ready for Pickup
                </button>
            @elseif($order->order_status === 'ready_for_pickup' && $order->payment_method === 'cash' && $order->payment_status === 'pending')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary">Print Receipt</a>
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#cashPaymentModal">Confirm Cash Payment</button>
            @elseif($order->order_status === 'ready_for_pickup')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary">
                    Print Receipt
                </a>
                <button type="button" class="btn btn-tokobii-primary" data-bs-toggle="modal" data-bs-target="#completeOrderModal">
                    Mark Completed
                </button>
            @elseif($order->order_status === 'completed')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary">
                    Print Receipt
                </a>
            @endif

            <button type="button" onclick="window.location.reload();" class="btn btn-tokobii-secondary" title="Refresh">
                🔄
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary">
                Back to Orders
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column: Cards --}}
        <div class="col-12 col-md-5">
            
            {{-- CARD 1: Order Information --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Order Overview</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 40%;">Invoice</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $order->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Order Date</th>
                                    <td class="text-slate-800">: {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Overall Status</th>
                                    <td>: 
                                        @if($order->status === 'completed')
                                            <span class="tokobii-badge tokobii-badge-success">Completed</span>
                                        @elseif($order->status === 'ready_for_pickup')
                                            <span class="tokobii-badge tokobii-badge-info">Ready for Pickup</span>
                                        @elseif($order->status === 'processing')
                                            <span class="tokobii-badge tokobii-badge-info">Processing</span>
                                        @elseif($order->status === 'paid')
                                            <span class="tokobii-badge tokobii-badge-success">Paid</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="tokobii-badge tokobii-badge-danger">Cancelled</span>
                                        @elseif($order->status === 'waiting_verification')
                                            <span class="tokobii-badge tokobii-badge-warning">Verification</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CARD 2: Customer Information --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Customer Overview</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Customer</th>
                                    <td class="text-slate-900 fw-bold">: {{ $order->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Email</th>
                                    <td class="text-slate-800 font-monospace">: {{ $order->user->email ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CARD 4: Payment Verification & Proof --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Payment Verification</h5>
                </div>
                <div class="p-4">
                    @if($order->payment_method === 'cash')
                        <table class="table table-borderless align-middle mb-3" style="font-size: 0.875rem;"><tbody>
                            <tr><th class="ps-0 text-slate-500" style="width:42%">Grand Total</th><td>: <strong class="font-monospace text-slate-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong></td></tr>
                            <tr><th class="ps-0 text-slate-500">Status</th><td>: <span class="tokobii-badge {{ $order->payment_status === 'paid' ? 'tokobii-badge-success' : 'tokobii-badge-warning' }}">{{ $order->payment_status === 'paid' ? 'Paid' : 'Pending Cash' }}</span></td></tr>
                            @if($order->payment?->received_amount !== null)
                                <tr><th class="ps-0 text-slate-500">Amount Received</th><td>: Rp {{ number_format($order->payment->received_amount, 0, ',', '.') }}</td></tr>
                                <tr><th class="ps-0 text-slate-500">Change</th><td>: Rp {{ number_format($order->payment->change_amount, 0, ',', '.') }}</td></tr>
                                <tr><th class="ps-0 text-slate-500">Processed By</th><td>: {{ $order->payment->verifiedByAdmin?->name ?? '-' }}</td></tr>
                            @endif
                        </tbody></table>
                        <p class="text-slate-400 mb-0" style="font-size: 0.75rem;">Cash payments are accepted directly at the counter upon order pickup.</p>
                    @else
                    <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 42%;">Payment Method</th>
                                    <td>: 
                                        @if($order->payment_method === 'qris')
                                            <span class="tokobii-badge tokobii-badge-info">📱 QRIS</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-neutral">💵 Cash</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Payment Status</th>
                                    <td>: 
                                        @if($order->payment_status === 'paid' || in_array($order->order_status, ['ready_for_pickup', 'completed']))
                                            <span class="tokobii-badge tokobii-badge-success">Paid</span>
                                        @elseif($order->payment_status === 'rejected')
                                            <span class="tokobii-badge tokobii-badge-danger">Rejected</span>
                                        @elseif($order->payment_status === 'waiting_verification')
                                            <span class="tokobii-badge tokobii-badge-warning">Verification</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Upload Time</th>
                                    <td class="text-slate-500">: {{ $order->payment && $order->payment->payment_date ? $order->payment->payment_date->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Verification Time</th>
                                    <td class="text-slate-500">: {{ $order->payment && $order->payment->verified_at ? $order->payment->verified_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Verified By</th>
                                    <td class="text-slate-800">: {{ $order->payment && $order->payment->verifiedByAdmin ? $order->payment->verifiedByAdmin->name : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Transfer Proof Preview --}}
                    @if($order->payment_method === 'qris')
                        <div class="border-top border-slate-100 pt-3 text-center">
                            <span class="text-slate-400 d-block mb-2" style="font-size: 0.75rem;">Payment Proof Screenshot:</span>
                            @if($order->payment && $order->payment->proof_of_payment)
                                @php
                                    $proofPath = $order->payment->proof_of_payment;
                                    $isPdf = \Illuminate\Support\Str::endsWith(strtolower($proofPath), '.pdf');
                                @endphp

                                @if($isPdf)
                                    <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-tokobii-secondary w-100">
                                        📄 Open PDF Proof
                                    </a>
                                @else
                                    <button type="button" class="btn p-0 border-0 shadow-sm rounded-3 overflow-hidden" data-bs-toggle="modal" data-bs-target="#proofModal">
                                        <img src="{{ asset('storage/' . $proofPath) }}" 
                                             alt="Payment proof {{ $order->invoice_number }}" 
                                             class="img-fluid rounded-3 border border-slate-200" 
                                             style="max-height: 180px; object-fit: contain;">
                                    </button>
                                    <span class="text-slate-400 d-block mt-1" style="font-size: 0.75rem;">(Click to view full size)</span>
                                @endif
                            @else
                                <div class="p-3 bg-slate-50 rounded-3 text-slate-400" style="font-size: 0.8125rem;">
                                    No payment proof uploaded yet.
                                </div>
                            @endif
                        </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- CARD 5: Pickup Information --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Pickup Status</h5>
                    <span class="tokobii-badge tokobii-badge-info">Main Counter</span>
                </div>
                <div class="p-4">
                    <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 40%;">Pickup Status</th>
                                    <td>: 
                                        @if($order->status === 'completed')
                                            <span class="tokobii-badge tokobii-badge-success">Completed</span>
                                        @elseif($order->status === 'ready_for_pickup')
                                            <span class="tokobii-badge tokobii-badge-info">Ready for Pickup</span>
                                        @elseif($order->status === 'processing' || $order->status === 'paid')
                                            <span class="tokobii-badge tokobii-badge-info">Processing</span>
                                        @elseif($order->status === 'waiting_verification')
                                            <span class="tokobii-badge tokobii-badge-warning">Verification</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Pending Payment</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Pickup Receipt</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $order->invoice_number }}</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if(in_array($order->order_status, ['ready_for_pickup', 'completed']) || in_array($order->payment_status, ['paid', 'completed']))
                        <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary w-100">
                            🖨 Open Pickup Receipt Page
                        </a>
                    @endif
                </div>
            </div>

            {{-- CARD 6: Order Timeline --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Order Progress</h5>
                </div>
                <div class="p-4">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex align-items-start gap-3">
                            <span class="rounded-circle bg-emerald-100 text-emerald-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem; background-color: #f0fdf4; color: #16a34a;">✓</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Order Created</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 {{ in_array($order->status, ['waiting_payment', 'waiting_verification', 'paid', 'processing', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">💳</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Payment Pending</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Customer checkout</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 {{ in_array($order->status, ['waiting_verification', 'paid', 'processing', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">🔍</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Payment Verification</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Admin verification</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 {{ in_array($order->status, ['paid', 'processing', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">🟢</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Payment Approved</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Confirmed paid</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 {{ in_array($order->status, ['processing', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">⚙️</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Processing Items</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Item preparation</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 {{ in_array($order->status, ['ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">📦</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Ready for Pickup</h6>
                                <span class="text-slate-400" style="font-size: 0.75rem;">Awaiting customer pickup</span>
                            </div>
                        </li>
                        @if($order->status === 'cancelled')
                            <li class="d-flex align-items-start gap-3">
                                <span class="rounded-circle bg-rose-100 text-rose-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem; background-color: #ffe4e6; color: #e11d48;">❌</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-rose-600" style="font-size: 0.875rem;">Cancelled / Rejected</h6>
                                    <span class="text-slate-400" style="font-size: 0.75rem;">Order rejected</span>
                                </div>
                            </li>
                        @else
                            <li class="d-flex align-items-start gap-3 {{ $order->status === 'completed' ? '' : 'opacity-50' }}">
                                <span class="rounded-circle bg-slate-100 text-slate-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">🏁</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.875rem;">Completed</h6>
                                    <span class="text-slate-400" style="font-size: 0.75rem;">Handed over</span>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>

        {{-- Right Column: Items & Summary --}}
        <div class="col-12 col-md-7">
            
            {{-- Order Items Table --}}
            <div class="tokobii-table-container mb-4">
                <div class="p-3 border-bottom border-slate-100 bg-white">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Order Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="tokobii-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 14%;">Item</th>
                                <th>Product Name</th>
                                <th class="text-center" style="width: 12%;">Qty</th>
                                <th class="text-end" style="width: 18%;">Price</th>
                                <th class="text-end" style="width: 20%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="fw-semibold text-slate-400">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($item->product && $item->product->thumbnail)
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="rounded-3 border border-slate-200" 
                                                 style="width: 44px; height: 44px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-slate-100 d-flex align-items-center justify-content-center text-slate-400 border border-slate-200" style="width: 44px; height: 44px;">
                                                📦
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-slate-900 d-block">{{ $item->product_name }}</span>
                                        @if($item->product && $item->product->sku)
                                            <span class="text-slate-400 font-monospace" style="font-size: 0.75rem;">SKU: {{ $item->product->sku }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold text-slate-800 font-monospace">
                                        {{ $item->qty }}
                                    </td>
                                    <td class="text-end text-slate-700 font-monospace">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-slate-900 font-monospace">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-slate-400">
                                        No items recorded for this order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="tokobii-card">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Payment Summary</h5>
                </div>
                <div class="p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Items Subtotal</th>
                                            <td class="text-end font-monospace text-slate-800 fw-semibold">: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Pickup Fee</th>
                                            <td class="text-end font-monospace text-emerald-600 fw-semibold">: Rp 0</td>
                                        </tr>
                                        <tr class="border-top border-slate-200">
                                            <th class="ps-0 text-slate-900 fw-bold pt-3 fs-6">Grand Total</th>
                                            <td class="text-end font-monospace text-blue-600 fw-bold pt-3 fs-5">: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
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

{{-- Modal Image Preview --}}
@if($order->payment && $order->payment->proof_of_payment)
    <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="proofModalLabel">QRIS Payment Proof {{ $order->invoice_number }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 text-center bg-slate-50">
                    <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}" 
                         alt="Payment proof {{ $order->invoice_number }}" 
                         class="img-fluid rounded-3 border border-slate-200" 
                         style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer bg-slate-50 border-top border-slate-200">
                    <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" target="_blank" class="btn btn-tokobii-secondary">
                        Open in New Tab
                    </a>
                    <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Confirmation Modals --}}

@if($order->payment_method === 'cash' && $order->order_status === 'ready_for_pickup' && $order->payment_status === 'pending')
    <div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-slate-900 text-white border-0"><h5 class="modal-title fw-bold text-white">Confirm Cash Payment</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="cashPaymentForm">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="confirm_cash_payment">
                <div class="modal-body p-4"><p class="mb-3 text-slate-700">Grand Total: <strong class="font-monospace text-slate-900">Rp {{ number_format($order->grand_total_in_rupiah, 0, ',', '.') }}</strong></p>
                    <label for="received_amount" class="form-label">Amount Received (Rp)</label>
                    <input id="received_amount" name="received_amount" type="number" min="{{ $order->grand_total_in_rupiah }}" step="1" inputmode="numeric" class="tokobii-input w-100 @error('received_amount') is-invalid @enderror" value="{{ old('received_amount') }}" required>
                    @error('received_amount')<div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>@enderror
                    <div class="mt-3 p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-700">Change Due: <strong id="cashChangePreview" class="font-monospace text-blue-600">Rp 0</strong></div>
                    <small id="cashAmountError" class="text-danger d-none mt-1">Received amount is less than total.</small>
                </div>
                <div class="modal-footer border-top border-slate-100"><button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" id="cashPaymentSubmit" class="btn btn-tokobii-primary" disabled>Confirm Cash Payment</button></div>
            </form>
        </div></div>
    </div>
@endif

@if($order->payment_status === 'waiting_verification')
    <div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="approvePaymentModalLabel">Approve Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="approveOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="approve_payment">

                    <div class="modal-body p-4 text-center">
                        <h5 class="fw-bold text-slate-900 mb-2">Approve Order Payment?</h5>
                        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> (Rp {{ number_format($order->grand_total, 0, ',', '.') }}) will be marked as <span class="tokobii-badge tokobii-badge-success">Paid</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-tokobii-primary" id="approveSubmitBtn">
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
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="rejectOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject_payment">

                    <div class="modal-body p-4">
                        <h5 class="fw-bold text-slate-900 text-center mb-3">Reject Payment Reason</h5>
                        
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="reject_reason" 
                                      id="reject_reason" 
                                      rows="3" 
                                      class="tokobii-input w-100 @error('reject_reason') is-invalid @enderror" 
                                      placeholder="e.g. Amount mismatch, Blurry transfer proof..." 
                                      required></textarea>
                            @error('reject_reason')
                                <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="rejectSubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="rejectSpinner" role="status" aria-hidden="true"></span>
                            <span id="rejectBtnText">Yes, Reject Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($order->payment_status === 'paid' || $order->order_status === 'processing')
    <div class="modal fade" id="readyForPickupModal" tabindex="-1" aria-labelledby="readyForPickupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="readyForPickupModalLabel">Set Ready for Pickup</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="readyOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="ready_for_pickup">

                    <div class="modal-body p-4 text-center">
                        <h5 class="fw-bold text-slate-900 mb-2">Mark Ready for Pickup?</h5>
                        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> will be marked as Ready for Pickup.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-tokobii-primary" id="readySubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="readySpinner" role="status" aria-hidden="true"></span>
                            <span id="readyBtnText">Yes, Ready for Pickup</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($order->order_status === 'ready_for_pickup')
    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-labelledby="completeOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-slate-900 text-white border-0">
                    <h5 class="modal-title fw-bold text-white" id="completeOrderModalLabel">Mark as Completed</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="completeOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="complete">

                    <div class="modal-body p-4 text-center">
                        <h5 class="fw-bold text-slate-900 mb-2">Complete this Order?</h5>
                        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> will be finalized as <span class="tokobii-badge tokobii-badge-success">Completed</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-tokobii-primary" id="completeSubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="completeSpinner" role="status" aria-hidden="true"></span>
                            <span id="completeBtnText">Yes, Completed</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

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

        setupSubmitHandler('approveOrderForm', 'approveSubmitBtn', 'approveSpinner', 'approveBtnText', 'Processing...');
        setupSubmitHandler('rejectOrderForm', 'rejectSubmitBtn', 'rejectSpinner', 'rejectBtnText', 'Processing...');
        setupSubmitHandler('readyOrderForm', 'readySubmitBtn', 'readySpinner', 'readyBtnText', 'Processing...');
        setupSubmitHandler('completeOrderForm', 'completeSubmitBtn', 'completeSpinner', 'completeBtnText', 'Processing...');

        const receivedAmount = document.getElementById('received_amount');
        const changePreview = document.getElementById('cashChangePreview');
        const cashPaymentForm = document.getElementById('cashPaymentForm');
        const cashPaymentSubmit = document.getElementById('cashPaymentSubmit');
        const cashAmountError = document.getElementById('cashAmountError');
        if (receivedAmount && changePreview && cashPaymentSubmit) {
            const total = BigInt({{ $order->grand_total_in_rupiah }});
            const formatRupiah = (amount) => 'Rp ' + amount.toLocaleString('id-ID');
            const updateCashPayment = () => {
                let received;

                try {
                    received = BigInt(receivedAmount.value);
                } catch (error) {
                    received = null;
                }

                const isValid = received !== null && received >= total;
                cashPaymentSubmit.disabled = !isValid;
                cashAmountError?.classList.toggle('d-none', received === null || isValid);
                changePreview.textContent = formatRupiah(isValid ? received - total : BigInt(0));

                return isValid;
            };

            receivedAmount.addEventListener('input', updateCashPayment);
            cashPaymentForm?.addEventListener('submit', function (event) {
                if (!updateCashPayment()) {
                    event.preventDefault();
                    return;
                }

                cashPaymentSubmit.disabled = true;
            });

            updateCashPayment();
        }
    });
</script>
@endpush
