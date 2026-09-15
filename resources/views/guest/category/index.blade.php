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
            $sampleCategories = \App\Models\Category::where('status', 'active')
                ->withCount(['products' => function ($q) {
                    $q->where('status', 'active');
                }])
                ->get();
        @endphp

        @forelse($sampleCategories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="tokobii-category-card">
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
                                {{ $category->products_count }} Produk
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
                    <p class="text-slate-400 mb-0">Belum ada kategori yang tersedia saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection