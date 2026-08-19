@extends('layouts.guest.auth')

@section('title', 'Verifikasi Alamat Email - Tokobii')

@section('subtitle', 'Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda.')

@section('content')

<div class="mb-4 text-slate-600 small" style="line-height: 1.6;">
    Kami telah mengirimkan tautan verifikasi ke email Anda. Silakan klik tautan tersebut untuk mengaktifkan akun Tokobii Anda. Jika Anda tidak menerima email, kami dapat mengirimkannya kembali.
</div>

@if(session('status') == 'verification-link-sent')
    <div class="alert alert-success border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 mb-4 shadow-sm small fw-semibold" role="alert">
        Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.
    </div>
@endif

<div class="d-flex flex-column gap-3">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span>Kirim Ulang Email Verifikasi</span>
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="text-center">
        @csrf
        <button type="submit" class="btn btn-link text-slate-500 text-decoration-none small hover-text-rose-600">
            Keluar dari Akun
        </button>
    </form>
</div>

@endsection