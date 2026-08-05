@extends('layouts.guest.auth')

@section('title', 'Login')

@section('subtitle', 'Masuk ke akun Tokobii Anda.')

@section('content')

@if(session('status'))

    <div class="alert alert-success">

        {{ session('status') }}

    </div>

@endif

<form method="POST"
      action="{{ route('login') }}">

    @csrf

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
            required
            autofocus>

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
            autocomplete="current-password"
            required>

        @error('password')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- Remember Me --}}
    <div class="form-check mb-3">

        <input
            class="form-check-input"
            type="checkbox"
            id="remember"
            name="remember">

        <label
            class="form-check-label"
            for="remember">

            Remember Me

        </label>

    </div>

    {{-- Button --}}
    <div class="d-grid mb-3">

        <button
            type="submit"
            class="btn btn-primary">

            Login

        </button>

    </div>

    {{-- Links --}}
    <div class="d-flex justify-content-between align-items-center">

        @if(Route::has('password.request'))

            <a
                href="{{ route('password.request') }}"
                class="text-decoration-none">

                Lupa Password?

            </a>

        @endif

        @if(Route::has('register'))

            <a
                href="{{ route('register') }}"
                class="text-decoration-none">

                Daftar

            </a>

        @endif

    </div>

</form>

@endsection