@extends('layouts.guest.auth')

@section('title', 'Lupa Kata Sandi - Tokobii')

@section('subtitle', 'Reset kata sandi akun Tokobii Anda.')

@section('content')

<div class="mb-4 text-muted small">
    Masukkan alamat email yang terdaftar pada akun Tokobii Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
</div>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-4">
        <label for="email" class="form-label fw-semibold text-secondary small">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">✉️</span>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                   placeholder="Masukkan alamat email terdaftar"
                   required
                   autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2.5 fw-bold shadow-sm">
            Kirim Tautan Reset Kata Sandi
        </button>
    </div>

    {{-- Back to Login Link --}}
    <div class="text-center">
        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-secondary small">
            ← Kembali ke Halaman Login
        </a>
    </div>
</form>

@endsection
