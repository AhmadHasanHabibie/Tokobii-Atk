@extends('layouts.guest.app')

@section('title', 'Katalog Toko Online - Tokobii')

@section('content')
<div class="py-4 bg-white border-bottom shadow-sm mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="fw-bold text-dark mb-1">🛍️ Tokobii Catalog</h1>
                <p class="text-muted mb-0">Temukan berbagai produk berkualitas dengan harga terjangkau di Tokobii.</p>
            </div>
            @auth
                @if(auth()->user()->role === 'customer')
                    <div>
                        <a href="{{ route('customer.cart.index') }}" class="btn btn-outline-primary fw-semibold shadow-sm">
                            🛒 Lihat Keranjang Saya
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

<div class="container mb-5">
    {{-- Search & Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('shop') }}" method="GET" class="row g-2 align-items-center">
                {{-- Search Input --}}
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari berdasarkan nama produk atau SKU..." 
                               value="{{ request('search') }}"
                               aria-label="Cari Produk">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <select name="category" class="form-select" onchange="this.form.submit()" aria-label="Pilih Kategori">
                        <option value="">Semua Kategori Produk</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit Button --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Cari Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Product List Grid --}}
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 hover-shadow transition">
                    {{-- Product Image --}}
                    <div class="position-relative bg-light rounded-top text-center p-3" style="height: 200px;">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid h-100 rounded" 
                                 style="object-fit: cover; width: 100%;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted fs-1">
                                📦
                            </div>
                        @endif

                        {{-- Category Badge Overlay --}}
                        @if($product->category)
                            <span class="position-absolute top-0 start-0 m-2 badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small font-monospace">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark hover-primary">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="text-muted small mb-2 text-truncate-2" style="min-height: 38px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->description ?? 'Tidak ada deskripsi singkat.' }}
                        </p>

                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold fs-5 text-primary font-monospace">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @if($product->stock > 0)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                                        Stok: {{ $product->stock }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 small">
                                        Stok Habis
                                    </span>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            <div class="d-grid gap-2">
                                @guest
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                        🔑 Login untuk Membeli
                                    </a>
                                @else
                                    @if(auth()->user()->role === 'customer')
                                        @if($product->stock > 0)
                                            <form action="{{ route('customer.cart.add') }}" method="POST" class="d-grid">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="qty" value="1">
                                                <button type="submit" class="btn btn-primary btn-sm fw-bold">
                                                    🛒 Tambah ke Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                Out of Stock
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                                            👁 Lihat Detail
                                        </a>
                                    @endif
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-3 shadow-sm p-4">
                    <div class="fs-1 mb-2">🛍️</div>
                    <h5 class="fw-bold text-dark">Produk tidak ditemukan.</h5>
                    <p class="text-muted">Tidak ada produk yang sesuai dengan pencarian atau filter kategori Anda.</p>
                    <a href="{{ route('shop') }}" class="btn btn-outline-primary px-4 fw-semibold">
                        Reset Filter
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection