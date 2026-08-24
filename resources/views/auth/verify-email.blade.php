@extends('layouts.guest.auth')

@section('title', 'Periksa Email Anda - Tokobii')

@section('subtitle', 'Terima kasih telah mendaftar di Tokobii! Silakan verifikasi email Anda untuk melanjutkan.')

@section('content')

<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center bg-blue-100 text-blue-600 rounded-circle mb-3 p-3 shadow-sm" style="width: 72px; height: 72px; background-color: #dbeafe; color: #2563eb;">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
    </div>
    <h3 class="h4 fw-bold text-slate-900 mb-1">Periksa Email Anda</h3>
    <p class="text-slate-500 small mb-0">Kami telah mengirimkan link verifikasi ke alamat email Anda.</p>
</div>

<div class="mb-4">
    <div class="p-3 bg-slate-100 rounded-xl text-slate-900 font-monospace fw-bold text-center border border-slate-200 shadow-sm" style="word-break: break-all;">
        {{ Auth::user()?->email ?? session('email') }}
    </div>
    <p class="text-slate-500 small text-center mt-2 mb-0" style="line-height: 1.5;">
        Silakan buka inbox email Anda dan klik link verifikasi untuk mengaktifkan akun. Periksa juga folder spam/junk jika email belum terlihat.
    </p>
</div>

@if(session('status') == 'verification-link-sent' || session('success'))
    <div class="alert alert-success border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 mb-4 shadow-sm small fw-semibold" role="alert">
        {{ session('success') ?? 'Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.' }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 bg-rose-50 text-rose-800 rounded-xl p-3.5 mb-4 shadow-sm small fw-semibold" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="d-flex flex-column gap-3">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>Kirim Ulang Email Verifikasi</span>
        </button>
    </form>

    <a href="{{ route('verification.notice', ['refresh' => 1]) }}" class="btn btn-outline-secondary btn-tokobii-lg w-100 shadow-sm">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span>Refresh Status Verifikasi</span>
    </a>

    <div class="d-flex align-items-center justify-content-between pt-2 border-top border-slate-100">
        <a href="{{ route('login') }}" class="text-decoration-none small text-slate-600 hover-text-blue-600 fw-medium">
            &larr; Kembali ke Halaman Login
        </a>

        @if(Auth::check())
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link p-0 text-slate-500 text-decoration-none small hover-text-rose-600">
                    Keluar dari Akun
                </button>
            </form>
        @endif
    </div>
</div>

@endsection