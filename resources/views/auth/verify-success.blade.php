@extends('layouts.guest.auth')

@section('title', isset($alreadyVerified) && $alreadyVerified ? 'Email Sudah Terverifikasi - Tokobii' : 'Email Berhasil Diverifikasi! - Tokobii')

@section('subtitle', 'Status akun pelanggan Tokobii Anda telah aktif.')

@section('content')

<div class="text-center py-2">
    <div class="d-inline-flex align-items-center justify-content-center bg-emerald-100 text-emerald-600 rounded-circle mb-3 p-3 shadow-sm" style="width: 72px; height: 72px; background-color: #d1fae5; color: #059669;">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    @if(isset($alreadyVerified) && $alreadyVerified)
        <h3 class="h4 fw-bold text-slate-900 mb-2">Email Sudah Terverifikasi</h3>
        <p class="text-slate-600 small mb-4" style="line-height: 1.6;">
            Alamat email akun Tokobii Anda (<strong class="text-slate-800">{{ $user->email }}</strong>) sudah terverifikasi.
        </p>
    @else
        <h3 class="h4 fw-bold text-slate-900 mb-2">Email Berhasil Diverifikasi!</h3>
        <p class="text-slate-600 small mb-4" style="line-height: 1.6;">
            Alamat email Anda (<strong class="text-slate-800">{{ $user->email }}</strong>) telah berhasil diverifikasi. Akun Tokobii Anda sekarang sudah aktif.
        </p>
    @endif

    <div class="d-flex flex-column gap-3">
        @if(Auth::check() && Auth::user()->isCustomer())
            <a href="{{ route('customer.dashboard') }}" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm">
                <span>Refresh & Lanjut ke Dashboard</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="ms-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        @else
            <a href="{{ route('customer.dashboard') }}" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm mb-1">
                <span>Refresh & Lanjut ke Dashboard</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="ms-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-tokobii-lg shadow-sm">
                <span>Masuk ke Akun Tokobii</span>
            </a>
        @endif
    </div>
</div>

@endsection
