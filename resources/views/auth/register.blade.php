@extends('layouts.guest.auth')

@section('title', 'Daftar Akun Baru - Tokobii')

@section('subtitle', 'Buat akun baru Tokobii Anda.')

@section('content')

<form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Nama --}}
    <div class="mb-3">
        <label for="name" class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">👤</span>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                   placeholder="Masukkan nama lengkap"
                   autocomplete="name"
                   required
                   autofocus>
        </div>
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label fw-semibold text-secondary small">Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">✉️</span>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                   placeholder="Masukkan email aktif"
                   autocomplete="username"
                   required>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <label for="password" class="form-label fw-semibold text-secondary small">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">🔒</span>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                   placeholder="Minimal 8 karakter"
                   autocomplete="new-password"
                   required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div class="mb-4">
        <label for="password_confirmation" class="form-label fw-semibold text-secondary small">Konfirmasi Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">🔑</span>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control border-start-0 ps-0 @error('password_confirmation') is-invalid @enderror"
                   placeholder="Masukkan ulang password Anda"
                   autocomplete="new-password"
                   required>
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2.5 fw-bold shadow-sm">
            Daftar Akun Tokobii
        </button>
    </div>

    {{-- Login Link --}}
    <div class="text-center">
        <span class="text-muted small">Sudah memiliki akun?</span>
        <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary ms-1 small">
            Login ke Akun
        </a>
    </div>
</form>

@endsection
