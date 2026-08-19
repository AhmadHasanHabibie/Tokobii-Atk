@extends('layouts.customer.app')

@section('title', 'Riwayat Pesanan - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Riwayat Pesanan</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Riwayat Pesanan Saya</h1>
                <p class="text-slate-500 mb-0 small">Lacak status transaksi, pembayaran, dan pengambilan pesanan Anda di Tokobii.</p>
            </div>
            <div>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Segarkan Data">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Segarkan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Dedicated Filter Card --}}
    <div class="tokobii-filter-card">
        <form action="{{ route('customer.orders.index') }}" method="GET" class="row g-2 align-items-center">
            
            {{-- Search Input --}}
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-slate-50 border-slate-300 text-slate-400 ps-3">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control tokobii-input border-start-0 ps-1" 
                           placeholder="Cari berdasarkan nomor invoice atau nama produk..." 
                           value="{{ request('search') }}"
                           aria-label="Cari Pesanan">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-12 col-sm-6 col-md-4">
                <select name="status" class="form-select tokobii-select" aria-label="Filter Status" onchange="this.form.submit()">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status Pesanan</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Menunggu Verifikasi Kasir</option>
                    <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Dibatalkan / Ditolak</option>
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="col-12 col-sm-6 col-md-2">
                <button type="submit" class="btn btn-tokobii-primary w-100">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Cari</span>
                </button>
            </div>

        </form>
    </div>

    {{-- Order List --}}
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($orders as $order)
            <div class="tokobii-card p-4 tokobii-card-interactive" onclick="window.location='{{ route('customer.orders.show', $order) }}';">
                {{-- Order Header --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 pb-3 mb-3 border-bottom border-slate-100">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold text-blue-600 font-monospace" style="font-size: 0.9375rem;">
                            {{ $order->invoice_number }}
                        </span>
                        <span class="text-slate-400 small">·</span>
                        <span class="text-slate-500 small">
                            {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }} WIB
                        </span>
                        <span class="text-slate-400 small">·</span>
                        @if($order->payment_method === 'qris')
                            <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-neutral">Tunai di Kasir</span>
                        @endif
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @if($order->status === 'pending')
                            <span class="tokobii-badge tokobii-badge-warning">Menunggu Pembayaran</span>
                        @elseif($order->status === 'paid')
                            <span class="tokobii-badge tokobii-badge-info">Menunggu Verifikasi Kasir</span>
                        @elseif($order->status === 'ready_for_pickup')
                            <span class="tokobii-badge tokobii-badge-success">Siap Diambil di Toko</span>
                        @elseif($order->status === 'completed')
                            <span class="tokobii-badge tokobii-badge-success">Pesanan Selesai</span>
                        @elseif($order->status === 'rejected')
                            <span class="tokobii-badge tokobii-badge-danger">Dibatalkan</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-neutral">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Order Items Preview --}}
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-7">
                        <div class="d-flex flex-column gap-1">
                            @foreach($order->items->take(2) as $item)
                                <div class="d-flex align-items-center gap-2 small">
                                    <span class="text-slate-900 fw-semibold">{{ $item->product_name }}</span>
                                    <span class="text-slate-400">× {{ $item->qty }}</span>
                                    <span class="text-slate-600 font-monospace">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span class="text-slate-400 small">+ {{ $order->items->count() - 2 }} produk lainnya</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-5 d-flex flex-column flex-sm-row align-items-sm-center justify-content-md-end gap-3" onclick="event.stopPropagation();">
                        <div class="text-start text-sm-end">
                            <span class="text-slate-400 small d-block">Total Tagihan</span>
                            <span class="h5 fw-bold text-blue-600 font-monospace mb-0" style="color: #2563eb;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            @if($order->status === 'pending' && $order->payment_method === 'qris')
                                <a href="{{ route('customer.orders.pay', $order) }}" class="btn btn-tokobii-warning btn-tokobii-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    <span>Bayar QRIS</span>
                                </a>
                            @endif

                            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Detail</span>
                            </a>

                            @if(in_array($order->status, ['ready_for_pickup', 'completed']))
                                <a href="{{ route('customer.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Cetak Struk">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="tokobii-empty-state">
                <div class="tokobii-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h5 class="fw-bold text-slate-900 mb-1">Belum Ada Riwayat Pesanan</h5>
                <p class="text-slate-500 small mb-4">Anda belum pernah melakukan pemesanan produk di Tokobii.</p>
                <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span>Mulai Belanja Sekarang</span>
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination Card --}}
    @if($orders->hasPages())
        <div class="tokobii-card p-3 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection
