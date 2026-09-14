@extends('layouts.customer.app')

@section('title', 'Edit Profil - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.profile.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Profil Saya</a></li>
                <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Edit Profil</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Edit Profil Pelanggan</h1>
        <p class="text-slate-500 mb-0 small">Perbarui informasi data pribadi dan kata sandi akun Tokobii Anda.</p>
    </div>

    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="tokobii-card p-4 p-md-5">

                @if ($errors->any())
                    <div class="alert tokobii-alert tokobii-alert-error mb-4" role="alert">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="fw-semibold">Gagal memperbarui profil:</div>
                            <ul class="mb-0 ps-3 small mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.profile.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-rose-600">*</span></label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               class="form-control tokobii-input @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="form-label">Alamat Email <span class="text-rose-600">*</span></label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               class="form-control tokobii-input @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                        @enderror
                        @if($user->email_verified_at)
                            <span class="tokobii-badge tokobii-badge-success mt-2">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Email Terverifikasi
                            </span>
                        @else
                            <span class="tokobii-badge tokobii-badge-warning mt-2">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Email Belum Terverifikasi
                            </span>
                        @endif
                    </div>

                    <hr class="my-4 border-slate-100">

                    <h6 class="fw-bold text-slate-900 mb-1" style="font-size: 0.9375rem;">Ganti Kata Sandi (Opsional)</h6>
                    <p class="text-slate-400 small mb-3">Kosongkan bidang kata sandi jika Anda tidak ingin mengubah kata sandi saat ini.</p>

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi Baru</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               class="form-control tokobii-input @error('password') is-invalid @enderror" 
                               placeholder="Minimal 8 karakter..."
                               autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               class="form-control tokobii-input @error('password_confirmation') is-invalid @enderror" 
                               placeholder="Ulangi kata sandi baru..."
                               autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-tokobii-primary">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ route('customer.profile.index') }}" class="btn btn-tokobii-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection