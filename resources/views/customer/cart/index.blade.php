@extends('layouts.customer.app')

@section('title', 'Keranjang Belanja - ' . config('app.name', 'Tokobii'))

@section('content')



<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.shop.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Katalog</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Keranjang Belanja</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Keranjang Belanja Anda</h1>
                <p class="text-slate-500 mb-0 small">Tinjau daftar produk ATK sebelum melanjutkan proses checkout.</p>
            </div>

            @if(!empty($cart))
                <div>
                    <form id="clearCartForm" action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return openClearCartModal(event);">
                        @csrf
                        <button type="submit" class="btn btn-tokobii-danger btn-tokobii-sm">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Kosongkan Keranjang</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if(!empty($cart))
        <div class="row g-4">

            {{-- Cart Items Table Card --}}
            <div class="col-12 col-lg-8">
                <div class="tokobii-table-container">
                    <div class="p-3.5 bg-white border-bottom border-slate-100 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-slate-900 fs-6">Daftar Produk di Keranjang</h5>
                        <span class="tokobii-badge tokobii-badge-info">{{ count($cart) }} Macam Produk</span>
                    </div>
                    <div class="table-responsive">
                        <table class="tokobii-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 14%;">Foto</th>
                                    <th>Nama Produk</th>
                                    <th class="text-end" style="width: 18%;">Harga</th>
                                    <th class="text-center" style="width: 18%;">Jumlah</th>
                                    <th class="text-end" style="width: 20%;">Subtotal</th>
                                    <th class="text-center" style="width: 8%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr>
                                        <td class="text-slate-400 font-monospace">{{ $loop->iteration }}</td>
                                        <td>
                                            @if(isset($item['thumbnail']) && $item['thumbnail'])
                                                <img src="{{ asset('storage/' . $item['thumbnail']) }}" 
                                                     alt="Thumbnail {{ $item['name'] }}" 
                                                     class="rounded-3 border border-slate-200" 
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="rounded-3 border border-slate-200 bg-slate-100 d-flex align-items-center justify-content-center text-slate-400" style="width: 48px; height: 48px;">
                                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.shop.show', $item['slug'] ?? '#') }}" class="fw-bold text-slate-900 text-decoration-none d-block text-truncate hover-text-blue-600" style="max-width: 200px;" title="{{ $item['name'] }}">
                                                {{ $item['name'] }}
                                            </a>
                                            <span class="text-slate-400 small">Stok: {{ $item['stock'] ?? 'Tersedia' }}</span>
                                        </td>
                                        <td class="text-end font-monospace text-slate-700 small">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            {{-- Quantity Update Form --}}
                                            <form action="{{ route('customer.cart.update') }}" method="POST" class="d-inline-flex align-items-center justify-content-center m-0">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $id }}">
                                                <div class="tokobii-qty-control" style="transform: scale(0.88);">
                                                    <button type="submit" name="qty" value="{{ max(1, $item['qty'] - 1) }}" class="tokobii-qty-btn" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>-</button>
                                                    <input type="text" readonly value="{{ $item['qty'] }}" class="tokobii-qty-input">
                                                    <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}" class="tokobii-qty-btn" {{ isset($item['stock']) && $item['qty'] >= $item['stock'] ? 'disabled' : '' }}>+</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end fw-bold text-blue-600 font-monospace">
                                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('customer.cart.remove') }}" method="POST" onsubmit="return openDeleteItemModal(event, '{{ addslashes($item['name']) }}');">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $id }}">
                                                <button type="submit" class="btn btn-tokobii-secondary btn-tokobii-sm p-1.5 text-rose-600 border-rose-200" title="Hapus Produk">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Lanjut Belanja</span>
                    </a>
                </div>
            </div>

            {{-- Cart Summary Card --}}
            <div class="col-12 col-lg-4">
                <div class="tokobii-card p-4 sticky-top" style="top: 84px;">
                    <h5 class="fw-bold text-slate-900 mb-3 pb-2 border-bottom border-slate-100" style="font-size: 1.05rem;">Ringkasan Belanja</h5>

                    <div class="d-flex flex-column gap-2.5 mb-4 text-slate-600 small">
                        <div class="d-flex justify-content-between">
                            <span>Total Item</span>
                            <span class="fw-semibold text-slate-800">{{ $totalItems ?? array_sum(array_column($cart, 'qty')) }} Pcs</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Metode Pengambilan</span>
                            <span class="tokobii-badge tokobii-badge-info">Ambil di Toko</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Biaya Layanan</span>
                            <span class="text-emerald-600 fw-semibold">Gratis (Rp 0)</span>
                        </div>
                        <hr class="my-1 border-slate-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-slate-900 fs-6">Total Pembayaran</span>
                            <span class="h4 fw-bold text-blue-600 font-monospace mb-0" style="color: #2563eb;">
                                Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('customer.checkout.index') }}" class="btn btn-tokobii-primary btn-tokobii-lg w-100 mb-3">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Lanjut ke Checkout</span>
                    </a>

                    <div class="d-flex align-items-center gap-2 p-2.5 bg-slate-50 rounded-3 border border-slate-200 text-slate-500 small">
                        <svg class="text-blue-600 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Pesanan dapat diambil dan dibayar langsung di kasir atau via QRIS.</span>
                    </div>
                </div>
            </div>

        </div>
    @else
        <div class="tokobii-empty-state">
            <div class="tokobii-empty-icon">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h5 class="fw-bold text-slate-900 mb-1">Keranjang Belanja Anda Kosong</h5>
            <p class="text-slate-500 small mb-4">Anda belum menambahkan produk apapun ke dalam keranjang belanja.</p>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>Mulai Belanja Sekarang</span>
            </a>
        </div>
    @endif

