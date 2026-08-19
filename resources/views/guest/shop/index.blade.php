@extends('layouts.guest.app')

@section('title', 'Katalog Produk Tokobii - Pusat Alat Tulis & Kantor')

@section('content')
<div class="container py-4">

    {{-- Hero Banner Section --}}
    <div class="tokobii-card mb-4 overflow-hidden border-0 text-white" style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);">
        <div class="p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-4 mb-md-0">
                    <span class="tokobii-badge bg-white text-blue-700 fw-bold px-3 py-1.5 text-uppercase mb-3 shadow-sm" style="font-size: 0.75rem; background-color: #ffffff; color: #1d4ed8;">
                        Pusat Alat Tulis Terlengkap & Resmi
                    </span>
                    <h1 class="fw-bold display-6 mb-2 text-white">Katalog Produk Tokobii</h1>
                    <p class="lead mb-4 text-blue-100 small" style="max-width: 560px; opacity: 0.95; line-height: 1.6;">
                        Temukan perlengkapan kantor, sekolah, dan alat tulis kualitas terbaik dengan kemudahan pembelian dan pengambilan langsung di toko kami.
                    </p>
                    
                    @guest
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('register') }}" class="btn btn-light text-blue-700 fw-bold px-4 py-2 shadow-sm rounded-3">
                                Daftar Sebagai Pelanggan
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light fw-semibold px-3 py-2 rounded-3">
                                Masuk Akun
                            </a>
                        </div>
                    @endguest
                </div>
                
                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-20 backdrop-blur d-inline-flex flex-column align-items-center justify-content-center">
                        <div class="rounded-circle bg-white text-blue-600 p-3 mb-2 shadow-sm" style="background-color: #ffffff; color: #2563eb;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="fw-bold text-white fs-6">Tokobii Store</span>
                        <span class="text-blue-100 small" style="font-size: 0.75rem;">Terpercaya Sejak 2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="tokobii-card p-3 mb-4">
        <form action="{{ route('shop') }}" method="GET" class="row g-2 align-items-center">
            
            {{-- Search Input --}}
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
                           placeholder="Cari alat tulis, buku, pulpen..." 
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
                    <span>Filter</span>
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
                    <div class="position-relative bg-slate-100 d-flex align-items-center justify-content-center p-3" style="height: 180px;">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid object-fit-contain h-100 w-100" 
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
                                    Tersedia ({{ $product->stock }})
                                </span>
                            @else
                                <span class="tokobii-badge tokobii-badge-danger" style="font-size: 0.6875rem;">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <span class="text-slate-400 text-uppercase fw-semibold mb-1" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            {{ $product->category->name ?? 'Umum' }}
                        </span>
                        <a href="{{ route('product.show', $product->slug) }}" class="fw-bold text-slate-900 text-decoration-none mb-1 text-truncate" title="{{ $product->name }}" style="font-size: 0.9rem;">
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

                        {{-- Price & Detail Button --}}
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top border-slate-100">
                            <span class="fw-bold text-blue-600 font-monospace" style="font-size: 0.9375rem; color: #2563eb;">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>

                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-tokobii-secondary btn-tokobii-sm py-1 px-2.5" title="Lihat Detail Produk">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Detail</span>
                            </a>
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
                    <p class="text-slate-500 small mb-3">Tidak ada produk yang sesuai dengan kriteria pencarian yang Anda masukkan.</p>
                    <a href="{{ route('shop') }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <span>Lihat Semua Produk</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>

</div>
@endsection