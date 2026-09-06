@extends('layouts.customer.app')

@section('title', 'Profil Saya - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Profil Saya</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Profil Pelanggan</h1>
                <p class="text-slate-500 mb-0 small">Informasi rincian data akun dan pengaturan keamanan pelanggan Tokobii Anda.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Profil & Password</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Profile Information Card --}}
        <div class="col-12 col-lg-7">
            <div class="tokobii-card p-4 p-md-5 h-100">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-slate-100">
                    <div class="rounded-circle bg-blue-100 text-blue-600 fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; font-size: 1.25rem; background-color: #eff6ff; color: #2563eb;">
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
                                <td>: <span class="tokobii-badge tokobii-badge-info">Pelanggan (Customer)</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Status Akun</th>
                                <td>: 
                                    @if($user->status === 'active')
                                        <span class="tokobii-badge tokobii-badge-success">Aktif</span>
                                    @else
                                        <span class="tokobii-badge tokobii-badge-neutral">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Verifikasi Email</th>
                                <td>: 
                                    @if($user->hasVerifiedEmail())
                                        <span class="tokobii-badge tokobii-badge-success">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display:inline; vertical-align:-1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Email Terverifikasi
                                        </span>
                                    @else
                                        <div class="d-inline-flex align-items-center gap-2 flex-wrap">
                                            <span class="tokobii-badge tokobii-badge-warning">Belum Terverifikasi</span>
                                            <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary py-0 px-2 small">
                                                    Kirim Ulang Email Verifikasi
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
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

        {{-- Account Security & 2FA Section --}}
        <div class="col-12 col-lg-5">
            <div class="tokobii-card p-4 p-md-5 h-100">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom border-slate-100">
                    <span class="d-inline-flex align-items-center justify-content-center bg-blue-50 text-blue-600 rounded-2 p-2" style="background-color: #eff6ff; color: #2563eb;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </span>
                    <div>
                        <h5 class="fw-bold text-slate-900 mb-0">Keamanan Akun</h5>
                        <span class="text-slate-400 small">Proteksi autentikasi berlapis</span>
                    </div>
                </div>

                {{-- Subsection: Verifikasi 2 Langkah --}}
                <div class="bg-slate-50 rounded-3 p-3 p-sm-4 border border-slate-200">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <div>
                            <h6 class="fw-bold text-slate-900 mb-1">Verifikasi 2 Langkah (2FA)</h6>
                            <span class="text-slate-500 small d-block mb-3" style="line-height: 1.5;">
                                Tambahkan lapisan keamanan tambahan pada akun Anda. Jika diaktifkan, setiap login baru akan memerlukan kode verifikasi yang dikirim ke email terdaftar.
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-2 pt-2 border-top border-slate-200">
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-slate-500 fw-semibold">Status:</span>
                            @if($user->hasTwoFactorEnabled())
                                <span class="tokobii-badge tokobii-badge-success">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="tokobii-badge tokobii-badge-neutral">Tidak Aktif</span>
                            @endif
                        </div>

                        <div>
                            @if($user->hasTwoFactorEnabled())
                                <button type="button" class="btn btn-outline-danger btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#disableTwoFactorModal">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display:inline; vertical-align:-1px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    Nonaktifkan 2FA
                                </button>
                            @else
                                <button type="button" class="btn btn-tokobii-primary btn-tokobii-sm" data-bs-toggle="modal" data-bs-target="#enableTwoFactorModal">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display:inline; vertical-align:-1px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Aktifkan 2FA
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($user->hasTwoFactorEnabled())
                        <div class="mt-3 p-2 bg-emerald-50 rounded-2 border border-emerald-200 small text-emerald-800 d-flex align-items-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-emerald-600 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Verifikasi 2 langkah aktif. Kode keamanan akan dikirim ke email Anda setiap kali login baru.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL 1: Request Enable 2FA (Enter Password to Request OTP) --}}
