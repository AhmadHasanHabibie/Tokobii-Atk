@extends('layouts.customer.app')

@section('title', $product->name . ' - Tokobii')

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
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('customer.shop.category', $product->category->slug) }}" class="text-decoration-none text-secondary">{{ $product->category->name }}</a>
                </li>
            @endif
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    {{-- Main Product Detail Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-5">
        <div class="card-body p-4">
            <div class="row g-4">
                
                {{-- Product Image / Gallery Column --}}
                <div class="col-12 col-md-5 text-center">
                    <div class="bg-light rounded-3 p-3 border mb-3 position-relative">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="Gambar {{ $product->name }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 380px; object-fit: contain;">
                        @else
                            <div class="py-5 text-muted">
                                <span class="fs-1 d-block mb-2">📦</span>
                                <small>Tidak ada gambar produk</small>
                            </div>
                        @endif

                        {{-- Stock Badge Overlay --}}
                        <div class="position-absolute top-0 start-0 m-3">
                            @if($product->stock > 5)
                                <span class="badge bg-success shadow-sm px-3 py-2 fs-6">Stok Tersedia ({{ $product->stock }})</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark shadow-sm px-3 py-2 fs-6">Stok Menipis ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-danger shadow-sm px-3 py-2 fs-6">Stok Habis</span>
                            @endif
                        </div>
                    </div>

                    {{-- Small Gallery Placeholder --}}
                    @if($product->thumbnail)
                        <div class="d-flex justify-content-center gap-2">
                            <div class="border border-primary rounded p-1" style="width: 60px; height: 60px; cursor: pointer;">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Preview" class="img-fluid h-100 w-100 object-fit-cover rounded">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Product Details Column --}}
                <div class="col-12 col-md-7 d-flex flex-column justify-content-between">
                    <div>
                        {{-- Category & SKU --}}
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($product->category)
                                <a href="{{ route('customer.shop.category', $product->category->slug) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-1.5 fs-6 fw-normal">
                                    📂 {{ $product->category->name }}
                                </a>
                            @endif
                            @if($product->sku)
                                <span class="text-muted small font-monospace">SKU: {{ $product->sku }}</span>
                            @endif
                        </div>

                        {{-- Product Name --}}
                        <h2 class="fw-bold text-dark mb-3">{{ $product->name }}</h2>

                        {{-- Price --}}
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <span class="text-secondary small d-block mb-1">Harga Produk</span>
                            <h2 class="text-primary fw-bold mb-0 font-monospace">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </h2>
                        </div>

                        {{-- Stock & Availability Info --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-3 text-muted small">
                                <div>
                                    <span>Status Stok:</span>
                                    <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->stock > 0 ? 'Tersedia (' . $product->stock . ' Pcs)' : 'Tidak Tersedia' }}
                                    </strong>
                                </div>
                                <div>•</div>
                                <div>
                                    <span>Metode Ambil:</span>
                                    <strong class="text-dark">Pickup Store (Ambil di Toko Tokobii)</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Quantity Form & Add to Cart --}}
                        <form action="{{ route('customer.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="mb-4" style="max-width: 220px;">
                                <label for="qty" class="form-label text-secondary small fw-semibold">Jumlah Kuantitas</label>
                                <input type="number" 
                                       name="qty" 
                                       id="qty" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock > 0 ? $product->stock : 1 }}" 
                                       class="form-control font-monospace fw-bold text-center" 
                                       {{ $product->stock <= 0 ? 'disabled' : '' }} 
                                       required>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg px-4 fw-bold shadow-sm {{ $product->stock <= 0 ? 'disabled' : '' }}" 
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    🛒 + Tambah ke Keranjang
                                </button>
                                <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary btn-lg px-4 fw-semibold">
                                    Kembali ke Shop
                                </a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

            {{-- Product Description Section --}}
            <div class="mt-5 pt-4 border-top">
                <h5 class="fw-bold text-dark mb-3">📝 Deskripsi Produk</h5>
                <div class="p-4 bg-light rounded-3 border text-secondary leading-relaxed">
                    @if($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="text-muted mb-0">Belum ada deskripsi untuk produk ini.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Related Products Section --}}
    @if($relatedProducts->isNotEmpty())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-0 text-dark">🛍️ Produk Terkait</h4>
                    <p class="text-muted small mb-0">Rekomendasi produk pilihan lainnya dalam kategori serupa.</p>
                </div>
            </div>

            <div class="row g-3">
                @foreach($relatedProducts as $rel)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100 transition card-hover">
                            <a href="{{ route('customer.shop.show', $rel->slug) }}">
                                @if($rel->thumbnail)
                                    <img src="{{ asset('storage/' . $rel->thumbnail) }}" 
                                         alt="Gambar {{ $rel->name }}" 
                                         class="card-img-top rounded-top-3" 
                                         style="height: 160px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-top-3 d-flex align-items-center justify-content-center text-muted" style="height: 160px;">
                                        <span class="fs-2">📦</span>
                                    </div>
                                @endif
                            </a>

                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $rel->name }}">
                                        <a href="{{ route('customer.shop.show', $rel->slug) }}" class="text-decoration-none text-dark">
                                            {{ $rel->name }}
                                        </a>
                                    </h6>
                                    <p class="text-primary fw-bold mb-2 font-monospace small">
                                        Rp {{ number_format($rel->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="badge {{ $rel->stock > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2 py-1 small">
                                        {{ $rel->stock > 0 ? 'Stok: ' . $rel->stock : 'Habis' }}
                                    </span>
                                    <a href="{{ route('customer.shop.show', $rel->slug) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
