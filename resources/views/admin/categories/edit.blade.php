@extends('layouts.admin.app')

@section('title', 'Edit Kategori - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Kategori</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Edit</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Edit Kategori</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Perbarui detail kategori untuk <strong class="text-slate-800">{{ $category->name }}</strong>.</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-tokobii-secondary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Kategori</span>
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="tokobii-card">
        <div class="tokobii-card-header">
            <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Form Edit Kategori</h5>
        </div>
        <div class="p-4">

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="categoryForm" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Category Name --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="tokobii-input w-100 @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}" 
                               placeholder="Nama kategori..." 
                               autofocus 
                               required>
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="col-md-6">
                        <label for="slug" class="form-label">
                            Slug Kategori
                        </label>
                        <input type="text" 
                               name="slug" 
                               id="slug" 
                               class="tokobii-input w-100 bg-slate-100" 
                               value="{{ old('slug', $category->slug) }}" 
                               readonly>
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
                                  placeholder="Deskripsi kategori...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-md-6">
                        <label for="thumbnail" class="form-label">
                            Gambar Kategori
                        </label>
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail" 
                               class="tokobii-input w-100 @error('thumbnail') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg,image/webp">
                        <span class="text-slate-400 d-block mt-1" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah gambar. JPG, PNG, WEBP (Maks 2MB).</span>
                        @error('thumbnail')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror

                        {{-- Image Preview Container --}}
                        <div class="mt-3">
                            <span class="text-slate-400 d-block mb-1" style="font-size: 0.75rem;">Pratinjau Gambar:</span>
                            @if($category->thumbnail)
                                <img id="thumbnail-preview" 
                                     src="{{ asset('storage/' . $category->thumbnail) }}" 
                                     alt="Thumbnail {{ $category->name }}" 
                                     class="rounded-3 border border-slate-200" 
                                     style="max-height: 140px; object-fit: cover;">
                            @else
                                <img id="thumbnail-preview" 
                                     src="#" 
                                     alt="Pratinjau Gambar" 
                                     class="rounded-3 border border-slate-200 d-none" 
                                     style="max-height: 140px; object-fit: cover;">
                            @endif
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
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Aktif (Tampak di toko)</option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif (Tersembunyi)</option>
                        </select>
                        @error('status')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit & Cancel Buttons --}}
                    <div class="col-12 pt-3 border-top border-slate-100 d-flex gap-2">
                        <button type="submit" class="btn btn-tokobii-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                            <span id="btnText">Perbarui Kategori</span>
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-tokobii-secondary">
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

        if (categoryForm && submitBtn) {
            categoryForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                if (btnSpinner) btnSpinner.classList.remove('d-none');
            });
        }
    });
</script>
@endpush
