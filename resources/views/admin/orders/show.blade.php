@extends('layouts.admin.app')

@section('title', 'Detail Pesanan ' . $order->invoice_number . ' - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3 d-print-none">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Manajemen Pesanan</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $order->invoice_number }}</li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 shadow-sm d-print-none" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg class="text-emerald-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="small fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 bg-amber-50 text-amber-800 rounded-xl p-3.5 shadow-sm d-print-none" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg class="text-amber-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="small fw-semibold">{{ session('warning') }}</span>
            </div>
            <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 bg-rose-50 text-rose-800 rounded-xl p-3.5 shadow-sm d-print-none" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg class="text-rose-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="small fw-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Page Header & Actions --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold text-slate-900 mb-0" style="color: #0f172a;">Pesanan:</h1>
                <span class="text-blue-600 font-monospace fs-4 fw-bold">{{ $order->invoice_number }}</span>
            </div>
            <p class="text-slate-500 mb-0 small">Verifikasi pembayaran, kelola rincian item produk, dan status penyerahan pesanan.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            {{-- Workflow Action Buttons --}}
            @if($order->payment_method === 'cash' && $order->status === 'pending')
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="start_processing">
                    <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        <span>Mulai Proses Pesanan</span>
                    </button>
                </form>
            @elseif($order->status === 'waiting_verification')
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#approvePaymentModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Verifikasi Pembayaran</span>
                </button>
                <button type="button" class="btn btn-tokobii-danger btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Tolak Pembayaran</span>
                </button>
            @elseif($order->status === 'paid' || $order->status === 'processing')
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#readyForPickupModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Set Siap Diambil</span>
                </button>
            @elseif($order->status === 'ready_for_pickup')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Struk</span>
                </a>
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#completeOrderModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Selesaikan Pesanan</span>
                </button>
            @elseif($order->status === 'completed')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Struk</span>
                </a>
            @endif

            <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column: Cards --}}
        <div class="col-12 col-md-5">
            
            {{-- CARD 1: Order Information --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">1. Informasi Pesanan</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 40%;">No. Invoice</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $order->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Tanggal Pesanan</th>
                                    <td class="text-slate-800">: {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }} WIB</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Status Pesanan</th>
                                    <td>: 
                                        <span class="tokobii-badge {{ $order->status_badge_class }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Metode Bayar</th>
                                    <td>: 
                                        @if($order->payment_method === 'qris')
                                            <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-neutral">Tunai Kasir</span>
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
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">2. Data Pelanggan</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Nama</th>
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

            {{-- CARD 3: Payment Verification & Proof --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">3. Verifikasi Pembayaran</h5>
                    @if($order->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Tunai Kasir</span>
                    @endif
                </div>
                <div class="p-4">
                    @php
                        $proofPath = $order->payment?->proof_of_payment ?? $order->payment_proof;
                        $isPdf = $proofPath && str_ends_with(strtolower($proofPath), '.pdf');
                    @endphp

                    @if($proofPath)
                        <div class="mb-3 text-center">
                            @if($isPdf)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-rose-500 mb-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="fw-bold text-slate-800 small mb-2">Dokumen Bukti Transfer (PDF)</span>
                                    <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-tokobii-primary btn-tokobii-sm">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        <span>Buka Dokumen PDF</span>
                                    </a>
                                </div>
                            @else
                                <div class="position-relative d-inline-block w-100 text-center">
                                    <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" title="Klik untuk memperbesar bukti transfer">
                                        <img src="{{ asset('storage/' . $proofPath) }}" 
                                             alt="Bukti Transfer {{ $order->invoice_number }}" 
                                             class="img-fluid rounded-3 border border-slate-200 shadow-sm" 
                                             style="max-height: 260px; object-fit: contain; width: auto; background-color: #f8fafc;">
                                    </a>
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-tokobii-secondary btn-tokobii-sm d-inline-flex align-items-center gap-1.5 py-1 px-2.5">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                            <span style="font-size: 0.75rem;">Lihat Ukuran Penuh</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive pt-2 border-top border-slate-100">
                            <table class="table table-borderless align-middle mb-0 small">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold" style="width: 42%;">Waktu Unggah</th>
                                        <td class="text-slate-800">: {{ $order->payment?->payment_date ? $order->payment->payment_date->format('d M Y, H:i') : ($order->payment?->updated_at ? $order->payment->updated_at->format('d M Y, H:i') : '-') }} WIB</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Nominal Bukti</th>
                                        <td class="text-slate-900 fw-bold font-monospace">: Rp {{ number_format($order->payment?->amount ?? $order->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($order->payment?->verifiedByAdmin)
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold">Diverifikasi Oleh</th>
                                            <td class="text-slate-800">: {{ $order->payment->verifiedByAdmin->name }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if($order->payment?->reject_reason)
                            <div class="mt-3 p-3 bg-rose-50 text-rose-800 rounded-3 border border-rose-200 small">
                                <strong>Alasan Penolakan:</strong> {{ $order->payment->reject_reason }}
                            </div>
                        @endif

                    @else
                        <div class="p-3 bg-slate-50 text-slate-500 rounded-3 border border-slate-200 text-center small mb-2">
                            @if($order->payment_method === 'qris')
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400 d-block mx-auto mb-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada bukti transfer QRIS yang diunggah oleh pelanggan.
                            @else
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400 d-block mx-auto mb-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Pembayaran tunai kasir dilakukan langsung saat pengambilan pesanan di toko.
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column: Items & Summary --}}
        <div class="col-12 col-md-7">
            
            {{-- Order Items Table --}}
            <div class="tokobii-table-container mb-4">
                <div class="p-3 border-bottom border-slate-100 bg-white">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Daftar Produk Pesanan</h5>
                </div>
                <div class="table-responsive">
                    <table class="tokobii-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 14%;">Foto</th>
                                <th>Nama Produk</th>
                                <th class="text-center" style="width: 12%;">Qty</th>
                                <th class="text-end" style="width: 20%;">Harga</th>
                                <th class="text-end" style="width: 22%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="fw-semibold text-slate-400 font-monospace">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($item->product && $item->product->thumbnail)
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="rounded-3 border border-slate-200" 
                                                 style="width: 44px; height: 44px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-slate-100 d-flex align-items-center justify-content-center text-slate-400 border border-slate-200" style="width: 44px; height: 44px;">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
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
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-end text-slate-700 font-monospace">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-blue-600 font-monospace">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-slate-400 small">
                                        Tidak ada item produk pada pesanan ini.
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
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Ringkasan Pembayaran</h5>
                </div>
                <div class="p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0 small">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Subtotal Item</th>
                                            <td class="text-end font-monospace text-slate-800 fw-semibold">: Rp {{ number_format($order->total_amount ?? $order->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Biaya Pengambilan Toko</th>
                                            <td class="text-end font-monospace text-emerald-600 fw-semibold">: Rp 0</td>
                                        </tr>
                                        <tr class="border-top border-slate-200">
                                            <th class="ps-0 text-slate-900 fw-bold pt-3 fs-6">Total Tagihan</th>
                                            <td class="text-end font-monospace text-blue-600 fw-bold pt-3 fs-5" style="color: #2563eb;">: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
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
@if($order->status === 'waiting_verification')
    <div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="approvePaymentModalLabel">Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="approveOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="approve_payment">

                    <div class="modal-body p-4 text-center">
                        <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 52px; height: 52px; background-color: #d1fae5; color: #059669;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-slate-900 mb-2">Verifikasi Pembayaran Pesanan?</h5>
                        <p class="text-slate-500 mb-0 small">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> (Rp {{ number_format($order->grand_total, 0, ',', '.') }}) akan ditandai sebagai <span class="tokobii-badge tokobii-badge-success">Lunas</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="approveSubmitBtn">
                            <span>Ya, Verifikasi Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="rejectPaymentModalLabel">Tolak Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="rejectOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject_payment">

                    <div class="modal-body p-4">
                        <h5 class="fw-bold text-slate-900 text-center mb-3">Alasan Penolakan Pembayaran</h5>
                        
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">Alasan Penolakan <span class="text-rose-600">*</span></label>
                            <textarea name="reject_reason" 
                                      id="reject_reason" 
                                      rows="3" 
                                      class="form-control tokobii-input @error('reject_reason') is-invalid @enderror" 
                                      placeholder="Contoh: Bukti transfer buram, nominal tidak sesuai..." 
                                      required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-danger btn-tokobii-sm" id="rejectSubmitBtn">
                            <span>Tolak Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($order->status === 'paid' || $order->status === 'processing')
    <div class="modal fade" id="readyForPickupModal" tabindex="-1" aria-labelledby="readyForPickupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="readyForPickupModalLabel">Set Siap Diambil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="readyOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="ready_for_pickup">

                    <div class="modal-body p-4 text-center">
                        <div class="rounded-circle bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 52px; height: 52px; background-color: #eff6ff; color: #2563eb;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-slate-900 mb-2">Tandai Siap Diambil?</h5>
                        <p class="text-slate-500 mb-0 small">
                            Pesanan invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> akan ditandai siap diambil di kasir toko.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="readySubmitBtn">
                            <span>Ya, Siap Diambil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($order->status === 'ready_for_pickup')
    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-labelledby="completeOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="completeOrderModalLabel">Selesaikan Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="completeOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="complete">

                    <div class="modal-body p-4 text-center">
                        <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 52px; height: 52px; background-color: #d1fae5; color: #059669;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-slate-900 mb-2">Selesaikan Pesanan Ini?</h5>
                        <p class="text-slate-500 mb-0 small">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> akan diselesaikan dan barang telah diserahkan ke pelanggan.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="completeSubmitBtn">
                            <span>Ya, Selesaikan Pesanan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection
