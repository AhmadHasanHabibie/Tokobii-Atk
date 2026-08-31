@extends('layouts.guest.auth')

@section('title', 'Verifikasi Wajah - ' . config('app.name', 'Tokobii'))

@section('subtitle', 'Autentikasi biometrik wajah untuk melanjutkan masuk.')

@section('content')

{{-- User & Role Header --}}
<div class="text-center mb-3">
    <div class="d-inline-flex align-items-center justify-content-center bg-blue-50 text-blue-600 rounded-circle mb-2 shadow-sm" style="width: 56px; height: 56px; background-color: #eff6ff; color: #2563eb;">
        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h2 class="h5 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Verifikasi Wajah</h2>
    <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
        <span class="text-slate-800 fw-semibold small">{{ $user->name }}</span>
        @if($user->isAdmin())
            <span class="tokobii-badge tokobii-badge-info">Administrator</span>
        @elseif($user->isOwner())
            <span class="tokobii-badge tokobii-badge-primary" style="background-color: #f3e8ff; color: #7e22ce;">Owner</span>
        @endif
    </div>
</div>

@if($isLocked)
    <div class="alert alert-danger border-0 bg-rose-50 text-rose-800 rounded-xl p-4 mb-4 text-center shadow-sm" role="alert">
        <div class="d-flex flex-column align-items-center gap-2">
            <svg class="text-rose-600 flex-shrink-0" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span class="fw-bold fs-6">Akun Terkunci Sementara</span>
            <p class="small mb-0 text-rose-700">
                Terlalu banyak percobaan verifikasi wajah yang tidak cocok. Silakan tunggu sekitar <strong>{{ $remainingLockout }} menit</strong> sebelum mencoba kembali.
            </p>
        </div>
    </div>
@else
    {{-- Camera Container with Oval Guide --}}
    <div class="position-relative mb-3 rounded-3 overflow-hidden bg-slate-950 shadow-inner" style="height: 320px; max-width: 100%;">
        <video id="faceVideo" 
               autoplay 
               playsinline 
               muted 
               class="w-100 h-100 object-cover" 
               style="transform: scaleX(-1); object-fit: cover;">
        </video>

        {{-- Oval Face Guide Overlay --}}
        <div id="faceOvalGuide" class="position-absolute top-50 start-50 translate-middle pointer-events-none" 
             style="width: 180px; height: 230px; border: 3px dashed rgba(59, 130, 246, 0.7); border-radius: 50%; box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.55); pointer-events: none; transition: border-color 0.25s ease, box-shadow 0.25s ease;">
        </div>

        {{-- Live Scanning Animation Line --}}
        <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none d-flex align-items-center justify-content-center" style="pointer-events: none;">
            <div class="text-center text-white-50 small opacity-75">
                <span class="d-block mb-1">Posisikan wajah di dalam oval</span>
            </div>
        </div>
    </div>

    {{-- Progress & Status --}}
    <div class="mb-3 text-center">
        <div class="progress mb-2 rounded-pill bg-slate-100" style="height: 6px;">
            <div id="faceProgress" class="progress-bar bg-blue-600 progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" style="width: 0%;"></div>
        </div>
        <div class="d-flex justify-content-center mb-1">
            <span id="faceStatus" class="tokobii-badge bg-blue-50 text-blue-700 border-blue-200">
                Mempersiapkan kamera...
            </span>
        </div>
        <p id="faceInstruction" class="text-slate-500 small mb-0">
            Pastikan pencahayaan cukup dan wajah Anda menghadap lurus ke kamera.
        </p>
    </div>

    {{-- Camera Switcher & Actions --}}
    <div class="d-flex flex-column align-items-center gap-2 mb-3">
        <select id="faceCameraSelect" class="form-select form-select-sm tokobii-select w-75" style="display: none;"></select>
        <button id="btnRetryFace" type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" style="display: none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Coba Pindai Ulang
        </button>
    </div>
@endif

{{-- Cancel & Return to Login --}}
<div class="text-center pt-3 border-top border-slate-100 mt-3">
    <form method="POST" action="{{ route('face-verification.cancel') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none text-slate-400 hover-text-slate-600 small p-0">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display: inline; vertical-align: -1px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Batalkan dan Kembali ke Halaman Masuk
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
