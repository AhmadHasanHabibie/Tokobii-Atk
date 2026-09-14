@extends('layouts.guest.auth')

@section('title', 'Masuk Akun - Tokobii')

@section('subtitle', 'Masuk ke akun Tokobii Anda untuk melanjutkan.')

@section('content')

@if(session('status'))
    <div class="auth-alert auth-alert-success">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="fw-medium small">{{ session('status') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="p-3 mb-3 d-flex align-items-center gap-2 rounded-xl" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 12px; font-size: 0.8125rem;">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(app()->isDownForMaintenance())
    <div class="p-3 mb-4 d-flex align-items-start gap-2.5 shadow-xs" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; border-radius: 14px;">
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark flex-shrink-0" style="width: 30px; height: 30px; margin-top: 2px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="min-w-0">
            <div class="fw-bold text-amber-950 mb-0.5" style="font-size: 0.84rem;">Mode Pemeliharaan Sedang Aktif</div>
            <div class="text-amber-900" style="font-size: 0.775rem; line-height: 1.45;">
                Saat ini sistem Tokobii sedang dalam proses pemeliharaan berkala untuk peningkatan performa dan keandalan layanan. Akses masuk ditangguhkan sementara waktu.
            </div>
        </div>
    </div>
@endif

<div class="text-center mb-4">
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.3125rem; letter-spacing: -0.01em;">Selamat Datang Kembali</h2>
    <p class="text-slate-500 mb-0" style="font-size: 0.85rem;">Masuk dengan email dan kata sandi Anda</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </span>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control tokobii-input @error('email') is-invalid @enderror"
                   placeholder="nama@email.com"
                   autocomplete="username"
                   required
                   autofocus
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('email')
            <div class="text-rose-600 small mt-1 d-flex align-items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label mb-0">Kata Sandi</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold" style="color: #3b82f6; font-size: 0.78125rem; transition: color 0.15s;">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>
        <div class="input-group">
            <span class="input-group-text">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </span>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control tokobii-input @error('password') is-invalid @enderror"
                   placeholder="Masukkan kata sandi"
                   autocomplete="current-password"
                   required
                   style="border-left: none; border-radius: 0 12px 12px 0;">
        </div>
        @error('password')
            <div class="text-rose-600 small mt-1 d-flex align-items-center gap-1">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Submit --}}
    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <span>Masuk ke Tokobii</span>
        </button>
    </div>

    {{-- Register Link --}}
    @if(Route::has('register'))
        <div class="text-center" style="padding-top: 0.75rem; border-top: 1px solid #f1f5f9;">
            <span class="text-slate-500 small">Belum memiliki akun?</span>
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold ms-1 small" style="color: #3b82f6;">
                Daftar Sekarang →
            </a>
        </div>
    @endif
</form>

@endsection
