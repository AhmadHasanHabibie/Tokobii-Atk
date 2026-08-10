@extends('layouts.admin.app')

@section('title', 'Payment Management - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Payment Management</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Payment Management</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Track customer payments, verification queues, and pickup readiness.</p>
        </div>
        <div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary" title="Refresh">
                🔄 Refresh
            </a>
        </div>
    </div>

    {{-- Statistics Cards Grid --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Total</span>
                    <h4 class="fw-bold text-slate-900 mb-0 mt-1" style="font-size: 1.25rem;">{{ number_format($totalPayments) }}</h4>
                </div>
                <div class="rounded-circle bg-slate-100 text-slate-600 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem;">
                    💳
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Pending</span>
                    <h4 class="fw-bold text-amber-600 mb-0 mt-1" style="font-size: 1.25rem; color: #d97706;">{{ number_format($pendingPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem; background-color: #fef3c7; color: #d97706;">
                    🟡
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Wait Verify</span>
                    <h4 class="fw-bold text-blue-600 mb-0 mt-1" style="font-size: 1.25rem;">{{ number_format($waitingVerificationPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem; background-color: #eff6ff; color: #2563eb;">
                    🔵
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Paid</span>
                    <h4 class="fw-bold text-emerald-600 mb-0 mt-1" style="font-size: 1.25rem; color: #16a34a;">{{ number_format($paidPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem; background-color: #f0fdf4; color: #16a34a;">
                    🟢
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Ready Pickup</span>
                    <h4 class="fw-bold text-blue-600 mb-0 mt-1" style="font-size: 1.25rem;">{{ number_format($readyForPickupPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem; background-color: #eff6ff; color: #2563eb;">
                    📦
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Completed</span>
                    <h4 class="fw-bold text-slate-800 mb-0 mt-1" style="font-size: 1.25rem;">{{ number_format($completedPayments) }}</h4>
                </div>
                <div class="rounded-circle bg-slate-100 text-slate-700 d-flex align-items-center justify-content-center border border-slate-200" style="width: 40px; height: 40px; font-size: 1.1rem;">
                    ✅
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Rejected</span>
                    <h4 class="fw-bold text-rose-600 mb-0 mt-1" style="font-size: 1.25rem; color: #e11d48;">{{ number_format($rejectedPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem; background-color: #ffe4e6; color: #e11d48;">
                    🔴
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="tokobii-card mb-4 p-3">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-2 align-items-center">
            
            {{-- Search Input --}}
            <div class="col-12 col-md-3">
                <input type="text" 
                       name="search" 
                       class="tokobii-input w-100" 
                       placeholder="Search invoice, customer name..." 
                       value="{{ request('search') }}"
                       aria-label="Search Payments"
                       autofocus>
            </div>

            {{-- Payment Method Filter --}}
            <div class="col-12 col-sm-6 col-md-3">
                <select name="payment_method" class="tokobii-select w-100" aria-label="Filter payment method">
                    <option value="">Method: All (Cash & QRIS)</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS (Digital)</option>
                </select>
            </div>

            {{-- Payment Status Filter --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="payment_status" class="tokobii-select w-100" aria-label="Filter payment status">
                    <option value="">Status: All</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="waiting_verification" {{ request('payment_status') === 'waiting_verification' ? 'selected' : '' }}>Waiting Verification</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="ready_for_pickup" {{ request('payment_status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="completed" {{ request('payment_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('payment_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="sort" class="tokobii-select w-100" aria-label="Sort payments">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest Payment</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest Payment</option>
                    <option value="invoice_asc" {{ request('sort') === 'invoice_asc' ? 'selected' : '' }}>Invoice (A-Z)</option>
                    <option value="invoice_desc" {{ request('sort') === 'invoice_desc' ? 'selected' : '' }}>Invoice (Z-A)</option>
                    <option value="amount_desc" {{ request('sort') === 'amount_desc' ? 'selected' : '' }}>Amount (Highest)</option>
                    <option value="amount_asc" {{ request('sort') === 'amount_asc' ? 'selected' : '' }}>Amount (Lowest)</option>
                </select>
            </div>

            {{-- Filter Action Buttons --}}
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-tokobii-primary w-100" id="filterBtn">
                    <span class="spinner-border spinner-border-sm me-1 d-none" id="filterSpinner" role="status" aria-hidden="true"></span>
                    <span id="filterBtnText">Filter</span>
                </button>
                @if(request()->hasAny(['search', 'payment_method', 'payment_status', 'sort']))
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary" title="Reset Filter">
                        ↺
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Active Filter Info Banner --}}
    @if(request()->hasAny(['search', 'payment_method', 'payment_status']) && $payments->isNotEmpty())
        <div class="alert border-0 bg-blue-50 text-blue-800 py-2 px-3 mb-3 d-flex align-items-center justify-content-between rounded-3" role="alert" style="font-size: 0.8125rem; background-color: #eff6ff; color: #1e40af;">
            <div>
                🔍 Filter applied. Showing <strong>{{ $payments->total() }}</strong> payment transactions.
            </div>
            <a href="{{ route('admin.payments.index') }}" class="text-decoration-none fw-semibold text-blue-600">Reset Filter</a>
        </div>
    @endif

    {{-- Payment Table Card --}}
    <div class="tokobii-table-container">
        @forelse($payments as $payment)
            @if($loop->first)
                <div class="table-responsive">
                    <table class="tokobii-table">
                        <thead>
                            <tr>
                                <th style="width: 4%;">No</th>
                                <th style="width: 14%;">Invoice</th>
                                <th style="width: 20%;">Customer</th>
                                <th class="text-center" style="width: 12%;">Method</th>
                                <th class="text-end" style="width: 14%;">Amount</th>
                                <th style="width: 13%;">Payment Status</th>
                                <th style="width: 13%;">Pickup Status</th>
                                <th style="width: 10%;">Date</th>
                                <th class="text-end" style="width: 4%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
            @endif

            <tr>
                <td class="fw-semibold text-slate-400">{{ $payments->firstItem() + $loop->index }}</td>
                <td>
                    <code class="text-blue-600 font-monospace fw-bold">{{ $payment->invoice_number }}</code>
                </td>
                <td>
                    <span class="fw-bold text-slate-900 d-block text-truncate" style="max-width: 180px;" title="{{ $payment->order->user->name ?? '-' }}">
                        {{ $payment->order->user->name ?? '-' }}
                    </span>
                    <span class="text-slate-400 font-monospace d-block text-truncate" style="max-width: 180px; font-size: 0.75rem;" title="{{ $payment->order->user->email ?? '-' }}">{{ $payment->order->user->email ?? '-' }}</span>
                </td>
                <td class="text-center">
                    @if($payment->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">
                            📱 QRIS
                        </span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">
                            💵 Cash
                        </span>
                    @endif
                </td>
                <td class="fw-bold text-slate-900 text-end font-monospace">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </td>
                <td>
                    @if($payment->payment_status === 'paid' || $payment->payment_status === 'ready_for_pickup' || $payment->payment_status === 'completed')
                        <span class="tokobii-badge tokobii-badge-success">Paid</span>
                    @elseif($payment->payment_status === 'waiting_verification')
                        <span class="tokobii-badge tokobii-badge-warning">Verification</span>
                    @elseif($payment->payment_status === 'rejected')
                        <span class="tokobii-badge tokobii-badge-danger">Rejected</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-warning">Pending</span>
                    @endif
                </td>
                <td>
                    @if($payment->payment_status === 'ready_for_pickup')
                        <span class="tokobii-badge tokobii-badge-info">Ready Pickup</span>
                    @elseif($payment->payment_status === 'completed')
                        <span class="tokobii-badge tokobii-badge-success">Completed</span>
                    @elseif(in_array($payment->payment_status, ['paid', 'waiting_verification']))
                        <span class="tokobii-badge tokobii-badge-info">Preparing</span>
                    @elseif($payment->payment_status === 'rejected')
                        <span class="tokobii-badge tokobii-badge-neutral">Cancelled</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Unprocessed</span>
                    @endif
                </td>
                <td class="text-slate-500" style="font-size: 0.8125rem;">
                    {{ $payment->payment_date ? $payment->payment_date->format('d M Y, H:i') : '-' }}
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.payments.show', $payment) }}" 
                       class="btn btn-tokobii-secondary btn-sm" 
                       title="Details">
                        👁 Detail
                    </a>
                </td>
            </tr>

            @if($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
            {{-- Empty State --}}
            <div class="text-center py-5 px-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-slate-100 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <span class="fs-3">💳</span>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'payment_method', 'payment_status']))
                    <h5 class="fw-bold text-slate-800 mb-1">No payments found.</h5>
                    <p class="text-slate-400 mb-4" style="font-size: 0.875rem;">No payment transactions match your query or filters.</p>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary">
                        Reset Filters
                    </a>
                @else
                    <h5 class="fw-bold text-slate-800 mb-1">No payments yet.</h5>
                    <p class="text-slate-400 mb-4" style="font-size: 0.875rem;">No transaction payments recorded from Tokobii customers.</p>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary">
                        🔄 Refresh Data
                    </a>
                @endif
            </div>
        @endforelse

        {{-- Pagination Footer --}}
        @if($payments->hasPages())
            <div class="px-4 py-3 border-top border-slate-100 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span class="text-slate-400" style="font-size: 0.8125rem;">
                    Showing {{ $payments->firstItem() }} - {{ $payments->lastItem() }} of {{ $payments->total() }} payments
                </span>
                <div>
                    {{ $payments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.querySelector('form[action="{{ route("admin.payments.index") }}"]');
        const filterBtn = document.getElementById('filterBtn');
        const filterSpinner = document.getElementById('filterSpinner');
        const filterBtnText = document.getElementById('filterBtnText');

        if (filterForm && filterBtn) {
            filterForm.addEventListener('submit', function () {
                filterBtn.disabled = true;
                if (filterSpinner) filterSpinner.classList.remove('d-none');
                if (filterBtnText) filterBtnText.textContent = 'Loading...';
            });
        }
    });
</script>
@endpush
