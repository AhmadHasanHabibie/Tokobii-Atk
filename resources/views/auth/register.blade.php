@extends('layouts.guest.auth')

@section('title', 'Daftar Akun Baru - Tokobii')

@section('subtitle', 'Buat akun pelanggan Tokobii dan nikmati kemudahan belanja ATK.')

@section('content')

<div class="text-center mb-4">
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.3125rem; letter-spacing: -0.01em;">Buat Akun Baru</h2>
    <p class="text-slate-500 mb-0" style="font-size: 0.85rem;">Daftar gratis, belanja langsung.</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Nama --}}
    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </span>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   class="form-control tokobii-input @error('name') is-invalid @enderror"
                   placeholder="Masukkan nama lengkap"
                   autocomplete="name"
                   required
                   autofocus
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('name')
            <div class="text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

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
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('email')
            <div class="text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
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
                   placeholder="Minimal 8 karakter"
                   autocomplete="new-password"
                   required
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('password')
            <div class="text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </span>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control tokobii-input"
                   placeholder="Ulangi kata sandi"
                   autocomplete="new-password"
                   required
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
    </div>

    {{-- Submit --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <span>Daftar Akun Pelanggan</span>
        </button>
    </div>

    {{-- Login Link --}}
    <div class="text-center" style="padding-top: 0.75rem; border-top: 1px solid #f1f5f9;">
        <span class="text-slate-500 small">Sudah memiliki akun?</span>
        <a href="{{ route('login') }}" class="text-decoration-none fw-bold ms-1 small" style="color: #3b82f6;">
            Masuk Sekarang →
        </a>
    </div>
</form>

@endsection
