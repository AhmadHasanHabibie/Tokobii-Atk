@extends('layouts.admin.app')

@section('title', 'Edit Category - Tokobii')

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
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Category</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">Edit Category</h2>
        <p class="text-muted mb-0">Perbarui informasi kategori produk <strong class="text-dark">{{ $category->name }}</strong>.</p>
    </div>

    {{-- Form Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark">Formulir Edit Kategori</h5>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="categoryForm" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Section 1: Informasi Utama --}}
                    <div class="col-12 border-bottom pb-2">
                        <h6 class="fw-bold text-primary mb-0">1. Informasi Utama</h6>
                    </div>

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold text-dark">
                            Nama Category <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}" 
                               placeholder="Masukkan nama kategori" 
                               autofocus 
                               required
                               aria-describedby="nameHelp">
                        <small id="nameHelp" class="text-muted d-block mt-1">Nama unik kategori (Maksimal 100 karakter).</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">
                        <label for="slug" class="form-label fw-semibold text-dark">
                            Slug Category
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               class="form-control bg-light" 
                               value="{{ old('slug', $category->slug) }}" 
                               placeholder="Otomatis terisi dari nama..." 
                               readonly
                               aria-describedby="slugHelp">
                        <small id="slugHelp" class="text-muted d-block mt-1">Slug URL diperbarui secara otomatis jika nama diketik ulang.</small>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold text-dark">
                            Deskripsi Kategori
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Masukkan deskripsi penjelasan kategori produk (opsional)"
                                  aria-describedby="descHelp">{{ old('description', $category->description) }}</textarea>
                        <small id="descHelp" class="text-muted d-block mt-1">Penjelasan singkat tentang isi kategori (Maksimal 1000 karakter).</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 2: Media & Status --}}
                    <div class="col-12 border-bottom pb-2 mt-4">
                        <h6 class="fw-bold text-primary mb-0">2. Media & Status</h6>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-md-6">
                        <label for="thumbnail" class="form-label fw-semibold text-dark">
                            Thumbnail Category
                        </label>
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail" 
                               class="form-control @error('thumbnail') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               aria-describedby="thumbHelp">
                        <small id="thumbHelp" class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengganti gambar. JPG, JPEG, PNG, WEBP (Max 2MB).</small>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Image Preview Container --}}
                        <div class="mt-3">
                            <span class="small text-muted d-block mb-1">Preview Thumbnail:</span>
                            @if($category->thumbnail)
                                <img id="thumbnail-preview" 
                                     src="{{ asset('storage/' . $category->thumbnail) }}" 
                                     alt="Thumbnail {{ $category->name }}" 
                                     class="img-thumbnail rounded shadow-sm" 
                                     style="max-height: 160px; object-fit: cover;">
                            @else
                                <img id="thumbnail-preview" 
                                     src="#" 
                                     alt="Thumbnail Preview" 
                                     class="img-thumbnail rounded shadow-sm d-none" 
                                     style="max-height: 160px; object-fit: cover;">
                            @endif
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
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active (Tampil di Toko)</option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive (Disembunyikan)</option>
                        </select>
                        <small id="statusHelp" class="text-muted d-block mt-1">Status menentukan apakah kategori ini dapat diakses oleh publik.</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit & Cancel Buttons --}}
                    <div class="col-12 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                            <span id="btnText">Update Kategori</span>
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary px-4 fw-semibold">
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
        const categoryForm = document.getElementById('categoryForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = document.getElementById('btnSpinner');

        // Automatic slug generation from name
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

        // Live image preview without reload when new image selected
        if (thumbnailInput && thumbnailPreview) {
            thumbnailInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        thumbnailPreview.src = e.target.result;
                        thumbnailPreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Loading state on form submit to prevent double-submit
        if (categoryForm && submitBtn) {
            categoryForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                if (btnSpinner) btnSpinner.classList.remove('d-none');
            });
        }
    });
</script>
@endpush
