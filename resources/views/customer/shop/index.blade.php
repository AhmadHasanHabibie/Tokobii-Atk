@extends('layouts.customer.app')

@section('title', 'Katalog Produk Shop - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Shop Catalog</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Katalog Produk Tokobii</h2>
            <p class="text-muted mb-0">Temukan alat tulis, perlengkapan sekolah, dan kebutuhan kantor terlengkap.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold shadow-sm" title="Reset Filter & Refresh" aria-label="Reset Filter">
                ↺ Reset Filter
            </a>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('customer.shop.index') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Search Input (Name / SKU) --}}
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari berdasarkan nama atau SKU..." 
                               value="{{ request('search') }}"
                               aria-label="Cari Produk"
                               aria-describedby="search-addon"
                               autofocus>
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="category" class="form-select" aria-label="Filter Kategori" onchange="this.form.submit()">
                        <option value="">Kategori: Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ (request('category') === $cat->slug || (isset($selectedCategory) && $selectedCategory->id === $cat->id)) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="sort" class="form-select" aria-label="Urutkan Produk" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Produk Terbaru</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>

                {{-- Submit Button --}}
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Active Filter Info Banner --}}
    @if(request()->hasAny(['search', 'category', 'sort']) && $products->isNotEmpty())
        <div class="alert alert-info py-2 px-3 mb-4 d-flex align-items-center justify-content-between small" role="alert">
            <div>
                🔍 Menampilkan <strong>{{ $products->total() }}</strong> produk hasil pencarian/filter.
            </div>
            <a href="{{ route('customer.shop.index') }}" class="text-decoration-none fw-semibold">Reset Filter</a>
        </div>
    @endif

    {{-- Product Grid --}}
    <div class="row g-4 mb-4">
        @forelse($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 transition card-hover">
                    {{-- Thumbnail & Badges --}}
                    <div class="position-relative">
                        <a href="{{ route('customer.shop.show', $product->slug) }}">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                     alt="Gambar {{ $product->name }}" 
                                     class="card-img-top rounded-top-3" 
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-top-3 d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                                    <span class="fs-1">📦</span>
                                </div>
                            @endif
                        </a>

                        {{-- Category Badge --}}
                        @if($product->category)
                            <a href="{{ route('customer.shop.category', $product->category->slug) }}" class="badge bg-dark text-decoration-none position-absolute top-0 start-0 m-2 px-2.5 py-1 small">
                                {{ $product->category->name }}
                            </a>
                        @endif

                        {{-- Stock Badge --}}
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($product->stock > 5)
                                <span class="badge bg-success shadow-sm">Stok: {{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark shadow-sm">Stok Menipis ({{ $product->stock }})</span>
                            @else
                                <span class="badge bg-danger shadow-sm">Stok Habis</span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
                                <a href="{{ route('customer.shop.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            @if($product->sku)
                                <small class="text-muted d-block mb-2 font-monospace">SKU: {{ $product->sku }}</small>
                            @endif
                            <h5 class="text-primary fw-bold mb-3 font-monospace">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </h5>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-2 border-top d-grid gap-2">
                            <a href="{{ route('customer.shop.show', $product->slug) }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                👁 Lihat Detail
                            </a>
                            <form action="{{ route('customer.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" 
                                        class="btn btn-light btn-sm text-primary border border-primary-subtle fw-semibold w-100 {{ $product->stock <= 0 ? 'disabled' : '' }}" 
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    🛒 + Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 py-5 px-4 text-center">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="fs-1">🔍</span>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Produk Tidak Ditemukan</h5>
                    <p class="text-muted mb-4">Tidak ada produk yang sesuai dengan kata kunci pencarian atau filter kategori Anda.</p>
                    <div>
                        <a href="{{ route('customer.shop.index') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                            Reset Pencarian & Filter
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
            </small>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif

</div>
@endsection
