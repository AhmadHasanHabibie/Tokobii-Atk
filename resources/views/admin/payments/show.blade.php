@extends('layouts.admin.app')

@section('title', 'Detail Pembayaran ' . $payment->invoice_number . ' - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3 d-print-none">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.payments.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Manajemen Pembayaran</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $payment->invoice_number }}</li>
        </ol>
    </nav>

    {{-- Page Header & Actions --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="h3 fw-bold text-slate-900 mb-0" style="color: #0f172a;">Pembayaran:</h1>
                <span class="text-blue-600 font-monospace fs-4 fw-bold">{{ $payment->invoice_number }}</span>
            </div>
            <p class="text-slate-500 mb-0 small">Verifikasi bukti transfer, cetak struk pengambilan, dan pembaruan status transaksi.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            
            {{-- Action Buttons --}}
            @if($payment->payment_status === 'waiting_verification')
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#approvePaymentModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Verifikasi Pembayaran</span>
                </button>
                <button type="button" class="btn btn-tokobii-danger btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Tolak Pembayaran</span>
                </button>
            @elseif($payment->payment_status === 'paid')
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#readyForPickupModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Set Siap Diambil</span>
                </button>
            @elseif($payment->payment_status === 'ready_for_pickup')
                <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#printReceiptModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Struk</span>
                </button>
                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#completePaymentModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Selesaikan Pesanan</span>
                </button>
            @endif

            <a href="{{ route('admin.payments.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-12 col-md-5">
            
            {{-- Section 1: Payment Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">1. Informasi Pembayaran</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 42%;">No. Invoice</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $payment->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Metode Pembayaran</th>
                                    <td>: 
                                        @if($payment->payment_method === 'qris')
                                            <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-neutral">Tunai Kasir</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Status Pembayaran</th>
                                    <td>: 
                                        <span class="tokobii-badge {{ $payment->status_badge_class }}">
                                            {{ $payment->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Waktu Pembayaran</th>
                                    <td class="text-slate-800">: {{ $payment->payment_date ? $payment->payment_date->format('d M Y, H:i') : '-' }} WIB</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Diverifikasi Oleh</th>
                                    <td class="text-slate-800">: {{ $payment->verifiedByAdmin->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Waktu Verifikasi</th>
                                    <td class="text-slate-400">: {{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '-' }} WIB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section 2: Customer Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">2. Data Pelanggan</h5>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-blue-100 text-blue-600 fw-bold d-inline-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; font-size: 1.1rem; background-color: #eff6ff; color: #2563eb;">
                            {{ strtoupper(substr($payment->order->user->name ?? 'G', 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-900 mb-0">{{ $payment->order->user->name ?? '-' }}</h6>
                            <span class="text-slate-400 font-monospace d-block small">{{ $payment->order->user->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Proof of Payment Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">3. Bukti Transfer Pelanggan</h5>
                    @if($payment->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Tunai Kasir</span>
                    @endif
                </div>
                <div class="p-4">
                    @php
                        $proofPath = $payment->proof_of_payment;
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
                                             alt="Bukti Transfer {{ $payment->invoice_number }}" 
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
                    @else
                        <div class="p-3 bg-slate-50 text-slate-500 rounded-3 border border-slate-200 text-center small">
                            @if($payment->payment_method === 'qris')
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400 d-block mx-auto mb-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum ada bukti transfer QRIS yang diunggah oleh pelanggan.
                            @else
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400 d-block mx-auto mb-1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Transaksi tunai diselesaikan langsung di kasir toko saat pengambilan pesanan.
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section 4: Pickup Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">3. Data Pengambilan</h5>
                    <span class="tokobii-badge tokobii-badge-info">Kasir Tokobii</span>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-slate-100">
                        <div>
                            <span class="text-slate-400 d-block mb-1" style="font-size: 0.75rem;">Kode QR Invoice:</span>
                            <span class="text-blue-600 fw-bold font-monospace">{{ $payment->invoice_number }}</span>
                        </div>
                        <div class="bg-white p-1 rounded-3 border border-slate-200 shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($payment->invoice_number) }}" 
                                 alt="QR Code {{ $payment->invoice_number }}" 
                                 style="width: 68px; height: 68px;">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 40%;">Status Ambil</th>
                                    <td>: 
                                        @if($payment->payment_status === 'completed')
                                            <span class="tokobii-badge tokobii-badge-success">Selesai</span>
                                        @elseif($payment->payment_status === 'ready_for_pickup')
                                            <span class="tokobii-badge tokobii-badge-info">Siap Diambil</span>
                                        @elseif($payment->payment_status === 'paid')
                                            <span class="tokobii-badge tokobii-badge-info">Disiapkan</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-warning">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">No. Struk</th>
                                    <td>: <span class="text-blue-600 font-monospace fw-bold">{{ $payment->pickup_receipt_number }}</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Lokasi Ambil</th>
                                    <td class="text-slate-800">: Toko Fisik Tokobii</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section: Verification Notes --}}
            @if($payment->reject_reason)
                <div class="tokobii-card">
                    <div class="tokobii-card-header">
                        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Catatan Penolakan</h5>
                    </div>
                    <div class="p-4">
                        <div class="p-3 bg-rose-50 text-rose-800 rounded-3 border border-rose-200 small">
                            <strong>Alasan Ditolak:</strong> {{ $payment->reject_reason }}
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column --}}
        <div class="col-12 col-md-7">
            
            {{-- Section 3: Order Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">4. Rincian Pesanan Terkait</h5>
                    @if($payment->order)
                        <a href="{{ route('admin.orders.show', $payment->order) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>Detail Pesanan</span>
                        </a>
                    @endif
                </div>
                <div class="p-4">
                    @if($payment->order)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0 small">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">No. Invoice</th>
                                        <td class="text-slate-900 fw-bold">: <span class="text-blue-600 font-monospace">{{ $payment->order->invoice_number }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Tanggal Pesan</th>
                                        <td class="text-slate-800">: {{ $payment->order->order_date ? $payment->order->order_date->format('d M Y, H:i') : '-' }} WIB</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Total Item</th>
                                        <td class="text-slate-800 fw-bold">: {{ $payment->order->items ? $payment->order->items->sum('quantity') : 0 }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-slate-500 fw-semibold">Total Tagihan</th>
                                        <td class="text-slate-900 font-monospace fw-bold">: Rp {{ number_format($payment->order->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-slate-400 mb-0 small">Data pesanan tidak ditemukan.</p>
                    @endif
                </div>
            </div>

            {{-- Section 5: Financial Summary Card --}}
            <div class="tokobii-card">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">5. Ringkasan Keuangan</h5>
                </div>
                <div class="p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0 small">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Subtotal Item</th>
                                            <td class="text-end font-monospace text-slate-800 fw-semibold">: Rp {{ number_format($payment->order->total_amount ?? $payment->amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-slate-500 fw-normal">Biaya Layanan</th>
                                            <td class="text-end font-monospace text-emerald-600 fw-semibold">: Rp 0</td>
                                        </tr>
                                        <tr class="border-top border-slate-200">
                                            <th class="ps-0 text-slate-900 fw-bold pt-3 fs-6">Total Pembayaran</th>
                                            <td class="text-end font-monospace text-blue-600 fw-bold pt-3 fs-5" style="color: #2563eb;">: Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
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
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="approvePaymentModalLabel">Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="approveForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="approve">

                    <div class="modal-body p-4 text-center">
                        <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 52px; height: 52px; background-color: #d1fae5; color: #059669;">
                            <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-slate-900 mb-2">Verifikasi Pembayaran Ini?</h5>
                        <p class="text-slate-500 mb-0 small">
                            Invoice <strong class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</strong> (Rp {{ number_format($payment->amount, 0, ',', '.') }}) akan ditandai sebagai <span class="tokobii-badge tokobii-badge-success">Lunas</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="approveBtn">
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
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="rejectForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject">

                    <div class="modal-body p-4">
                        <h5 class="fw-bold text-slate-900 text-center mb-3">Alasan Penolakan Pembayaran</h5>
                        
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">Alasan Penolakan <span class="text-rose-600">*</span></label>
                            <textarea name="reject_reason" 
                                      id="reject_reason" 
                                      rows="3" 
                                      class="form-control tokobii-input @error('reject_reason') is-invalid @enderror" 
                                      placeholder="Tuliskan alasan penolakan secara jelas..." 
                                      required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-danger btn-tokobii-sm" id="rejectBtn">
                            <span>Tolak Pembayaran</span>
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
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="readyForPickupModalLabel">Set Siap Diambil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="readyForm">
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
                            Pesanan invoice <strong class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</strong> akan ditandai siap diambil di kasir toko.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="readyBtn">
                            <span>Ya, Siap Diambil</span>
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
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-slate-900 text-white border-0 py-3">
                    <h5 class="modal-title fw-bold text-white fs-6" id="completePaymentModalLabel">Selesaikan Transaksi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="completeForm">
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
                            Invoice <strong class="text-blue-600 font-monospace">{{ $payment->invoice_number }}</strong> akan ditandai sebagai <span class="tokobii-badge tokobii-badge-success">Selesai</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-slate-50 border-top border-slate-100 py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm" id="completeBtn">
                            <span>Ya, Selesaikan Pesanan</span>
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-white border-bottom border-slate-200 d-print-none py-3">
                <div class="d-flex align-items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <h5 class="modal-title fw-bold text-slate-900 fs-6" id="printReceiptModalLabel">Struk Pengambilan Pesanan</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4" id="printableReceiptArea">
                <div class="text-center border-bottom border-slate-200 pb-3 mb-3">
                    <h3 class="fw-bold text-slate-900 mb-1">TOKOBII STORE</h3>
                    <p class="text-slate-500 small mb-0">Struk Resmi Pengambilan Barang</p>
                </div>

                <div class="row g-3 mb-3 text-slate-600 small">
                    <div class="col-6">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">No. Invoice:</span>
                        <strong class="text-blue-600 font-monospace fs-6">{{ $payment->invoice_number }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Tanggal Struk:</span>
                        <strong class="text-slate-800">{{ date('d M Y, H:i') }} WIB</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Nama Pelanggan:</span>
                        <strong class="text-slate-800">{{ $payment->order->user->name ?? '-' }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Metode Bayar:</span>
                        <strong class="text-uppercase text-slate-800">{{ $payment->payment_method }} ({{ $payment->status_label }})</strong>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered border-slate-200 align-middle mb-0 small">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th>No</th>
                                <th>Item Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($payment->order && $payment->order->items)
                                @foreach($payment->order->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-slate-900">{{ $item->product_name }}</td>
                                        <td class="text-center text-slate-900 fw-bold">{{ $item->quantity }}</td>
                                        <td class="text-end font-monospace text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end font-monospace fw-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50">
                                <td colspan="4" class="text-end fw-bold text-slate-800">Total Pembayaran:</td>
                                <td class="text-end font-monospace fw-bold text-blue-600 fs-6">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top border-slate-200 pt-3">
                    <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-500 me-3 small" style="max-width: 70%;">
                        <strong>Catatan:</strong> Tunjukkan struk ini ke kasir Tokobii saat melakukan serah terima barang.
                    </div>
                    <div class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($payment->invoice_number) }}" 
                             alt="QR Code {{ $payment->invoice_number }}" 
                             class="border border-slate-200 rounded p-1 shadow-sm mb-1" 
                             style="width: 68px; height: 68px;">
                        <span class="d-block text-slate-400 font-monospace" style="font-size: 0.6875rem;">{{ $payment->invoice_number }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-slate-50 border-top border-slate-100 py-2 d-print-none">
                <button type="button" onclick="window.print();" class="btn btn-tokobii-primary btn-tokobii-sm">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Struk</span>
                </button>
                <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
