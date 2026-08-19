@extends('layouts.customer.app')

@section('title', 'Edit Ulasan - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.reviews.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Ulasan Saya</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Edit Ulasan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            
            <div class="tokobii-card">
                <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1.05rem;">Edit Ulasan Produk</h5>
                    <a href="{{ route('customer.reviews.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
                <div class="p-4 p-md-5">

                    {{-- Product Box --}}
                    <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 mb-4 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #eff6ff; color: #2563eb;">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-900 mb-0" style="font-size: 0.9375rem;">{{ $review->product->name }}</h6>
                            <span class="text-slate-400 small">Kategori: {{ $review->product->category->name ?? 'Umum' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('customer.reviews.update', $review) }}">
                        @csrf
                        @method('PUT')

                        {{-- Rating Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-slate-800">Rating Kepuasan (1 - 5 Bintang) <span class="text-rose-600">*</span></label>
                            <select name="rating" class="form-select tokobii-select @error('rating') is-invalid @enderror" required>
                                <option value="5" @selected(old('rating', $review->rating) == 5)>5 Bintang (Sangat Puas)</option>
                                <option value="4" @selected(old('rating', $review->rating) == 4)>4 Bintang (Puas)</option>
                                <option value="3" @selected(old('rating', $review->rating) == 3)>3 Bintang (Cukup)</option>
                                <option value="2" @selected(old('rating', $review->rating) == 2)>2 Bintang (Kurang Puas)</option>
                                <option value="1" @selected(old('rating', $review->rating) == 1)>1 Bintang (Kecewa)</option>
                            </select>
                            @error('rating')
                                <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Comment --}}
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold text-slate-800">Ulasan & Komentar Anda <span class="text-rose-600">*</span></label>
                            <textarea id="comment" name="comment" rows="5" class="form-control tokobii-input @error('comment') is-invalid @enderror" required>{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Perubahan Ulasan</span>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
