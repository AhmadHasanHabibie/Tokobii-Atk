@extends('layouts.superadmin.app')

@section('title', 'Manajemen Biometrik Wajah - Superadmin Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dasbor</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Manajemen Biometrik Wajah</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Manajemen Biometrik Wajah</h1>
                <p class="text-slate-500 mb-0 small">Kendali akses biometrik, reset akun, dan penerbitan kode pemulihan darurat untuk Administrator & Owner.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="tokobii-badge bg-blue-50 text-blue-700 border-blue-200">
                    Engine: {{ config('face.engine_version', 'human-v3') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-success flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-danger flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Highlight Box: Generated Emergency Recovery Code --}}
    @if(session('generated_recovery_code'))
        <div class="p-4 mb-4 rounded-3 border-2 border-primary bg-blue-50 shadow-sm">
            <div class="d-flex align-items-start gap-3">
                <div class="p-2 bg-primary text-white rounded-circle flex-shrink-0">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold text-slate-900 mb-1">Kode Pemulihan Darurat Sekali Pakai (Recovery Code)</h5>
                    <p class="text-slate-600 small mb-2">
                        Berikan kode ini kepada <strong>{{ session('recovery_user_name') }}</strong> ({{ session('recovery_user_email') }}). Kode ini hanya berlaku <strong>1 kali</strong> untuk melewati tantangan verifikasi wajah jika kamera mengalami kerusakan.
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="font-monospace fw-bold fs-3 text-primary bg-white px-3 py-1 rounded border border-primary shadow-sm" id="recoveryCodeText">
                            {{ session('generated_recovery_code') }}
                        </span>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyRecoveryCode()">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span id="copyBtnLabel">Salin Kode</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Table Card --}}
    <div class="tokobii-card p-4 shadow-sm border border-slate-200">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 pb-2 border-bottom border-slate-100">
            <div>
                <h5 class="fw-bold text-slate-900 mb-0">Daftar Akun Administrator & Owner</h5>
                <span class="text-slate-500 small">Total {{ $adminsAndOwners->count() }} akun dengan proteksi biometrik wajib</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-slate-50 text-slate-600 small fw-bold">
                    <tr>
                        <th style="min-width: 200px;">Pengguna</th>
                        <th>Role</th>
                        <th>Status Biometrik</th>
                        <th>Versi Engine</th>
                        <th>Terdaftar Sejak</th>
                        <th>Percobaan Gagal</th>
                        <th class="text-end" style="min-width: 250px;">Aksi Keamanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($adminsAndOwners as $user)
                        @php
                            $profile = $user->faceProfile;
                            $legacy = $user->faceVerification;
                            $hasActive = $user->hasFaceVerificationEnabled();
                            $isLocked = ($profile && $profile->isLocked()) || ($legacy && $legacy->isLocked());
                            $needsReEnroll = $profile && $profile->needs_re_enroll;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-blue-50 text-blue-600 fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.875rem;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-slate-900 small">{{ $user->name }}</div>
                                        <span class="text-slate-400 font-monospace small" style="font-size: 0.75rem;">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="tokobii-badge tokobii-badge-info">Admin</span>
                                @else
                                    <span class="tokobii-badge bg-purple-50 text-purple-700 border-purple-200">Owner</span>
                                @endif
                            </td>
                            <td>
                                @if($isLocked)
                                    <span class="tokobii-badge tokobii-badge-danger">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Terkunci
                                    </span>
                                @elseif($needsReEnroll)
                                    <span class="tokobii-badge tokobii-badge-warning">
                                        Perlu Daftar Ulang
                                    </span>
                                @elseif($hasActive)
                                    <span class="tokobii-badge tokobii-badge-success">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-neutral">Belum Aktif</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-monospace small text-slate-600">
                                    {{ $profile ? $profile->engine_version : ($legacy ? 'face-api-v1' : '-') }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-slate-600">
                                    @if($profile && $profile->enrolled_at)
                                        {{ $profile->enrolled_at->format('d M Y, H:i') }}
                                    @elseif($legacy && $legacy->enrolled_at)
                                        {{ $legacy->enrolled_at->format('d M Y, H:i') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                            <td>
                                @php
                                    $fails = $profile ? $profile->failed_attempts : ($legacy ? $legacy->failed_attempts : 0);
                                @endphp
                                <span class="badge {{ $fails > 0 ? 'bg-danger text-white' : 'bg-light text-slate-700' }} rounded-pill">
                                    {{ $fails }} / {{ config('face.max_attempts', 5) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    {{-- Tombol Recovery Code --}}
                                    <form method="POST" action="{{ route('superadmin.face-management.recovery-code', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm px-2 py-1" title="Terbitkan Kode Pemulihan Darurat Sekali Pakai">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                            <span class="d-none d-lg-inline ms-1">Beri Recovery</span>
                                        </button>
                                    </form>

                                    {{-- Tombol Reset / Paksa Daftar Ulang --}}
                                    <form method="POST" action="{{ route('superadmin.face-management.reset', $user->id) }}" onsubmit="return confirm('Reset biometrik dan minta pengguna {{ $user->name }} mendaftar ulang?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm px-2 py-1" title="Reset & Minta Daftar Ulang">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            <span class="d-none d-lg-inline ms-1">Reset</span>
                                        </button>
                                    </form>

                                    {{-- Tombol Nonaktifkan --}}
                                    @if($hasActive)
                                        <form method="POST" action="{{ route('superadmin.face-management.disable', $user->id) }}" onsubmit="return confirm('Nonaktifkan verifikasi wajah untuk {{ $user->name }}?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" title="Nonaktifkan Verifikasi Wajah">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                                <span class="d-none d-lg-inline ms-1">Nonaktifkan</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-slate-400">
                                Tidak ada data akun Administrator atau Owner.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function copyRecoveryCode() {
    const textEl = document.getElementById('recoveryCodeText');
    const btnLabel = document.getElementById('copyBtnLabel');
    if (!textEl) return;

    navigator.clipboard.writeText(textEl.textContent.trim()).then(() => {
        if (btnLabel) {
            btnLabel.textContent = 'Tersalin!';
            setTimeout(() => { btnLabel.textContent = 'Salin Kode'; }, 2500);
        }
    });
}
</script>
@endsection
