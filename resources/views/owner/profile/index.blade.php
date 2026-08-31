@extends('layouts.owner.app')

@section('title', 'Profil Owner - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Profil Owner</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Profil Pemilik Toko</h1>
                <p class="text-slate-500 mb-0 small">Informasi rincian akun Owner Tokobii Anda.</p>
            </div>
            <div>
                <a href="{{ route('owner.profile.edit') }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Profil</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-12 col-lg-7">
            <div class="tokobii-card p-4 p-md-5 h-100">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-slate-100">
                    <div class="rounded-circle bg-purple-100 text-purple-700 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; font-size: 1.25rem; background-color: #f3e8ff; color: #9333ea;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold text-slate-900 mb-0">{{ $user->name }}</h4>
                        <span class="text-slate-400 font-monospace small">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0 small">
                        <tbody>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Nama Lengkap</th>
                                <td class="text-slate-900 fw-bold">: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Alamat Email</th>
                                <td class="text-slate-800 font-monospace">: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Hak Akses</th>
                                <td>: <span class="tokobii-badge tokobii-badge-primary">Pemilik Toko (Owner)</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Status Akun</th>
                                <td>: <span class="tokobii-badge tokobii-badge-success">Aktif</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Terdaftar Sejak</th>
                                <td class="text-slate-800">: {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }} WIB</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Biometric Security Card --}}
        <div class="col-12 col-lg-5">
            <div class="tokobii-card p-4 p-md-5 h-100">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-slate-100">
                    <span class="d-inline-flex align-items-center justify-content-center bg-purple-50 text-purple-600 rounded-2 p-2" style="background-color: #f3e8ff; color: #9333ea;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    <div>
                        <h5 class="fw-bold text-slate-900 mb-0">Keamanan Biometrik</h5>
                        <span class="text-slate-400 small">Autentikasi wajah Pemilik Toko</span>
                    </div>
                </div>

                {{-- Face Verification Box --}}
                <div class="bg-slate-50 rounded-3 p-3 p-sm-4 border border-slate-200">
                    <div class="mb-2">
                        <h6 class="fw-bold text-slate-900 mb-1">Verifikasi Wajah (Face Verification)</h6>
                        <span class="text-slate-500 small d-block mb-3" style="line-height: 1.5;">
                            Wajah Anda akan digunakan sebagai lapisan verifikasi biometrik tambahan saat login ke Dashboard Owner Tokobii.
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-2 pt-2 border-top border-slate-200">
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-slate-500 fw-semibold">Status:</span>
                            @if($user->hasFaceVerificationEnabled())
                                <span class="tokobii-badge tokobii-badge-success">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="tokobii-badge tokobii-badge-neutral">Belum Aktif</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            @if($user->hasFaceVerificationEnabled())
                                <button type="button" class="btn btn-outline-danger btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#disableFaceModal">
                                    Nonaktifkan
                                </button>
                                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#enrollFaceModal">
                                    Daftar Ulang
                                </button>
                            @else
                                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#enrollFaceModal">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Aktifkan Verifikasi Wajah
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($user->hasFaceVerificationEnabled() && $user->faceVerification)
                        <div class="mt-3 p-2 bg-emerald-50 rounded-2 border border-emerald-200 small text-emerald-800 d-flex align-items-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-emerald-600 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Terdaftar pada: <strong>{{ $user->faceVerification->enrolled_at ? $user->faceVerification->enrolled_at->format('d M Y, H:i') : '-' }} WIB</strong></span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL ENROLL FACE --}}
