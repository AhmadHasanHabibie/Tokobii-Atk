@extends('layouts.guest.app')

@section('title', 'Kategori Produk - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Beranda</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Kategori Produk</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="text-center mb-5">
        <span class="tokobii-badge tokobii-badge-info text-uppercase mb-2">Katalog Kategori</span>
        <h1 class="display-6 fw-bold text-slate-900 mb-2" style="color: #0f172a;">Kategori Produk Tokobii</h1>
        <p class="text-slate-500 small">Pilih kelompok produk alat tulis yang ingin Anda jelajahi.</p>
    </div>

    <div class="row g-4">
        @php
            $sampleCategories = \App\Models\Category::withCount('products')->get();
        @endphp

        @forelse($sampleCategories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="tokobii-card p-4 text-decoration-none text-slate-800 d-flex flex-column align-items-center text-center h-100">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px; background-color: #eff6ff; color: #2563eb;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <h5 class="fw-bold text-slate-900 mb-1" style="font-size: 1rem;">{{ $category->name }}</h5>
                    <span class="text-slate-400 small mb-3">{{ $category->products_count }} Produk</span>
                    <span class="btn btn-tokobii-secondary btn-tokobii-sm mt-auto w-100">
                        Lihat Produk
                    </span>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="tokobii-empty-state">
                    <p class="text-slate-400 mb-0">Belum ada kategori yang tersedia saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection