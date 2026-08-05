@extends('layouts.guest.auth')

@section('title', 'Lupa Password')

@section('subtitle', 'Masukkan email Anda untuk menerima tautan reset password.')

@section('content')

<div class="mb-4 text-muted">

    Lupa password? Tidak masalah. Masukkan alamat email yang terdaftar,
    kemudian kami akan mengirimkan tautan untuk mengatur ulang password Anda.

</div>

@if(session('status'))

    <div class="alert alert-success">

        {{ session('status') }}

    </div>

@endif

<form method="POST"
      action="{{ route('password.email') }}">

    @csrf

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
            placeholder="Masukkan email Anda"
            required
            autofocus>

        @error('email')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    <div class="d-grid mb-3">

        <button
            type="submit"
            class="btn btn-primary">

            Kirim Link Reset Password

        </button>

    </div>

    <div class="text-center">

        <a
            href="{{ route('login') }}"
            class="text-decoration-none">

            ← Kembali ke Login

        </a>

    </div>

</form>

@endsection