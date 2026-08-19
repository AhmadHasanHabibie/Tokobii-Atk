@extends('layouts.admin.app')

@section('title', 'Manajemen Pembayaran - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Manajemen Pembayaran</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Manajemen Pembayaran</h1>
                <p class="text-slate-500 mb-0 small">Pantau status transaksi pembayaran pelanggan, verifikasi bukti transfer QRIS, dan pencatatan kasir.</p>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Segarkan Data">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Segarkan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Cards Grid --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Total Transaksi</span>
                    <h4 class="fw-bold text-slate-900 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">{{ number_format($totalPayments) }}</h4>
                </div>
                <div class="rounded-circle bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #eff6ff; color: #2563eb;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Menunggu Bayar</span>
                    <h4 class="fw-bold text-amber-600 mb-0 mt-1 font-monospace" style="font-size: 1.15rem; color: #d97706;">{{ number_format($pendingPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #fef3c7; color: #d97706;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Perlu Verifikasi</span>
                    <h4 class="fw-bold text-blue-600 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">{{ number_format($waitingVerificationPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #eff6ff; color: #2563eb;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Lunas Terbayar</span>
                    <h4 class="fw-bold text-emerald-600 mb-0 mt-1 font-monospace" style="font-size: 1.15rem; color: #16a34a;">{{ number_format($paidPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #f0fdf4; color: #16a34a;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Siap Diambil</span>
                    <h4 class="fw-bold text-blue-600 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">{{ number_format($readyForPickupPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #eff6ff; color: #2563eb;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Selesai</span>
                    <h4 class="fw-bold text-slate-800 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">{{ number_format($completedPayments) }}</h4>
                </div>
                <div class="rounded-circle bg-slate-100 text-slate-700 d-flex align-items-center justify-content-center border border-slate-200" style="width: 38px; height: 38px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-xl-1-7">
            <div class="tokobii-card h-100 p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Ditolak / Batal</span>
                    <h4 class="fw-bold text-rose-600 mb-0 mt-1 font-monospace" style="font-size: 1.15rem; color: #e11d48;">{{ number_format($rejectedPayments) }}</h4>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #ffe4e6; color: #e11d48;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
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
                       placeholder="Cari nomor invoice, nama pelanggan..." 
                       value="{{ request('search') }}"
                       aria-label="Cari Pembayaran">
            </div>

            {{-- Payment Method Filter --}}
            <div class="col-12 col-sm-6 col-md-3">
                <select name="payment_method" class="tokobii-select w-100" aria-label="Filter Metode Pembayaran" onchange="this.form.submit()">
                    <option value="">Semua Metode Pembayaran</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai di Kasir</option>
                    <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS Tokobii</option>
                </select>
            </div>

            {{-- Payment Status Filter --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="payment_status" class="tokobii-select w-100" aria-label="Filter Status Pembayaran" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="waiting_verification" {{ request('payment_status') === 'waiting_verification' ? 'selected' : '' }}>Verifikasi Bukti</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas Terbayar</option>
                    <option value="ready_for_pickup" {{ request('payment_status') === 'ready_for_pickup' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="completed" {{ request('payment_status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('payment_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="sort" class="tokobii-select w-100" aria-label="Urutan Data" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="amount_desc" {{ request('sort') === 'amount_desc' ? 'selected' : '' }}>Nominal Tertinggi</option>
                    <option value="amount_asc" {{ request('sort') === 'amount_asc' ? 'selected' : '' }}>Nominal Terendah</option>
                </select>
            </div>

            {{-- Filter Action Buttons --}}
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-tokobii-primary w-100">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Cari</span>
                </button>
                @if(request()->hasAny(['search', 'payment_method', 'payment_status', 'sort']))
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Reset Filter">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Payment Table Card --}}
    <div class="tokobii-table-container">
        @forelse($payments as $payment)
            @if($loop->first)
                <div class="table-responsive">
                    <table class="tokobii-table">
                        <thead>
                            <tr>
                                <th style="width: 4%;">No</th>
                                <th style="width: 14%;">No. Invoice</th>
                                <th style="width: 20%;">Pelanggan</th>
                                <th class="text-center" style="width: 12%;">Metode</th>
                                <th class="text-end" style="width: 14%;">Nominal</th>
                                <th style="width: 13%;">Status Bayar</th>
                                <th style="width: 13%;">Status Ambil</th>
                                <th style="width: 10%;">Tanggal</th>
                                <th class="text-end" style="width: 6%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            @endif

            <tr>
                <td class="fw-semibold text-slate-400 font-monospace">{{ $payments->firstItem() + $loop->index }}</td>
                <td>
                    <span class="text-blue-600 font-monospace fw-bold">{{ $payment->invoice_number }}</span>
                </td>
                <td>
                    <span class="fw-bold text-slate-900 d-block text-truncate" style="max-width: 180px;" title="{{ $payment->order->user->name ?? '-' }}">
                        {{ $payment->order->user->name ?? '-' }}
                    </span>
                    <span class="text-slate-400 font-monospace d-block text-truncate" style="max-width: 180px; font-size: 0.75rem;">{{ $payment->order->user->email ?? '-' }}</span>
                </td>
                <td class="text-center">
                    @if($payment->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Tunai Kasir</span>
                    @endif
                </td>
                <td class="fw-bold text-slate-900 text-end font-monospace">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </td>
                <td>
                    @if($payment->payment_status === 'paid' || $payment->payment_status === 'ready_for_pickup' || $payment->payment_status === 'completed')
                        <span class="tokobii-badge tokobii-badge-success">Lunas</span>
                    @elseif($payment->payment_status === 'waiting_verification')
                        <span class="tokobii-badge tokobii-badge-warning">Perlu Verifikasi</span>
                    @elseif($payment->payment_status === 'rejected')
                        <span class="tokobii-badge tokobii-badge-danger">Ditolak</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-warning">Menunggu</span>
                    @endif
                </td>
                <td>
                    @if($payment->payment_status === 'ready_for_pickup')
                        <span class="tokobii-badge tokobii-badge-info">Siap Diambil</span>
                    @elseif($payment->payment_status === 'completed')
                        <span class="tokobii-badge tokobii-badge-success">Selesai</span>
                    @elseif(in_array($payment->payment_status, ['paid', 'waiting_verification']))
                        <span class="tokobii-badge tokobii-badge-info">Disiapkan</span>
                    @elseif($payment->payment_status === 'rejected')
                        <span class="tokobii-badge tokobii-badge-neutral">Dibatalkan</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Belum Diproses</span>
                    @endif
                </td>
                <td class="text-slate-500 small">
                    {{ $payment->payment_date ? $payment->payment_date->format('d M Y, H:i') : '-' }}
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-tokobii-primary btn-tokobii-sm">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h5 class="fw-bold text-slate-800 mb-1">Tidak Ada Data Pembayaran</h5>
                <p class="text-slate-400 small mb-0">Belum ada transaksi pembayaran yang sesuai dengan filter pencarian.</p>
            </div>
        @endforelse

        {{-- Pagination Footer --}}
        @if($payments->hasPages())
            <div class="px-4 py-3 border-top border-slate-100 d-flex justify-content-center">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
