@extends('layouts.guest.auth')

@section('title', 'Verifikasi Email Akun - ' . config('app.name', 'Tokobii'))

@section('subtitle', 'Langkah terakhir untuk mengaktifkan akun Tokobii Anda.')

@section('content')

{{-- Info Header --}}
<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm"
         style="width: 70px; height: 70px; background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; box-shadow: 0 0 0 8px rgba(219,234,254,0.5);">
        <svg width="34" height="34" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
    </div>
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.35rem; letter-spacing: -0.01em;">Verifikasi Alamat Email</h2>
    <p class="text-slate-500 small mb-2">Kami telah mengirimkan 6 digit kode OTP ke alamat email:</p>
    
    <div class="d-inline-block fw-bold font-monospace px-3 py-1.5 rounded-pill small border"
         style="background-color: #f8fafc; border-color: #e2e8f0; color: #1e293b; letter-spacing: 0.02em;">
        {{ Auth::user()?->email ?? session('email', 'email Anda') }}
    </div>
</div>

{{-- Flash Alerts --}}
@if(session('status') == 'verification-link-sent' || session('success'))
    <div class="alert alert-success border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 mb-4 shadow-sm small fw-semibold d-flex align-items-center gap-2" role="alert">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('success') ?? 'Kode OTP baru telah berhasil dikirim ke email Anda.' }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 bg-rose-50 text-rose-800 rounded-xl p-3.5 mb-4 shadow-sm small fw-semibold d-flex align-items-center gap-2" role="alert">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info border-0 bg-blue-50 text-blue-800 rounded-xl p-3.5 mb-4 shadow-sm small fw-semibold d-flex align-items-center gap-2" role="alert">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('info') }}</span>
    </div>
@endif

{{-- OTP Input Form --}}
<form method="POST" action="{{ route('verification.verify.otp') }}" id="otpVerificationForm">
    @csrf

    <div class="mb-4">
        <label for="otp" class="form-label text-center d-block fw-semibold text-slate-700 mb-2" style="font-size: 0.9rem;">
            Masukkan 6 Digit Kode OTP
        </label>
        
        <input type="text"
               id="otp"
               name="otp"
               class="form-control tokobii-input text-center fw-bold font-monospace @error('otp') is-invalid @enderror"
               placeholder="— — — — — —"
               maxlength="6"
               inputmode="numeric"
               pattern="[0-9]*"
               autocomplete="one-time-code"
               value="{{ old('otp') }}"
               required
               autofocus
               style="letter-spacing: 0.75rem; height: 62px; font-size: 1.85rem; text-indent: 0.5rem;">

        @error('otp')
            <div class="text-center text-rose-600 small mt-2 d-flex align-items-center justify-content-center gap-1 fw-medium">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <div class="text-center text-slate-400 small mt-2 d-flex align-items-center justify-content-center gap-1">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Kode OTP berlaku selama <strong class="text-slate-600">15 menit</strong>.</span>
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-3">
        <button type="submit" id="btnVerifyOtp" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Verifikasi Email Sekarang</span>
        </button>
    </div>
</form>

{{-- Resend OTP Section --}}
<div class="text-center pt-3 mt-3" style="border-top: 1px solid #f1f5f9;">
    <p class="text-slate-500 small mb-2">Belum menerima kode OTP di email?</p>
    <form method="POST" action="{{ route('verification.send') }}" id="resendOtpForm" class="d-inline">
        @csrf
        <button type="submit"
                id="btnResendOtp"
                class="btn btn-link text-decoration-none fw-semibold p-0 small"
                style="color: #2563eb; transition: color 0.15s;">
            <span id="resendText">Kirim Ulang Kode OTP</span>
        </button>
    </form>
</div>

{{-- Navigation Options --}}
<div class="d-flex align-items-center justify-content-between pt-3 mt-3 border-top border-slate-100">
    @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link p-0 text-decoration-none small text-slate-600 hover-text-blue-600 fw-medium d-inline-flex align-items-center gap-1 border-0 bg-transparent">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Halaman Login</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link p-0 text-slate-400 text-decoration-none small hover-text-rose-600 border-0 bg-transparent">
                Keluar dari Akun
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="text-decoration-none small text-slate-600 hover-text-blue-600 fw-medium d-inline-flex align-items-center gap-1">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke Halaman Login</span>
        </a>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otpInput = document.getElementById('otp');
        const form = document.getElementById('otpVerificationForm');
        const resendForm = document.getElementById('resendOtpForm');
        const btnResend = document.getElementById('btnResendOtp');
        const resendText = document.getElementById('resendText');

        if (otpInput) {
            // Hanya izinkan angka numerik
            otpInput.addEventListener('input', function (e) {
                this.value = this.value.replace(/[^0-9]/g, '');

                // Auto-submit saat 6 digit lengkap terisi
                if (this.value.length === 6) {
                    form.submit();
                }
            });

            // Format saat paste
            otpInput.addEventListener('paste', function (e) {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                const cleanData = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
                this.value = cleanData;

                if (cleanData.length === 6) {
                    form.submit();
                }
            });
        }

        // Tangani cooldown kirim ulang jika baru saja dikirim
        const lastSentKey = 'tokobii_otp_last_sent_' + '{{ Auth::id() ?? 0 }}';
        const cooldownSeconds = 60;

        function startCooldown(remaining) {
            btnResend.setAttribute('disabled', 'disabled');
            btnResend.style.pointerEvents = 'none';
            btnResend.style.opacity = '0.6';

            const interval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    btnResend.removeAttribute('disabled');
                    btnResend.style.pointerEvents = 'auto';
                    btnResend.style.opacity = '1';
                    resendText.textContent = 'Kirim Ulang Kode OTP';
                    localStorage.removeItem(lastSentKey);
                } else {
                    resendText.textContent = `Kirim Ulang Kode (${remaining}s)`;
                }
            }, 1000);
        }

        // Cek apakah ada status baru dikirim
        @if(session('status') == 'verification-link-sent' || session('success'))
            localStorage.setItem(lastSentKey, Date.now());
            startCooldown(cooldownSeconds);
        @else
            const lastSent = localStorage.getItem(lastSentKey);
            if (lastSent) {
                const elapsed = Math.floor((Date.now() - parseInt(lastSent, 10)) / 1000);
                if (elapsed < cooldownSeconds) {
                    startCooldown(cooldownSeconds - elapsed);
                } else {
                    localStorage.removeItem(lastSentKey);
                }
            }
        @endif

        if (resendForm) {
            resendForm.addEventListener('submit', function () {
                localStorage.setItem(lastSentKey, Date.now());
            });
        }
    });
</script>
@endpush

@endsection