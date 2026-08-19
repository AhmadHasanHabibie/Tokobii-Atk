@extends('layouts.guest.auth')

@section('title', 'Lupa Kata Sandi - Tokobii')

@section('subtitle', 'Masukkan email terdaftar untuk menerima tautan pemulihan kata sandi.')

@section('content')

@if(session('status'))
    <div class="alert alert-success border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert">
        <div class="d-flex align-items-center gap-2">
            <svg class="text-emerald-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="small fw-semibold">{{ session('status') }}</span>
        </div>
        <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-4">
        <label for="email" class="form-label">Alamat Email Terdaftar</label>
        <div class="input-group">
            <span class="input-group-text bg-slate-50 border-slate-300 text-slate-400 ps-3">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </span>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control tokobii-input border-start-0 ps-1 @error('email') is-invalid @enderror"
                   placeholder="nama@email.com"
                   autocomplete="email"
                   required
                   autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span>Kirim Tautan Pemulihan</span>
        </button>
    </div>

    {{-- Back to Login --}}
    <div class="text-center">
        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-slate-500 hover-text-blue-600 small">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Halaman Masuk
        </a>
    </div>
</form>

@endsection
