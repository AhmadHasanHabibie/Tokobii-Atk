@extends('layouts.guest.auth')

@section('title', 'Verifikasi 2 Langkah - ' . config('app.name', 'Tokobii'))

@section('subtitle', 'Verifikasi identitas Anda untuk melanjutkan masuk ke Tokobii.')

@section('content')

{{-- Info Header --}}
<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center bg-blue-50 text-blue-600 rounded-circle mb-3 shadow-sm" style="width: 64px; height: 64px; background-color: #eff6ff; color: #2563eb;">
        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
    </div>
    <h2 class="h4 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Verifikasi 2 Langkah</h2>
    <p class="text-slate-500 small mb-2">
        Kami telah mengirimkan 6 digit kode verifikasi keamanan ke email:
    </p>
    <div class="d-inline-block bg-slate-100 text-slate-800 font-monospace fw-bold px-3 py-1 rounded-pill small border border-slate-200">
        {{ $maskedEmail }}
    </div>
</div>

{{-- OTP Verification Form --}}
<form method="POST" action="{{ route('two-factor.verify') }}" id="twoFactorForm">
    @csrf

    <div class="mb-4">
        <label for="code" class="form-label text-center d-block fw-semibold text-slate-700 mb-2">
            Masukkan Kode OTP 6 Digit
        </label>
        <div class="position-relative">
            <input type="text"
                   id="code"
                   name="code"
                   class="form-control text-center fw-bold fs-3 font-monospace tracking-widest tokobii-input @error('code') is-invalid @enderror"
                   placeholder="------"
                   maxlength="6"
                   inputmode="numeric"
                   pattern="[0-9]*"
                   autocomplete="one-time-code"
                   value="{{ old('code') }}"
                   required
                   autofocus
                   style="letter-spacing: 0.5rem; height: 56px; font-size: 1.65rem;">
        </div>
        @error('code')
            <div class="invalid-feedback d-block text-center text-rose-600 small mt-2">{{ $message }}</div>
        @enderror
        <div class="form-text text-center text-slate-400 small mt-2">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display: inline; vertical-align: -1px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Kode berlaku selama <strong>5 menit</strong>.
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="d-grid mb-3">
        <button type="submit" id="btnSubmitOtp" class="btn btn-tokobii-primary btn-tokobii-lg shadow-sm">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Verifikasi & Masuk</span>
        </button>
    </div>
</form>

{{-- Resend OTP Section --}}
<div class="text-center pt-2 border-top border-slate-100 mt-3">
    <p class="text-slate-500 small mb-2">Tidak menerima kode verifikasi?</p>
    
    <form method="POST" action="{{ route('two-factor.resend') }}" id="resendForm" class="d-inline">
        @csrf
        <button type="submit"
                id="btnResend"
                class="btn btn-sm btn-link text-decoration-none fw-semibold text-blue-600 hover-text-blue-700 p-0"
                @if($cooldown > 0) disabled @endif>
            <span id="resendText">
                @if($cooldown > 0)
                    Kirim Ulang Kode (<span id="countdownTimer">{{ $cooldown }}</span>s)
                @else
                    Kirim Ulang Kode Verifikasi
                @endif
            </span>
        </button>
    </form>
</div>

{{-- Cancel & Back to Login --}}
<div class="text-center mt-3">
    <form method="POST" action="{{ route('two-factor.cancel') }}" class="d-inline">
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const codeInput = document.getElementById('code');
    const form = document.getElementById('twoFactorForm');
    const btnResend = document.getElementById('btnResend');
    const resendText = document.getElementById('resendText');
    const countdownTimer = document.getElementById('countdownTimer');

    // Filter only numbers
    if (codeInput) {
        codeInput.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) {
                // Auto submit when 6 digits are typed/pasted
                form.submit();
            }
        });

        // Handle Paste
        codeInput.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            const cleanDigits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
            if (cleanDigits.length > 0) {
                this.value = cleanDigits;
                if (cleanDigits.length === 6) {
                    form.submit();
                }
            }
        });
    }

    // Cooldown countdown timer
    let remaining = {{ (int) $cooldown }};
    if (remaining > 0 && btnResend) {
        const interval = setInterval(function () {
            remaining--;
            if (remaining > 0) {
                if (countdownTimer) {
                    countdownTimer.textContent = remaining;
                } else {
                    resendText.innerHTML = 'Kirim Ulang Kode (' + remaining + 's)';
                }
            } else {
                clearInterval(interval);
                btnResend.removeAttribute('disabled');
                resendText.textContent = 'Kirim Ulang Kode Verifikasi';
            }
        }, 1000);
    }
});
</script>
@endpush

@endsection
