@extends('layouts.guest.auth')

@section('title', 'Konfirmasi Password')

@section('subtitle', 'Masukkan password Anda untuk melanjutkan.')

@section('content')

<form method="POST"
      action="{{ route('password.confirm') }}">

    @csrf

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
            required
            autocomplete="current-password"
            autofocus>

        @error('password')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    <div class="d-grid">

        <button
            type="submit"
            class="btn btn-primary">

            Konfirmasi Password

        </button>

    </div>

</form>

@endsection