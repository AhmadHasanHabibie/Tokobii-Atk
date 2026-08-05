@extends('layouts.guest.auth')

@section('title', 'Register')

@section('subtitle', 'Buat akun baru Tokobii.')

@section('content')

<form method="POST"
      action="{{ route('register') }}">

    @csrf

    {{-- Nama --}}
    <div class="mb-3">

        <label
            for="name"
            class="form-label fw-semibold">

            Nama Lengkap

        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Masukkan nama lengkap"
            autocomplete="name"
            required
            autofocus>

        @error('name')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

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
            value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="Masukkan email"
            autocomplete="username"
            required>

        @error('email')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Password --}}
    <div class="mb-3">

        <label
            for="password"
            class="form-label fw-semibold">

            Password

        </label>

        <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Masukkan password"
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

            Konfirmasi Password

        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            placeholder="Masukkan ulang password"
            autocomplete="new-password"
            required>

        @error('password_confirmation')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Tombol Register --}}
    <div class="d-grid mb-3">

        <button
            type="submit"
            class="btn btn-primary">

            Register

        </button>

    </div>

    {{-- Link Login --}}
    <div class="text-center">

        <span class="text-muted">

            Sudah memiliki akun?

        </span>

        <a
            href="{{ route('login') }}"
            class="text-decoration-none fw-semibold">

            Login

        </a>

    </div>

</form>

@endsection