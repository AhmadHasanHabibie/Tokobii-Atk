@extends('layouts.admin.app')

@section('title', 'Category Management - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                Category Management
            </li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Category Management</h2>
            <p class="text-muted mb-0">Kelola dan atur kategori produk e-commerce Tokobii secara efisien.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm rounded-2">
                📂 Tambah Category
            </a>
        </div>
    </div>

    {{-- Small Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Category</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalCategories) }}</h3>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-primary fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        📂
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Active Status</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($activeCategories) }}</h3>
                    </div>
                    <div class="bg-success-subtle rounded-circle p-3 text-success fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        ✅
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Inactive Status</span>
                        <h3 class="fw-bold text-secondary mb-0 mt-1">{{ number_format($inactiveCategories) }}</h3>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-secondary fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        ⏸️
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search, Filter, and Sorting Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama, slug, atau deskripsi..." 
                               value="{{ request('search') }}"
                               aria-label="Cari kategori"
                               aria-describedby="search-addon">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="status" class="form-select" aria-label="Filter status kategori">
                        <option value="">Status: Semua</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="sort" class="form-select" aria-label="Urutkan kategori">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Urutkan: Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Urutkan: Nama (Z-A)</option>
                        <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Urutkan: Status</option>
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'sort']))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary" title="Reset Filter" aria-label="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Category Content Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">

            @forelse($categories as $category)
                @if($loop->first)
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light sticky-top shadow-sm border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 5%;">No</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Thumbnail</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 20%;">Nama</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 20%;">Slug</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Status</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 12%;">Jumlah Produk</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 13%;">Tanggal Dibuat</th>
                                    <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif

                <tr>
                    <td class="ps-4 fw-semibold text-secondary">{{ $categories->firstItem() + $loop->index }}</td>
                    <td>
                        @if($category->thumbnail)
                            <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                 alt="Thumbnail {{ $category->name }}" 
                                 class="rounded shadow-sm border" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border" 
                                 style="width: 60px; height: 60px;" 
                                 title="No Thumbnail">
                                <span class="small text-secondary fw-semibold">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold text-dark d-inline-block text-truncate" style="max-width: 180px;" title="{{ $category->name }}">
                            {{ $category->name }}
                        </span>
                    </td>
                    <td>
                        <code class="text-secondary bg-light px-2 py-1 rounded small d-inline-block text-truncate" style="max-width: 180px;" title="{{ $category->slug }}">
                            {{ $category->slug }}
                        </code>
                    </td>
                    <td>
                        @if($category->status === 'active')
                            <span class="badge bg-success px-2.5 py-1.5 fw-normal">active</span>
                        @else
                            <span class="badge bg-secondary px-2.5 py-1.5 fw-normal">inactive</span>
                        @endif
                    </td>
                    <td class="text-center fw-semibold text-secondary">
                        {{ $category->products_count }}
                    </td>
                    <td class="text-muted small">
                        {{ $category->created_at ? $category->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="pe-4 text-end">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Kategori">
                            <a href="{{ route('admin.categories.show', $category) }}" 
                               class="btn btn-outline-info" 
                               title="Detail Category" 
                               aria-label="Detail {{ $category->name }}">
                                Detail
                            </a>
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="btn btn-outline-warning" 
                               title="Edit Category" 
                               aria-label="Edit {{ $category->name }}">
                                Edit
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $category->id }}" 
                                    title="Delete Category" 
                                    aria-label="Hapus {{ $category->name }}">
                                Delete
                            </button>
                        </div>

                        {{-- Delete Confirmation Modal --}}
                        <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $category->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-3">
                                    <div class="modal-header bg-danger text-white border-0 py-3">
                                        <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $category->id }}">Konfirmasi Hapus Category</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($category->thumbnail)
                                                <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                                     alt="Thumbnail {{ $category->name }}" 
                                                     class="rounded me-3 border shadow-sm" 
                                                     style="width: 65px; height: 65px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border me-3" 
                                                     style="width: 65px; height: 65px;">
                                                    <span class="small text-secondary">No Image</span>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $category->name }}</h6>
                                                <small class="text-muted">Slug: {{ $category->slug }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-2 text-secondary">Apakah Anda yakin ingin menghapus kategori produk ini?</p>
                                        <div class="alert alert-warning mb-0 py-2 small" role="alert">
                                            ⚠️ Tindakan ini bersifat permanen dan data yang telah dihapus tidak dapat dikembalikan.
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0 py-3">
                                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
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
                            <span class="fs-1">🔍</span>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'status']))
                        <h5 class="fw-bold text-dark mb-1">Kategori tidak ditemukan.</h5>
                        <p class="text-muted mb-4">Tidak ada kategori yang sesuai dengan kata kunci pencarian atau filter Anda.</p>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                            Reset Filter
                        </a>
                    @else
                        <h5 class="fw-bold text-dark mb-1">Belum ada kategori.</h5>
                        <p class="text-muted mb-4">Tambahkan kategori produk pertama Anda untuk mulai mengatur inventaris Tokobii.</p>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4 py-2 fw-semibold">
                            Tambah Category
                        </a>
                    @endif
                </div>
            @endforelse

        </div>

        {{-- Pagination Footer --}}
        @if($categories->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
                </small>
                <div>
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
