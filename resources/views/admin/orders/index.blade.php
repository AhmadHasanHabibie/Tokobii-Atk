@extends('layouts.admin.app')

@section('title', 'Order Management - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Orders</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Order Management</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Manage store orders, verify payments, and handle order pickups.</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Refresh</span>
            </a>
        </div>
    </div>

    {{-- 6 Order Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Total Orders</span>
                        <h4 class="fw-bold text-slate-900 mb-0 mt-1" style="font-size: 1.35rem;">{{ number_format($totalOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Waiting Verify</span>
                        <h4 class="fw-bold text-amber-500 mb-0 mt-1" style="font-size: 1.35rem;">{{ number_format($waitingVerificationOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Paid</span>
                        <h4 class="fw-bold text-emerald-600 mb-0 mt-1" style="font-size: 1.35rem;">{{ number_format($paidOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Ready Pickup</span>
                        <h4 class="fw-bold text-blue-600 mb-0 mt-1" style="font-size: 1.35rem;">{{ number_format($readyForPickupOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Completed</span>
                        <h4 class="fw-bold text-slate-900 mb-0 mt-1" style="font-size: 1.35rem;">{{ number_format($completedOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Rejected</span>
                        <h4 class="fw-bold text-rose-500 mb-0 mt-1" style="font-size: 1.35rem;">{{ number_format($rejectedOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="tokobii-card p-3 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            
            {{-- Search Input --}}
            <div class="col-12 col-md-3">
                <input type="text" 
                       name="search" 
                       class="tokobii-input w-100" 
                       placeholder="Search invoice, customer..." 
                       value="{{ request('search') }}"
                       autofocus>
            </div>

            {{-- Payment Method Filter --}}
            <div class="col-12 col-sm-6 col-md-3">
                <select name="payment_method" class="tokobii-select w-100">
                    <option value="">Payment Method: All</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash (In Store)</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS (Digital QR)</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="status" class="tokobii-select w-100">
                    <option value="">Order Status: All</option>
                    <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Waiting Verification</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="sort" class="tokobii-select w-100">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="invoice_asc" {{ request('sort') === 'invoice_asc' ? 'selected' : '' }}>Invoice (A-Z)</option>
                    <option value="total_desc" {{ request('sort') === 'total_desc' ? 'selected' : '' }}>Highest Total</option>
                    <option value="total_asc" {{ request('sort') === 'total_asc' ? 'selected' : '' }}>Lowest Total</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-tokobii-primary w-100" id="filterBtn">
                    <span class="spinner-border spinner-border-sm me-1 d-none" id="filterSpinner" role="status" aria-hidden="true"></span>
                    <span id="filterBtnText">Filter</span>
                </button>
                @if(request()->hasAny(['search', 'payment_method', 'status', 'sort']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary px-3" title="Reset">
                        ↺
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Orders Table --}}
    <div class="tokobii-table-container">
        @forelse($orders as $order)
            @if($loop->first)
                <div class="table-responsive">
                    <table class="tokobii-table">
                        <thead>
                            <tr>
                                <th style="width: 4%;">No</th>
                                <th style="width: 15%;">Invoice</th>
                                <th style="width: 20%;">Customer</th>
                                <th class="text-center" style="width: 10%;">Items</th>
                                <th class="text-end" style="width: 14%;">Grand Total</th>
                                <th class="text-center" style="width: 12%;">Payment</th>
                                <th style="width: 13%;">Status</th>
                                <th style="width: 12%;">Date</th>
                                <th class="text-end" style="width: 6%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
            @endif

            <tr>
                <td class="fw-semibold text-slate-400">{{ $orders->firstItem() + $loop->index }}</td>
                <td>
                    <code class="text-blue-600 font-monospace fw-bold" style="font-size: 0.8125rem;">{{ $order->invoice_number }}</code>
                </td>
                <td>
                    <span class="fw-semibold text-slate-900 d-block text-truncate" style="max-width: 160px;" title="{{ $order->user->name ?? '-' }}">
                        {{ $order->user->name ?? '-' }}
                    </span>
                    <span class="text-slate-400 d-block text-truncate font-monospace" style="font-size: 0.75rem; max-width: 160px;">{{ $order->user->email ?? '-' }}</span>
                </td>
                <td class="text-center fw-semibold text-slate-700 font-monospace">
                    {{ $order->items->sum('qty') }} Pcs
                </td>
                <td class="fw-bold text-slate-900 text-end font-monospace">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    @if($order->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">📱 QRIS</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">💵 Cash</span>
                    @endif
                </td>
                <td>
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
                <td class="text-slate-500" style="font-size: 0.8125rem;">
                    {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.orders.show', $order) }}" 
                       class="btn btn-sm btn-tokobii-secondary">
                        Detail
                    </a>
                </td>
            </tr>

            @if($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
            <div class="text-center py-5 px-4">
                <p class="text-slate-400 mb-3">No orders found.</p>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary">Reset Filters</a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="p-3 border-top border-slate-100 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span class="text-slate-400" style="font-size: 0.8125rem;">
                    Showing {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </span>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.querySelector('form[action="{{ route("admin.orders.index") }}"]');
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
