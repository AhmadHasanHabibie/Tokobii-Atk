@extends('layouts.customer.app')

@section('title', 'Edit Profil Customer - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('customer.profile.index') }}" class="text-decoration-none text-secondary">Profil Saya</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit Profil</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">✏️ Edit Profil Customer</h2>
        <p class="text-muted mb-0">Perbarui informasi data pribadi dan password akun Customer Tokobii Anda.</p>
    </div>

    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input id="name" 
                                   type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-secondary small">Alamat Email <span class="text-danger">*</span></label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($user->email_verified_at)
                                <span class="badge bg-success-subtle text-success border border-success-subtle mt-2">✓ Email Terverifikasi</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle mt-2">⚠️ Email Belum Terverifikasi</span>
                            @endif
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-dark mb-3">🔒 Ubah Password (Opsional)</h6>
                        <p class="text-muted small mb-3">Kosongkan bidang password jika Anda tidak ingin mengubah password saat ini.</p>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-secondary small">Password Baru</label>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 8 karakter...">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-secondary small">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ulangi password baru...">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-success fw-semibold px-4 shadow-sm">
                                💾 Simpan Perubahan
                            </button>
                            <a href="{{ route('customer.profile.index') }}" class="btn btn-outline-secondary px-3">
                                Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection