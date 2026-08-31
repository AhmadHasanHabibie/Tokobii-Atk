@extends('layouts.guest.auth')

@section('title', 'Verifikasi 2 Langkah - ' . config('app.name', 'Tokobii'))

@section('subtitle', 'Verifikasi identitas Anda untuk melanjutkan masuk.')

@section('content')

{{-- Info Header --}}
<div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
         style="width: 68px; height: 68px; background: linear-gradient(135deg, #eff6ff, #dbeafe); box-shadow: 0 0 0 8px rgba(219,234,254,0.5);">
        <svg width="32" height="32" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
    </div>
    <h2 class="fw-bold text-slate-900 mb-1" style="font-size: 1.25rem; letter-spacing: -0.01em;">Verifikasi 2 Langkah</h2>
    <p class="text-slate-500 small mb-2">Kode 6 digit telah dikirim ke email:</p>
    <div class="d-inline-block fw-bold font-monospace px-3 py-1 rounded-pill small"
         style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border: 1px solid #e2e8f0; color: #334155; letter-spacing: 0.04em;">
        {{ $maskedEmail }}
    </div>
</div>

{{-- OTP Form --}}
<form method="POST" action="{{ route('two-factor.verify') }}" id="twoFactorForm">
    @csrf

    <div class="mb-4">
        <label for="code" class="form-label text-center d-block fw-semibold text-slate-700 mb-2">
            Masukkan Kode OTP 6 Digit
        </label>
        <input type="text"
               id="code"
               name="code"
               class="form-control tokobii-input text-center fw-bold font-monospace @error('code') is-invalid @enderror"
               placeholder="— — — — — —"
               maxlength="6"
               inputmode="numeric"
               pattern="[0-9]*"
               autocomplete="one-time-code"
               value="{{ old('code') }}"
               required
               autofocus
               style="letter-spacing: 0.75rem; height: 60px; font-size: 1.75rem; text-indent: 0.5rem;">
        @error('code')
            <div class="text-center text-rose-600 small mt-2 d-flex align-items-center justify-content-center gap-1">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @enderror
        <div class="text-center text-slate-400 small mt-2 d-flex align-items-center justify-content-center gap-1">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Kode berlaku selama <strong class="text-slate-600 ms-1">5 menit</strong>.
        </div>
    </div>

    {{-- Submit --}}
    <div class="d-grid mb-3">
        <button type="submit" id="btnSubmitOtp" class="btn btn-tokobii-primary btn-tokobii-lg">
            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Verifikasi & Masuk</span>
        </button>
    </div>
</form>

{{-- Resend Section --}}
<div class="text-center pt-3 mt-2" style="border-top: 1px solid #f1f5f9;">
    <p class="text-slate-500 small mb-2">Tidak menerima kode?</p>
    <form method="POST" action="{{ route('two-factor.resend') }}" id="resendForm" class="d-inline">
        @csrf
        <button type="submit"
                id="btnResend"
                class="btn btn-link text-decoration-none fw-semibold p-0 small"
                style="color: #3b82f6; transition: color 0.15s;"
                @if($cooldown > 0) disabled @endif>
            <span id="resendText">
                @if($cooldown > 0)
                    Kirim Ulang (<span id="countdownTimer">{{ $cooldown }}</span>s)
                @else
                    Kirim Ulang Kode Verifikasi
                @endif
            </span>
        </button>
    </form>
</div>

{{-- Cancel --}}
<div class="text-center mt-3">
    <form method="POST" action="{{ route('two-factor.cancel') }}" class="d-inline">
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const codeInput = document.getElementById('code');
    const form = document.getElementById('twoFactorForm');
    const btnResend = document.getElementById('btnResend');
    const resendText = document.getElementById('resendText');
    const countdownTimer = document.getElementById('countdownTimer');

    if (codeInput) {
        codeInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) form.submit();
        });
        codeInput.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            const cleanDigits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
            if (cleanDigits.length > 0) {
                this.value = cleanDigits;
                if (cleanDigits.length === 6) form.submit();
            }
        });
    }

    let remaining = {{ (int) $cooldown }};
    if (remaining > 0 && btnResend) {
        const interval = setInterval(function () {
            remaining--;
            if (remaining > 0) {
                if (countdownTimer) countdownTimer.textContent = remaining;
                else resendText.innerHTML = 'Kirim Ulang (' + remaining + 's)';
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
