@extends('layouts.admin.app')

@section('title', 'Buat Akun Owner')

@section('content')

<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="text-muted small mb-2">
                Akun & Sistem
            </div>

            <h1 class="h3 fw-bold mb-1">
                Buat Akun Owner
            </h1>

            <p class="text-muted mb-0">
                Tambahkan akun Owner untuk mengelola operasional Tokobii.
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-secondary rounded-3 px-3">
            ← Kembali
        </a>
    </div>



    {{-- Form Card --}}
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">

            <div class="card border-0 shadow-sm rounded-4">

                {{-- Card Header --}}
                <div class="card-body p-4 p-lg-5">

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
                                Isi data akun yang akan digunakan oleh Owner.
                            </p>
                        </div>

                    </div>


                    {{-- Validation Error --}}
                    @if ($errors->any())

                        <div class="alert alert-danger border-0 rounded-3 mb-4">

                            <div class="fw-semibold mb-2">
                                Periksa kembali data berikut:
                            </div>

                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>

                    @endif


                    <form method="POST"
                          action="{{ route('admin.owners.store') }}">

                        @csrf


                        {{-- Name --}}
                        <div class="mb-4">

                            <label for="name"
                                   class="form-label fw-semibold">
                                Nama Owner
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
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
                                value="{{ old('email') }}"
                                class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                placeholder="owner@example.com"
                                autocomplete="email"
                                required
                            >

                            <div class="form-text">
                                Gunakan email yang aktif agar Owner dapat menggunakannya untuk akun Tokobii.
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
                                Password
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Password Confirmation --}}
                        <div class="mb-4">

                            <label for="password_confirmation"
                                   class="form-label fw-semibold">
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control form-control-lg rounded-3"
                                placeholder="Masukkan ulang password"
                                autocomplete="new-password"
                                required
                            >

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
                                        Akun ini otomatis dibuat dengan role
                                        <strong>Owner</strong>
                                        dan status
                                        <strong>Aktif</strong>.
                                    </div>
                                </div>

                            </div>

                        </div>


                        {{-- Actions --}}
                        <div class="d-flex flex-column flex-sm-row gap-2 pt-2">

                            <a href="{{ route('admin.dashboard') }}"
                               class="btn btn-light border rounded-3 px-4 py-2">
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary rounded-3 px-4 py-2 flex-grow-1 position-relative">

                                <svg width="18"
                                     height="18"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24"
                                     class="position-absolute"
                                     style="left: 18px;">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M5 13l4 4L19 7"/>

                                </svg>

                                <span class="d-block text-center">
                                    Buat Akun Owner
                                </span>

                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- DAFTAR AKUN OWNER --}}
    {{-- ========================================================= --}}

    <div class="row justify-content-center mt-4">
        <div class="col-12 col-xl-10">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-4">

                    {{-- List Header --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">

                                <h4 class="fw-bold mb-0">
                                    Daftar Akun Owner
                                </h4>

                                <span class="badge rounded-pill px-2 py-1"
                                      style="
                                        background: #eff6ff;
                                        color: #2563eb;
                                        font-size: 0.72rem;
                                      ">
                                    {{ $owners->count() }}
                                </span>

                            </div>

                            <p class="text-muted mb-0 small">
                                Daftar akun Owner yang sudah terdaftar di Tokobii.
                            </p>
                        </div>

                        <span class="badge rounded-pill px-3 py-2"
                              style="
                                background: #eff6ff;
                                color: #2563eb;
                                font-size: 0.8rem;
                              ">
                            {{ $owners->count() }} Owner
                        </span>

                    </div>


                    {{-- Jika belum ada Owner --}}
                    @if ($owners->isEmpty())

                        <div class="text-center py-5">

                            <div class="d-flex align-items-center justify-content-center mx-auto mb-3 rounded-circle"
                                 style="
                                    width: 64px;
                                    height: 64px;
                                    background: #f8fafc;
                                    color: #64748b;
                                 ">

                                <svg width="30"
                                     height="30"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.5"
                                          d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm6 3a4 4 0 014 4v1m-3-9a3 3 0 100-6"/>

                                </svg>

                            </div>

                            <h5 class="fw-semibold mb-2">
                                Belum Ada Akun Owner
                            </h5>

                            <p class="text-muted mb-0">
                                Akun Owner yang berhasil dibuat akan muncul di sini.
                            </p>

                        </div>

                    @else

                        {{-- Table --}}
                        <div class="table-responsive rounded-3 border">

                            <table class="table align-middle mb-0">

                                <thead style="background: #f8fafc;">

                                    <tr>

                                        <th class="text-muted fw-semibold small py-3 px-3"
                                            style="width: 65px;">
                                            No
                                        </th>

                                        <th class="text-muted fw-semibold small py-3"
                                            style="min-width: 190px;">
                                            Nama Owner
                                        </th>

                                        <th class="text-muted fw-semibold small py-3"
                                            style="min-width: 230px;">
                                            Email
                                        </th>

                                        <th class="text-muted fw-semibold small py-3"
                                            style="width: 125px;">
                                            Status
                                        </th>

                                        <th class="text-muted fw-semibold small py-3"
                                            style="width: 125px;">
                                            Dibuat
                                        </th>

                                        <th class="text-muted fw-semibold small py-3 px-3 text-end"
                                            style="width: 95px;">
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($owners as $owner)

                                        <tr>

                                            {{-- No --}}
                                            <td class="text-muted px-3">
                                                {{ $loop->iteration }}
                                            </td>


                                            {{-- Name --}}
                                            <td>

                                                <div class="d-flex align-items-center gap-2">

                                                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-semibold flex-shrink-0"
                                                         style="
                                                            width: 38px;
                                                            height: 38px;
                                                            background: #eff6ff;
                                                            color: #2563eb;
                                                            font-size: 0.9rem;
                                                         ">
                                                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                                                    </div>

                                                    <div class="min-w-0">

                                                        <div class="fw-semibold text-dark text-truncate"
                                                             style="max-width: 180px;">
                                                            {{ $owner->name }}
                                                        </div>

                                                        <div class="text-muted small">
                                                            Owner
                                                        </div>

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- Email --}}
                                            <td>

                                                <span class="text-muted"
                                                      style="font-size: 0.9rem;">
                                                    {{ $owner->email }}
                                                </span>

                                            </td>


                                            {{-- Status --}}
                                            <td>

                                                @if ($owner->status === 'active')

                                                    <span class="badge rounded-pill px-3 py-2"
                                                          style="
                                                            background: #ecfdf5;
                                                            color: #059669;
                                                          ">
                                                        Aktif
                                                    </span>

                                                @elseif ($owner->status === 'inactive')

                                                    <span class="badge rounded-pill px-3 py-2"
                                                          style="
                                                            background: #fffbeb;
                                                            color: #d97706;
                                                          ">
                                                        Nonaktif
                                                    </span>

                                                @elseif ($owner->status === 'banned')

                                                    <span class="badge rounded-pill px-3 py-2"
                                                          style="
                                                            background: #fef2f2;
                                                            color: #dc2626;
                                                          ">
                                                        Diblokir
                                                    </span>

                                                @else

                                                    <span class="badge rounded-pill px-3 py-2"
                                                          style="
                                                            background: #f1f5f9;
                                                            color: #64748b;
                                                          ">
                                                        {{ ucfirst($owner->status) }}
                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Created --}}
                                            <td>

                                                <div class="fw-medium"
                                                     style="font-size: 0.88rem;">
                                                    {{ $owner->created_at?->format('d M Y') }}
                                                </div>

                                                <div class="text-muted small">
                                                    {{ $owner->created_at?->format('H:i') }}
                                                </div>

                                            </td>


                                            {{-- Action --}}
                                            <td class="text-end px-3">

                                                <a href="{{ route('admin.owners.edit', $owner) }}"
                                                   class="btn btn-sm btn-outline-primary rounded-3 px-3 d-inline-flex align-items-center gap-1">

                                                    <svg width="14"
                                                         height="14"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 8.5-8.5z"/>

                                                    </svg>

                                                    Edit

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @endif

                </div>

            </div>

        </div>
    </div>

</div>

@endsection