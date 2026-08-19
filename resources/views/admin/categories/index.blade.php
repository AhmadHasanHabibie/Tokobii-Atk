@extends('layouts.admin.app')

@section('title', 'Manajemen Kategori - Tokobii')

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
                            Kategori
                        </li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">
                    Manajemen Kategori Produk
                </h1>
                <p class="text-slate-500 mb-0 small">
                    Kelola kategori produk dan taksonomi inventaris ATK Tokobii.
                </p>
            </div>

            <div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-tokobii-primary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Kategori</span>
                </a>
            </div>
        </div>
    </div>


    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Kategori --}}
        <div class="col-12 col-sm-4">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Kategori
                        </span>

                        <h3 class="fw-bold text-slate-900 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($totalCategories) }}
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
                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Status Aktif --}}
        <div class="col-12 col-sm-4">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Status Aktif
                        </span>

                        <h3 class="fw-bold text-emerald-600 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($activeCategories) }}
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
        <div class="col-12 col-sm-4">

            <div class="tokobii-card p-3">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Status Tidak Aktif
                        </span>

                        <h3 class="fw-bold text-slate-500 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($inactiveCategories) }}
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

    </div>


    {{-- Search & Filter Bar --}}
    <div class="tokobii-card p-3 mb-4">

        <form action="{{ route('admin.categories.index') }}"
              method="GET"
              class="row g-2 align-items-center">

            <div class="col-12 col-md-4">

                <input type="text"
                       name="search"
                       class="tokobii-input w-100"
                       placeholder="Cari nama kategori, slug..."
                       value="{{ request('search') }}">

            </div>


            <div class="col-12 col-sm-6 col-md-3">

                <select name="status"
                        class="tokobii-select w-100">

                    <option value="">
                        Status: Semua
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


            <div class="col-12 col-sm-6 col-md-3">

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
                        Urutan: Nama (A-Z)
                    </option>

                    <option value="name_desc"
                        {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                        Urutan: Nama (Z-A)
                    </option>

                    <option value="status"
                        {{ request('sort') === 'status' ? 'selected' : '' }}>
                        Urutan: Status
                    </option>

                </select>

            </div>


            <div class="col-12 col-md-2 d-flex gap-2">

                <button type="submit"
                        class="btn btn-tokobii-primary w-100">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'sort']))

                    <a href="{{ route('admin.categories.index') }}"
                       class="btn btn-tokobii-secondary px-3"
                       title="Reset">
                        ↺
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Category Content Table --}}
    <div class="tokobii-table-container">

        @forelse($categories as $category)

            @if($loop->first)

                <div class="table-responsive">

                    <table class="tokobii-table">

                        <thead>

                            <tr>

                                <th style="width: 5%;">
                                    No
                                </th>

                                <th style="width: 10%;">
                                    Gambar
                                </th>

                                <th style="width: 25%;">
                                    Nama
                                </th>

                                <th style="width: 20%;">
                                    Slug
                                </th>

                                <th style="width: 10%;">
                                    Status
                                </th>

                                <th class="text-center"
                                    style="width: 10%;">
                                    Produk
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
                    {{ $categories->firstItem() + $loop->index }}
                </td>


                {{-- Gambar --}}
                <td>

                    @if($category->thumbnail)

                        <img src="{{ asset('storage/' . $category->thumbnail) }}"
                             alt="Thumbnail {{ $category->name }}"
                             class="rounded-3 border border-slate-200"
                             style="
                                width: 48px;
                                height: 48px;
                                object-fit: cover;
                             ">

                    @else

                        <div class="bg-slate-100 rounded-3 d-flex align-items-center justify-content-center text-slate-400 border border-slate-200"
                             style="
                                width: 48px;
                                height: 48px;
                                font-size: 0.75rem;
                             ">
                            Tidak Ada
                        </div>

                    @endif

                </td>


                {{-- Nama --}}
                <td>

                    <span class="fw-semibold text-slate-900 d-inline-block text-truncate"
                          style="max-width: 220px;"
                          title="{{ $category->name }}">
                        {{ $category->name }}
                    </span>

                </td>


                {{-- Slug --}}
                <td>

                    <code class="text-slate-600 bg-slate-100 px-2 py-1 rounded small font-monospace">
                        {{ $category->slug }}
                    </code>

                </td>


                {{-- Status --}}
                <td>

                    @if($category->status === 'active')

                        <span class="tokobii-badge tokobii-badge-success">
                            Aktif
                        </span>

                    @else

                        <span class="tokobii-badge tokobii-badge-neutral">
                            Tidak Aktif
                        </span>

                    @endif

                </td>


                {{-- Produk --}}
                <td class="text-center fw-semibold text-slate-700">
                    {{ $category->products_count }}
                </td>


                {{-- Dibuat --}}
                <td class="text-slate-500"
                    style="font-size: 0.8125rem;">
                    {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}
                </td>


                {{-- Aksi --}}
                <td class="text-end">

                    <div class="d-inline-flex gap-2">

                        {{-- Tombol Lihat --}}
                        <a href="{{ route('admin.categories.show', $category) }}"
                           class="btn btn-sm btn-outline-primary rounded-3 px-3 d-inline-flex align-items-center gap-1">

                            <svg width="14"
                                 height="14"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                <circle cx="12"
                                        cy="12"
                                        r="3"
                                        stroke-width="2"/>

                            </svg>

                            <span>
                                Lihat
                            </span>

                        </a>


                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-sm btn-outline-primary rounded-3 px-3 d-inline-flex align-items-center gap-1">

                            <svg width="14"
                                 height="14"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"/>

                            </svg>

                            <span>
                                Edit
                            </span>

                        </a>

                    </div>


                    {{-- Delete Confirmation Modal --}}
                    <div class="modal fade text-start"
                         id="deleteModal{{ $category->id }}"
                         tabindex="-1"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content border-0 shadow-lg rounded-3">

                                <div class="modal-header border-bottom py-3 px-4"
                                     style="border-color: #e2e8f0;">

                                    <h5 class="modal-title fw-bold text-slate-900"
                                        style="font-size: 1rem;">
                                        Konfirmasi Hapus
                                    </h5>

                                    <button type="button"
                                            class="btn-close shadow-none"
                                            data-bs-dismiss="modal"
                                            aria-label="Tutup">
                                    </button>

                                </div>


                                <div class="modal-body p-4">

                                    <div class="d-flex align-items-center gap-3 mb-3">

                                        @if($category->thumbnail)

                                            <img src="{{ asset('storage/' . $category->thumbnail) }}"
                                                 alt="{{ $category->name }}"
                                                 class="rounded-3 border border-slate-200"
                                                 style="
                                                    width: 54px;
                                                    height: 54px;
                                                    object-fit: cover;
                                                 ">

                                        @endif

                                        <div>

                                            <h6 class="fw-bold mb-1 text-slate-900">
                                                {{ $category->name }}
                                            </h6>

                                            <span class="text-slate-400"
                                                  style="font-size: 0.75rem;">
                                                Slug: {{ $category->slug }}
                                            </span>

                                        </div>

                                    </div>

                                    <p class="text-slate-600 mb-0"
                                       style="font-size: 0.875rem;">
                                        Apakah Anda yakin ingin menghapus kategori ini?
                                        Produk yang terhubung dengan kategori ini mungkin akan terpengaruh.
                                    </p>

                                </div>


                                <div class="modal-footer border-top py-3 px-4 bg-slate-50"
                                     style="border-color: #e2e8f0;">

                                    <button type="button"
                                            class="btn btn-tokobii-secondary"
                                            data-bs-dismiss="modal">
                                        Batal
                                    </button>

                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger rounded-2 px-3 fw-semibold"
                                                style="border-radius: 10px;">
                                            Hapus Kategori
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
                    Tidak ada kategori yang ditemukan sesuai kriteria Anda.
                </p>

                <a href="{{ route('admin.categories.create') }}"
                   class="btn btn-tokobii-primary">
                    Tambah Kategori
                </a>

            </div>

        @endforelse


        {{-- Pagination --}}
        @if($categories->hasPages())

            <div class="p-3 border-top border-slate-100 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

                <span class="text-slate-400"
                      style="font-size: 0.8125rem;">
                    Menampilkan
                    {{ $categories->firstItem() }}
                    -
                    {{ $categories->lastItem() }}
                    dari
                    {{ $categories->total() }}
                    kategori
                </span>

                <div>
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>

            </div>

        @endif

    </div>

</div>
@endsection