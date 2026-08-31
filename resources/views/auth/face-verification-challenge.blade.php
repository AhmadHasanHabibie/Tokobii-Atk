@extends('layouts.guest.auth')

@section('title', 'Verifikasi Wajah - ' . config('app.name', 'Tokobii'))

@section('subtitle', 'Autentikasi biometrik untuk melanjutkan masuk.')

@section('content')

{{-- Header --}}
<div class="text-center mb-3">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
         style="width: 56px; height: 56px; background: linear-gradient(135deg, #eff6ff, #dbeafe); box-shadow: 0 0 0 6px rgba(219,234,254,0.45);">
        <svg width="26" height="26" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.1875rem; letter-spacing: -0.01em;">Verifikasi Wajah</h2>
    <div class="d-flex align-items-center justify-content-center gap-2">
        <span class="fw-semibold text-slate-700 small">{{ $user->name }}</span>
        @if($user->isAdmin())
            <span class="tokobii-badge tokobii-badge-info" style="font-size: 0.68rem;">Administrator</span>
        @elseif($user->isOwner())
            <span class="tokobii-badge" style="background: linear-gradient(135deg, #faf5ff, #ede9fe); color: #7c3aed; border: 1px solid #c4b5fd; font-size: 0.68rem;">Owner</span>
        @endif
    </div>
</div>

@if($isLocked)
    <div class="d-flex flex-column align-items-center text-center p-4 rounded-xl mb-3"
         style="background: linear-gradient(135deg, #fff1f2, #ffe4e6); border: 1.5px solid #fecdd3;">
        <div class="d-flex align-items-center justify-content-center rounded-circle mb-3"
             style="width: 56px; height: 56px; background: #ffe4e6; color: #be123c;">
            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <span class="fw-bold text-rose-800 mb-1">Akun Terkunci Sementara</span>
        <p class="text-rose-700 small mb-0">
            Terlalu banyak percobaan yang gagal. Silakan tunggu sekitar <strong>{{ $remainingLockout }} menit</strong>.
        </p>
    </div>
@else
    {{-- Camera Container --}}
    <div class="position-relative mb-3 rounded-2xl overflow-hidden" style="height: 310px; border-radius: 18px; background: #0f172a; box-shadow: 0 6px 24px rgba(15,23,42,0.25);">
        <video id="faceVideo"
               autoplay
               playsinline
               muted
               class="w-100 h-100"
               style="transform: scaleX(-1); object-fit: cover; height: 100%; width: 100%;">
        </video>

        {{-- Oval Guide --}}
        <div id="faceOvalGuide"
             class="position-absolute top-50 start-50 translate-middle pointer-events-none"
             style="width: 175px; height: 225px; border: 2.5px dashed rgba(96,165,250,0.8); border-radius: 50%; box-shadow: 0 0 0 9999px rgba(15,23,42,0.5); pointer-events: none; transition: border-color 0.3s ease, box-shadow 0.3s ease;">
        </div>

        {{-- Instruction Overlay --}}
        <div class="position-absolute bottom-0 start-0 end-0 d-flex align-items-center justify-content-center pb-3 pointer-events-none">
            <div class="text-center">
                <span class="text-white-50 small d-block" style="font-size: 0.78125rem; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">
                    Posisikan wajah di dalam oval
                </span>
            </div>
        </div>
    </div>

    {{-- Progress & Status --}}
    <div class="mb-3">
        <div class="tokobii-progress mb-2">
            <div id="faceProgress" class="tokobii-progress-bar" style="width: 0%;"></div>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <span id="faceStatus" class="tokobii-badge tokobii-badge-info" style="font-size: 0.71875rem;">
                Mempersiapkan kamera...
            </span>
            <p id="faceInstruction" class="text-slate-500 small mb-0" style="font-size: 0.75rem;">
                Pastikan pencahayaan cukup.
            </p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex flex-column align-items-center gap-2 mb-3">
        <select id="faceCameraSelect" class="form-select form-select-sm tokobii-select w-75" style="display: none;"></select>
        <button id="btnRetryFace" type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" style="display: none;">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Coba Pindai Ulang
        </button>
    </div>
@endif

{{-- Cancel --}}
<div class="text-center pt-3 mt-1" style="border-top: 1px solid #f1f5f9;">
    <form method="POST" action="{{ route('face-verification.cancel') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none text-slate-400 small p-0">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display: inline; vertical-align: -1px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Batalkan dan Kembali ke Login
        </button>
    </form>
</div>

@push('scripts')
@if(!$isLocked)
@vite(['resources/js/face-verification.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.TokobiiFaceVerification) {
        new window.TokobiiFaceVerification({
            mode: 'verify',
            verifyUrl: '{{ route('face-verification.verify') }}',
            modelsUri: '/models/face-api',
        });
    }
});
</script>
@endif
@endpush

@endsection