<div class="modal fade" id="enrollFaceModal" tabindex="-1" aria-labelledby="enrollFaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tokobii-card border-0 shadow-lg">
            <div class="modal-header border-bottom border-slate-100 p-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-purple-50 text-purple-600 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-slate-900 mb-0" id="enrollFaceModalLabel">Pendaftaran Biometrik Wajah</h5>
                        <small class="text-slate-400">Perekaman Data Biometrik & Liveness</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            
            <div class="modal-body p-4">
                <p class="text-slate-600 small mb-3">
                    Wajah Anda akan dipindai menggunakan kecerdasan buatan (AI) untuk mengekstrak vektor biometrik unik. Pastikan pencahayaan terang dan wajah Anda berada di dalam lingkaran panduan.
                </p>

                {{-- Password Confirmation Input --}}
                <div class="mb-3">
                    <label for="enrollPassword" class="form-label">Kata Sandi Akun <span class="text-rose-600">*</span></label>
                    <input type="password" 
                           id="enrollPassword" 
                           class="form-control tokobii-input" 
                           placeholder="Masukkan kata sandi untuk konfirmasi" 
                           required>
                    <small class="text-slate-400">Kata sandi diperlukan demi keamanan otorisasi pendaftaran biometrik.</small>
                </div>

                {{-- Camera Preview with Oval Overlay --}}
                <div class="position-relative mb-3 rounded-3 overflow-hidden bg-slate-950 shadow-inner" style="height: 260px;">
                    <video id="faceVideo" 
                           autoplay 
                           playsinline 
                           muted 
                           class="w-100 h-100 object-cover" 
                           style="transform: scaleX(-1); object-fit: cover;">
                    </video>

                    {{-- Oval Overlay --}}
                    <div id="faceOvalGuide" class="position-absolute top-50 start-50 translate-middle pointer-events-none" 
                         style="width: 150px; height: 190px; border: 3px dashed rgba(147, 51, 234, 0.7); border-radius: 50%; box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.55); pointer-events: none; transition: border-color 0.25s ease, box-shadow 0.25s ease;">
                    </div>
                </div>

                {{-- Status & Progress --}}
                <div class="text-center mb-2">
                    <div class="progress mb-2 rounded-pill bg-slate-100" style="height: 6px;">
                        <div id="faceProgress" class="progress-bar bg-purple-600 progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" style="width: 0%;"></div>
                    </div>
                    <span id="faceStatus" class="tokobii-badge bg-purple-50 text-purple-700 border-purple-200 mb-1">
                        Mempersiapkan kamera...
                    </span>
                    <p id="faceInstruction" class="text-slate-500 small mb-0">
                        Posisikan wajah Anda tepat di dalam bingkai oval.
                    </p>
                </div>

                {{-- Camera Select Dropdown --}}
                <div class="d-flex justify-content-center">
                    <select id="faceCameraSelect" class="form-select form-select-sm tokobii-select w-75 mt-2" style="display: none;"></select>
                </div>
            </div>

            <div class="modal-footer border-top border-slate-100 p-3 bg-slate-50 d-flex justify-content-between">
                <button id="btnRetryFace" type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" style="display: none;">
                    Ulangi Perekaman
                </button>
                <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm ms-auto" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DISABLE FACE --}}
<div class="modal fade" id="disableFaceModal" tabindex="-1" aria-labelledby="disableFaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tokobii-card border-0 shadow-lg">
            <div class="modal-header border-bottom border-slate-100 p-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-rose-50 text-rose-600 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-slate-900 mb-0" id="disableFaceModalLabel">Nonaktifkan Verifikasi Wajah</h5>
                        <small class="text-slate-400">Konfirmasi Keamanan Owner</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            
            <form method="POST" action="{{ route('owner.profile.face-verification.disable') }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-slate-600 small mb-3">
                        Apakah Anda yakin ingin menonaktifkan <strong>Verifikasi Wajah (Face Verification)</strong>? Login Owner berikutnya hanya akan memerlukan email dan kata sandi.
                    </p>

                    <div class="mb-3">
                        <label for="disable_owner_face_password" class="form-label">Kata Sandi Akun <span class="text-rose-600">*</span></label>
                        <input type="password"
                               id="disable_owner_face_password"
                               name="password"
                               class="form-control tokobii-input"
                               placeholder="Masukkan kata sandi untuk konfirmasi"
                               required>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-100 p-3 bg-slate-50 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tokobii-danger btn-tokobii-sm">
                        <span>Nonaktifkan Verifikasi Wajah</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
@vite(['resources/js/face-verification.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    const enrollModal = document.getElementById('enrollFaceModal');
    let faceInstance = null;

    if (enrollModal) {
        enrollModal.addEventListener('shown.bs.modal', function () {
            if (window.TokobiiFaceVerification) {
                faceInstance = new window.TokobiiFaceVerification({
                    mode: 'enroll',
                    enrollUrl: '{{ route('owner.profile.face-verification.enroll') }}',
                    modelsUri: '/models/face-api',
                });
            }
        });

        enrollModal.addEventListener('hidden.bs.modal', function () {
            if (faceInstance) {
                faceInstance.stop();
                faceInstance = null;
            }
        });
    }
});
</script>
@endpush

@endsection