</div>

{{-- Confirmation Modals --}}
<div id="confirmModalOverlay" class="tokobii-guidance-backdrop" onclick="closeConfirmModal();">
    <div class="tokobii-guidance-box p-4 p-md-5 text-center" onclick="event.stopPropagation();">
        <div class="tokobii-guidance-icon-wrap danger">
            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </div>
        <h3 id="confirmModalTitle" class="fw-bold text-slate-900 mb-2" style="font-size: 1.25rem;">Konfirmasi</h3>
        <p id="confirmModalText" class="text-slate-500 small mb-4">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>

        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
            <button type="button" class="btn btn-tokobii-secondary flex-grow-1 order-2 order-sm-1" onclick="closeConfirmModal();">Batal</button>
            <button type="button" id="confirmModalSubmitBtn" class="btn btn-tokobii-danger flex-grow-1 order-1 order-sm-2" onclick="submitConfirmedForm();">
                <span class="spinner-border spinner-border-sm d-none me-1" id="confirmCartSpinner" role="status" aria-hidden="true"></span>
                <span id="confirmCartBtnText">Ya, Hapus</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let activeForm = null;

    function openClearCartModal(event) {
        event.preventDefault();
        activeForm = document.getElementById('clearCartForm');
        document.getElementById('confirmModalTitle').innerText = 'Kosongkan Semua Keranjang?';
        document.getElementById('confirmModalText').innerText = 'Semua produk di dalam keranjang belanja Anda akan dihapus sekaligus.';
        document.getElementById('confirmCartBtnText').innerText = 'Ya, Kosongkan';
        if (window.openTokobiiModal) {
            window.openTokobiiModal('confirmModalOverlay');
        } else {
            document.getElementById('confirmModalOverlay').classList.add('show');
            document.body.classList.add('modal-open', 'tokobii-modal-open');
        }
        return false;
    }

    function openDeleteItemModal(event, productName) {
        event.preventDefault();
        activeForm = event.target;
        document.getElementById('confirmModalTitle').innerText = 'Hapus Produk dari Keranjang?';
        document.getElementById('confirmModalText').innerText = 'Produk "' + productName + '" akan dikeluarkan dari keranjang belanja Anda.';
        document.getElementById('confirmCartBtnText').innerText = 'Ya, Hapus';
        if (window.openTokobiiModal) {
            window.openTokobiiModal('confirmModalOverlay');
        } else {
            document.getElementById('confirmModalOverlay').classList.add('show');
            document.body.classList.add('modal-open', 'tokobii-modal-open');
        }
        return false;
    }

    function closeConfirmModal() {
        if (window.closeTokobiiModal) {
            window.closeTokobiiModal('confirmModalOverlay');
        } else {
            document.getElementById('confirmModalOverlay').classList.remove('show');
            document.body.classList.remove('modal-open', 'tokobii-modal-open');
        }
        activeForm = null;
    }

    function submitConfirmedForm() {
        if (activeForm) {
            var btn = document.getElementById('confirmModalSubmitBtn');
            var spinner = document.getElementById('confirmCartSpinner');
            var text = document.getElementById('confirmCartBtnText');
            if (btn) btn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            if (text) text.innerText = 'Memproses...';
            activeForm.submit();
        }
    }
</script>
@endpush
@endsection