@extends('layouts.admin.app')

@section('title', 'Order Management - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Order Management</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Order Management</h2>
            <p class="text-muted mb-0">Pusat pengelolaan transaksi, verifikasi pembayaran, dan status pengambilan pesanan customer Tokobii.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold shadow-sm" title="Refresh Halaman" aria-label="Refresh Halaman">
                🔄 Refresh
            </a>
        </div>
    </div>

    {{-- 6 Order Dashboard Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Orders</span>
                        <h4 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalOrders) }}</h4>
                    </div>
                    <div class="bg-light rounded-circle p-2 text-primary fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        🛒
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Waiting Verify</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1">{{ number_format($waitingVerificationOrders) }}</h4>
                    </div>
                    <div class="bg-warning-subtle rounded-circle p-2 text-warning fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        🟡
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Paid</span>
                        <h4 class="fw-bold text-success mb-0 mt-1">{{ number_format($paidOrders) }}</h4>
                    </div>
                    <div class="bg-success-subtle rounded-circle p-2 text-success fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        🟢
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Ready Pickup</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">{{ number_format($readyForPickupOrders) }}</h4>
                    </div>
                    <div class="bg-primary-subtle rounded-circle p-2 text-primary fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        📦
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Completed</span>
                        <h4 class="fw-bold text-dark mb-0 mt-1">{{ number_format($completedOrders) }}</h4>
                    </div>
                    <div class="bg-light border rounded-circle p-2 text-dark fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        ✅
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Rejected</span>
                        <h4 class="fw-bold text-danger mb-0 mt-1">{{ number_format($rejectedOrders) }}</h4>
                    </div>
                    <div class="bg-danger-subtle rounded-circle p-2 text-danger fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        🔴
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Search Input (Invoice, Nama Customer, Username) --}}
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari invoice, nama, username..." 
                               value="{{ request('search') }}"
                               aria-label="Cari Pesanan berdasarkan Invoice, Nama, atau Username"
                               aria-describedby="search-addon"
                               autofocus>
                    </div>
                </div>

                {{-- Payment Method Filter --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="payment_method" class="form-select" aria-label="Filter metode pembayaran">
                        <option value="">Payment Method: Semua</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash (Tunai Toko)</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS (Digital QR)</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="status" class="form-select" aria-label="Filter status">
                        <option value="">Order Status: Semua</option>
                        <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Waiting Verification</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="sort" class="form-select" aria-label="Urutkan pesanan">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Order Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Order Terlama</option>
                        <option value="invoice_asc" {{ request('sort') === 'invoice_asc' ? 'selected' : '' }}>Invoice (A-Z)</option>
                        <option value="total_desc" {{ request('sort') === 'total_desc' ? 'selected' : '' }}>Grand Total Highest</option>
                        <option value="total_asc" {{ request('sort') === 'total_asc' ? 'selected' : '' }}>Grand Total Lowest</option>
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold" id="filterBtn">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="filterSpinner" role="status" aria-hidden="true"></span>
                        <span id="filterBtnText">Filter</span>
                    </button>
                    @if(request()->hasAny(['search', 'payment_method', 'status', 'sort']))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary" title="Reset Filter" aria-label="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Active Filter Info Banner --}}
    @if(request()->hasAny(['search', 'payment_method', 'status']) && $orders->isNotEmpty())
        <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between small" role="alert">
            <div>
                🔍 Menampilkan <strong>{{ $orders->total() }}</strong> transaksi pesanan hasil pencarian/filter.
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none fw-semibold">Reset Filter</a>
        </div>
    @endif

    {{-- Responsive Orders Table Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">

            @forelse($orders as $order)
                @if($loop->first)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 4%;">No</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 14%;">Invoice</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 16%;">Customer</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 10%;">Total Item</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-end" style="width: 13%;">Grand Total</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 12%;">Payment Method</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 14%;">Status</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 11%;">Tanggal</th>
                                    <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 6%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif

                <tr>
                    <td class="ps-4 fw-semibold text-secondary">{{ $orders->firstItem() + $loop->index }}</td>
                    <td>
                        <code class="text-primary bg-primary-subtle px-2 py-1 rounded small fw-bold">{{ $order->invoice_number }}</code>
                    </td>
                    <td>
                        <span class="fw-bold text-dark d-inline-block text-truncate" style="max-width: 150px;" title="{{ $order->user->name ?? '-' }}">
                            {{ $order->user->name ?? '-' }}
                        </span>
                        <small class="text-muted d-block text-truncate" style="max-width: 150px;" title="{{ $order->user->email ?? '-' }}">{{ $order->user->email ?? '-' }}</small>
                    </td>
                    <td class="text-center font-monospace fw-bold text-dark">
                        {{ $order->items->sum('qty') }} Pcs
                    </td>
                    <td class="fw-bold text-dark text-end font-monospace">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($order->payment_method === 'qris')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-normal">📱 QRIS</span>
                        @else
                            <span class="badge bg-light text-dark border border-dark-subtle px-2.5 py-1.5 fw-normal">💵 Cash</span>
                        @endif
                    </td>
                    <td>
                        @if($order->order_status === 'completed')
                            <span class="badge bg-dark px-2.5 py-1.5 fw-normal">🏁 Completed</span>
                        @elseif($order->order_status === 'ready_for_pickup')
                            <span class="badge bg-primary px-2.5 py-1.5 fw-normal">📦 Ready for Pickup</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-normal">Paid</span>
                        @elseif($order->payment_status === 'rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-normal">Rejected</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fw-normal">Waiting Verification</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="btn btn-sm btn-outline-info" 
                           title="Detail Pesanan & Verifikasi Pickup" 
                           aria-label="Detail {{ $order->invoice_number }}">
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
                {{-- Empty State Card --}}
                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="fs-1">🛒</span>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'payment_method', 'status']))
                        <h5 class="fw-bold text-dark mb-1">Pesanan tidak ditemukan.</h5>
                        <p class="text-muted mb-4">Tidak ada data transaksi yang sesuai dengan kata kunci pencarian atau filter Anda.</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                            Reset Filter
                        </a>
                    @else
                        <h5 class="fw-bold text-dark mb-1">Belum ada data pesanan.</h5>
                        <p class="text-muted mb-4">Belum ada catatan transaksi pesanan dari customer Tokobii.</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                            🔄 Refresh Data
                        </a>
                    @endif
                </div>
            @endforelse

        </div>

        {{-- Pagination Footer --}}
        @if($orders->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                </small>
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
                if (filterBtnText) filterBtnText.textContent = 'Memuat...';
            });
        }
    });
</script>
@endpush
