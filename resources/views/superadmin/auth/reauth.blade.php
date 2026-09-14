@extends('layouts.superadmin.app')

@section('title', 'Zero Trust Re-Authentication - Tokobii')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="tokobii-card p-4 p-sm-5 animate-fade-in-up" style="border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: #eff6ff; border: 1px solid #dbeafe;">
                        <svg width="32" height="32" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="h5 fw-bold text-slate-900 mb-1">Zero Trust Re-Authentication</h2>
                    <p class="text-slate-500 small mb-0">Area ini berisi kontrol operasi kritikal. Harap masukkan kata sandi akun Superadmin Anda untuk verifikasi identitas.</p>
                </div>

                <div class="p-3 mb-4 rounded-3 small" style="background: #eff6ff; border: 1px dashed #bfdbfe; color: #1e40af;">
                    <div class="fw-bold mb-1">PROTOKOL KEAMANAN ZERO TRUST:</div>
                    &bull; Otorisasi berlaku selama 15 menit.<br>
                    &bull; Setiap aktivitas tercatat dalam audit log forensik.
                </div>

                <form action="{{ route('superadmin.reauth.confirm') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="password" class="form-label small fw-bold text-slate-700">Kata Sandi Superadmin</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-slate-200 text-slate-400">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required autofocus style="border-left: 0;">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg">
                            Verifikasi Otorisasi
                        </button>
                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-tokobii-secondary btn-tokobii-sm text-center">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
