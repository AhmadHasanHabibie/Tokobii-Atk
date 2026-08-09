@extends('layouts.guest.auth')

@section('title', 'Masuk - Tokobii')

@section('subtitle', 'Masuk ke akun Tokobii Anda.')

@section('content')

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

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
                   placeholder="Masukkan email terdaftar"
                   autocomplete="username"
                   required
                   autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label fw-semibold text-secondary small mb-0">Password</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary fw-semibold">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">🔒</span>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                   placeholder="Masukkan password Anda"
                   autocomplete="current-password"
                   required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="form-check mb-4">
        <input class="form-check-input"
               type="checkbox"
               id="remember"
               name="remember">
        <label class="form-check-label text-muted small" for="remember">
            Ingat Saya di Perangkat Ini
        </label>
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2.5 fw-bold shadow-sm">
            Masuk ke Tokobii
        </button>
    </div>

    {{-- Register Link --}}
    <div class="text-center">
        <span class="text-muted small">Belum memiliki akun?</span>
        @if(Route::has('register'))
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-primary ms-1 small">
                Daftar Sekarang
            </a>
        @endif
    </div>
</form>

@endsection
