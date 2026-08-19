@extends('layouts.guest.auth')

@section('title', 'Masuk Akun - Tokobii')

@section('subtitle', 'Masuk ke akun Tokobii Anda untuk melanjutkan.')

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

<form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
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
                   autocomplete="username"
                   required
                   autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label mb-0">Kata Sandi</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-blue-600 hover-text-blue-700 fw-semibold">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>
        <div class="input-group">
            <span class="input-group-text bg-slate-50 border-slate-300 text-slate-400 ps-3">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </span>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control tokobii-input border-start-0 ps-1 @error('password') is-invalid @enderror"
                   placeholder="Masukkan kata sandi akun"
                   autocomplete="current-password"
                   required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="form-check mb-4">
        <input class="form-check-input"
               type="checkbox"
               id="remember"
               name="remember">
        <label class="form-check-label text-slate-500 small" for="remember">
            Ingat saya di perangkat ini
        </label>
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <span>Masuk ke Tokobii</span>
        </button>
    </div>

    {{-- Register Link --}}
    <div class="text-center">
        <span class="text-slate-500 small">Belum memiliki akun?</span>
        @if(Route::has('register'))
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-blue-600 hover-text-blue-700 ms-1 small">
                Daftar Sekarang
            </a>
        @endif
    </div>
</form>

@endsection
