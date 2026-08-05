@extends('layouts.admin.app')

@section('title', 'Tambah Product - Tokobii')

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
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tambah Product</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">Tambah Product Baru</h2>
        <p class="text-muted mb-0">Isi formulir di bawah ini untuk menambahkan produk baru ke katalog Tokobii.</p>
    </div>

    {{-- Form Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark">Formulir Produk</h5>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
                @csrf

                <div class="row g-4">

                    {{-- Section 1: Informasi Utama --}}
                    <div class="col-12 border-bottom pb-2">
                        <h6 class="fw-bold text-primary mb-0">1. Informasi Produk</h6>
                    </div>

                    {{-- Nama Product --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold text-dark">
                            Nama Product <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Contoh: Buku Tulis Spiral A5" 
                               autofocus 
                               required
                               aria-describedby="nameHelp">
                        <small id="nameHelp" class="text-muted d-block mt-1">Nama lengkap produk (Maksimal 150 karakter).</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">
                        <label for="slug" class="form-label fw-semibold text-dark">
                            Slug Product
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               class="form-control bg-light" 
                               value="{{ old('slug') }}" 
                               placeholder="Otomatis terisi dari nama..." 
                               readonly
                               aria-describedby="slugHelp">
                        <small id="slugHelp" class="text-muted d-block mt-1">Slug URL dibuat secara otomatis berdasarkan nama produk.</small>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold text-dark">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" 
                                id="category_id" 
                                class="form-select @error('category_id') is-invalid @enderror" 
                                required
                                aria-describedby="categoryHelp">
                            <option value="">-- Pilih Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="categoryHelp" class="text-muted d-block mt-1">Pilih kategori yang sesuai untuk produk ini.</small>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div class="col-md-6">
                        <label for="sku" class="form-label fw-semibold text-dark">
                            SKU (Stock Keeping Unit) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="sku" 
                               id="sku" 
                               class="form-control @error('sku') is-invalid @enderror" 
                               value="{{ old('sku') }}" 
                               placeholder="PRD-001" 
                               required
                               aria-describedby="skuHelp">
                        <small id="skuHelp" class="text-muted d-block mt-1">Kode unik identifikasi stok (Maksimal 50 karakter).</small>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 2: Harga & Stok --}}
                    <div class="col-12 border-bottom pb-2 mt-4">
                        <h6 class="fw-bold text-primary mb-0">2. Harga & Inventaris</h6>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold text-dark">
                            Harga Product (Rp) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary font-monospace">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   step="0.01" 
                                   min="0" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price') }}" 
                                   placeholder="0" 
                                   required
                                   aria-describedby="priceHelp">
                        </div>
                        <small id="priceHelp" class="text-muted d-block mt-1">Harga jual produk dalam mata uang Rupiah.</small>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="col-md-6">
                        <label for="stock" class="form-label fw-semibold text-dark">
                            Jumlah Stock <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               name="stock" 
                               id="stock" 
                               min="0" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', 0) }}" 
                               placeholder="0" 
                               required
                               aria-describedby="stockHelp">
                        <small id="stockHelp" class="text-muted d-block mt-1">Jumlah stok barang fisik yang tersedia saat ini.</small>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold text-dark">
                            Deskripsi Product
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Masukkan deskripsi spesifikasi dan keunggulan produk..."
                                  aria-describedby="descHelp">{{ old('description') }}</textarea>
                        <small id="descHelp" class="text-muted d-block mt-1">Deskripsi rinci mengenai produk (Maksimal 3000 karakter).</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 3: Media & Status --}}
                    <div class="col-12 border-bottom pb-2 mt-4">
                        <h6 class="fw-bold text-primary mb-0">3. Media & Status</h6>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-md-6">
                        <label for="thumbnail" class="form-label fw-semibold text-dark">
                            Thumbnail Product
                        </label>
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail" 
                               class="form-control @error('thumbnail') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               aria-describedby="thumbHelp">
                        <small id="thumbHelp" class="text-muted d-block mt-1">Format gambar: JPG, JPEG, PNG, WEBP (Ukuran maks: 2MB).</small>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Image Preview Container --}}
                        <div class="mt-3">
                            <span class="d-none small text-muted d-block mb-1" id="previewLabel">Live Preview Thumbnail:</span>
                            <img id="thumbnail-preview" 
                                 src="#" 
                                 alt="Thumbnail Preview" 
                                 class="img-thumbnail rounded shadow-sm d-none" 
                                 style="max-height: 160px; object-fit: cover;">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold text-dark">
                            Status Publikasi <span class="text-danger">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                class="form-select @error('status') is-invalid @enderror" 
                                required
                                aria-describedby="statusHelp">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active (Tampil di Toko)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Disembunyikan)</option>
                        </select>
                        <small id="statusHelp" class="text-muted d-block mt-1">Status menentukan ketersediaan produk di katalog publik.</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit & Cancel Buttons --}}
                    <div class="col-12 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                            <span id="btnText">Simpan Product</span>
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4 fw-semibold">
                            Batal
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnail-preview');
        const previewLabel = document.getElementById('previewLabel');
        const productForm = document.getElementById('productForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');

        // Automatic slug generation from product name
        function slugify(text) {
            return text.toString().toLowerCase()
                .trim()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
        }

        if (nameInput && slugInput) {
            nameInput.addEventListener('keyup', function () {
                slugInput.value = slugify(this.value);
            });

            nameInput.addEventListener('change', function () {
                slugInput.value = slugify(this.value);
            });
        }

        // Live image preview without page reload
        if (thumbnailInput && thumbnailPreview) {
            thumbnailInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        thumbnailPreview.src = e.target.result;
                        thumbnailPreview.classList.remove('d-none');
                        if (previewLabel) previewLabel.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Loading state on form submit to prevent double-submission
        if (productForm && submitBtn) {
            productForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                if (btnSpinner) btnSpinner.classList.remove('d-none');
                if (btnText) btnText.textContent = 'Menyimpan...';
            });
        }
    });
</script>
@endpush
