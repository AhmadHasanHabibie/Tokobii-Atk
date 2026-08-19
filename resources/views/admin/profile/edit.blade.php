@extends('layouts.admin.app')

@section('title', 'Edit Profil Administrator - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.profile.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Profil</a></li>
                <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Edit Profil</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Edit Profil Administrator</h1>
        <p class="text-slate-500 mb-0 small">Perbarui nama lengkap dan alamat email akun administrator Tokobii Anda.</p>
    </div>

    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-12 col-lg-8">
            <div class="tokobii-card p-4 p-md-5">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-rose-600">*</span></label>
                        <input type="text"
                               name="name"
                               class="tokobii-input w-100 @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}"
                               required>
                        @error('name')
                            <div class="text-rose-600 mt-1 small">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alamat Email <span class="text-rose-600">*</span></label>
                        <input type="email"
                               name="email"
                               class="tokobii-input w-100 @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="text-rose-600 mt-1 small">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-tokobii-primary">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ route('admin.profile.index') }}" class="btn btn-tokobii-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection