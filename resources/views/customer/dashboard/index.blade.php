@extends('layouts.customer.app')

@section('title', 'Dashboard Pelanggan - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Welcome Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge tokobii-badge-info">Pelanggan Tokobii</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">
                    {{ $greeting }}, <span class="text-blue-600" style="color: #2563eb;">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-slate-500 mb-0 small">
                    Pusat belanja alat tulis kantor dan perlengkapan kerja terpercaya dengan kemudahan ambil di toko.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-primary btn-tokobii-sm shadow-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span>Mulai Belanja</span>
                </a>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Riwayat Pesanan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Hero Banner Card Section --}}
    <div class="tokobii-card mb-4 overflow-hidden border-0 text-white" style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);">
        <div class="p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-4 mb-md-0">
                    <span class="tokobii-badge bg-white text-blue-700 fw-bold px-3 py-1.5 text-uppercase mb-3 shadow-sm" style="font-size: 0.75rem; background-color: #ffffff; color: #1d4ed8;">
                        Pusat Alat Tulis & Perlengkapan Kantor
                    </span>
                    <h2 class="fw-bold display-6 mb-2 text-white">Lengkapi Kebutuhan ATK Anda</h2>
                    <p class="lead mb-4 text-blue-100 small" style="max-width: 580px; opacity: 0.95; line-height: 1.6;">
                        Temukan ratusan produk alat tulis kantor, kertas, buku, dan perlengkapan kerja berkualitas tinggi dengan kemudahan pemesanan serta pengambilan langsung di toko.
                    </p>
                    
                    {{-- Quick Search Form inside Hero --}}
                    <form action="{{ route('customer.shop.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2" style="max-width: 500px;">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 text-slate-400 ps-3">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-0 py-2.5 ps-1 shadow-none" 
                                   placeholder="Cari buku, pulpen, kertas, spidol..." 
                                   aria-label="Cari Produk">
                        </div>
                        <button type="submit" class="btn btn-dark fw-bold px-4 py-2.5 shadow-sm" style="background-color: #0f172a; border-color: #0f172a; border-radius: 12px;">
                            Cari
                        </button>
                    </form>
                </div>
                
                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-20 backdrop-blur d-inline-flex flex-column align-items-center justify-content-center">
                        <div class="rounded-circle bg-white text-blue-600 p-3 mb-2 shadow-sm" style="background-color: #ffffff; color: #2563eb;">
                            <svg width="42" height="42" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="fw-bold text-white fs-6">Tokobii Official</span>
                        <span class="text-blue-100 small" style="font-size: 0.75rem;">Stok Lengkap & Terverifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Section Card Container --}}
    <div class="tokobii-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-slate-900" style="font-size: 1.15rem;">Kategori Produk</h4>
                <p class="text-slate-500 small mb-0">Pilih kategori alat tulis yang Anda butuhkan.</p>
            </div>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <span>Lihat Semua ({{ $totalCategories }})</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @forelse($categories as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('customer.shop.category', $category->slug) }}" class="tokobii-category-card">
                        {{-- Category Image Container --}}
                        <div class="category-image-container">
                            @if($category->thumbnail)
                                <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                     alt="{{ $category->name }}" 
                                     class="category-image" 
                                     loading="lazy">
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center text-slate-400">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-1 shadow-sm" style="width: 44px; height: 44px; background-color: #dbeafe; color: #2563eb;">
                                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-slate-500 fw-medium" style="font-size: 0.7rem;">Kategori ATK</span>
                                </div>
                            @endif

                            {{-- Product Count Badge --}}
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="tokobii-badge bg-white text-slate-700 shadow-sm border border-slate-100 px-2 py-1" style="font-size: 0.6875rem; font-weight: 600;">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1 text-blue-600" style="display:inline; vertical-align:-1px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    {{ $category->products_count ?? $category->products()->count() }} Produk
                                </span>
                            </div>
                        </div>

                        {{-- Category Footer/Details --}}
                        <div class="category-body">
                            <div class="text-start pe-2 text-truncate">
                                <h6 class="fw-bold text-slate-900 mb-0 text-truncate" style="font-size: 0.9rem;" title="{{ $category->name }}">
                                    {{ $category->name }}
                                </h6>
                                <span class="text-slate-400" style="font-size: 0.725rem;">Lihat Produk &rarr;</span>
                            </div>
                            <div class="category-arrow-btn">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="tokobii-empty-state">
                        <span class="text-slate-400 small">Belum ada kategori yang tersedia saat ini.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- New Product Section Card Container --}}
    <div class="tokobii-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-slate-900" style="font-size: 1.15rem;">Produk Rekomendasi</h4>
                <p class="text-slate-500 small mb-0">Rekomendasi perlengkapan ATK terpopuler minggu ini.</p>
            </div>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <span>Semua Produk</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @forelse($newProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="tokobii-product-card" onclick="if(!event.target.closest('button, form, a')) window.location='{{ route('customer.shop.show', $product->slug) }}';">
                        {{-- Thumbnail Container --}}
                        <a href="{{ route('customer.shop.show', $product->slug) }}" class="product-image-container position-relative bg-slate-100 d-flex align-items-center justify-content-center p-3 text-decoration-none" style="height: 180px;">
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
                        </a>

                        {{-- Details --}}
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

                            {{-- Price & Add to Cart Action --}}
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top border-slate-100">
                                <span class="fw-bold text-blue-600 font-monospace" style="font-size: 0.9375rem; color: #2563eb;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>

                                @if($product->stock > 0)
                                    <form action="{{ route('customer.cart.add') }}" method="POST" class="m-0" onclick="event.stopPropagation();">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h6 class="fw-bold text-slate-800 mb-1">Belum Ada Produk Tersedia</h6>
                        <p class="text-slate-400 small mb-0">Silakan kembali lagi nanti untuk melihat produk terbaru dari Tokobii.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
