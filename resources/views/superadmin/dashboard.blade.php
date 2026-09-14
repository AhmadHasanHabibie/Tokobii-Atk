@extends('layouts.superadmin.app')

@section('title', 'Dasbor Kontrol Sistem - Superadmin')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1">Dasbor Kontrol Sistem</h1>
            <p class="mb-0" style="font-size: 0.9375rem; color: #334155; font-weight: 500;">
                Kelola pemeliharaan toko, proteksi IP, pencadangan database, dan pemantauan login secara praktis.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-white border border-slate-300 px-3 py-2 rounded-lg d-flex align-items-center gap-1.5 shadow-xs" style="font-size: 0.8125rem; font-weight: 700; color: #000000 !important;">
                <svg width="15" height="15" fill="none" stroke="#000000" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span style="color: #000000 !important; font-weight: 700;">{{ now()->translatedFormat('l, d F Y') }}</span>
            </span>
        </div>
    </div>

    {{-- Quick Stat Cards --}}
    <div class="row g-3 mb-4">
        {{-- Maintenance Mode Status --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 p-3" style="background: {{ $stats['is_maintenance'] ? 'linear-gradient(135deg, #fff1f2, #ffe4e6)' : 'linear-gradient(135deg, #f0fdf4, #dcfce7)' }}; border: 1px solid {{ $stats['is_maintenance'] ? '#fca5a5' : '#86efac' }} !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size: 0.8125rem; font-weight: 700; color: {{ $stats['is_maintenance'] ? '#991b1b' : '#166534' }};">Mode Pemeliharaan</span>
                    <div class="rounded-lg d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: {{ $stats['is_maintenance'] ? '#fee2e2' : '#dcfce7' }}; color: {{ $stats['is_maintenance'] ? '#b91c1c' : '#15803d' }};">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h4 fw-bold mb-1" style="color: {{ $stats['is_maintenance'] ? '#7f1d1d' : '#14532d' }}; font-weight: 800;">
                    {{ $stats['is_maintenance'] ? 'Aktif (Down)' : 'Nonaktif (Normal)' }}
                </div>
                <div style="font-size: 0.75rem; font-weight: 600; color: {{ $stats['is_maintenance'] ? '#991b1b' : '#166534' }};">
                    {{ $stats['is_maintenance'] ? 'Publik dialihkan ke halaman perbaikan' : 'Toko dapat diakses publik' }}
                </div>
            </div>
        </div>

        {{-- Blocked IPs Count --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 p-3 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size: 0.8125rem; font-weight: 700; color: #1e293b;">IP Dicekal (Blacklist)</span>
                    <div class="rounded-lg d-flex align-items-center justify-content-center bg-danger-subtle text-danger" style="width: 32px; height: 32px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
                <div class="h4 fw-bold mb-1" style="color: #0f172a; font-weight: 800;">{{ $stats['total_blocked_ips'] }} IP</div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #334155;">Akses ditolak (403 Forbidden)</div>
            </div>
        </div>

        {{-- Anti-DDoS Rate Limiting --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 p-3 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size: 0.8125rem; font-weight: 700; color: #1e293b;">Rate Limiter (Anti-DDoS)</span>
                    <div class="rounded-lg d-flex align-items-center justify-content-center bg-blue-subtle text-primary" style="width: 32px; height: 32px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h4 fw-bold mb-1" style="color: #1d4ed8; font-weight: 800;">Maks. 5 Req/Mnt</div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #334155;">Jalur login terlindungi (429 limit)</div>
            </div>
        </div>

        {{-- Today's Logins --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-xl h-100 p-3 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size: 0.8125rem; font-weight: 700; color: #1e293b;">Aktivitas Login Hari Ini</span>
                    <div class="rounded-lg d-flex align-items-center justify-content-center bg-indigo-subtle text-indigo-600" style="width: 32px; height: 32px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <div class="h4 fw-bold mb-1" style="color: #0f172a; font-weight: 800;">{{ $stats['total_logins_today'] }} Kali</div>
                <div class="d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                    <span style="color: #15803d; font-weight: 700;">{{ $stats['successful_logins_today'] }} sukses</span>
                    <span style="color: #64748b; font-weight: 700;">•</span>
                    <span style="color: #b91c1c; font-weight: 700;">{{ $stats['failed_logins_today'] }} gagal</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Grid: Control Cards --}}
    <div class="row g-4 mb-4">
        {{-- Section 1: Maintenance Mode Controller --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-2xl h-100 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white border-bottom border-slate-100 p-3 p-sm-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-xl d-flex align-items-center justify-content-center {{ $stats['is_maintenance'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}" style="width: 38px; height: 38px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="h6 fw-bold text-slate-900 mb-0">Mode Pemeliharaan Toko</h2>
                            <small style="color: #334155; font-weight: 500;">Kendalikan akses publik saat perbaikan atau rilis baru</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-sm-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="p-3 rounded-xl mb-3" style="background: {{ $stats['is_maintenance'] ? '#fef2f2' : '#f0fdf4' }}; border: 1px solid {{ $stats['is_maintenance'] ? '#fca5a5' : '#86efac' }};">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="{{ $stats['is_maintenance'] ? 'pulse-dot-red' : 'pulse-dot-green' }}"></span>
                                <strong style="font-size: 0.875rem; font-weight: 800; color: {{ $stats['is_maintenance'] ? '#991b1b' : '#14532d' }};">
                                    {{ $stats['is_maintenance'] ? 'Mode Perbaikan Sedang AKTIF' : 'Aplikasi Berjalan Normal' }}
                                </strong>
                            </div>
                            <p class="mb-0" style="font-size: 0.85rem; font-weight: 500; color: {{ $stats['is_maintenance'] ? '#7f1d1d' : '#166534' }}; line-height: 1.5;">
                                @if($stats['is_maintenance'])
                                    Semua pengunjung dialihkan ke halaman pemeliharaan (503). Sebagai Superadmin yang sedang login, Anda tetap dapat mengakses dan mengelola sistem secara penuh.
                                @else
                                    Sistem beroperasi normal untuk seluruh pelanggan, admin, dan pemilik toko Tokobii.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Custom Trigger Button (No Browser Alert / Confirm) --}}
                    <div class="pt-2 border-top border-slate-100 mt-2">
                        @if($stats['is_maintenance'])
                            <button type="button" class="btn btn-success w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 rounded-xl shadow-sm" data-bs-toggle="modal" data-bs-target="#customMaintenanceModal">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Matikan Mode Pemeliharaan (Buka Toko)</span>
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-danger w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 rounded-xl" data-bs-toggle="modal" data-bs-target="#customMaintenanceModal">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Nyalakan Mode Pemeliharaan (Tutup Sementara)</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Database Backup & Anti-DDoS Info --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-2xl h-100 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white border-bottom border-slate-100 p-3 p-sm-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-xl d-flex align-items-center justify-content-center bg-blue-subtle text-primary" style="width: 38px; height: 38px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="h6 fw-bold text-slate-900 mb-0">Cadangan Database & Anti-DDoS</h2>
                            <small style="color: #334155; font-weight: 500;">Unduh arsip data MySQL & pantau proteksi frekuensi request</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-sm-4 d-flex flex-column justify-content-between">
                    <div>
                        {{-- Database Backup Box --}}
                        <div class="p-3 rounded-xl mb-3" style="background: #f8fafc; border: 1px solid #cbd5e1;">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <strong style="font-size: 0.875rem; font-weight: 700; color: #0f172a;">Ekspor Basis Data MySQL (.sql)</strong>
                                    <p class="mb-0 mt-1" style="font-size: 0.8125rem; font-weight: 500; color: #334155; line-height: 1.5;">
                                        Mengekspor seluruh tabel, skema, produk, pesanan, dan akun Tokobii menjadi berkas SQL mandiri siap unduh.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.backup.download') }}" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 rounded-xl mt-2 shadow-sm">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span>Download Backup SQL Sekarang</span>
                            </a>
                        </div>

                        {{-- Anti-DDoS Rate Limiting Box --}}
                        <div class="p-3 rounded-xl" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <svg width="18" height="18" fill="none" stroke="#0284c7" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <strong style="font-size: 0.84rem; font-weight: 800; color: #0369a1;">Anti-DDoS & Brute-Force Rate Limiter:</strong>
                            </div>
                            <p class="mb-0" style="font-size: 0.8125rem; font-weight: 500; color: #0f172a; line-height: 1.55;">
                                Menggunakan <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 700; font-size: 0.78rem;">RateLimiter</span> bawaan Laravel di <span class="badge" style="background: #dbeafe; color: #1e40af; font-weight: 700; font-size: 0.78rem;">RouteServiceProvider</span>. Percobaan login dibatasi maksimal <strong style="color: #0369a1; font-weight: 800;">5 kali per menit per IP</strong>. Percobaan berlebih otomatis diblokir dengan respons <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 0.78rem;">429 Too Many Requests</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: IP Blocker (Blacklist) --}}
    <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-header bg-white border-bottom border-slate-100 p-3 p-sm-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-xl d-flex align-items-center justify-content-center bg-danger-subtle text-danger" style="width: 38px; height: 38px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="h6 fw-bold text-slate-900 mb-0">Pengelolaan IP Blocker (Blacklist)</h2>
                    <small style="color: #334155; font-weight: 500;">Blokir alamat IP mencurigakan secara instan (IpBlockerMiddleware akan menolak dengan HTTP 403)</small>
                </div>
            </div>
            <span class="badge" style="background: #e2e8f0; color: #0f172a; font-weight: 700; font-size: 0.78rem; padding: 6px 12px; border-radius: 20px;">
                {{ $blockedIps->total() }} IP Terdaftar
            </span>
        </div>

        <div class="card-body p-3 p-sm-4">
            {{-- Form Tambah IP --}}
            <div class="p-3 rounded-xl mb-4" style="background: #f8fafc; border: 1px solid #cbd5e1;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #0f172a;" class="mb-2">Tambah Alamat IP ke Daftar Blokir</h3>
                <form action="{{ route('superadmin.ip-blocker.store') }}" method="POST" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1" style="font-size: 0.8rem; font-weight: 700; color: #1e293b;">Alamat IP <span class="text-danger">*</span></label>
                        <input type="text" name="ip_address" class="form-control form-control-sm @error('ip_address') is-invalid @enderror" placeholder="Contoh: 192.168.1.100" value="{{ old('ip_address') }}" required style="color: #0f172a; font-weight: 600;">
                        @error('ip_address')
                            <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label mb-1" style="font-size: 0.8rem; font-weight: 700; color: #1e293b;">Alasan Pemblokiran</label>
                        <input type="text" name="reason" class="form-control form-control-sm @error('reason') is-invalid @enderror" placeholder="Contoh: Spamming form login berulang kali" value="{{ old('reason') }}" style="color: #0f172a; font-weight: 500;">
                        @error('reason')
                            <div class="invalid-feedback" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-danger btn-sm w-100 py-1.5 fw-semibold d-flex align-items-center justify-content-center gap-1.5 rounded-lg shadow-xs">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Blokir IP Ini</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel IP Terblokir --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
                    <thead style="background-color: #f1f5f9; color: #0f172a; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">
                        <tr style="border-bottom: 2px solid #cbd5e1;">
                            <th scope="col" style="width: 50px;">#</th>
                            <th scope="col">Alamat IP</th>
                            <th scope="col">Alasan Pemblokiran</th>
                            <th scope="col">Tanggal Diblokir</th>
                            <th scope="col" class="text-end" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blockedIps as $index => $item)
                            <tr>
                                <td style="color: #334155; font-weight: 600;">{{ $blockedIps->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge font-monospace" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-weight: 700; font-size: 0.8125rem; padding: 5px 10px;">
                                        {{ $item->ip_address }}
                                    </span>
                                </td>
                                <td style="color: #0f172a; font-weight: 600;">{{ $item->reason ?: '-' }}</td>
                                <td style="color: #334155; font-weight: 600;">{{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB</td>
                                <td class="text-end">
                                    {{-- Custom Delete Button (Triggers Custom Modal) --}}
                                    <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 py-1 rounded-lg d-inline-flex align-items-center gap-1" onclick="openDeleteIpModal('{{ $item->id }}', '{{ $item->ip_address }}', '{{ route('superadmin.ip-blocker.destroy', $item) }}')">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mb-1 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <span style="color: #334155; font-weight: 600;">Tidak ada alamat IP yang sedang diblokir. Sistem aman.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($blockedIps->hasPages())
                <div class="mt-3">
                    {{ $blockedIps->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Section 4: Cuplikan Riwayat Login Terakhir --}}
    <div class="card border-0 shadow-sm rounded-2xl bg-white" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-header bg-white border-bottom border-slate-100 p-3 p-sm-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-xl d-flex align-items-center justify-content-center bg-indigo-subtle text-indigo-600" style="width: 38px; height: 38px;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="h6 fw-bold text-slate-900 mb-0">Riwayat Login Pengguna Terakhir</h2>
                    <small style="color: #334155; font-weight: 500;">Terekam otomatis oleh Event Listener Laravel (Login & Failed)</small>
                </div>
            </div>
            <a href="{{ route('superadmin.login-histories') }}" class="btn btn-sm btn-outline-primary fw-bold px-3 py-1.5 rounded-lg d-flex align-items-center gap-1" style="font-size: 0.8125rem;">
                <span>Buka Seluruh Riwayat</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
                    <thead style="background-color: #f1f5f9; color: #0f172a; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">
                        <tr style="border-bottom: 2px solid #cbd5e1;">
                            <th scope="col" class="ps-4">Waktu</th>
                            <th scope="col">Pengguna / Email</th>
                            <th scope="col">Alamat IP</th>
                            <th scope="col">Perangkat / User Agent</th>
                            <th scope="col" class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogins as $log)
                            <tr>
                                <td class="ps-4 whitespace-nowrap" style="color: #334155; font-weight: 600;">
                                    {{ $log->created_at->translatedFormat('d M Y, H:i:s') }}
                                </td>
                                <td>
                                    @if($log->user)
                                        <div class="fw-bold" style="color: #000000 !important;">{{ $log->user->name }}</div>
                                        <div style="color: #334155; font-size: 0.75rem; font-weight: 600;">
                                            {{ $log->user->email }}
                                            <span class="badge ms-1" style="background: #f1f5f9; color: #000000 !important; border: 1px solid #cbd5e1; font-weight: 800; font-size: 0.68rem;">{{ ucfirst($log->user->role) }}</span>
                                        </div>
                                    @else
                                        <span class="fst-italic" style="color: #475569; font-weight: 600;">Pengunjung / Tidak Terdaftar</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-monospace" style="color: #0f172a; font-weight: 700;">{{ $log->ip_address }}</span>
                                </td>
                                <td class="text-truncate" style="max-width: 260px; color: #334155; font-weight: 500;" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 45) ?: '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    @if($log->isSuccess())
                                        <span class="badge rounded-pill fw-bold" style="background: #dcfce7; color: #166534; border: 1px solid #86efac; font-size: 0.78rem; padding: 5px 10px;">
                                            ✓ Sukses
                                        </span>
                                    @else
                                        <span class="badge rounded-pill fw-bold" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-size: 0.78rem; padding: 5px 10px;">
                                            ✕ Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color: #334155; font-weight: 600;">
                                    Belum ada catatan riwayat login.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Custom Modal for Deleting Blocked IP (Full custom, no native alert/confirm) --}}
<div class="modal fade" id="customDeleteIpModal" tabindex="-1" aria-labelledby="customDeleteIpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg p-2" style="border-radius: 20px;">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 bg-danger-subtle text-danger" style="width: 60px; height: 60px;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>

                <h4 class="fw-bold text-slate-900 mb-2" style="font-size: 1.2rem;">
                    Cabut Pemblokiran IP?
                </h4>

                <p style="color: #334155; font-size: 0.875rem; line-height: 1.5; font-weight: 500;" class="mb-4">
                    Alamat IP <strong style="color: #0f172a; font-weight: 700;" class="font-monospace" id="modalTargetIp"></strong> akan dihapus dari daftar blokir dan dapat kembali mengakses aplikasi Tokobii.
                </p>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 py-2.5 fw-semibold text-slate-700 rounded-xl" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <form id="deleteIpForm" method="POST" class="w-50 m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 py-2.5 fw-semibold rounded-xl shadow-sm">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteIpModal(id, ipAddress, actionUrl) {
        const modalIp = document.getElementById('modalTargetIp');
        const form = document.getElementById('deleteIpForm');
        
        if (modalIp) modalIp.textContent = ipAddress;
        if (form) form.action = actionUrl;

        const modalEl = document.getElementById('customDeleteIpModal');
        if (modalEl && window.bootstrap) {
            const modalInstance = new bootstrap.Modal(modalEl);
            modalInstance.show();
        }
    }
</script>
@endpush
@endsection
