@extends('layouts.admin.app')

@section('title', 'Edit Akun Owner')

@section('content')

<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="text-muted small mb-2">
                Akun & Sistem
            </div>

            <h1 class="h3 fw-bold mb-1">
                Edit Akun Owner
            </h1>

            <p class="text-muted mb-0">
                Perbarui informasi akun Owner Tokobii.
            </p>
        </div>

        <a href="{{ route('admin.owners.create') }}"
           class="btn btn-outline-secondary rounded-3 px-3">
            ← Kembali
        </a>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4">
            {{ session('success') }}
        </div>
    @endif


    {{-- Form Card --}}
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">

                    {{-- Card Header --}}
                    <div class="d-flex align-items-center gap-3 mb-4">

                        <div class="d-flex align-items-center justify-content-center rounded-4"
                             style="
                                width: 52px;
                                height: 52px;
                                background: #eff6ff;
                                color: #2563eb;
                             ">

                            <svg width="26"
                                 height="26"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM4 21a8 8 0 0116 0M19 8v6m3-3h-6"/>

                            </svg>

                        </div>

                        <div>

                            <h5 class="fw-bold mb-1">
                                Informasi Akun Owner
                            </h5>

                            <p class="text-muted small mb-0">
                                Perbarui data akun yang digunakan oleh Owner.
                            </p>

                        </div>

                    </div>


                    {{-- Validation Error --}}
                    @if($errors->any())

                        <div class="alert alert-danger border-0 rounded-3 mb-4">

                            <div class="fw-semibold mb-2">
                                Periksa kembali data berikut:
                            </div>

                            <ul class="mb-0 ps-3">

                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- Form --}}
                    <form method="POST"
                          action="{{ route('admin.owners.update', $owner) }}">

                        @csrf
                        @method('PUT')


                        {{-- Nama --}}
                        <div class="mb-4">

                            <label for="name"
                                   class="form-label fw-semibold">
                                Nama Owner
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $owner->name) }}"
                                class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama Owner"
                                autocomplete="name"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="mb-4">

                            <label for="email"
                                   class="form-label fw-semibold">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $owner->email) }}"
                                class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                placeholder="owner@example.com"
                                autocomplete="email"
                                required
                            >

                            <div class="form-text">
                                Gunakan email aktif yang dapat digunakan oleh Owner untuk login.
                            </div>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Password --}}
                        <div class="mb-4">

                            <label for="password"
                                   class="form-label fw-semibold">
                                Password Baru
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah password"
                                autocomplete="new-password"
                            >

                            <div class="form-text">
                                Kosongkan jika password tidak ingin diubah.
                            </div>

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">

                            <label for="password_confirmation"
                                   class="form-label fw-semibold">
                                Konfirmasi Password Baru
                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control form-control-lg rounded-3"
                                placeholder="Masukkan ulang password baru"
                                autocomplete="new-password"
                            >

                        </div>


                        {{-- Status --}}
<div class="mb-4">

    <label for="status"
           class="form-label fw-semibold">
        Status Akun
    </label>

    <select
        id="status"
        name="status"
        class="form-select form-select-lg rounded-3 @error('status') is-invalid @enderror"
        required
    >

        <option value="active"
            {{ old('status', $owner->status) === 'active' ? 'selected' : '' }}>
            Aktif
        </option>

        <option value="inactive"
            {{ old('status', $owner->status) === 'inactive' ? 'selected' : '' }}>
            Non-Aktif
        </option>

    </select>

    @error('status')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


                        {{-- Role Information --}}
                        <div class="p-3 rounded-3 mb-4"
                             style="
                                background: #f8fafc;
                                border: 1px solid #e2e8f0;
                             ">

                            <div class="d-flex align-items-start gap-3">

                                <div class="text-primary">

                                    <svg width="20"
                                         height="20"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>

                                    </svg>

                                </div>

                                <div>

                                    <div class="fw-semibold mb-1">
                                        Role Owner
                                    </div>

                                    <div class="text-muted small">
                                        Akun ini memiliki role
                                        <strong>Owner</strong>.
                                        Role tidak dapat diubah dari halaman ini.
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Action --}}
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-2">

                            <a href="{{ route('admin.owners.create') }}"
                               class="btn btn-light border rounded-3 px-4 py-2">
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary rounded-3 px-4 py-2 flex-grow-1">

                                <svg width="18"
                                     height="18"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24"
                                     class="me-1">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M5 13l4 4L19 7"/>

                                </svg>

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection