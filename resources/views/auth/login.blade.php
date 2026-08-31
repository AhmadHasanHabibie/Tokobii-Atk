@extends('layouts.guest.auth')

@section('title', 'Masuk Akun - Tokobii')

@section('subtitle', 'Masuk ke akun Tokobii Anda untuk melanjutkan.')

@section('content')

@if(session('status'))
    <div class="auth-alert auth-alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="fw-medium small">{{ session('status') }}</span>
    </div>
@endif

<div class="text-center mb-4">
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.3125rem; letter-spacing: -0.01em;">Selamat Datang Kembali</h2>
    <p class="text-slate-500 mb-0" style="font-size: 0.85rem;">Masuk dengan email dan kata sandi Anda</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
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
                   autocomplete="username"
                   required
                   autofocus
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('email')
            <div class="text-rose-600 small mt-1 d-flex align-items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label mb-0">Kata Sandi</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold" style="color: #3b82f6; font-size: 0.78125rem; transition: color 0.15s;">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>
        <div class="input-group">
            <span class="input-group-text">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </span>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control tokobii-input @error('password') is-invalid @enderror"
                   placeholder="Masukkan kata sandi"
                   autocomplete="current-password"
                   required
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('password')
            <div class="text-rose-600 small mt-1 d-flex align-items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="remember" name="remember" style="border-color: #cbd5e1; border-radius: 5px;">
        <label class="form-check-label text-slate-500 small" for="remember">Ingat saya di perangkat ini</label>
    </div>

    {{-- Submit --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <span>Masuk ke Tokobii</span>
        </button>
    </div>

    {{-- Register Link --}}
    @if(Route::has('register'))
        <div class="text-center" style="padding-top: 0.75rem; border-top: 1px solid #f1f5f9;">
            <span class="text-slate-500 small">Belum memiliki akun?</span>
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold ms-1 small" style="color: #3b82f6;">
                Daftar Sekarang →
            </a>
        </div>
    @endif
</form>

@endsection
