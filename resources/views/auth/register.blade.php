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
            <span class="input-group-text" style="border-right: none; border-radius: 12px 0 0 12px; background-color: #f8fafc; border-color: #cbd5e1;">
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
                   style="border-left: none; border-right: none; border-radius: 0; border-color: #cbd5e1;">
            <button type="button"
                    class="btn input-group-text toggle-password"
                    data-target="password"
                    tabindex="-1"
                    aria-label="Tampilkan atau sembunyikan kata sandi"
                    style="border-left: none; border-radius: 0 12px 12px 0; background-color: #ffffff; border-color: #cbd5e1; color: #94a3b8; cursor: pointer; transition: color 0.15s ease, background-color 0.15s ease;">
                <svg class="eye-open" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg class="eye-closed d-none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                </svg>
            </button>
        </div>
        @error('password')
            <div class="text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text" style="border-right: none; border-radius: 12px 0 0 12px; background-color: #f8fafc; border-color: #cbd5e1;">
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
                   style="border-left: none; border-right: none; border-radius: 0; border-color: #cbd5e1;">
            <button type="button"
                    class="btn input-group-text toggle-password"
                    data-target="password_confirmation"
                    tabindex="-1"
                    aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi"
                    style="border-left: none; border-radius: 0 12px 12px 0; background-color: #ffffff; border-color: #cbd5e1; color: #94a3b8; cursor: pointer; transition: color 0.15s ease, background-color 0.15s ease;">
                <svg class="eye-open" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg class="eye-closed d-none" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                </svg>
            </button>
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

@push('scripts')
<style>
    .toggle-password:hover {
        color: #2563eb !important;
        background-color: #f8fafc !important;
    }
    .input-group:focus-within .toggle-password {
        border-color: #3b82f6 !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                var targetId = this.getAttribute('data-target');
                var input = document.getElementById(targetId);
                if (!input) return;

                var eyeOpen = this.querySelector('.eye-open');
                var eyeClosed = this.querySelector('.eye-closed');

                if (input.type === 'password') {
                    input.type = 'text';
                    if (eyeOpen) eyeOpen.classList.add('d-none');
                    if (eyeClosed) eyeClosed.classList.remove('d-none');
                    this.setAttribute('aria-label', 'Sembunyikan kata sandi');
                } else {
                    input.type = 'password';
                    if (eyeOpen) eyeOpen.classList.remove('d-none');
                    if (eyeClosed) eyeClosed.classList.add('d-none');
                    this.setAttribute('aria-label', 'Tampilkan kata sandi');
                }
            });
        });
    });
</script>
@endpush

@endsection
