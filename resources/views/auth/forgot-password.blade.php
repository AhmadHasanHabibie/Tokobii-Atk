@extends('layouts.guest.auth')

@section('title', 'Lupa Kata Sandi')

@section('subtitle', 'Masukkan alamat email Anda untuk menerima tautan mengatur ulang kata sandi.')

@section('content')

<div class="mb-4 text-muted">

    Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.

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

            Alamat Email

        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="Masukkan alamat email Anda"
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

            Kirim Tautan Reset Kata Sandi

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
