@extends('layouts.guest.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('subtitle', 'Buat kata sandi baru untuk akun Tokobii Anda.')

@section('content')

<form method="POST"
      action="{{ route('password.store') }}">

    @csrf

    {{-- Password Reset Token --}}
    <input
        type="hidden"
        name="token"
        value="{{ $request->route('token') }}">

    {{-- Email --}}
    <div class="mb-3">

        <label
            for="email"
            class="form-label fw-semibold">

            Email

        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $request->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="Masukkan email"
            autocomplete="username"
            required
            autofocus>

        @error('email')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Password Baru --}}
    <div class="mb-3">

        <label
            for="password"
            class="form-label fw-semibold">

            Kata Sandi Baru

        </label>

        <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Masukkan password baru"
            autocomplete="new-password"
            required>

        @error('password')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Konfirmasi Password --}}
    <div class="mb-4">

        <label
            for="password_confirmation"
            class="form-label fw-semibold">

            Konfirmasi Kata Sandi

        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            placeholder="Masukkan ulang password baru"
            autocomplete="new-password"
            required>

        @error('password_confirmation')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Tombol Reset --}}
    <div class="d-grid mb-3">

        <button
            type="submit"
            class="btn btn-primary">

            Atur Ulang Kata Sandi

        </button>

    </div>

    {{-- Kembali Login --}}
    <div class="text-center">

        <a
            href="{{ route('login') }}"
            class="text-decoration-none">

            ← Kembali ke Login

        </a>

    </div>

</form>

@endsection
