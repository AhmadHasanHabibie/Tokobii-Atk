@extends('layouts.customer.app')

@section('title', 'Katalog Produk - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Katalog Produk</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Katalog Produk Tokobii</h1>
                <p class="text-slate-500 mb-0 small">Temukan alat tulis, perlengkapan sekolah, dan kebutuhan kantor terlengkap.</p>
            </div>
            <div>
                <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm" title="Reset Filter">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Reset Filter</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Dedicated Filter Card --}}
    <div class="tokobii-filter-card">
        <form action="{{ route('customer.shop.index') }}" method="GET" class="row g-2 align-items-center">
            
            {{-- Search Input (Name / SKU) --}}
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-slate-50 border-slate-300 text-slate-400 ps-3">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control tokobii-input border-start-0 ps-1" 
                           placeholder="Cari berdasarkan nama atau kode SKU..." 
                           value="{{ request('search') }}"
                           aria-label="Cari Produk">
                </div>
            </div>

            {{-- Category Filter --}}
            <div class="col-12 col-sm-6 col-md-3">
                <select name="category" class="form-select tokobii-select" aria-label="Filter Kategori" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ (request('category') === $cat->slug || (isset($selectedCategory) && $selectedCategory->id === $cat->id)) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sorting --}}
            <div class="col-12 col-sm-6 col-md-2">
                <select name="sort" class="form-select tokobii-select" aria-label="Urutkan Produk" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </div>

            {{-- Submit Button --}}
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-tokobii-primary w-100">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Terapkan</span>
                </button>
            </div>

        </form>
    </div>

    {{-- Product Grid --}}
    <div class="row g-3 g-md-4 mb-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="tokobii-product-card">
                    {{-- Thumbnail --}}
                    <div class="product-image-container position-relative bg-slate-100 d-flex align-items-center justify-content-center p-3" style="height: 180px;">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image img-fluid object-fit-contain h-100 w-100" 
                                 loading="lazy">
                        @else
                            <div class="text-slate-400 text-center">
                                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mx-auto mb-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="d-block" style="font-size: 0.7rem;">Tanpa Foto</span>
                            </div>
                        @endif

                        {{-- Stock Badge --}}
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($product->stock > 0)
                                <span class="tokobii-badge tokobii-badge-success" style="font-size: 0.6875rem;">
                                    Stok: {{ $product->stock }}
                                </span>
                            @else
                                <span class="tokobii-badge tokobii-badge-danger" style="font-size: 0.6875rem;">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <span class="text-slate-400 text-uppercase fw-semibold mb-1" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            {{ $product->category->name ?? 'Umum' }}
                        </span>
                        <a href="{{ route('customer.shop.show', $product->slug) }}" class="fw-bold text-slate-900 text-decoration-none mb-1 text-truncate hover-text-blue-600" title="{{ $product->name }}" style="font-size: 0.9rem;">
                            {{ $product->name }}
                        </a>

                        {{-- Rating --}}
                        <div class="d-flex align-items-center gap-1 mb-2">
                            <div class="d-flex text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg width="13" height="13" fill="{{ $i <= round($product->reviews_avg_rating ?? 0) ? '#f59e0b' : 'none' }}" stroke="#f59e0b" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-slate-400" style="font-size: 0.75rem;">
                                ({{ $product->reviews_count ?? 0 }})
                            </span>
                        </div>

                        {{-- Price & Cart --}}
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top border-slate-100">
                            <span class="fw-bold text-blue-600 font-monospace" style="font-size: 0.9375rem; color: #2563eb;">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>

                            @if($product->stock > 0)
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm p-1.5 rounded-3" title="Tambah ke Keranjang">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="tokobii-empty-state">
                    <div class="tokobii-empty-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h5 class="fw-bold text-slate-900 mb-1">Produk Tidak Ditemukan</h5>
                    <p class="text-slate-500 small mb-3">Tidak ada produk yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.</p>
                    <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <span>Lihat Semua Produk</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination Card --}}
    @if($products->hasPages())
        <div class="tokobii-card p-3 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection
