@extends('layouts.customer.app')

@section('title', 'Checkout Pesanan - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.cart.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Keranjang Belanja</a></li>
                <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Checkout Pesanan</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Checkout Pesanan Tokobii</h1>
        <p class="text-slate-500 mb-0 small">Konfirmasi rincian produk, catatan pesanan, dan metode pembayaran Anda.</p>
    </div>

    <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkoutForm">
        @csrf

        @if(!empty($selectedIds))
            @foreach($selectedIds as $selId)
                <input type="hidden" name="selected_items[]" value="{{ $selId }}">
            @endforeach
        @endif

        <div class="row g-4">
            
            {{-- Left Column: Product Summary & Notes --}}
            <div class="col-12 col-lg-8">
                
                {{-- Product List Card --}}
                <div class="tokobii-card mb-4">
                    <div class="tokobii-card-header d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">1. Rincian Produk yang Dipesan</h5>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="tokobii-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 14%;">Foto</th>
                                        <th>Nama Produk</th>
                                        <th class="text-center" style="width: 15%;">Qty</th>
                                        <th class="text-end" style="width: 22%;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                        <tr>
                                            <td class="text-slate-400 font-monospace">{{ $loop->iteration }}</td>
                                            <td>
                                                @if(isset($item['thumbnail']) && $item['thumbnail'])
                                                    <img src="{{ asset('storage/' . $item['thumbnail']) }}" 
                                                         alt="Thumbnail {{ $item['name'] }}" 
                                                         class="rounded-3 border border-slate-200" 
                                                         style="width: 46px; height: 46px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-3 border border-slate-200 bg-slate-100 d-flex align-items-center justify-content-center text-slate-400" style="width: 46px; height: 46px;">
                                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-slate-900 d-block">{{ $item['name'] }}</span>
                                                <small class="text-slate-400 font-monospace">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                                            </td>
                                            <td class="text-center fw-semibold text-slate-700">
                                                {{ $item['qty'] }} pcs
                                            </td>
                                            <td class="text-end fw-bold text-blue-600 font-monospace">
                                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Payment Method Selection Card --}}
                <div class="tokobii-card mb-4">
                    <div class="tokobii-card-header d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">2. Pilihan Metode Pembayaran</h5>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="border border-slate-200 rounded-3 p-3 d-flex align-items-start gap-3 w-100 transition" style="cursor: pointer;">
                                    <input type="radio" name="payment_method" value="qris" class="form-check-input mt-1" checked>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-slate-900">QRIS Tokobii</span>
                                            <span class="tokobii-badge tokobii-badge-info">Instan & Praktis</span>
                                        </div>
                                        <p class="text-slate-500 small mb-0" style="line-height: 1.5;">
                                            Bayar langsung menggunakan aplikasi E-Wallet atau Mobile Banking Anda.
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="border border-slate-200 rounded-3 p-3 d-flex align-items-start gap-3 w-100 transition" style="cursor: pointer;">
                                    <input type="radio" name="payment_method" value="cash" class="form-check-input mt-1">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-slate-900">Tunai di Kasir</span>
                                            <span class="tokobii-badge tokobii-badge-neutral">Saat Pengambilan</span>
                                        </div>
                                        <p class="text-slate-500 small mb-0" style="line-height: 1.5;">
                                            Lakukan pembayaran tunai saat Anda mengambil pesanan di kasir toko.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes Card --}}
                <div class="tokobii-card">
                    <div class="tokobii-card-header d-flex align-items-center gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">3. Catatan Pesanan (Opsional)</h5>
                    </div>
                    <div class="p-4">
                        <textarea name="notes" rows="3" class="form-control tokobii-input w-100" placeholder="Contoh: Tolong siapkan sebelum jam 3 sore, warna pulpen tinta biru, dsb.">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Right Column: Order Total & Confirm --}}
            <div class="col-12 col-lg-4">
                <div class="tokobii-card p-4 sticky-top" style="top: 84px;">
                    <h5 class="fw-bold text-slate-900 mb-3 pb-2 border-bottom border-slate-100" style="font-size: 1.05rem;">Ringkasan Pesanan</h5>

                    <div class="d-flex flex-column gap-2.5 mb-4 text-slate-600 small">
                        <div class="d-flex justify-content-between">
                            <span>Nama Pemesan</span>
                            <span class="fw-semibold text-slate-800">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Email Akun</span>
                            <span class="text-slate-600 font-monospace">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Metode Penyerahan</span>
                            <span class="tokobii-badge tokobii-badge-info">Ambil di Toko</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal Belanja</span>
                            <span class="fw-semibold font-monospace text-slate-800">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Biaya Layanan</span>
                            <span class="text-emerald-600 fw-semibold">Gratis</span>
                        </div>
                        <hr class="my-1 border-slate-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-slate-900 fs-6">Total Tagihan</span>
                            <span class="h4 fw-bold text-blue-600 font-monospace mb-0" style="color: #2563eb;">
                                Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-tokobii-primary btn-tokobii-lg w-100 mb-3" onclick="showCheckoutConfirmModal()">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Buat Pesanan Sekarang</span>
                    </button>

                    <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-500 small">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <svg class="text-blue-600 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span class="fw-bold text-slate-700">Jaminan Transaksi Aman</span>
                        </div>
                        <p class="mb-0" style="font-size: 0.75rem; line-height: 1.5;">
                            Setelah checkout, Anda akan menerima nomor invoice dan panduan langkah pembayaran atau pengambilan di toko.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- Pre-Action Custom Checkout Confirmation Modal --}}
    <div class="tokobii-guidance-backdrop" id="checkoutConfirmModal">
        <div class="tokobii-guidance-box p-4 p-md-5 text-center">
            
            <div class="tokobii-guidance-icon-wrap info">
                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>

            <h3 class="fw-bold text-slate-900 mb-2" style="font-size: 1.25rem;">
                Konfirmasi Pesanan Anda
            </h3>
            <p class="text-slate-500 small mb-3">
                Pastikan rincian produk dan metode pembayaran yang Anda pilih sudah sesuai sebelum melanjutkan.
            </p>

            <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-start small mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-slate-500">Jumlah Jenis Item:</span>
                    <span class="fw-bold text-slate-800">{{ count($cart) }} Produk</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-slate-500">Metode Pembayaran:</span>
                    <span class="fw-bold text-blue-600" id="confirmPaymentMethodLabel">QRIS Tokobii</span>
                </div>
                <div class="d-flex justify-content-between border-top border-slate-200 pt-2">
                    <span class="fw-bold text-slate-900">Total Tagihan:</span>
                    <span class="fw-bold text-blue-600 font-monospace fs-6">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-3 rounded-3 mb-4 text-start small" id="confirmPaymentGuidanceBox" style="background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af;">
                <div class="d-flex gap-2 align-items-start">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="confirmPaymentGuidanceText" style="line-height: 1.5;">
                        Setelah mengonfirmasi, Anda akan diarahkan ke halaman pesanan untuk memindai QRIS dan mengunggah foto bukti pembayaran.
                    </span>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                <button type="button" class="btn btn-tokobii-secondary flex-grow-1 order-2 order-sm-1" onclick="closeCheckoutConfirmModal()">
                    Periksa Kembali
                </button>
                <button type="button" class="btn btn-tokobii-primary flex-grow-1 order-1 order-sm-2" id="submitOrderFinalBtn" onclick="submitFinalOrder()">
                    <span class="spinner-border spinner-border-sm d-none me-1" id="orderSpinner" role="status" aria-hidden="true"></span>
                    <span id="orderBtnText">Ya, Buat Pesanan</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="ms-1" id="orderBtnIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
    function showCheckoutConfirmModal() {
        var paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'qris';
        var methodLabel = document.getElementById('confirmPaymentMethodLabel');
        var guidanceText = document.getElementById('confirmPaymentGuidanceText');

        if (paymentMethod === 'qris') {
            if (methodLabel) methodLabel.textContent = 'QRIS Tokobii (Transfer)';
            if (guidanceText) guidanceText.textContent = 'Setelah mengonfirmasi, Anda akan diarahkan ke halaman pesanan untuk memindai QRIS dan mengunggah foto bukti transfer.';
        } else {
            if (methodLabel) methodLabel.textContent = 'Tunai di Kasir (Saat Ambil)';
            if (guidanceText) guidanceText.textContent = 'Setelah mengonfirmasi, pesanan Anda akan langsung disiapkan oleh toko Tokobii untuk diambil dan dibayar langsung di kasir.';
        }

        if (window.openTokobiiModal) {
            window.openTokobiiModal('checkoutConfirmModal');
        } else {
            var modal = document.getElementById('checkoutConfirmModal');
            if (modal) modal.classList.add('show');
            document.body.classList.add('modal-open', 'tokobii-modal-open');
        }
    }

    function closeCheckoutConfirmModal() {
        if (window.closeTokobiiModal) {
            window.closeTokobiiModal('checkoutConfirmModal');
        } else {
            var modal = document.getElementById('checkoutConfirmModal');
            if (modal) modal.classList.remove('show');
            document.body.classList.remove('modal-open', 'tokobii-modal-open');
        }
    }

    function submitFinalOrder() {
        var form = document.getElementById('checkoutForm');
        var btn = document.getElementById('submitOrderFinalBtn');
        var spinner = document.getElementById('orderSpinner');
        var btnText = document.getElementById('orderBtnText');
        var btnIcon = document.getElementById('orderBtnIcon');

        if (btn) btn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');
        if (btnIcon) btnIcon.classList.add('d-none');
        if (btnText) btnText.textContent = 'Memproses Pesanan...';

        form.submit();
    }
</script>
@endpush
@endsection

