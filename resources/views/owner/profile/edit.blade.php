@extends('layouts.owner.app')

@section('title', 'Edit Profil Owner - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('owner.profile.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Profil Saya</a></li>
                <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Edit Profil</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Edit Profil Owner</h1>
        <p class="text-slate-500 mb-0 small">Perbarui informasi data diri dan akun Owner Tokobii Anda.</p>
    </div>

    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="tokobii-card p-4 p-md-5">

                <form method="POST" action="{{ route('owner.profile.update') }}">
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
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center gap-2 pt-2">
                        <button type="submit" class="btn btn-tokobii-primary">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="{{ route('owner.profile.index') }}" class="btn btn-tokobii-secondary">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection