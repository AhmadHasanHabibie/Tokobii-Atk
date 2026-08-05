@extends('layouts.customer.app')

@section('title', 'Keranjang Belanja - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('customer.shop.index') }}" class="text-decoration-none text-secondary">Shop Catalog</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Keranjang Belanja</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">🛒 Keranjang Belanja Anda</h2>
            <p class="text-muted mb-0">Tinjau daftar produk yang ingin Anda beli sebelum melakukan checkout.</p>
        </div>
        @if(!empty($cart))
            <div class="mt-3 mt-md-0">
                <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold shadow-sm">
                        🗑 Kosongkan Keranjang
                    </button>
                </form>
            </div>
        @endif
    </div>

    @if(!empty($cart))
        <div class="row g-4">

            {{-- Cart Items Table --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 5%;">No</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 15%;">Thumbnail</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase">Produk</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase text-end" style="width: 15%;">Harga</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 20%;">Qty</th>
                                        <th scope="col" class="py-3 text-secondary small text-uppercase text-end" style="width: 18%;">Subtotal</th>
                                        <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 7%;">Aksi</th>
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
                                                         style="width: 55px; height: 55px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 55px; height: 55px;">
                                                        <span class="fs-4">📦</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('customer.shop.show', $item['slug'] ?? '#') }}" class="fw-bold text-dark text-decoration-none d-block">
                                                    {{ $item['name'] }}
                                                </a>
                                                @if(isset($item['sku']) && $item['sku'])
                                                    <small class="text-muted font-monospace">SKU: {{ $item['sku'] }}</small>
                                                @endif
                                            </td>
                                            <td class="text-end font-monospace text-dark">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <form action="{{ route('customer.cart.update') }}" method="POST" class="d-flex justify-content-center align-items-center gap-1">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <input type="number" 
                                                           name="qty" 
                                                           value="{{ $item['qty'] }}" 
                                                           min="1" 
                                                           max="{{ $item['stock'] ?? 99 }}" 
                                                           class="form-control form-control-sm text-center font-monospace fw-bold" 
                                                           style="width: 65px;"
                                                           onchange="this.form.submit()">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary px-2" title="Simpan Perubahan">
                                                        ✓
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end font-monospace fw-bold text-primary">
                                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                            </td>
                                            <td class="pe-4 text-end">
                                                <form action="{{ route('customer.cart.remove') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus dari Keranjang" aria-label="Hapus Item">
                                                        ✖
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary Card --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive mb-3">
                            <table class="table table-borderless align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-normal">Total Item Produk:</th>
                                        <td class="text-end font-monospace text-dark fw-bold">{{ $totalItems }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-normal">Subtotal Ringkasan:</th>
                                        <td class="text-end font-monospace text-dark fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="border-top fs-5">
                                        <th class="ps-0 text-dark fw-bold pt-3">Grand Total:</th>
                                        <td class="text-end font-monospace text-primary fw-bold pt-3">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                🚀 Lanjut ke Checkout →
                            </a>
                            <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary fw-semibold">
                                ← Tambah Produk Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        {{-- Empty Cart State --}}
        <div class="card border-0 shadow-sm rounded-3 py-5 px-4 text-center">
            <div class="mb-3">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                    <span class="display-4">🛒</span>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Keranjang Belanja Anda Kosong</h4>
            <p class="text-muted mb-4">Anda belum menambahkan produk alat tulis ke dalam keranjang belanja.</p>
            <div>
                <a href="{{ route('customer.shop.index') }}" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm">
                    🛍️ Jelajahi Shop Katalog Now
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
