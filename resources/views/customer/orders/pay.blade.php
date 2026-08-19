@extends('layouts.customer.app')

@section('title', 'Pembayaran QRIS ' . $order->invoice_number . ' - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Riwayat Pesanan</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Pembayaran QRIS</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Pembayaran QRIS Tokobii</h1>
                <p class="text-slate-500 mb-0 small">Pesanan invoice <strong class="text-blue-600 font-monospace">{{ $order->invoice_number }}</strong> siap dibayar via QRIS.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Lihat Detail Pesanan</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">

        {{-- Main QRIS Box Card --}}
        <div class="col-12 col-lg-7">
            <div class="tokobii-card">
                <div class="tokobii-card-header text-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1.05rem;">Kode QR Pembayaran QRIS</h5>
                </div>
                <div class="p-4 p-md-5 text-center">

                    {{-- Invoice & Amount Box --}}
                    <div class="mb-4 bg-slate-50 p-4 rounded-3 border border-slate-200">
                        <span class="text-slate-500 small d-block mb-1">Total Tagihan Pembayaran:</span>
                        <h2 class="fw-bold text-blue-600 font-monospace mb-1" style="color: #2563eb;">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </h2>
                        <span class="text-slate-400 font-monospace small">No. Invoice: {{ $order->invoice_number }}</span>
                    </div>

                    {{-- QR Code Display --}}
                    <div class="mb-4">
                        <div class="d-inline-block bg-white p-3 border border-slate-200 rounded-3 shadow-sm mb-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=TOKOBII-QRIS-{{ urlencode($order->invoice_number) }}" 
                                 alt="QRIS Tokobii Code {{ $order->invoice_number }}" 
                                 class="img-fluid" style="width: 200px; height: 200px;">
                        </div>
                        <span class="d-block text-slate-400 small">NMID: ID1029384756102 - TOKOBII OFFICIAL STORE</span>
                    </div>

                    {{-- Payment Steps Card --}}
                    <div class="text-start bg-slate-50 p-4 rounded-3 border border-slate-200 mb-4">
                        <h6 class="fw-bold text-slate-900 mb-3" style="font-size: 0.875rem;">Langkah-Langkah Pembayaran:</h6>
                        <ol class="mb-0 text-slate-600 small ps-3" style="line-height: 1.8;">
                            <li class="mb-1">Buka aplikasi E-Wallet (GoPay, OVO, Dana, ShopeePay) atau Mobile Banking Anda.</li>
                            <li class="mb-1">Pindai / Scan kode <strong>QRIS Tokobii</strong> di atas dan pastikan nominal sesuai tepat <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong>.</li>
                            <li class="mb-1">Selesaikan transaksi pembayaran dan simpan foto / tangkapan layar bukti transfer Anda.</li>
                            <li class="mb-0">Unggah file bukti transfer pada formulir di bawah ini lalu klik <strong>Kirim Bukti Pembayaran</strong>.</li>
                        </ol>
                    </div>

                    {{-- Upload Proof Form Card --}}
                    <div class="p-4 bg-white rounded-3 border-2 border-dashed border-slate-300 text-start">
                        <h6 class="fw-bold text-slate-900 mb-2">Unggah Bukti Transfer</h6>
                        <p class="text-slate-400 small mb-3">Format gambar: JPG, JPEG, PNG, atau PDF (Maksimal 2MB).</p>

                        <form action="{{ route('customer.orders.upload-proof', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="proof_of_payment" accept="image/*,application/pdf" class="form-control tokobii-input" required>
                            </div>
                            <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
