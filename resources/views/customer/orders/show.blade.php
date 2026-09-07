@extends('layouts.customer.app')

@section('title', 'Detail Pesanan ' . $order->invoice_number . ' - ' . config('app.name', 'Tokobii'))

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
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $order->invoice_number }}</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <h1 class="h3 fw-bold text-slate-900 mb-0" style="color: #0f172a;">Detail Pesanan:</h1>
                    <span class="fw-bold text-blue-600 font-monospace fs-5">{{ $order->invoice_number }}</span>
                </div>
                <p class="text-slate-500 mb-0 small">Informasi rincian produk, status verifikasi, dan bukti pengambilan pesanan Anda.</p>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                @if(in_array($order->status, ['ready_for_pickup', 'completed']) || in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']))
                    <a href="{{ route('customer.orders.receipt', $order) }}" target="_blank" class="btn btn-tokobii-secondary btn-tokobii-sm shadow-sm">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Cetak Struk</span>
                    </a>
                @endif

                @if($order->status === 'pending' && $order->payment_method === 'qris')
                    <a href="{{ route('customer.orders.pay', $order) }}" class="btn btn-tokobii-warning btn-tokobii-sm shadow-sm">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span>Bayar QRIS</span>
                    </a>
                @endif

                <a href="{{ route('customer.orders.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Order Status Announcement --}}
    @if($order->status === 'ready_for_pickup')
        <div class="alert alert-success border-0 bg-emerald-50 text-emerald-900 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center gap-3">
            <div class="rounded-circle bg-emerald-100 text-emerald-700 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: #d1fae5; color: #059669;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <strong class="d-block" style="font-size: 0.9rem;">Pesanan Anda Siap Diambil di Toko!</strong>
                <span class="small text-emerald-800">Tunjukkan kode QR pengambilan atau sebutkan nomor invoice ke kasir saat datang ke toko.</span>
            </div>
        </div>
    @elseif($order->status === 'waiting_verification')
        <div class="alert alert-warning border-0 bg-amber-50 text-amber-900 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center gap-3">
            <div class="rounded-circle bg-amber-100 text-amber-700 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: #fef3c7; color: #d97706;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <strong class="d-block" style="font-size: 0.9rem;">Bukti Pembayaran Sedang Diverifikasi Kasir</strong>
                <span class="small text-amber-800">Bukti pembayaran Anda telah dikirim. Kasir Tokobii akan segera memeriksa dan memproses pesanan Anda.</span>
            </div>
        </div>
    @elseif($order->status === 'pending' && $order->payment_method === 'qris')
        <div class="alert alert-warning border-0 bg-amber-50 text-amber-900 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center gap-3">
            <div class="rounded-circle bg-amber-100 text-amber-700 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background-color: #fef3c7; color: #d97706;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <strong class="d-block" style="font-size: 0.9rem;">Menunggu Pembayaran QRIS</strong>
                <span class="small text-amber-800">Silakan selesaikan pembayaran via QRIS dan unggah bukti transfer agar pesanan dapat segera disiapkan.</span>
            </div>
        </div>
    @endif

    <div class="row g-4">

        {{-- Left Column: Order Meta, QR Pickup, Proof Upload --}}
        <div class="col-12 col-lg-5">
            
            {{-- Order Summary Info Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex align-items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Informasi Pesanan</h5>
                </div>
                <div class="p-4">
                    <table class="table table-borderless align-middle mb-0 small">
                        <tbody>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold" style="width: 42%;">No. Invoice</th>
                                <td>: <span class="fw-bold text-blue-600 font-monospace">{{ $order->invoice_number }}</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Tanggal Pesanan</th>
                                <td class="text-slate-800">: {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Metode Bayar</th>
                                <td>: 
                                    @if($order->payment_method === 'qris')
                                        <span class="tokobii-badge tokobii-badge-info">QRIS Tokobii</span>
                                    @else
                                        <span class="tokobii-badge tokobii-badge-neutral">Tunai di Kasir</span>
                                    @endif
                                </td>
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
                                <th class="ps-0 text-slate-500 fw-semibold">Metode Ambil</th>
                                <td class="text-slate-800">: Ambil di Toko Fisik</td>
                            </tr>
                            @if($order->notes)
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Catatan Anda</th>
                                    <td class="text-slate-800">: {{ $order->notes }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- QRIS Store Payment Card (Only for Non-Tunai / QRIS) --}}
            @if($order->payment_method === 'qris')
                <div class="tokobii-card mb-4 overflow-hidden">
                    <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">QRIS Pembayaran Toko</h5>
                        </div>
                        <span class="tokobii-badge tokobii-badge-info">QRIS Resmi Tokobii</span>
                    </div>
                    <div class="p-4 text-center">
                        <div class="mb-3">
                            <span class="text-slate-500 small d-block mb-1">Total Tagihan:</span>
                            <h3 class="fw-bold text-blue-600 font-monospace mb-1" style="color: #2563eb;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </h3>
                            <span class="text-slate-500 font-monospace small" style="font-size: 0.75rem;">NMID: ID1026585888914 · TOKOBII, ALAT TULIS KANTOR</span>
                        </div>

                        {{-- Static Official QRIS Standee Image --}}
                        <div class="p-2 bg-white border border-slate-200 rounded-3 d-inline-block shadow-sm mb-3">
                            <a href="{{ asset('images/Qr_Pembayaran.jpeg') }}" target="_blank" title="Klik untuk memperbesar gambar QRIS">
                                <img src="{{ asset('images/Qr_Pembayaran.jpeg') }}" 
                                     alt="QRIS Tokobii Official" 
                                     class="img-fluid rounded-2" 
                                     style="max-width: 250px; width: 100%; height: auto; object-fit: contain;">
                            </a>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <a href="{{ asset('images/Qr_Pembayaran.jpeg') }}" target="_blank" download="QRIS-Tokobii-{{ $order->invoice_number }}.jpeg" class="btn btn-tokobii-secondary btn-tokobii-sm">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span>Unduh / Buka QRIS Penuh</span>
                            </a>
                        </div>

                        {{-- Instructions --}}
                        <div class="p-3 bg-blue-50/60 border border-blue-100 rounded-3 text-start small text-slate-700">
                            <div class="fw-bold text-blue-900 mb-1.5 d-flex align-items-center gap-1.5">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Petunjuk Pembayaran QRIS:</span>
                            </div>
                            <ol class="mb-0 ps-3 text-slate-600" style="line-height: 1.6; font-size: 0.8125rem;">
                                <li>Buka aplikasi Mobile Banking / E-Wallet Anda (BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay, dll.).</li>
                                <li>Pindai / Scan QRIS Tokobii di atas.</li>
                                <li>Pastikan nama merchant adalah <strong>TOKOBII, ALAT TULIS KANTOR</strong> dan nominal tepat <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong>.</li>
                                <li>Simpan bukti transfer dan unggah pada formulir bukti transfer di bawah.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Pickup QR Code Card --}}
            <div class="tokobii-card p-4 text-center mb-4">
                <h6 class="fw-bold text-slate-900 mb-2">Kode QR Struk Pengambilan</h6>
                <p class="text-slate-400 small mb-3">Tunjukkan QR ini ke kasir untuk proses verifikasi pengambilan cepat.</p>
                
                <div class="d-inline-block bg-white p-3 border border-slate-200 rounded-3 shadow-sm mb-2">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($order->invoice_number) }}" 
                         alt="QR Pengambilan {{ $order->invoice_number }}" 
                         class="img-fluid" style="width: 130px; height: 130px;">
                </div>
                <div class="fw-bold font-monospace text-blue-600 small">{{ $order->invoice_number }}</div>
            </div>

            {{-- Cash Payment Status Info Card --}}
            @if($order->payment_method === 'cash')
                <div class="tokobii-card mb-4">
                    <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Status Pembayaran Tunai</h5>
                        </div>
                        @if($order->payment_status === 'paid')
                            <span class="tokobii-badge tokobii-badge-success">Lunas</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-warning">Menunggu Pembayaran</span>
                        @endif
                    </div>
                    <div class="p-4">
                        @if($order->payment_status === 'paid')
                            <div class="p-3 bg-emerald-50 text-emerald-900 rounded-3 border border-emerald-200 mb-3 small">
                                <strong class="d-block mb-1">Pembayaran Tunai Telah Dikonfirmasi</strong>
                                <span>Kasir telah menerima pembayaran dan menyerahkan kembalian sesuai transaksi.</span>
                            </div>
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
                                </tbody>
                            </table>
                        @else
                            <div class="p-3 bg-slate-50 text-slate-600 rounded-3 border border-slate-200 small">
                                Silakan siapkan uang tunai sebesar <strong class="text-blue-600 font-monospace">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong> saat mengambil barang di kasir toko Tokobii.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Payment Proof Box / Upload --}}
            @if($order->payment_method === 'qris')
                <div class="tokobii-card mb-4">
                    <div class="tokobii-card-header d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Bukti Pembayaran QRIS</h5>
                    </div>
                    <div class="p-4">
                        @php
                            $proofPath = $order->payment?->proof_of_payment ?? $order->payment_proof;
                            $isPdf = $proofPath && str_ends_with(strtolower($proofPath), '.pdf');
                        @endphp

                        @if($proofPath)
                            <div class="mb-3 text-center">
                                @if($isPdf)
                                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 mb-2">
                                        <span class="fw-bold text-slate-800 small d-block mb-2">Dokumen Bukti Transfer (PDF)</span>
                                        <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-tokobii-primary btn-tokobii-sm">
                                            <span>Buka Dokumen PDF</span>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/' . $proofPath) }}" alt="Bukti Transfer" class="img-fluid rounded-3 border border-slate-200 shadow-sm" style="max-height: 220px; object-fit: contain; background: #f8fafc;">
                                    </a>
                                    <small class="text-slate-400 d-block mt-1.5">Klik gambar untuk melihat ukuran penuh</small>
                                @endif
                            </div>
                        @else
                            <div class="p-3 bg-slate-50 text-slate-600 rounded-3 border border-slate-200 small mb-3">
                                Bukti pembayaran QRIS belum diunggah. Silakan pilih foto struk transfer Anda di bawah ini.
                            </div>
                        @endif

                        @if(in_array($order->payment_status, ['pending', 'rejected']))
                            <form action="{{ route('customer.orders.upload-proof', $order) }}" method="POST" enctype="multipart/form-data" id="uploadProofForm" onsubmit="return validateAndSubmitProof();">
                                @csrf
                                <div class="mb-3">
                                    <label for="proof_of_payment" class="form-label small fw-semibold text-slate-700">Unggah Bukti Transfer Baru</label>
                                    <input type="file" name="proof_of_payment" id="proof_of_payment" class="form-control tokobii-input small" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                                    <span class="text-slate-400 d-block mt-1" style="font-size: 0.75rem;">Format: JPG, PNG, PDF (Maks 2MB)</span>
                                </div>
                                <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm w-100" id="btnUploadProof">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span>Kirim Bukti Pembayaran</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Items Table --}}
        <div class="col-12 col-lg-7">
            <div class="tokobii-card">
                <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Rincian Item Produk</h5>
                    <span class="tokobii-badge tokobii-badge-neutral">{{ $order->items->count() }} Produk</span>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-slate-50 text-slate-500 small">
                                <tr>
                                    <th class="ps-4 py-3 border-bottom border-slate-200">Produk</th>
                                    <th class="text-center py-3 border-bottom border-slate-200">Jumlah</th>
                                    <th class="text-end py-3 border-bottom border-slate-200">Harga Satuan</th>
                                    <th class="text-end py-3 border-bottom border-slate-200 {{ $order->status !== 'completed' ? 'pe-4' : '' }}">Subtotal</th>
                                    @if($order->status === 'completed')
                                        <th class="text-end pe-4 py-3 border-bottom border-slate-200" style="min-width: 220px;">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-slate-900 small">{{ $item->product_name }}</div>
                                            @if($item->product && $item->product->sku)
                                                <span class="text-slate-400 font-monospace" style="font-size: 0.75rem;">SKU: {{ $item->product->sku }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3 text-slate-700 small fw-semibold">
                                            × {{ $item->qty }}
                                        </td>
                                        <td class="text-end py-3 text-slate-600 font-monospace small">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end py-3 font-monospace fw-bold text-slate-900 small {{ $order->status !== 'completed' ? 'pe-4' : '' }}">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        @if($order->status === 'completed')
                                            <td class="text-end pe-4 py-3">
                                                <div class="d-flex align-items-center justify-content-end gap-1.5 flex-wrap">
                                                    @if($item->review)
                                                        <a href="{{ route('customer.reviews.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm py-1 px-2.5" style="font-size: 0.75rem;" title="Lihat Ulasan">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            <span>Ulasan Diberikan</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('customer.orders.reviews.create', [$order, $item]) }}" class="btn btn-tokobii-secondary btn-tokobii-sm text-amber-700 border-amber-300 hover-bg-amber-50 py-1 px-2.5" style="font-size: 0.75rem;">
                                                            <svg width="12" height="12" fill="#f59e0b" stroke="#f59e0b" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                            </svg>
                                                            <span>Beri Ulasan</span>
                                                        </a>
                                                    @endif

                                                    @if($item->reports->where('user_id', auth()->id())->isNotEmpty())
                                                        <a href="{{ route('customer.reports.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm py-1 px-2.5" style="font-size: 0.75rem;" title="Lihat Laporan">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            <span>Laporan Terkirim</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('customer.orders.reports.create', [$order, $item]) }}" class="btn btn-tokobii-secondary btn-tokobii-sm text-rose-700 border-rose-200 hover-bg-rose-50 py-1 px-2.5" style="font-size: 0.75rem;">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                            </svg>
                                                            <span>Laporkan</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-slate-50 border-top border-slate-200">
                                <tr>
                                    <td colspan="{{ $order->status === 'completed' ? 4 : 3 }}" class="text-end fw-semibold text-slate-600 ps-4 py-2 small">Subtotal Produk:</td>
                                    <td class="text-end pe-4 py-2 font-monospace fw-semibold text-slate-800 small">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="{{ $order->status === 'completed' ? 4 : 3 }}" class="text-end fw-semibold text-slate-600 ps-4 py-2 small">Biaya Penanganan:</td>
                                    <td class="text-end pe-4 py-2 font-monospace fw-semibold text-slate-800 small">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="{{ $order->status === 'completed' ? 4 : 3 }}" class="text-end fw-bold text-slate-900 ps-4 py-3">Total Tagihan:</td>
                                    <td class="text-end pe-4 py-3 font-monospace fw-bold text-blue-600 fs-6">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