<div class="modal fade" id="enableTwoFactorModal" tabindex="-1" aria-labelledby="enableTwoFactorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tokobii-card border-0 shadow-lg">
            <div class="modal-header border-bottom border-slate-100 p-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-blue-50 text-blue-600 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-slate-900 mb-0" id="enableTwoFactorModalLabel">Aktifkan Verifikasi 2 Langkah</h5>
                        <small class="text-slate-400">Langkah 1 dari 2: Konfirmasi Kata Sandi</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            
            <form method="POST" action="{{ route('customer.profile.security.2fa.request-enable') }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-slate-600 small mb-3">
                        Untuk memastikan keamanan, masukkan kata sandi akun Anda. Kami akan mengirimkan <strong>kode konfirmasi 6 digit</strong> ke alamat email <strong>{{ $user->email }}</strong>.
                    </p>

                    <div class="mb-3">
                        <label for="enable_password" class="form-label">Kata Sandi Akun <span class="text-rose-600">*</span></label>
                        <input type="password"
                               id="enable_password"
                               name="password"
                               class="form-control tokobii-input"
                               placeholder="Masukkan kata sandi saat ini"
                               required>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-100 p-3 bg-slate-50 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Kirim Kode Konfirmasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: Confirm Enable 2FA (Enter OTP from Email) --}}
@if(session('two_factor_enabling'))
<div class="modal fade show d-block" id="confirmEnableTwoFactorModal" tabindex="-1" style="z-index: 99995 !important; background-color: rgba(15, 23, 42, 0.72); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);" aria-labelledby="confirmEnableTwoFactorModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tokobii-card border-0 shadow-lg">
            <div class="modal-header border-bottom border-slate-100 p-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-emerald-50 text-emerald-600 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-slate-900 mb-0" id="confirmEnableTwoFactorModalLabel">Konfirmasi Kode Verifikasi</h5>
                        <small class="text-slate-400">Langkah 2 dari 2: Masukkan Kode OTP</small>
                    </div>
                </div>
                <a href="{{ route('customer.profile.index') }}" class="btn-close" aria-label="Tutup"></a>
            </div>
            
            <form method="POST" action="{{ route('customer.profile.security.2fa.confirm-enable') }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-slate-600 small mb-3">
                        Masukkan 6 digit kode konfirmasi yang telah kami kirimkan ke email <strong>{{ $user->email }}</strong>.
                    </p>

                    <div class="mb-3">
                        <label for="enable_otp_code" class="form-label text-center d-block">Kode OTP 6 Digit</label>
                        <input type="text"
                               id="enable_otp_code"
                               name="code"
                               class="form-control text-center fw-bold fs-3 font-monospace tracking-widest tokobii-input"
                               placeholder="------"
                               maxlength="6"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               required
                               autofocus
                               style="letter-spacing: 0.5rem; height: 52px;">
                        <small class="form-text text-center text-slate-400 d-block mt-2">
                            Kode berlaku selama 5 menit.
                        </small>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-100 p-3 bg-slate-50 d-flex justify-content-between align-items-center">
                    <form method="POST" action="{{ route('customer.profile.security.2fa.resend-enable') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-decoration-none text-blue-600 p-0 small fw-semibold">
                            Kirim Ulang Kode
                        </button>
                    </form>

                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.profile.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">Batal</a>
                        <button type="submit" class="btn btn-tokobii-success btn-tokobii-sm">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Aktifkan Sekarang</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- MODAL 3: Disable 2FA (Enter Password to Confirm) --}}
<div class="modal fade" id="disableTwoFactorModal" tabindex="-1" aria-labelledby="disableTwoFactorModalLabel" aria-hidden="true">
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
                        <h5 class="modal-title fw-bold text-slate-900 mb-0" id="disableTwoFactorModalLabel">Nonaktifkan Verifikasi 2 Langkah</h5>
                        <small class="text-slate-400">Konfirmasi Keamanan Akun</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            
            <form method="POST" action="{{ route('customer.profile.security.2fa.disable') }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-slate-600 small mb-3">
                        Apakah Anda yakin ingin menonaktifkan <strong>Verifikasi 2 Langkah (2FA)</strong>? Tingkat perlindungan akun Anda akan berkurang dan login berikutnya hanya memerlukan email dan kata sandi.
                    </p>

                    <div class="mb-3">
                        <label for="disable_password" class="form-label">Kata Sandi Akun <span class="text-rose-600">*</span></label>
                        <input type="password"
                               id="disable_password"
                               name="password"
                               class="form-control tokobii-input"
                               placeholder="Masukkan kata sandi untuk konfirmasi"
                               required>
                    </div>
                </div>

                <div class="modal-footer border-top border-slate-100 p-3 bg-slate-50 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tokobii-danger btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Nonaktifkan 2FA</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const otpInput = document.getElementById('enable_otp_code');
    if (otpInput) {
        otpInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    }
});
</script>
@endpush

@endsection