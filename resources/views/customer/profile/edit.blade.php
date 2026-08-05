@extends('layouts.customer.app')

@section('title', 'Edit Profile')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            Edit Profile
        </h2>

        <p class="text-muted mb-0">
            Perbarui informasi akun Customer Tokobii.
        </p>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form method="POST"
                  action="{{ route('customer.profile.update') }}">

                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">

                    <label for="name" class="form-label">

                        Nama

                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus>

                    @error('name')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                {{-- Email --}}
                <div class="mb-4">

                    <label for="email" class="form-label">

                        Email

                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}"
                        required>

                    @error('email')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success">

                        💾 Simpan Perubahan

                    </button>

                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="btn btn-outline-secondary">

                        ← Kembali ke Dashboard

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection