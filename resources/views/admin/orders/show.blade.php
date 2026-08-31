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
            @if($order->payment_method === 'cash' && in_array($order->order_status, ['pending', 'processing']) && $order->order_status === 'pending')
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
            @elseif($order->order_status === 'processing')
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#readyForPickupModal">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Set Siap Diambil</span>
                </button>
            @elseif($order->order_status === 'ready_for_pickup')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Struk</span>
                </a>
                
                @if($order->payment_method === 'cash' && $order->payment_status !== 'paid')
                    <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#confirmCashPaymentModal">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Konfirmasi Pembayaran</span>
                    </button>
                @elseif($order->payment_status === 'paid')
                    <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#completeOrderModal">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Selesaikan Pesanan</span>
                    </button>
                @endif
            @elseif($order->order_status === 'completed')
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
                                    <th class="ps-0 text-slate-500 fw-semibold">Status Pembayaran</th>
                                    <td>: 
                                        @if($order->payment_status === 'paid')
                                            <span class="tokobii-badge tokobii-badge-success">Lunas</span>
                                        @elseif($order->payment_status === 'waiting_verification')
                                            <span class="tokobii-badge tokobii-badge-warning">Menunggu Verifikasi</span>
                                        @elseif($order->payment_status === 'rejected')
                                            <span class="tokobii-badge tokobii-badge-danger">Ditolak</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Menunggu Pembayaran</span>
                                        @endif
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
                    @if($order->payment_method === 'cash')
                        @if($order->payment_status === 'paid')
                            <div class="p-3 bg-emerald-50 text-emerald-900 rounded-3 border border-emerald-200 mb-3 small">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-emerald-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong class="text-emerald-950">Pembayaran Tunai Lunas</strong>
                                </div>
                                <span>Uang tunai telah diterima dan dikonfirmasi kasir toko.</span>
                            </div>

                            <div class="table-responsive pt-1">
                                <table class="table table-borderless align-middle mb-0 small">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold" style="width: 44%;">Total Tagihan</th>
                                            <td class="text-slate-900 font-monospace fw-bold">: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold">Uang Diterima</th>
                                            <td class="text-emerald-700 font-monospace fw-bold">: Rp {{ number_format($order->payment?->received_amount ?? $order->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold">Kembalian</th>
                                            <td class="text-slate-900 font-monospace fw-bold">: Rp {{ number_format($order->payment?->change_amount ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold">Waktu Pembayaran</th>
                                            <td class="text-slate-800">: {{ $order->payment?->payment_date ? $order->payment->payment_date->format('d M Y, H:i') : ($order->payment?->verified_at ? $order->payment->verified_at->format('d M Y, H:i') : '-') }} WIB</td>
                                        </tr>
                                        @if($order->payment?->verifiedByAdmin)
                                            <tr>
                                                <th class="ps-0 text-slate-500 fw-semibold">Dikonfirmasi Oleh</th>
                                                <td class="text-slate-800">: {{ $order->payment->verifiedByAdmin->name }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3 bg-slate-50 text-slate-500 rounded-3 border border-slate-200 text-center small mb-3">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400 d-block mx-auto mb-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Pembayaran tunai kasir dilakukan langsung saat serah terima barang di toko.
                            </div>

                            <div class="table-responsive pt-1">
                                <table class="table table-borderless align-middle mb-0 small">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold" style="width: 44%;">Total Tagihan</th>
                                            <td class="text-blue-600 font-monospace fw-bold">: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-semibold">Status Bayar</th>
                                            <td>: <span class="tokobii-badge tokobii-badge-warning">Menunggu Pembayaran</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        {{-- QRIS Flow --}}
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
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400 d-block mx-auto mb-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada bukti transfer QRIS yang diunggah oleh pelanggan.
                            </div>
                        @endif
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

{{-- Modal 1: Konfirmasi Pembayaran Tunai (Cash) --}}
@if($order->payment_method === 'cash' && $order->order_status === 'ready_for_pickup' && $order->payment_status !== 'paid')
    <div class="modal fade" id="confirmCashPaymentModal" tabindex="-1" aria-labelledby="confirmCashPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-emerald-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h5 class="modal-title fw-bold text-white fs-6" id="confirmCashPaymentModalLabel">Konfirmasi Pembayaran Tunai</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="confirmCashPaymentForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="confirm_cash_payment">

                    <div class="modal-body p-4">
                        {{-- Total Tagihan Display --}}
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 mb-3 text-center">
                            <span class="text-slate-500 small d-block mb-1">Total Tagihan</span>
                            <h3 class="fw-bold text-blue-600 font-monospace mb-0" style="color: #2563eb;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </h3>
                        </div>

                        {{-- Uang Diterima Input --}}
                        <div class="mb-3">
                            <label for="cash_received_display" class="form-label small fw-bold text-slate-800">
                                Uang Diterima <span class="text-rose-600">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-slate-100 border-slate-300 fw-bold text-slate-700">Rp</span>
                                <input type="text" 
                                       id="cash_received_display" 
                                       class="form-control tokobii-input font-monospace fw-bold fs-5" 
                                       placeholder="0" 
                                       autocomplete="off" 
                                       required>
                            </div>
                            <input type="hidden" name="received_amount" id="cash_received_value" value="">
                            <div id="cash_input_feedback" class="text-danger small mt-1.5 fw-semibold" style="display: none;"></div>
                        </div>

                        {{-- Quick Cash Nominal Helpers --}}
                        <div class="mb-3">
                            <span class="text-slate-400 d-block mb-1.5" style="font-size: 0.75rem;">Pilihan Cepat Nominal:</span>
                            <div class="d-flex flex-wrap gap-1.5">
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-chip py-1 px-2.5" data-val="{{ (int)$order->grand_total }}" style="font-size: 0.8125rem;">
                                    Uang Pas (Rp {{ number_format($order->grand_total, 0, ',', '.') }})
                                </button>
                                @php
                                    $billVal = (int) $order->grand_total;
                                    $quickOptions = [];
                                    // Next 10.000 round up
                                    $nextRound10k = (int) (ceil($billVal / 10000) * 10000);
                                    if ($nextRound10k > $billVal && !in_array($nextRound10k, [20000, 50000, 100000])) {
                                        $quickOptions[] = $nextRound10k;
                                    }
                                    // Next 50.000 round up
                                    $nextRound50k = (int) (ceil($billVal / 50000) * 50000);
                                    if ($nextRound50k > $billVal && !in_array($nextRound50k, [50000, 100000]) && !in_array($nextRound50k, $quickOptions)) {
                                        $quickOptions[] = $nextRound50k;
                                    }
                                    foreach ([20000, 50000, 100000] as $preset) {
                                        if ($preset > $billVal && !in_array($preset, $quickOptions)) {
                                            $quickOptions[] = $preset;
                                        }
                                    }
                                    sort($quickOptions);
                                @endphp
                                @foreach($quickOptions as $opt)
                                    <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-chip py-1 px-2.5" data-val="{{ $opt }}" style="font-size: 0.8125rem;">
                                        Rp {{ number_format($opt, 0, ',', '.') }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Kembalian Display Box --}}
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 text-center mb-1" id="change_box">
                            <span class="text-slate-500 small d-block mb-1 fw-semibold">Kembalian</span>
                            <h4 class="fw-bold text-slate-800 font-monospace mb-0" id="cash_change_display">
                                Rp 0
                            </h4>
                        </div>
                    </div>

                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="confirmCashSubmitBtn" disabled>
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Konfirmasi Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- Modal 2: Verifikasi Pembayaran QRIS --}}
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

{{-- Modal 3: Set Siap Diambil --}}
@if($order->order_status === 'processing')
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

{{-- Modal 4: Selesaikan Pesanan (Hanya saat status ready_for_pickup & payment sudah paid) --}}
@if($order->order_status === 'ready_for_pickup' && $order->payment_status === 'paid')
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalBill = {{ (int) $order->grand_total }};
    const inputDisplay = document.getElementById('cash_received_display');
    const inputValue = document.getElementById('cash_received_value');
    const feedback = document.getElementById('cash_input_feedback');
    const changeDisplay = document.getElementById('cash_change_display');
    const changeBox = document.getElementById('change_box');
    const submitBtn = document.getElementById('confirmCashSubmitBtn');
    const cashForm = document.getElementById('confirmCashPaymentForm');
    const quickChips = document.querySelectorAll('.quick-cash-chip');

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function calculateCash() {
        if (!inputDisplay) return;

        let rawStr = inputDisplay.value.replace(/\D/g, '');
        
        if (rawStr === '') {
            if (inputValue) inputValue.value = '';
            if (feedback) {
                feedback.innerText = 'Jumlah uang yang diterima wajib diisi.';
                feedback.style.display = 'block';
            }
            if (changeDisplay) {
                changeDisplay.innerText = 'Rp 0';
                changeDisplay.className = 'fw-bold text-slate-400 font-monospace mb-0';
            }
            if (changeBox) {
                changeBox.style.backgroundColor = '#f8fafc';
                changeBox.style.borderColor = '#e2e8f0';
            }
            if (submitBtn) {
                submitBtn.disabled = true;
            }
            return;
        }

        let numVal = parseInt(rawStr, 10);
        if (inputValue) inputValue.value = numVal;
        inputDisplay.value = formatNumber(numVal);

        if (numVal < totalBill) {
            if (feedback) {
                feedback.innerText = 'Uang yang diterima kurang dari total tagihan.';
                feedback.style.display = 'block';
            }
            if (changeDisplay) {
                changeDisplay.innerText = 'Rp 0';
                changeDisplay.className = 'fw-bold text-rose-600 font-monospace mb-0';
            }
            if (changeBox) {
                changeBox.style.backgroundColor = '#fff1f2';
                changeBox.style.borderColor = '#fecdd3';
            }
            if (submitBtn) {
                submitBtn.disabled = true;
            }
        } else {
            let change = numVal - totalBill;
            if (feedback) {
                feedback.innerText = '';
                feedback.style.display = 'none';
            }
            if (changeDisplay) {
                changeDisplay.innerText = 'Rp ' + formatNumber(change);
                changeDisplay.className = 'fw-bold text-emerald-700 font-monospace mb-0';
            }
            if (changeBox) {
                changeBox.style.backgroundColor = '#ecfdf5';
                changeBox.style.borderColor = '#a7f3d0';
            }
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        }
    }

    if (inputDisplay) {
        inputDisplay.addEventListener('input', calculateCash);
        
        const cashModal = document.getElementById('confirmCashPaymentModal');
        if (cashModal) {
            cashModal.addEventListener('shown.bs.modal', function () {
                inputDisplay.focus();
                calculateCash();
            });
        }
    }

    quickChips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            let val = this.getAttribute('data-val');
            if (inputDisplay) {
                inputDisplay.value = val;
                calculateCash();
            }
        });
    });

    if (cashForm) {
        cashForm.addEventListener('submit', function (e) {
            let numVal = parseInt(inputValue ? inputValue.value : '0', 10);
            if (isNaN(numVal) || numVal < totalBill) {
                e.preventDefault();
                calculateCash();
                return false;
            }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Memproses...</span>';
            }
        });
    }
});
</script>
@endpush
