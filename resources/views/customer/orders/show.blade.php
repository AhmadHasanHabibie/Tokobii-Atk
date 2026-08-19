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
                                    @if($order->status === 'pending')
                                        <span class="tokobii-badge tokobii-badge-warning">Menunggu Pembayaran</span>
                                    @elseif($order->status === 'paid')
                                        <span class="tokobii-badge tokobii-badge-info">Menunggu Verifikasi Kasir</span>
                                    @elseif($order->status === 'ready_for_pickup')
                                        <span class="tokobii-badge tokobii-badge-success">Siap Diambil</span>
                                    @elseif($order->status === 'completed')
                                        <span class="tokobii-badge tokobii-badge-success">Selesai</span>
                                    @elseif($order->status === 'rejected')
                                        <span class="tokobii-badge tokobii-badge-danger">Dibatalkan</span>
                                    @else
                                        <span class="tokobii-badge tokobii-badge-neutral">{{ ucfirst($order->status) }}</span>
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
                                    <label class="form-label small fw-semibold text-slate-700">Pilih Foto Bukti Transfer QRIS:</label>
                                    <input type="file" name="proof_of_payment" id="proofInput" accept="image/jpeg,image/png,image/jpg,application/pdf" class="form-control tokobii-input" required onchange="previewProofFile(this)">
                                    <small class="text-slate-400 d-block mt-1" style="font-size: 0.75rem;">Format: JPG, PNG, PDF (Maks. 2MB)</small>
                                </div>

                                {{-- Live Preview Box --}}
                                <div id="liveProofPreviewBox" class="mb-3 p-3 bg-slate-50 border border-blue-200 rounded-3 text-center d-none">
                                    <span class="text-slate-500 small d-block mb-2 fw-semibold">Pratinjau File Terpilih:</span>
                                    <img id="liveProofImage" src="" alt="Pratinjau" class="img-fluid rounded-2 border border-slate-200 shadow-sm mb-2" style="max-height: 160px; display: none;">
                                    <div id="liveProofFileName" class="text-blue-600 small fw-bold font-monospace text-truncate"></div>
                                </div>

                                <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm w-100" id="submitProofBtn">
                                    <span class="spinner-border spinner-border-sm d-none me-1" id="proofSpinner" role="status" aria-hidden="true"></span>
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="proofBtnIcon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span id="proofBtnText">Kirim Bukti Pembayaran</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Items Table & Actions --}}
        <div class="col-12 col-lg-7">
            
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Daftar Item Pesanan</h5>
                    </div>
                    <span class="tokobii-badge tokobii-badge-info">{{ $order->items->count() }} Macam Produk</span>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="tokobii-table mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end" style="width: 22%;">Harga Satuan</th>
                                    <th class="text-center" style="width: 14%;">Qty</th>
                                    <th class="text-end" style="width: 24%;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-slate-900">{{ $item->product_name }}</div>
                                            @if($item->product && $item->product->category)
                                                <span class="text-slate-400" style="font-size: 0.75rem;">{{ $item->product->category->name }}</span>
                                            @endif

                                            {{-- Review and Report Action Buttons --}}
                                            @if(in_array($order->order_status, ['completed', 'ready_for_pickup']))
                                                <div class="d-flex align-items-center gap-2 mt-2">
                                                    @php($existingReview = $item->product ? $item->product->reviews()->where('user_id', Auth::id())->where('order_id', $order->id)->first() : null)
                                                    @if($existingReview)
                                                        <a href="{{ route('customer.reviews.edit', $existingReview) }}" class="btn btn-tokobii-secondary btn-tokobii-sm py-0.5 px-2" style="font-size: 0.7rem;">
                                                            <svg width="12" height="12" fill="#f59e0b" stroke="#f59e0b" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                            </svg>
                                                            <span>Edit Ulasan</span>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('customer.orders.reviews.create', [$order, $item]) }}" class="btn btn-tokobii-secondary btn-tokobii-sm py-0.5 px-2" style="font-size: 0.7rem;">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                            </svg>
                                                            <span>Beri Ulasan</span>
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('customer.orders.reports.create', [$order, $item]) }}" class="btn btn-tokobii-secondary btn-tokobii-sm py-0.5 px-2 text-rose-600 border-rose-200" style="font-size: 0.7rem;">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                        </svg>
                                                        <span>Laporkan Masalah</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace text-slate-700">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center fw-semibold text-slate-800">
                                            {{ $item->qty }}
                                        </td>
                                        <td class="text-end fw-bold text-blue-600 font-monospace">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Total Calculation Block --}}
                <div class="p-4 bg-slate-50 border-top border-slate-100">
                    <div class="d-flex flex-column gap-2 small text-slate-600" style="max-width: 320px; margin-left: auto;">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span class="fw-semibold font-monospace text-slate-800">Rp {{ number_format($order->subtotal ?? $order->grand_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Biaya Pengambilan Toko:</span>
                            <span class="text-emerald-600 fw-semibold">Gratis</span>
                        </div>
                        <hr class="my-1 border-slate-200">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-slate-900 fs-6">Total Pembayaran:</span>
                            <span class="h5 fw-bold text-blue-600 font-monospace mb-0" style="color: #2563eb;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

    </div>

</div>

@push('scripts')
<script>
    function previewProofFile(input) {
        var box = document.getElementById('liveProofPreviewBox');
        var img = document.getElementById('liveProofImage');
        var nameLabel = document.getElementById('liveProofFileName');

        if (input.files && input.files[0]) {
            var file = input.files[0];
            
            // Check size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal adalah 2MB. Silakan pilih file yang lebih kecil.');
                input.value = '';
                if (box) box.classList.add('d-none');
                return;
            }

            if (box) box.classList.remove('d-none');
            if (nameLabel) nameLabel.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';

            if (file.type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (img) {
                        img.src = e.target.result;
                        img.style.display = 'inline-block';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                if (img) img.style.display = 'none';
            }
        } else {
            if (box) box.classList.add('d-none');
        }
    }

    function validateAndSubmitProof() {
        var input = document.getElementById('proofInput');
        if (!input || !input.files || input.files.length === 0) {
            alert('Pilih file bukti pembayaran terlebih dahulu.');
            return false;
        }

        var btn = document.getElementById('submitProofBtn');
        var spinner = document.getElementById('proofSpinner');
        var icon = document.getElementById('proofBtnIcon');
        var text = document.getElementById('proofBtnText');

        if (btn) btn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');
        if (icon) icon.classList.add('d-none');
        if (text) text.textContent = 'Mengunggah Bukti...';

        return true;
    }
</script>
@endpush
@endsection
