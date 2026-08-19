@extends('layouts.customer.app')

@section('title', $product->name . ' - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.shop.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Katalog</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('customer.shop.category', $product->category->slug) }}" class="text-decoration-none text-slate-500 hover-text-blue-600">{{ $product->category->name }}</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active text-slate-800 fw-semibold text-truncate" style="max-width: 280px;" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm align-self-start align-self-md-auto">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Katalog</span>
            </a>
        </div>
    </div>

    {{-- Main Product Detail Card --}}
    <div class="tokobii-card p-4 p-md-5 mb-4">
        <div class="row g-4 g-lg-5">
            
            {{-- Product Image / Gallery Column --}}
            <div class="col-12 col-md-5 text-center">
                <div class="bg-slate-50 rounded-4 p-4 border border-slate-200 position-relative d-flex align-items-center justify-content-center" style="min-height: 340px;">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                             alt="Gambar {{ $product->name }}" 
                             class="img-fluid object-fit-contain" 
                             style="max-height: 320px;">
                    @else
                        <div class="py-5 text-slate-400 text-center">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mx-auto mb-2">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="small d-block">Tidak ada gambar produk</span>
                        </div>
                    @endif

                    {{-- Stock Badge Overlay --}}
                    <div class="position-absolute top-0 start-0 m-3">
                        @if($product->stock > 5)
                            <span class="tokobii-badge tokobii-badge-success px-3 py-1.5 shadow-sm">
                                Stok Tersedia ({{ $product->stock }})
                            </span>
                        @elseif($product->stock > 0)
                            <span class="tokobii-badge tokobii-badge-warning px-3 py-1.5 shadow-sm">
                                Stok Menipis ({{ $product->stock }})
                            </span>
                        @else
                            <span class="tokobii-badge tokobii-badge-danger px-3 py-1.5 shadow-sm">
                                Stok Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Product Details Column --}}
            <div class="col-12 col-md-7 d-flex flex-column justify-content-between">
                <div>
                    {{-- Category & SKU --}}
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        @if($product->category)
                            <a href="{{ route('customer.shop.category', $product->category->slug) }}" class="tokobii-badge tokobii-badge-info text-decoration-none">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                {{ $product->category->name }}
                            </a>
                        @endif
                        @if($product->sku)
                            <span class="text-slate-400 font-monospace" style="font-size: 0.75rem;">SKU: {{ $product->sku }}</span>
                        @endif
                    </div>

                    {{-- Product Name --}}
                    <h1 class="h3 fw-bold text-slate-900 mb-2" style="color: #0f172a;">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="d-flex text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="16" height="16" fill="{{ $i <= round($product->reviews_avg_rating ?? 0) ? '#f59e0b' : 'none' }}" stroke="#f59e0b" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            @endfor
                        </div>
                        <span class="fw-semibold text-slate-700 small">
                            {{ number_format($product->reviews_avg_rating ?? 0, 1) }}
                        </span>
                        <span class="text-slate-400 small">
                            ({{ $product->reviews_count ?? 0 }} ulasan pelanggan)
                        </span>
                    </div>

                    {{-- Price Box Card --}}
                    <div class="p-3.5 bg-slate-50 rounded-3 border border-slate-200 mb-4">
                        <span class="text-slate-500 small d-block mb-1">Harga Satuan</span>
                        <h2 class="text-blue-600 fw-bold mb-0 font-monospace" style="color: #2563eb;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </h2>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-slate-900 mb-2" style="font-size: 0.875rem;">Deskripsi Produk:</h6>
                        <div class="text-slate-600 small" style="line-height: 1.7;">
                            {!! nl2br(e($product->description ?? 'Tidak ada deskripsi rinci untuk produk ini.')) !!}
                        </div>
                    </div>
                </div>

                {{-- Add to Cart Form --}}
                <div class="pt-4 border-top border-slate-100">
                    @if($product->stock > 0)
                        <form action="{{ route('customer.cart.add') }}" method="POST" class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            {{-- Quantity Stepper --}}
                            <div class="d-flex align-items-center gap-2">
                                <label for="qtyInput" class="form-label fw-semibold text-slate-700 small mb-0">Jumlah:</label>
                                <div class="tokobii-qty-control">
                                    <button type="button" class="tokobii-qty-btn" onclick="let el=document.getElementById('qtyInput'); if(parseInt(el.value)>1) el.value=parseInt(el.value)-1;">-</button>
                                    <input type="number" id="qtyInput" name="qty" value="1" min="1" max="{{ $product->stock }}" class="tokobii-qty-input">
                                    <button type="button" class="tokobii-qty-btn" onclick="let el=document.getElementById('qtyInput'); if(parseInt(el.value)<{{ $product->stock }}) el.value=parseInt(el.value)+1;">+</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg flex-grow-1 shadow-sm">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </form>
                    @else
                        <div class="p-3 bg-rose-50 text-rose-800 rounded-3 border border-rose-200 small fw-semibold text-center">
                            Produk ini sedang habis. Silakan periksa kembali beberapa saat lagi.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Customer Reviews Section Card --}}
    <div class="tokobii-card p-4 p-md-5 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-slate-100">
            <div>
                <h4 class="fw-bold text-slate-900 mb-1" style="font-size: 1.15rem;">Ulasan Pelanggan</h4>
                <p class="text-slate-500 small mb-0">Ulasan asli dari pembeli terverifikasi di Tokobii.</p>
            </div>
            <div class="text-end">
                <span class="h3 fw-bold text-slate-900 font-monospace mb-0">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                <span class="text-slate-400 small">/ 5.0</span>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($reviews as $review)
                <div class="p-3 bg-slate-50 rounded-3 border border-slate-200">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-blue-100 text-blue-700 fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.75rem; background-color: #eff6ff; color: #2563eb;">
                                {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-bold text-slate-900 mb-0" style="font-size: 0.875rem;">{{ $review->user->name ?? 'Pelanggan' }}</h6>
                                <span class="text-slate-400" style="font-size: 0.6875rem;">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="d-flex text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="14" height="14" fill="{{ $i <= $review->rating ? '#f59e0b' : 'none' }}" stroke="#f59e0b" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-slate-700 small mb-0" style="line-height: 1.6;">{{ $review->comment }}</p>
                </div>
            @empty
                <div class="text-center py-4 text-slate-400 small">
                    Belum ada ulasan untuk produk ini.
                </div>
            @endforelse
        </div>

        @if($reviews->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

    {{-- Related Products Section Card --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="tokobii-card p-4">
            <h4 class="fw-bold text-slate-900 mb-3" style="font-size: 1.15rem;">Produk Terkait</h4>
            <div class="row g-3 g-md-4">
                @foreach($relatedProducts as $rel)
                    <div class="col-6 col-md-3">
                        <div class="tokobii-product-card">
                            <div class="product-image-container position-relative bg-slate-100 d-flex align-items-center justify-content-center p-3" style="height: 150px;">
                                @if($rel->thumbnail)
                                    <img src="{{ asset('storage/' . $rel->thumbnail) }}" alt="{{ $rel->name }}" class="product-image img-fluid object-fit-contain h-100 w-100" loading="lazy">
                                @else
                                    <div class="text-slate-400 text-center">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mx-auto mb-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <a href="{{ route('customer.shop.show', $rel->slug) }}" class="fw-bold text-slate-900 text-decoration-none mb-1 text-truncate small hover-text-blue-600" title="{{ $rel->name }}">
                                    {{ $rel->name }}
                                </a>
                                <span class="fw-bold text-blue-600 font-monospace small mt-auto" style="color: #2563eb;">
                                    Rp {{ number_format($rel->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
