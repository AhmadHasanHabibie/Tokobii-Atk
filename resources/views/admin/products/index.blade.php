@extends('layouts.admin.app')

@section('title', 'Manajemen Produk - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">
                            Produk
                        </li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">
                    Manajemen Produk ATK
                </h1>
                <p class="text-slate-500 mb-0 small">
                    Kelola katalog produk toko, penyesuaian harga, dan tingkat stok inventaris Tokobii.
                </p>
            </div>

            <div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-tokobii-primary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Produk</span>
                </a>
            </div>
        </div>
    </div>


    {{-- Mini Dashboard Statistic Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Produk --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Total Produk
                        </span>

                        <h3 class="fw-bold text-slate-900 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($totalProducts) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #eff6ff;
                            color: #2563eb;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Status Aktif --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Status Aktif
                        </span>

                        <h3 class="fw-bold text-emerald-600 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($activeProducts) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #f0fdf4;
                            color: #16a34a;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Status Tidak Aktif --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Status Tidak Aktif
                        </span>

                        <h3 class="fw-bold text-slate-500 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($inactiveProducts) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #f8fafc;
                            color: #64748b;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Stok Habis --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Stok Habis
                        </span>

                        <h3 class="fw-bold text-rose-600 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($outOfStockProducts) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #fef2f2;
                            color: #dc2626;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Filter, Search, and Sort Bar --}}
    <div class="tokobii-card p-3 mb-4">

        <form action="{{ route('admin.products.index') }}"
              method="GET"
              class="row g-2 align-items-center">

            {{-- Search --}}
            <div class="col-12 col-md-3">

                <input type="text"
                       name="search"
                       class="tokobii-input w-100"
                       placeholder="Cari nama produk, SKU..."
                       value="{{ request('search') }}">

            </div>


            {{-- Category --}}
            <div class="col-12 col-sm-6 col-md-2">

                <select name="category_id"
                        class="tokobii-select w-100">

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            {{ (request('category_id') == $category->id || request('category') == $category->id) ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Status --}}
            <div class="col-12 col-sm-6 col-md-2">

                <select name="status"
                        class="tokobii-select w-100">

                    <option value="">
                        Semua Status
                    </option>

                    <option value="active"
                        {{ request('status') === 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="inactive"
                        {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                </select>

            </div>


            {{-- Stock --}}
            <div class="col-12 col-sm-6 col-md-2">

                <select name="stock"
                        class="tokobii-select w-100">

                    <option value="">
                        Semua Stok
                    </option>

                    <option value="in_stock"
                        {{ request('stock') === 'in_stock' ? 'selected' : '' }}>
                        Stok Tersedia (> 20)
                    </option>

                    <option value="low_stock"
                        {{ request('stock') === 'low_stock' ? 'selected' : '' }}>
                        Stok Menipis (1-20)
                    </option>

                    <option value="out_of_stock"
                        {{ request('stock') === 'out_of_stock' ? 'selected' : '' }}>
                        Stok Habis (0)
                    </option>

                </select>

            </div>


            {{-- Sort --}}
            <div class="col-12 col-sm-6 col-md-2">

                <select name="sort"
                        class="tokobii-select w-100">

                    <option value="latest"
                        {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                        Urutan: Terbaru
                    </option>

                    <option value="oldest"
                        {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                        Urutan: Terlama
                    </option>

                    <option value="name_asc"
                        {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                        Nama (A-Z)
                    </option>

                    <option value="name_desc"
                        {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                        Nama (Z-A)
                    </option>

                    <option value="price_asc"
                        {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                        Harga Terendah
                    </option>

                    <option value="price_desc"
                        {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                        Harga Tertinggi
                    </option>

                    <option value="stock_asc"
                        {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>
                        Stok Terendah
                    </option>

                    <option value="stock_desc"
                        {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>
                        Stok Tertinggi
                    </option>

                </select>

            </div>


            {{-- Filter Button --}}
            <div class="col-12 col-md-1 d-flex gap-1">

                <button type="submit"
                        class="btn btn-tokobii-primary w-100">
                    Filter
                </button>

                @if(request()->hasAny([
                    'search',
                    'category_id',
                    'category',
                    'status',
                    'stock',
                    'sort'
                ]))

                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-tokobii-secondary px-3"
                       title="Reset Filter">
                        ↺
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Product Table Container --}}
    <div class="tokobii-table-container">

        @forelse($products as $product)

            @if($loop->first)

                <div class="table-responsive">

                    <table class="tokobii-table">

                        <thead>

                            <tr>

                                <th style="width: 4%;">
                                    No
                                </th>

                                <th style="width: 8%;">
                                    Gambar
                                </th>

                                <th style="width: 22%;">
                                    Nama Produk
                                </th>

                                <th style="width: 14%;">
                                    Kategori
                                </th>

                                <th style="width: 10%;">
                                    SKU
                                </th>

                                <th class="text-end"
                                    style="width: 12%;">
                                    Harga
                                </th>

                                <th style="width: 12%;">
                                    Stok
                                </th>

                                <th style="width: 8%;">
                                    Status
                                </th>

                                <th style="width: 10%;">
                                    Dibuat
                                </th>

                                <th class="text-end"
                                    style="width: 10%;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

            @endif


            <tr>

                {{-- No --}}
                <td class="fw-semibold text-slate-400">
                    {{ $products->firstItem() + $loop->index }}
                </td>


                {{-- Gambar --}}
                <td>

                    @if($product->thumbnail)

                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                             alt="Thumbnail {{ $product->name }}"
                             class="rounded-3 border border-slate-200"
                             style="
                                width: 44px;
                                height: 44px;
                                object-fit: cover;
                             ">

                    @else

                        <div class="bg-slate-100 rounded-3 d-flex align-items-center justify-content-center text-slate-400 border border-slate-200"
                             style="
                                width: 44px;
                                height: 44px;
                                font-size: 0.75rem;
                             ">
                            Tidak Ada
                        </div>

                    @endif

                </td>


                {{-- Nama Produk --}}
                <td>

                    <span class="fw-semibold text-slate-900 d-inline-block text-truncate"
                          style="max-width: 220px;"
                          title="{{ $product->name }}">

                        {{ $product->name }}

                    </span>

                </td>


                {{-- Kategori --}}
                <td>

                    <span class="tokobii-badge tokobii-badge-info">
                        {{ $product->category->name ?? '-' }}
                    </span>

                </td>


                {{-- SKU --}}
                <td>

                    <code class="text-slate-600 bg-slate-100 px-2 py-1 rounded small font-monospace">
                        {{ $product->sku }}
                    </code>

                </td>


                {{-- Harga --}}
                <td class="fw-bold text-slate-900 text-end font-monospace">

                    Rp {{ number_format($product->price, 0, ',', '.') }}

                </td>


                {{-- Stok --}}
                <td>

                    @if($product->stock > 20)

                        <span class="tokobii-badge tokobii-badge-success">
                            Tersedia ({{ $product->stock }})
                        </span>

                    @elseif($product->stock >= 1)

                        <span class="tokobii-badge tokobii-badge-warning">
                            Stok Menipis ({{ $product->stock }})
                        </span>

                    @else

                        <span class="tokobii-badge tokobii-badge-danger">
                            Stok Habis
                        </span>

                    @endif

                </td>


                {{-- Status --}}
                <td>

                    @if($product->status === 'active' && (!$product->category || $product->category->status === 'active'))

                        <span class="tokobii-badge tokobii-badge-success">
                            Aktif
                        </span>

                    @elseif($product->status === 'active' && $product->category && $product->category->status === 'inactive')

                        <span class="tokobii-badge tokobii-badge-neutral" title="Kategori ({{ $product->category->name }}) sedang tidak aktif">
                            Tidak Aktif (Kategori)
                        </span>

                    @else

                        <span class="tokobii-badge tokobii-badge-neutral">
                            Tidak Aktif
                        </span>

                    @endif

                </td>


                {{-- Dibuat --}}
                <td class="text-slate-500"
                    style="font-size: 0.8125rem;">

                    {{ $product->created_at ? $product->created_at->format('d M Y') : '-' }}

                </td>


                {{-- Aksi --}}
                <td class="text-end">

                    <div class="d-inline-flex gap-2">

                        {{-- Tombol Lihat --}}
                        <a href="{{ route('admin.products.show', $product) }}"
                           class="btn btn-sm btn-outline-primary rounded-3 px-3 d-inline-flex align-items-center justify-content-center gap-1"
                           style="
                                border-color: #2563eb;
                                color: #2563eb;
                                min-height: 34px;
                           ">

                            <svg width="14"
                                 height="14"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>

                                <circle cx="12"
                                        cy="12"
                                        r="3"
                                        stroke-width="2">
                                </circle>

                            </svg>

                            <span>
                                Lihat
                            </span>

                        </a>


                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn btn-sm btn-outline-primary rounded-3 px-3 d-inline-flex align-items-center justify-content-center gap-1"
                           style="
                                border-color: #2563eb;
                                color: #2563eb;
                                min-height: 34px;
                           ">

                            <svg width="14"
                                 height="14"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z">
                                </path>

                            </svg>

                            <span>
                                Edit
                            </span>

                        </a>

                    </div>


                    {{-- Delete Confirmation Modal --}}
                    <div class="modal fade text-start"
                         id="deleteModal{{ $product->id }}"
                         tabindex="-1"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 shadow-lg rounded-3">

                                <div class="modal-header border-bottom py-3 px-4"
                                     style="border-color: #e2e8f0;">

                                    <h5 class="modal-title fw-bold text-slate-900"
                                        style="font-size: 1rem;">
                                        Konfirmasi Hapus Produk
                                    </h5>

                                    <button type="button"
                                            class="btn-close shadow-none"
                                            data-bs-dismiss="modal"
                                            aria-label="Tutup">
                                    </button>

                                </div>


                                <div class="modal-body p-4">

                                    <div class="d-flex align-items-center gap-3 mb-3">

                                        @if($product->thumbnail)

                                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded-3 border border-slate-200"
                                                 style="
                                                    width: 54px;
                                                    height: 54px;
                                                    object-fit: cover;
                                                 ">

                                        @endif

                                        <div>

                                            <h6 class="fw-bold mb-1 text-slate-900">
                                                {{ $product->name }}
                                            </h6>

                                            <span class="text-slate-400 d-block"
                                                  style="font-size: 0.75rem;">
                                                SKU: {{ $product->sku }}
                                            </span>

                                        </div>

                                    </div>

                                    <p class="text-slate-600 mb-0"
                                       style="font-size: 0.875rem;">
                                        Apakah Anda yakin ingin menghapus produk ini?
                                        Tindakan ini tidak dapat dibatalkan.
                                    </p>

                                </div>


                                <div class="modal-footer border-top py-3 px-4 bg-slate-50"
                                     style="border-color: #e2e8f0;">

                                    <button type="button"
                                            class="btn btn-tokobii-secondary"
                                            data-bs-dismiss="modal">
                                        Batal
                                    </button>

                                    <form action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger rounded-2 px-3 fw-semibold"
                                                style="border-radius: 10px;">
                                            Hapus Produk
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </td>

            </tr>


            @if($loop->last)

                        </tbody>

                    </table>

                </div>

            @endif

        @empty

            <div class="text-center py-5 px-4">

                <p class="text-slate-400 mb-3">
                    Tidak ada produk yang ditemukan sesuai pencarian atau filter Anda.
                </p>

                <a href="{{ route('admin.products.create') }}"
                   class="btn btn-tokobii-primary">
                    Tambah Produk
                </a>

            </div>

        @endforelse


        {{-- Pagination --}}
        @if($products->hasPages())

            <div class="p-3 border-top border-slate-100 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

                <span class="text-slate-400"
                      style="font-size: 0.8125rem;">

                    Menampilkan
                    {{ $products->firstItem() }}
                    -
                    {{ $products->lastItem() }}
                    dari
                    {{ $products->total() }}
                    produk

                </span>

                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>

            </div>

        @endif

    </div>

</div>
@endsection