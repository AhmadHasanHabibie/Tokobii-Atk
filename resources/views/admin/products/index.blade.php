@extends('layouts.admin.app')

@section('title', 'Product Management - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Product Management</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Product Management</h2>
            <p class="text-muted mb-0">Kelola dan atur katalog produk serta stok inventaris Tokobii.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm rounded-2">
                📦 Tambah Product
            </a>
        </div>
    </div>

    {{-- Mini Dashboard Statistic Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Product</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalProducts) }}</h3>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-primary fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        📦
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Active Status</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($activeProducts) }}</h3>
                    </div>
                    <div class="bg-success-subtle rounded-circle p-3 text-success fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        🟢
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Inactive Status</span>
                        <h3 class="fw-bold text-secondary mb-0 mt-1">{{ number_format($inactiveProducts) }}</h3>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-secondary fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        ⚫
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Out Of Stock</span>
                        <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($outOfStockProducts) }}</h3>
                    </div>
                    <div class="bg-danger-subtle rounded-circle p-3 text-danger fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        🔴
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama, SKU, deskripsi..." 
                               value="{{ request('search') }}"
                               aria-label="Cari produk"
                               aria-describedby="search-addon">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="category_id" class="form-select" aria-label="Filter kategori">
                        <option value="">Semua Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (request('category_id') == $category->id || request('category') == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="status" class="form-select" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Stock Filter --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="stock" class="form-select" aria-label="Filter stok">
                        <option value="">Semua Stock</option>
                        <option value="in_stock" {{ request('stock') === 'in_stock' ? 'selected' : '' }}>In Stock (> 20)</option>
                        <option value="low_stock" {{ request('stock') === 'low_stock' ? 'selected' : '' }}>Low Stock (1-20)</option>
                        <option value="out_of_stock" {{ request('stock') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="sort" class="form-select" aria-label="Urutkan produk">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                        <option value="stock_asc" {{ request('sort') === 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                        <option value="stock_desc" {{ request('sort') === 'stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                    </select>
                </div>

                {{-- Filter Action Buttons --}}
                <div class="col-12 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'category_id', 'category', 'status', 'stock', 'sort']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" title="Reset Filter" aria-label="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Active Filter Info Banner --}}
    @if(request()->hasAny(['search', 'category_id', 'category', 'status', 'stock']) && $products->isNotEmpty())
        <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between small" role="alert">
            <div>
                🔍 Filter aktif diterapkan. Menampilkan <strong>{{ $products->total() }}</strong> produk.
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-decoration-none fw-semibold">Reset Filter</a>
        </div>
    @endif

    {{-- Product Table Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">

            @forelse($products as $product)
                @if($loop->first)
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light sticky-top shadow-sm border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 4%;">No</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Thumbnail</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 20%;">Nama Product</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 12%;">Category</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">SKU</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-end" style="width: 12%;">Harga</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 12%;">Stock</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 8%;">Status</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Tanggal Dibuat</th>
                                    <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 12%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif

                <tr>
                    <td class="ps-4 fw-semibold text-secondary">{{ $products->firstItem() + $loop->index }}</td>
                    <td>
                        @if($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                 alt="Thumbnail {{ $product->name }}" 
                                 class="rounded shadow-sm border" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border" 
                                 style="width: 60px; height: 60px;" 
                                 title="No Image">
                                <span class="small text-secondary fw-semibold">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold text-dark d-inline-block text-truncate" style="max-width: 180px;" title="{{ $product->name }}">
                            {{ $product->name }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1.5 fw-normal">
                            {{ $product->category->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <code class="text-secondary bg-light px-2 py-1 rounded small fw-semibold">{{ $product->sku }}</code>
                    </td>
                    <td class="fw-bold text-dark text-end">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($product->stock > 20)
                            <span class="badge bg-success px-2.5 py-1.5 fw-normal" title="In Stock">
                                🟢 In Stock ({{ $product->stock }})
                            </span>
                        @elseif($product->stock >= 1)
                            <span class="badge bg-warning text-dark px-2.5 py-1.5 fw-normal" title="Low Stock">
                                🟡 Low Stock ({{ $product->stock }})
                            </span>
                        @else
                            <span class="badge bg-danger px-2.5 py-1.5 fw-normal" title="Out Of Stock">
                                🔴 Out Of Stock
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($product->status === 'active')
                            <span class="badge bg-success px-2.5 py-1.5 fw-normal">🟢 Active</span>
                        @else
                            <span class="badge bg-secondary px-2.5 py-1.5 fw-normal">⚫ Inactive</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $product->created_at ? $product->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="pe-4 text-end">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Produk">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="btn btn-outline-info" 
                               title="Detail Product" 
                               aria-label="Detail {{ $product->name }}">
                                Detail
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="btn btn-outline-warning" 
                               title="Edit Product" 
                               aria-label="Edit {{ $product->name }}">
                                Edit
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $product->id }}" 
                                    title="Delete Product" 
                                    aria-label="Hapus {{ $product->name }}">
                                Delete
                            </button>
                        </div>

                        {{-- Delete Confirmation Modal --}}
                        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-3">
                                    <div class="modal-header bg-danger text-white border-0 py-3">
                                        <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $product->id }}">Konfirmasi Hapus Product</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" 
                                                     alt="Thumbnail {{ $product->name }}" 
                                                     class="rounded me-3 border shadow-sm" 
                                                     style="width: 65px; height: 65px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border me-3" 
                                                     style="width: 65px; height: 65px;">
                                                    <span class="small text-secondary">No Image</span>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">{{ $product->name }}</h6>
                                                <small class="text-muted d-block">Category: {{ $product->category->name ?? '-' }}</small>
                                                <small class="text-muted d-block">SKU: {{ $product->sku }}</small>
                                                <small class="fw-bold text-primary">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-2 text-secondary">Apakah Anda yakin ingin menghapus produk ini dari katalog?</p>
                                        <div class="alert alert-warning mb-0 py-2 small" role="alert">
                                            ⚠️ Tindakan ini tidak dapat dibatalkan. Data produk yang dihapus akan terhapus secara permanen.
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0 py-3">
                                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger px-4 fw-semibold">Delete</button>
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
                {{-- Empty State --}}
                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="fs-1">📦</span>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'category_id', 'category', 'status', 'stock']))
                        <h5 class="fw-bold text-dark mb-1">Produk tidak ditemukan.</h5>
                        <p class="text-muted mb-4">Coba ubah kata kunci atau filter yang digunakan.</p>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                            Reset Filter
                        </a>
                    @else
                        <h5 class="fw-bold text-dark mb-1">Belum ada produk.</h5>
                        <p class="text-muted mb-4">Tambahkan produk pertama Anda untuk mulai mengisi katalog barang Tokobii.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                            Tambah Product
                        </a>
                    @endif
                </div>
            @endforelse

        </div>

        {{-- Pagination Footer --}}
        @if($products->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </small>
                <div>
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
