@extends('layouts.guest.auth')

@section('title', 'Verifikasi Wajah - ' . config('app.name', 'Tokobii'))

@section('subtitle', 'Autentikasi biometrik untuk melanjutkan masuk.')

@section('content')

<style>
    /* Base card styling (Desktop default) */
    .auth-card {
        width: 100%;
        max-width: 440px;
        margin-inline: auto;
        box-sizing: border-box;
    }
    .face-camera-wrapper {
        position: relative !important;
        width: 100% !important;
        max-width: 100% !important;
        margin-left: auto !important;
        margin-right: auto !important;
        overflow: hidden !important;
        border-radius: 18px !important;
        background: #0f172a !important;
        box-shadow: 0 6px 24px rgba(15,23,42,0.25) !important;
        height: 310px !important;
        max-height: 48vh !important;
    }
    #faceVideo, #faceCanvas {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transform: scaleX(-1) !important;
        display: block !important;
    }
    #faceOvalGuide {
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 175px !important;
        height: 225px !important;
        max-width: 72% !important;
        max-height: 80% !important;
    }
    #faceStatus {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        max-width: 100% !important;
        text-align: center !important;
        display: inline-block !important;
        line-height: 1.35 !important;
    }
    #faceInstruction {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        max-width: 100% !important;
        text-align: center !important;
    }
    #faceCameraSelect {
        width: 100% !important;
        max-width: 100% !important;
        text-overflow: ellipsis !important;
        overflow: hidden !important;
    }

    /* Strict Mobile Precision Centering & Overflow Prevention (< 576px) */
    @media (max-width: 575.98px) {
        .auth-card {
            width: 100% !important;
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 1.25rem 1rem !important;
            border-radius: 20px !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }
        .face-camera-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            height: 280px !important;
            max-height: 46vh !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        #faceOvalGuide {
            width: 160px !important;
            height: 210px !important;
            max-width: 72% !important;
            max-height: 82% !important;
        }
        .btn,
        .btn span,
        button,
        button span {
            white-space: normal !important;
            word-break: break-word !important;
        }
    }
</style>

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

@if(!empty($needsReEnroll))
    <div class="p-3 rounded-3 mb-3 bg-amber-50 border border-amber-200 small text-amber-900">
        <div class="d-flex align-items-start gap-2">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-amber-600 flex-shrink-0 mt-0.5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <strong>Pembaruan Sistem Biometrik:</strong> Akun Anda memerlukan pendaftaran ulang wajah di profil. Jika kamera terkendala, hubungi Superadmin untuk mendapatkan Kode Pemulihan Darurat.
            </div>
        </div>
    </div>
@endif

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
            Terlalu banyak percobaan yang salah. Silakan tunggu sekitar <strong>{{ $remainingLockout }} menit</strong> atau gunakan Kode Pemulihan Darurat dari Superadmin.
        </p>
    </div>
@else
    {{-- Camera Container Wrapper --}}
    <div class="face-camera-wrapper position-relative mb-3 rounded-2xl overflow-hidden mx-auto"
         style="position: relative; width: 100%; max-width: 100%; overflow: hidden; height: 310px; max-height: 52vh; border-radius: 18px; background: #0f172a; box-shadow: 0 6px 24px rgba(15,23,42,0.25);">
        <video id="faceVideo"
               autoplay
               playsinline
               muted
               style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); display: block;">
        </video>

        {{-- Detection Canvas Overlay (Bounding Box / Landmarks) --}}
        <canvas id="faceCanvas"
                style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; transform: scaleX(-1);">
        </canvas>

        {{-- Oval Guide --}}
        <div id="faceOvalGuide"
             class="position-absolute top-50 start-50 translate-middle pointer-events-none"
             style="width: 175px; height: 225px; max-width: 75%; max-height: 80%; border: 2.5px dashed rgba(96,165,250,0.8); border-radius: 50%; box-shadow: 0 0 0 9999px rgba(15,23,42,0.5); pointer-events: none; transition: border-color 0.3s ease, box-shadow 0.3s ease;">
        </div>

        {{-- Instruction Overlay --}}
        <div class="position-absolute bottom-0 start-0 end-0 d-flex align-items-center justify-content-center pb-3 pointer-events-none">
            <div class="text-center px-2">
                <span class="text-white small d-inline-block px-3 py-1 rounded-pill" style="font-size: 0.78125rem; background: rgba(15,23,42,0.72); backdrop-filter: blur(4px); box-shadow: 0 1px 4px rgba(0,0,0,0.4);">
                    Posisikan wajah di dalam oval
                </span>
            </div>
        </div>
    </div>

    {{-- Progress & Status --}}
    <div class="mb-3 text-center">
        <div class="tokobii-progress mb-2" style="height: 6px;">
            <div id="faceProgress" class="tokobii-progress-bar" style="width: 0%;"></div>
        </div>
        <div class="d-flex flex-column align-items-center gap-1.5 px-1">
            <span id="faceStatus" class="tokobii-badge tokobii-badge-info" style="font-size: 0.72rem; max-width: 100%; white-space: normal; text-align: center; cursor: default; user-select: none;">
                Mempersiapkan AI...
            </span>
            <p id="faceInstruction" class="text-slate-500 small mb-0 fw-medium" style="font-size: 0.78rem; line-height: 1.4; word-break: break-word; overflow-wrap: anywhere; text-align: center;">
                Pastikan pencahayaan cukup dan wajah menghadap kamera.
            </p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex flex-column align-items-center gap-2 mb-3 w-100">
        <select id="faceCameraSelect" class="form-select form-select-sm tokobii-select w-100" style="display: none; min-height: 44px; font-size: 16px; max-width: 100%;"></select>
        <button id="btnRetryFace" type="button" class="btn btn-tokobii-secondary w-100 d-inline-flex align-items-center justify-content-center gap-2" style="display: none; min-height: 44px; font-size: 0.875rem;">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>Coba Pindai Ulang</span>
        </button>
    </div>
