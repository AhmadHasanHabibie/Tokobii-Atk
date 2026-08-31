@extends('layouts.guest.auth')

@section('title', 'Lupa Kata Sandi - Tokobii')

@section('subtitle', 'Reset kata sandi akun Tokobii Anda.')

@section('content')

<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
         style="width: 64px; height: 64px; background: linear-gradient(135deg, #fef3c7, #fde68a); box-shadow: 0 0 0 7px rgba(253,230,138,0.3);">
        <svg width="28" height="28" fill="none" stroke="#d97706" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
    </div>
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.25rem; letter-spacing: -0.01em;">Lupa Kata Sandi?</h2>
    <p class="text-slate-500 small mb-0" style="max-width: 300px; margin: 0 auto;">
        Masukkan email terdaftar dan kami akan mengirimkan tautan pemulihan.
    </p>
</div>

@if(session('status'))
    <div class="auth-alert auth-alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="fw-medium small">{{ session('status') }}</span>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label">Alamat Email Terdaftar</label>
        <div class="input-group">
            <span class="input-group-text">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </span>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control tokobii-input @error('email') is-invalid @enderror"
                   placeholder="nama@email.com"
                   autocomplete="email"
                   required
                   autofocus
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('email')
            <div class="text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Submit --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span>Kirim Tautan Pemulihan</span>
        </button>
    </div>

    {{-- Back --}}
    <div class="text-center" style="padding-top: 0.75rem; border-top: 1px solid #f1f5f9;">
        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold small text-slate-500" style="transition: color 0.15s;">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display: inline; vertical-align: -1px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Halaman Masuk
        </a>
    </div>
</form>

@endsection
