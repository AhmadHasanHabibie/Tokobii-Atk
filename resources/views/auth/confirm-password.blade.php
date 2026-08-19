@extends('layouts.guest.auth')

@section('title', 'Konfirmasi Kata Sandi - Tokobii')

@section('subtitle', 'Ini adalah area aman aplikasi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.')

@section('content')

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    {{-- Password --}}
    <div class="mb-4">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text bg-slate-50 border-slate-300 text-slate-400 ps-3">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </span>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control tokobii-input border-start-0 ps-1 @error('password') is-invalid @enderror"
                   placeholder="Masukkan kata sandi Anda"
                   autocomplete="current-password"
                   required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Konfirmasi</span>
        </button>
    </div>
</form>

@endsection