@endif

{{-- Emergency Recovery Code Accordion --}}
<div class="text-center pt-2 mb-2 w-100">
    <button class="btn btn-link text-decoration-none small text-slate-500 p-2 d-inline-flex align-items-center justify-content-center w-100" style="min-height: 44px; white-space: normal !important;" type="button" data-bs-toggle="collapse" data-bs-target="#recoveryCodeCollapse">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1.5 flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
        <span style="overflow-wrap: anywhere; word-break: break-word; white-space: normal !important; text-align: center;">Kamera bermasalah? Masukkan Kode Pemulihan Darurat</span>
    </button>
    <div class="collapse mt-2" id="recoveryCodeCollapse">
        <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-start">
            <div id="recoveryAlert" class="alert alert-danger d-none py-1.5 px-2 small mb-2" role="alert"></div>
            <div class="mb-2">
                <label for="recoveryCodeInput" class="form-label small fw-semibold text-slate-700 mb-1">Kode Pemulihan Darurat Superadmin</label>
                <input type="text" id="recoveryCodeInput" class="form-control form-control-sm font-monospace text-uppercase" style="min-height: 44px; font-size: 16px;" placeholder="CONTOH: ABCD-1234" maxlength="16">
            </div>
            <button type="button" id="btnSubmitRecovery" class="btn btn-tokobii-primary btn-tokobii-sm w-100 d-inline-flex align-items-center justify-content-center" style="min-height: 44px; white-space: normal !important;" onclick="submitEmergencyCode()">
                <span style="white-space: normal !important; word-break: break-word; text-align: center;">Verifikasi Kode Pemulihan</span>
            </button>
        </div>
    </div>
</div>

{{-- Cancel --}}
<div class="text-center pt-3 mt-1 w-100" style="border-top: 1px solid #f1f5f9;">
    <form method="POST" action="{{ route('face-verification.cancel') }}" class="d-inline w-100">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none text-slate-400 small p-2 d-inline-flex align-items-center justify-content-center w-100" style="min-height: 44px; white-space: normal !important;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1.5 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span style="white-space: normal !important; word-break: break-word; text-align: center;">Batalkan dan Kembali ke Login</span>
        </button>
    </form>
</div>

@push('scripts')
<script>
function submitEmergencyCode() {
    const code = document.getElementById('recoveryCodeInput')?.value.trim();
    const alertBox = document.getElementById('recoveryAlert');
    const btn = document.getElementById('btnSubmitRecovery');

    if (!code) {
        if (alertBox) {
            alertBox.textContent = 'Harap masukkan kode pemulihan darurat.';
            alertBox.classList.remove('d-none');
        }
        return;
    }

    if (btn) btn.disabled = true;
    if (alertBox) alertBox.classList.add('d-none');

    fetch('{{ route('face-verification.recovery') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ recovery_code: code }),
    })
    .then(res => res.json().then(data => ({ status: res.status, data })))
    .then(({ status, data }) => {
        if (status === 200 && data.success) {
            window.location.href = data.redirect_url;
        } else {
            if (alertBox) {
                alertBox.textContent = data.message || 'Kode pemulihan darurat tidak valid.';
                alertBox.classList.remove('d-none');
            }
            if (btn) btn.disabled = false;
        }
    })
    .catch(() => {
        if (alertBox) {
            alertBox.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
            alertBox.classList.remove('d-none');
        }
        if (btn) btn.disabled = false;
    });
}
</script>

@if(!$isLocked)
@include('partials.face-config')
@vite(['resources/js/face-verification.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.TokobiiFaceVerification) {
        new window.TokobiiFaceVerification({
            mode: 'verify',
            verifyUrl: window.FACE_CONFIG?.endpoints?.verify || '{{ route('face-verification.verify') }}',
            challengeUrl: window.FACE_CONFIG?.endpoints?.challenge || '{{ route('face-verification.challenge-data') }}',
        });
    }
});
</script>
@endif
@endpush

@endsection
