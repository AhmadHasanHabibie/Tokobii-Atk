@extends('layouts.admin.app')

@section('title', 'Manajemen Pesanan - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Manajemen Pesanan</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Manajemen Pesanan</h1>
            <p class="text-slate-500 mb-0 small">Kelola seluruh pesanan masuk dari pelanggan, verifikasi status pembayaran, dan serah terima produk.</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Segarkan">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Segarkan</span>
            </a>
        </div>
    </div>

    {{-- Order Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Total Pesanan</span>
                <h4 class="fw-bold text-slate-900 mb-0 mt-1 font-monospace" style="font-size: 1.25rem;">{{ number_format($totalOrders) }}</h4>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Menunggu Bayar</span>
                <h4 class="fw-bold text-amber-600 mb-0 mt-1 font-monospace" style="font-size: 1.25rem; color: #d97706;">{{ number_format($waitingVerificationOrders) }}</h4>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Lunas Terbayar</span>
                <h4 class="fw-bold text-emerald-600 mb-0 mt-1 font-monospace" style="font-size: 1.25rem; color: #16a34a;">{{ number_format($paidOrders) }}</h4>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Siap Diambil</span>
                <h4 class="fw-bold text-blue-600 mb-0 mt-1 font-monospace" style="font-size: 1.25rem;">{{ number_format($readyForPickupOrders) }}</h4>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Pesanan Selesai</span>
                <h4 class="fw-bold text-slate-800 mb-0 mt-1 font-monospace" style="font-size: 1.25rem;">{{ number_format($completedOrders) }}</h4>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Dibatalkan</span>
                <h4 class="fw-bold text-rose-600 mb-0 mt-1 font-monospace" style="font-size: 1.25rem; color: #e11d48;">{{ number_format($rejectedOrders) }}</h4>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="tokobii-card p-3 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            
            {{-- Search Input --}}
            <div class="col-12 col-md-3">
                <input type="text" name="search" class="tokobii-input w-100" placeholder="Cari nomor invoice, pelanggan..." value="{{ request('search') }}">
            </div>

            {{-- Payment Method Filter --}}
            <div class="col-12 col-sm-6 col-md-3">
                <select name="payment_method" class="tokobii-select w-100" onchange="this.form.submit()">
                    <option value="">Semua Metode Pembayaran</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai di Kasir</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS Tokobii</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="status" class="tokobii-select w-100" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="sort" class="tokobii-select w-100" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="total_desc" {{ request('sort') === 'total_desc' ? 'selected' : '' }}>Total Tertinggi</option>
                    <option value="total_asc" {{ request('sort') === 'total_asc' ? 'selected' : '' }}>Total Terendah</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-tokobii-primary w-100">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Cari</span>
                </button>
                @if(request()->hasAny(['search', 'payment_method', 'status', 'sort']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Reset Filter">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
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
                                <th style="width: 15%;">No. Invoice</th>
                                <th style="width: 20%;">Pelanggan</th>
                                <th class="text-center" style="width: 10%;">Item</th>
                                <th class="text-end" style="width: 14%;">Total Tagihan</th>
                                <th class="text-center" style="width: 12%;">Metode</th>
                                <th style="width: 13%;">Status</th>
                                <th style="width: 12%;">Tanggal</th>
                                <th class="text-end" style="width: 6%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            @endif

            <tr>
                <td class="fw-semibold text-slate-400 font-monospace">{{ $orders->firstItem() + $loop->index }}</td>
                <td>
                    <span class="text-blue-600 font-monospace fw-bold" style="font-size: 0.8125rem;">{{ $order->invoice_number }}</span>
                </td>
                <td>
                    <span class="fw-semibold text-slate-900 d-block text-truncate" style="max-width: 160px;" title="{{ $order->user->name ?? '-' }}">
                        {{ $order->user->name ?? '-' }}
                    </span>
                    <span class="text-slate-400 d-block text-truncate font-monospace" style="font-size: 0.75rem; max-width: 160px;">
                        {{ $order->user->email ?? '-' }}
                    </span>
                </td>
                <td class="text-center fw-semibold text-slate-700 font-monospace">
                    {{ $order->items->sum('quantity') }} Pcs
                </td>
                <td class="fw-bold text-slate-900 text-end font-monospace">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    @if($order->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Tunai Kasir</span>
                    @endif
                </td>
                <td>
                    <span class="tokobii-badge {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td class="text-slate-500 small">
                    {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }}
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Detail</span>
                    </a>
                </td>
            </tr>

            @if($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
            <div class="tokobii-empty-state">
                <div class="tokobii-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h5 class="fw-bold text-slate-800 mb-1">Tidak Ada Data Pesanan</h5>
                <p class="text-slate-400 small mb-0">Belum ada pesanan yang sesuai dengan filter pencarian Anda.</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="p-3 border-top border-slate-100 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection