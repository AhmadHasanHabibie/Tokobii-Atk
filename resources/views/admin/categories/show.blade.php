@extends('layouts.admin.app')

@section('title', 'Detail Category - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-secondary">Category Management</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Detail Category</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Detail Category</h2>
            <p class="text-muted mb-0">Informasi lengkap mengenai kategori <strong class="text-dark">{{ $category->name }}</strong>.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning text-white px-4 fw-semibold shadow-sm">
                ✏️ Edit Kategori
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary px-4 fw-semibold">
                Kembali
            </a>
        </div>
    </div>

    {{-- Category Detail Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark">Informasi Kategori</h5>
        </div>
        <div class="card-body p-4">

            <div class="row g-4 align-items-start">

                {{-- Left Column: Large Thumbnail & Quick Status --}}
                <div class="col-12 col-md-4 text-center">
                    <div class="p-3 bg-light rounded-3 border">
                        @if($category->thumbnail)
                            <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                 alt="Thumbnail {{ $category->name }}" 
                                 class="img-fluid rounded shadow-sm mb-3" 
                                 style="max-height: 250px; width: 100%; object-fit: cover;">
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5 bg-white rounded border" style="min-height: 200px;">
                                <span class="fs-1 mb-2">🖼️</span>
                                <span class="small text-secondary fw-semibold">No Thumbnail Available</span>
                            </div>
                        @endif

                        <div class="mt-3">
                            <span class="text-muted small d-block mb-1">Status Publikasi:</span>
                            @if($category->status === 'active')
                                <span class="badge bg-success px-3 py-2 fw-normal fs-6">Active (Dipublikasikan)</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 fw-normal fs-6">Inactive (Disembunyikan)</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Information Details --}}
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 25%;">Nama Category</th>
                                    <td class="fw-bold fs-5 text-dark">: {{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Slug URL</th>
                                    <td>: <code class="text-secondary bg-light px-2 py-1 rounded fs-6">{{ $category->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Jumlah Produk</th>
                                    <td class="fw-semibold text-secondary">: {{ $category->products_count }} Produk</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold align-top">Deskripsi</th>
                                    <td class="text-dark">: {{ $category->description ?? 'Tidak ada deskripsi.' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Tanggal Dibuat</th>
                                    <td class="text-muted">: {{ $category->created_at ? $category->created_at->format('d F Y, H:i:s') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Terakhir Diperbarui</th>
                                    <td class="text-muted">: {{ $category->updated_at ? $category->updated_at->format('d F Y, H:i:s') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
