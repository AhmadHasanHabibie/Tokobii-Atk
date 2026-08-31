@extends('layouts.admin.app')

@section('title', 'Laporan Kategori ' . $category->name . ' - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Laporan Masalah</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="tokobii-header-card mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Laporan Kategori: {{ $category->name }}</h1>
                <p class="text-slate-500 mb-0 small">Pilih produk untuk melihat tiket keluhan pelanggan pada kategori {{ strtolower($category->name) }}.</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Laporan</span>
            </a>
        </div>
    </div>

    {{-- Products Grid --}}
    <div class="row g-3 mb-4">
        @forelse($products as $product)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="tokobii-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="tokobii-badge tokobii-badge-neutral font-monospace" style="font-size: 0.7rem;">SKU: {{ $product->sku ?? '-' }}</span>
                            <span class="tokobii-badge {{ $product->reports_count > 0 ? 'tokobii-badge-warning' : 'tokobii-badge-success' }}">
                                {{ $product->reports_count }} tiket
                            </span>
                        </div>
                        <h5 class="fw-bold text-slate-900 mb-1" style="font-size: 1.05rem;">{{ $product->name }}</h5>
                        <p class="text-slate-500 mb-3" style="font-size: 0.8125rem;">Stok: <strong>{{ $product->stock }}</strong> unit · Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.reports.products.show', $product) }}" class="btn btn-tokobii-primary btn-tokobii-sm w-100">
                            <span>Lihat Laporan Produk →</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="tokobii-card p-5 text-center text-slate-400">
                    Belum ada produk dalam kategori ini.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
