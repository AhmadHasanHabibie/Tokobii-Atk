@extends('layouts.admin.app')

@section('title', 'Detail Product - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none text-secondary">Product Management</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Detail Product</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Detail Product</h2>
            <p class="text-muted mb-0">Informasi spesifikasi lengkap produk <strong class="text-dark">{{ $product->name }}</strong>.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning text-white px-4 fw-semibold shadow-sm">
                ✏️ Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4 fw-semibold">
                Kembali
            </a>
        </div>
    </div>

    {{-- Product Detail Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark">Informasi Detail Produk</h5>
        </div>
        <div class="card-body p-4">

            <div class="row g-4 align-items-start">

                {{-- Left Column: Large Thumbnail & Badges --}}
                <div class="col-12 col-md-4 text-center">
                    <div class="p-3 bg-light rounded-3 border">
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="Thumbnail {{ $product->name }}" 
                                 class="img-fluid rounded shadow-sm mb-3" 
                                 style="max-height: 280px; width: 100%; object-fit: cover;">
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5 bg-white rounded border mb-3" style="min-height: 220px;">
                                <span class="fs-1 mb-2">📦</span>
                                <span class="small text-secondary fw-semibold">No Thumbnail Available</span>
                            </div>
                        @endif

                        <div class="d-flex flex-column gap-2 mt-2">
                            <div>
                                <span class="text-muted small d-block mb-1">Status Publikasi:</span>
                                @if($product->status === 'active')
                                    <span class="badge bg-success px-3 py-2 fw-normal fs-6">🟢 Active (Tampil di Toko)</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 fw-normal fs-6">⚫ Inactive (Disembunyikan)</span>
                                @endif
                            </div>

                            <div class="mt-2">
                                <span class="text-muted small d-block mb-1">Status Stock:</span>
                                @if($product->stock > 20)
                                    <span class="badge bg-success px-3 py-2 fw-normal fs-6">🟢 In Stock ({{ $product->stock }})</span>
                                @elseif($product->stock >= 1)
                                    <span class="badge bg-warning text-dark px-3 py-2 fw-normal fs-6">🟡 Low Stock ({{ $product->stock }})</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 fw-normal fs-6">🔴 Out Of Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Detailed Product Information Table --}}
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 25%;">Nama Product</th>
                                    <td class="fw-bold fs-5 text-dark">: {{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Category</th>
                                    <td>: 
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1.5 fs-6 fw-normal">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">SKU</th>
                                    <td>: <code class="text-secondary bg-light px-2.5 py-1 rounded fs-6 fw-semibold">{{ $product->sku }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Slug URL</th>
                                    <td>: <code class="text-secondary bg-light px-2.5 py-1 rounded fs-6">{{ $product->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Harga Jual</th>
                                    <td class="fw-bold text-primary fs-4">: Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Jumlah Stock</th>
                                    <td class="fw-bold text-dark fs-5">: {{ number_format($product->stock) }} Unit</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold align-top">Deskripsi Product</th>
                                    <td class="text-dark">: {{ $product->description ?? 'Tidak ada deskripsi.' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Tanggal Dibuat</th>
                                    <td class="text-muted">: {{ $product->created_at ? $product->created_at->format('d F Y, H:i:s') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Terakhir Diperbarui</th>
                                    <td class="text-muted">: {{ $product->updated_at ? $product->updated_at->format('d F Y, H:i:s') : '-' }}</td>
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
