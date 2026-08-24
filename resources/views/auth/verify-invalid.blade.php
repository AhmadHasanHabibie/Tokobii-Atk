@extends('layouts.guest.auth')

@section('title', $title ?? 'Link Verifikasi Tidak Valid - Tokobii')

@section('subtitle', 'Pemeriksaan tautan verifikasi email Tokobii.')

@section('content')

<div class="text-center py-2">
    <div class="d-inline-flex align-items-center justify-content-center bg-rose-100 text-rose-600 rounded-circle mb-3 p-3 shadow-sm" style="width: 72px; height: 72px; background-color: #ffe4e6; color: #e11d48;">
        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
    </div>

    <h3 class="h5 fw-bold text-slate-900 mb-2">{{ $title ?? 'Link Verifikasi Tidak Valid atau Kedaluwarsa' }}</h3>
    <p class="text-slate-600 small mb-4" style="line-height: 1.6;">
        {{ $message ?? 'Link verifikasi ini sudah tidak dapat digunakan. Silakan kirim ulang email verifikasi untuk mendapatkan link baru.' }}
    </p>

    <div class="d-flex flex-column gap-3">
        @if(Auth::check() && Auth::user()->isCustomer() && !Auth::user()->hasVerifiedEmail())
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Kirim Ulang Email Verifikasi</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm">
                <span>Kembali ke Login</span>
            </a>
        @endif
    </div>

    <div class="pt-3 mt-3 border-top border-slate-100 text-center">
        <a href="{{ route('shop') }}" class="text-decoration-none small text-slate-500 hover-text-blue-600">
            &larr; Kembali ke Beranda Tokobii
        </a>
    </div>
</div>

@endsection
