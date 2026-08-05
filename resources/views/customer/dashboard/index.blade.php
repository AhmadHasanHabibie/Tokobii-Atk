@extends('layouts.customer.app')

@section('title', 'Dashboard Customer - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Welcome Header & Dynamic Greeting --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">
                {{ $greeting }}, <span class="text-primary">{{ Auth::user()->name }}</span>! 👋
            </h2>
            <p class="text-muted mb-0">
                Selamat datang di Tokobii. Pusat kebutuhan alat tulis, kantor, dan perlengkapan sekolah Anda.
            </p>
        </div>
    </div>

    {{-- Search Bar UI --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('customer.shop.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari alat tulis, buku, pulpen, atau perlengkapan kantor..." 
                               aria-label="Cari alat tulis"
                               aria-describedby="search-addon">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Cari Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hero Banner Section --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary text-white overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-3 mb-md-0">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 text-uppercase mb-2">Pusat Alat Tulis Terlengkap</span>
                    <h1 class="fw-bold display-6 mb-2">Lengkapi Kebutuhan Alat Tulis Anda</h1>
                    <p class="lead mb-4 opacity-90">
                        Temukan koleksi perlengkapan kantor, sekolah, dan alat tulis kualitas terbaik dengan harga terjangkau dan pelayanan terbaik di Tokobii.
                    </p>
                    <a href="{{ route('customer.shop.index') }}" class="btn btn-light text-primary btn-lg fw-bold px-4 shadow-sm">
                        🛍️ Belanja Sekarang
                    </a>
                </div>
                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <span class="display-1">📚✏️</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Section --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark">📂 Kategori Produk</h4>
                <p class="text-muted small mb-0">Pilih kategori alat tulis yang Anda butuhkan.</p>
            </div>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                Lihat Semua Kategori →
            </a>
        </div>

        <div class="row g-3">
            @forelse($categories as $category)
                <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                    <a href="{{ route('customer.shop.category', $category->slug) }}" class="card border-0 shadow-sm rounded-3 text-decoration-none text-dark h-100 card-hover transition">
                        <div class="card-body p-3 text-center">
                            <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                <span class="fs-4">📂</span>
                            </div>
                            <h6 class="fw-bold mb-1 text-truncate" title="{{ $category->name }}">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count ?? $category->products()->count() }} Produk</small>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 p-4 text-center text-muted">
                        <span>Belum ada kategori yang tersedia saat ini.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- New Product Section --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark">✨ Produk Terbaru</h4>
                <p class="text-muted small mb-0">Koleksi alat tulis terbaru yang baru saja hadir.</p>
            </div>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                Lihat Semua Produk →
            </a>
        </div>

        <div class="row g-3">
            @forelse($newProducts as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="position-relative">
                            <a href="{{ route('customer.shop.show', $product->slug) }}">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                         alt="Gambar {{ $product->name }}" 
                                         class="card-img-top rounded-top-3" 
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-top-3 d-flex align-items-center justify-content-center text-muted" style="height: 180px;">
                                        <span class="fs-1">📦</span>
                                    </div>
                                @endif
                            </a>

                            @if($product->category)
                                <a href="{{ route('customer.shop.category', $product->category->slug) }}" class="badge bg-dark text-decoration-none position-absolute top-0 start-0 m-2 px-2.5 py-1 small">
                                    {{ $product->category->name }}
                                </a>
                            @endif
                        </div>

                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
                                    <a href="{{ route('customer.shop.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <p class="text-primary fw-bold mb-2 font-monospace">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <small class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                    {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Stok Habis' }}
                                </small>
                                <a href="{{ route('customer.shop.show', $product->slug) }}" class="btn btn-sm btn-outline-primary fw-semibold" aria-label="Detail {{ $product->name }}">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 p-4 text-center text-muted">
                        <span>Belum ada produk terbaru saat ini.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Popular Product Section --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark">🔥 Produk Populer</h4>
                <p class="text-muted small mb-0">Pilihan favorit pelanggan Tokobii.</p>
            </div>
        </div>

        <div class="row g-3">
            @forelse($popularProducts as $product)
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{ route('customer.shop.show', $product->slug) }}">
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                             alt="Thumbnail {{ $product->name }}" 
                                             class="rounded border" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border" style="width: 60px; height: 60px;">
                                            <span class="fs-4">📦</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">
                                        <a href="{{ route('customer.shop.show', $product->slug) }}" class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <p class="text-primary fw-bold mb-0 small font-monospace">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3 p-3 text-center text-muted small">
                        <span>Belum ada data produk populer.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Store Information Section ("Kenapa Belanja di Tokobii?") --}}
    <div class="mb-5">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1 text-dark">Kenapa Belanja di Tokobii?</h4>
            <p class="text-muted small mb-0">Komitmen kami dalam memberikan kenyamanan dan kualitas terbaik.</p>
        </div>

        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-3">
                    <div class="card-body p-2">
                        <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fs-3">✅</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Produk Berkualitas</h6>
                        <p class="text-muted small mb-0">Seluruh alat tulis dijamin orisinal dan siap pakai.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-3">
                    <div class="card-body p-2">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fs-3">💰</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Harga Terjangkau</h6>
                        <p class="text-muted small mb-0">Penawaran harga terbaik untuk eceran maupun grosir.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-3">
                    <div class="card-body p-2">
                        <div class="bg-info-subtle text-info-emphasis rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fs-3">📦</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Pickup Cepat</h6>
                        <p class="text-muted small mb-0">Pesanan disiapkan instan untuk diambil langsung di toko.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-3">
                    <div class="card-body p-2">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <span class="fs-3">😊</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Pelayanan Ramah</h6>
                        <p class="text-muted small mb-0">Tim Tokobii siap membantu seluruh kebutuhan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Store Profile Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-3 p-3 text-center">
                            <span class="fs-2">🏪</span>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Toko Tokobii Store</h5>
                            <small class="text-muted">Pusat Alat Tulis & Kantor</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <span class="text-secondary small d-block">🕒 Jam Operasional</span>
                            <strong class="text-dark small">Senin - Sabtu: 08.00 - 20.00 WIB</strong>
                        </div>
                        <div class="col-12 col-sm-4">
                            <span class="text-secondary small d-block">📍 Alamat Toko</span>
                            <strong class="text-dark small">Jl. Utama Tokobii No. 88, Jakarta</strong>
                        </div>
                        <div class="col-12 col-sm-4">
                            <span class="text-secondary small d-block">📞 Kontak CS</span>
                            <strong class="text-dark small">+62 812-3456-7890</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection