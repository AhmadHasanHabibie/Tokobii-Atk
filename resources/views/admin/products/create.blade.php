@extends('layouts.admin.app')

@section('title', 'Tambah Produk - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Produk</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Tambah</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Tambah Produk Baru</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Buat item baru dalam katalog inventaris Tokobii.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-tokobii-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Produk</span>
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="tokobii-card">
        <div class="tokobii-card-header">
            <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Form Spesifikasi Produk</h5>
        </div>
        <div class="p-4">

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
                @csrf

                <div class="row g-4">

                    {{-- Product Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="tokobii-input w-100 @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Contoh: Mouse Ergonomis Nirkabel..." 
                               autofocus 
                               required>
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">
                        <label for="slug" class="form-label">
                            Slug Produk
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               class="tokobii-input w-100 bg-slate-100" 
                               value="{{ old('slug') }}" 
                               placeholder="Dibuat otomatis dari nama..." 
                               readonly>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" 
                                id="category_id" 
                                class="tokobii-select w-100 @error('category_id') is-invalid @enderror" 
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div class="col-md-6">
                        <label for="sku" class="form-label">
                            SKU (Unit Penjualan/SKU) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="sku" 
                               id="sku" 
                               class="tokobii-input w-100 @error('sku') is-invalid @enderror" 
                               value="{{ old('sku') }}" 
                               placeholder="PRD-001" 
                               required>
                        @error('sku')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6">
                        <label for="price" class="form-label">
                            Harga (Rp) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-100 text-slate-500 font-monospace border-slate-300" style="border-radius: 10px 0 0 10px;">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   step="1" 
                                   min="1" 
                                   class="tokobii-input flex-1 @error('price') is-invalid @enderror" 
                                   style="border-radius: 0 10px 10px 0;"
                                   value="{{ old('price') }}" 
                                   placeholder="10000" 
                                   required>
                        </div>
                        <span class="text-slate-400 d-block mt-1" style="font-size: 0.75rem;">Masukkan harga dalam Rupiah tanpa desimal. Contoh: 10000.</span>
                        @error('price')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="col-md-6">
                        <label for="stock" class="form-label">
                            Jumlah Stok <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               name="stock" 
                               id="stock" 
                               min="0" 
                               step="1"
                               class="tokobii-input w-100 @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', 0) }}" 
                               placeholder="0" 
                               required>
                        @error('stock')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label">
                            Deskripsi
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="tokobii-input w-100 @error('description') is-invalid @enderror" 
                                  placeholder="Spesifikasi dan keunggulan detail produk...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-md-6">
                        <label for="thumbnail" class="form-label">
                            Gambar Produk
                        </label>
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail" 
                               class="tokobii-input w-100 @error('thumbnail') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg,image/webp">
                        <span class="text-slate-400 d-block mt-1" style="font-size: 0.75rem;">Format yang didukung: JPG, PNG, WEBP (Maks 2MB).</span>
                        @error('thumbnail')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror

                        {{-- Image Preview Container --}}
                        <div class="mt-3">
                            <span class="d-none text-slate-400 d-block mb-1" id="previewLabel" style="font-size: 0.75rem;">Pratinjau Gambar:</span>
                            <img id="thumbnail-preview" 
                                 src="#" 
                                 alt="Pratinjau Gambar" 
                                 class="rounded-3 border border-slate-200 d-none" 
                                 style="max-height: 140px; object-fit: cover;">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Status Publikasi <span class="text-danger">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                class="tokobii-select w-100 @error('status') is-invalid @enderror" 
                                required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif (Tampak di Toko)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif (Tersembunyi)</option>
                        </select>
                        @error('status')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit & Cancel Buttons --}}
                    <div class="col-12 pt-3 border-top border-slate-100 d-flex gap-2">
                        <button type="submit" class="btn btn-tokobii-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                            <span id="btnText">Simpan Produk</span>
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-tokobii-secondary">
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
        const priceInput = document.getElementById('price');
        const stockInput = document.getElementById('stock');
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnail-preview');
        const previewLabel = document.getElementById('previewLabel');
        const productForm = document.getElementById('productForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');

        function slugify(text) {
            return text.toString().toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        if (nameInput && slugInput) {
            nameInput.addEventListener('keyup', function () {
                slugInput.value = slugify(this.value);
            });

            nameInput.addEventListener('change', function () {
                slugInput.value = slugify(this.value);
            });
        }

        // Prevent decimal/exponent characters in price & stock input
        [priceInput, stockInput].forEach(function(input) {
            if (input) {
                input.addEventListener('keydown', function(e) {
                    if (['.', ',', 'e', 'E', '-', '+'].includes(e.key)) {
                        e.preventDefault();
                    }
                });
            }
        });

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
