@extends('layouts.customer.app')

@section('title', 'Checkout Pesanan - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('customer.cart.index') }}" class="text-decoration-none text-secondary">Keranjang Belanja</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Checkout Pesanan</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">📝 Checkout Pesanan Tokobii</h2>
            <p class="text-muted mb-0">Konfirmasi rincian produk, catatan pesanan, dan metode pembayaran Anda.</p>
        </div>
    </div>

    <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="row g-4">
            
            {{-- Left Column: Product Summary & Notes --}}
            <div class="col-12 col-lg-8">
                
                {{-- Product List Card --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">1. Daftar Produk Pesanan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 5%;">No</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 15%;">Thumbnail</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase">Nama Produk</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 15%;">Qty</th>
                                        <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 20%;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                        <tr>
                                            <td class="ps-4 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                            <td>
                                                @if(isset($item['thumbnail']) && $item['thumbnail'])
                                                    <img src="{{ asset('storage/' . $item['thumbnail']) }}" 
                                                         alt="Thumbnail {{ $item['name'] }}" 
                                                         class="rounded border" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                        <span class="fs-4">📦</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark d-block">{{ $item['name'] }}</span>
                                                <small class="text-muted font-monospace">Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs</small>
                                            </td>
                                            <td class="text-center fw-bold text-dark font-monospace">
                                                {{ $item['qty'] }} Pcs
                                            </td>
                                            <td class="pe-4 text-end font-monospace fw-bold text-primary">
                                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Order Notes Card --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">2. Catatan Pesanan (Opsional)</h5>
                    </div>
                    <div class="card-body p-4">
                        <label for="notes" class="form-label text-secondary small fw-semibold">Tuliskan pesan atau instruksi tambahan untuk penjual:</label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  placeholder="Contoh: Tolong disiapkan untuk pengambilan hari ini jam 14.00 WIB..."></textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Right Column: Payment Method & Grand Total --}}
            <div class="col-12 col-lg-4">
                
                {{-- Payment Method Selection Card --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">3. Pilih Metode Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="form-check p-3 border rounded-3 mb-3 bg-light cursor-pointer">
                            <input class="form-check-input ms-0 me-2" 
                                   type="radio" 
                                   name="payment_method" 
                                   id="method_cash" 
                                   value="cash" 
                                   checked>
                            <label class="form-check-label fw-bold text-dark cursor-pointer" for="method_cash">
                                💵 Cash (Tunai di Kasir Toko)
                            </label>
                            <small class="text-muted d-block mt-1">Lakukan pembayaran tunai secara langsung saat mengambil barang di toko Tokobii.</small>
                        </div>

                        <div class="form-check p-3 border rounded-3 bg-light cursor-pointer">
                            <input class="form-check-input ms-0 me-2" 
                                   type="radio" 
                                   name="payment_method" 
                                   id="method_qris" 
                                   value="qris">
                            <label class="form-check-label fw-bold text-primary cursor-pointer" for="method_qris">
                                📱 QRIS (Pembayaran Digital QR)
                            </label>
                            <small class="text-muted d-block mt-1">Bayar secara langsung menggunakan Scan QRIS dari e-wallet / mobile banking Anda.</small>
                        </div>

                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                    </div>
                </div>

                {{-- Payment Summary Card --}}
                <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">Ringkasan Tagihan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive mb-3">
                            <table class="table table-borderless align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-normal">Subtotal Produk:</th>
                                        <td class="text-end font-monospace text-dark fw-bold">: Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-normal">Ongkos Kirim (Pickup):</th>
                                        <td class="text-end font-monospace text-success fw-bold">: Rp 0 (Gratis)</td>
                                    </tr>
                                    <tr class="border-top fs-5">
                                        <th class="ps-0 text-dark fw-bold pt-3">Grand Total:</th>
                                        <td class="text-end font-monospace text-primary fw-bold pt-3">: Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm" id="checkoutBtn">
                                <span class="spinner-border spinner-border-sm me-1 d-none" id="checkoutSpinner" role="status" aria-hidden="true"></span>
                                <span id="checkoutBtnText">🚀 Buat Pesanan Sekarang</span>
                            </button>
                            <a href="{{ route('customer.cart.index') }}" class="btn btn-outline-secondary fw-semibold">
                                ← Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkoutForm = document.getElementById('checkoutForm');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const checkoutSpinner = document.getElementById('checkoutSpinner');
        const checkoutBtnText = document.getElementById('checkoutBtnText');

        if (checkoutForm && checkoutBtn) {
            checkoutForm.addEventListener('submit', function () {
                checkoutBtn.disabled = true;
                if (checkoutSpinner) checkoutSpinner.classList.remove('d-none');
                if (checkoutBtnText) checkoutBtnText.textContent = 'Memproses Pesanan...';
            });
        }
    });
</script>
@endpush
