@extends('layouts.superadmin.app')

@section('title', 'Pemeliharaan Sistem & Incident Response - Tokobii')

@section('content')

<style>
    .dashboard-action-card {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 40px;
        padding: 0.55rem 1rem;
        border: 1px solid #2563eb;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff !important;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.14);
        transition: all 0.15s ease;
    }
    .dashboard-action-card:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        transform: translateY(-1px);
    }
    .dashboard-action-card-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 40px;
        padding: 0.55rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
        color: #2563eb !important;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .dashboard-action-card-outline:hover {
        background: #eff6ff;
        border-color: #93c5fd;
    }
</style>

<div class="container-fluid px-0">

    {{-- Page Header Card --}}
    <div class="tokobii-header-card mb-4 animate-fade-in-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge tokobii-badge-warning">System Health & Incident Console</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1">System Maintenance & Incident Response</h1>
                <p class="text-slate-500 mb-0 small">
                    Audit konfigurasi lingkungan server, manajemen cache artisan, dan protokol darurat DEFCON 1.
                </p>
            </div>
            <div>
                <a href="{{ route('superadmin.dashboard') }}" class="btn btn-tokobii-secondary btn-tokobii-sm d-flex align-items-center gap-1.5">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 1. DEFCON 1 Panic Console (Emergency Response) --}}
    <div id="panic-console" class="tokobii-card mb-4 p-4 animate-fade-in-up" style="border: 2px solid {{ $isMaintenanceMode ? '#ef4444' : '#fca5a5' }}; background: #ffffff;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; flex-shrink: 0;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="h5 fw-bold text-slate-900 mb-0 d-flex align-items-center gap-2">
                        DEFCON 1 // GLOBAL PANIC BUTTON
                        @if($isMaintenanceMode)
                            <span class="tokobii-badge tokobii-badge-danger">MAINTENANCE MODE AKTIF</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-success">SISTEM NORMAL</span>
                        @endif
                    </h2>
                    <span class="text-slate-500 small">Protokol pemutusan akses darurat seketika & force logout seluruh pengguna</span>
                </div>
            </div>

            @if($isMaintenanceMode)
                <form action="{{ route('superadmin.maintenance.stand-down') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-success fw-bold px-4 py-2" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan Maintenance Mode dan mengembalikan sistem ke kondisi normal?')">
                        RESTORE NORMAL (STAND DOWN)
                    </button>
                </form>
            @endif
        </div>

        @if($isMaintenanceMode)
            <div class="p-3 mb-3 rounded-3 small" style="background: #fef2f2; border: 1px dashed #ef4444; color: #991b1b;">
                <div class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="pulse-dot-red"></span> STATUS INSIDEN: APLIKASI SEDANG DALAM MODE MAINTENANCE (503)
                </div>
                <div>Seluruh sesi pengguna publik telah diputus paksa. Pengguna umum menerima respons 503 Service Unavailable.</div>
                @if($activeBypassSecret)
                    <div class="mt-2 pt-2 border-top border-danger-subtle">
                        Token Bypass Superadmin: <strong class="text-slate-900">{{ $activeBypassSecret }}</strong><br>
                        URL Bypass: <a href="{{ url('/' . $activeBypassSecret) }}" class="text-blue-600 fw-semibold" target="_blank">{{ url('/' . $activeBypassSecret) }}</a>
                    </div>
                @endif
            </div>
        @else
            <div class="row g-4 align-items-center pt-2">
                <div class="col-lg-7">
                    <p class="text-slate-600 small mb-2">Saat tombol darurat ini dieksekusi, sistem secara instan menjalankan prosedur mitigasi kritis:</p>
                    <ul class="text-slate-500 small mb-0 ps-3">
                        <li class="mb-1"><strong class="text-slate-800">Force Logout Total:</strong> Menghapus seluruh sesi pengguna aktif dari tabel database & file session.</li>
                        <li class="mb-1"><strong class="text-slate-800">Maintenance Mode:</strong> Menjalankan <code>php artisan down</code> untuk memblokir lalu lintas publik dengan halaman 503.</li>
                        <li><strong class="text-slate-800">Zero Trust Bypass URL:</strong> Membuat token akses rahasia agar Superadmin tetap dapat mengakses sistem.</li>
                    </ul>
                </div>
                <div class="col-lg-5">
                    <div class="p-3 rounded-3" style="background: #fff5f5; border: 1px solid #fee2e2;">
                        <form action="{{ route('superadmin.maintenance.panic') }}" method="POST" onsubmit="return confirm('PERINGATAN DEFCON 1: Apakah Anda benar-benar yakin ingin mengeksekusi Panic Button? Seluruh pengguna akan di-logout paksa dan server masuk ke Maintenance Mode!');">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Superadmin</label>
                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Masukkan kata sandi akun Anda..." required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="reason" class="form-control form-control-sm" placeholder="Alasan insiden (cth: Indikasi SQLi Attack / Data Breach)">
                            </div>
                            <button type="submit" class="btn btn-danger w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-semibold shadow-sm" style="background: #dc2626; border-color: #dc2626;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span>Eksekusi DEFCON 1 Panic Button</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- 2. Cache Manager UI --}}
    <div class="tokobii-card mb-4 animate-fade-in-up">
        <div class="p-4 border-bottom border-slate-100 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-lg bg-blue-50 text-blue-600 p-1.5 rounded-3">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h2 class="h5 fw-bold text-slate-900 mb-0">Manajemen Cache Artisan</h2>
                    <span class="text-slate-400 small">Pembersihan cache aplikasi secara instan tanpa perlu akses terminal / SSH</span>
                </div>
            </div>
            <span class="tokobii-badge tokobii-badge-info">Terminal-Free Engine</span>
        </div>

        <div class="p-4">
            <div class="row g-3">
                {{-- Optimize Clear --}}
                <div class="col-md-6 col-xl-4">
                    <form action="{{ route('superadmin.maintenance.cache') }}" method="POST" class="h-100">
                        @csrf
                        <input type="hidden" name="type" value="optimize">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <div class="fw-bold text-slate-900 mb-1 font-monospace small">optimize:clear</div>
                                <div class="text-slate-500 small mb-3">Membersihkan config, route, view, dan cache aplikasi sekaligus.</div>
                            </div>
                            <button type="submit" class="dashboard-action-card w-100">
                                <span>Flush All Optimize</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Config Clear --}}
                <div class="col-md-6 col-xl-4">
                    <form action="{{ route('superadmin.maintenance.cache') }}" method="POST" class="h-100">
                        @csrf
                        <input type="hidden" name="type" value="config">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <div class="fw-bold text-slate-900 mb-1 font-monospace small">config:clear</div>
                                <div class="text-slate-500 small mb-3">Menghapus file cache konfigurasi .env & config php.</div>
                            </div>
                            <button type="submit" class="dashboard-action-card-outline w-100">
                                <span>Clear Config</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Route Clear --}}
                <div class="col-md-6 col-xl-4">
                    <form action="{{ route('superadmin.maintenance.cache') }}" method="POST" class="h-100">
                        @csrf
                        <input type="hidden" name="type" value="route">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <div class="fw-bold text-slate-900 mb-1 font-monospace small">route:clear</div>
                                <div class="text-slate-500 small mb-3">Menghapus cache registrasi route Laravel.</div>
                            </div>
                            <button type="submit" class="dashboard-action-card-outline w-100">
                                <span>Clear Routes</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- View Clear --}}
                <div class="col-md-6 col-xl-4">
                    <form action="{{ route('superadmin.maintenance.cache') }}" method="POST" class="h-100">
                        @csrf
                        <input type="hidden" name="type" value="view">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <div class="fw-bold text-slate-900 mb-1 font-monospace small">view:clear</div>
                                <div class="text-slate-500 small mb-3">Membersihkan file kompilasi Blade templates.</div>
                            </div>
                            <button type="submit" class="dashboard-action-card-outline w-100">
                                <span>Clear Blade Views</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Cache Clear --}}
                <div class="col-md-6 col-xl-4">
                    <form action="{{ route('superadmin.maintenance.cache') }}" method="POST" class="h-100">
                        @csrf
                        <input type="hidden" name="type" value="cache">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <div class="fw-bold text-slate-900 mb-1 font-monospace small">cache:clear</div>
                                <div class="text-slate-500 small mb-3">Menghapus seluruh cache data aplikasi (Redis/File).</div>
                            </div>
                            <button type="submit" class="dashboard-action-card-outline w-100">
                                <span>Clear App Cache</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Environment & Security Health Matrix --}}
    <div class="row g-4 animate-fade-in-up">
        <div class="col-lg-8">
            <div class="tokobii-card h-100 overflow-hidden">
                <div class="p-4 border-bottom border-slate-100">
                    <h2 class="h5 fw-bold text-slate-900 mb-0">Matriks Audit Konfigurasi & Keamanan Server</h2>
                    <span class="text-slate-400 small">Pemeriksaan integritas environment runtime dan perizinan sistem</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #f8fafc;">
                            <tr>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Parameter</th>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Status / Nilai</th>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Diagnosa Keamanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($envAudit as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-slate-800 small font-monospace">{{ $item['label'] }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($item['status'] === 'secure')
                                            <span class="tokobii-badge tokobii-badge-success">{{ $item['value'] }}</span>
                                        @elseif($item['status'] === 'warning')
                                            <span class="tokobii-badge tokobii-badge-warning">{{ $item['value'] }}</span>
                                        @else
                                            <span class="tokobii-badge tokobii-badge-danger">{{ $item['value'] }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 small">
                                        {{ $item['message'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Disk Capacity & Specs --}}
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4 h-100">
                <div class="tokobii-card p-4">
                    <h3 class="h6 fw-bold text-slate-900 mb-3">Kapasitas Storage Server</h3>

                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <span class="text-slate-500 small">Penggunaan Disk</span>
                        <span class="fw-bold text-slate-900 font-monospace">{{ $systemStats['disk_usage_percent'] }}%</span>
                    </div>

                    <div class="progress mb-3" style="height: 8px; background-color: #f1f5f9; border-radius: 4px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $systemStats['disk_usage_percent'] }}%; background-color: {{ $systemStats['disk_usage_percent'] > 85 ? '#ef4444' : '#2563eb' }}; border-radius: 4px;"></div>
                    </div>

                    <div class="d-flex justify-content-between small font-monospace text-slate-400 pt-2 border-top border-slate-100">
                        <span>Terpakai: {{ $systemStats['disk_used_gb'] }} GB</span>
                        <span>Sisa: {{ $systemStats['disk_free_gb'] }} GB</span>
                    </div>
                </div>

                <div class="tokobii-card p-4 flex-grow-1">
                    <h3 class="h6 fw-bold text-slate-900 mb-3">Spesifikasi Platform</h3>
                    <div class="d-flex flex-column gap-2 small font-monospace text-slate-600">
                        <div class="d-flex justify-content-between py-2 border-bottom border-slate-100">
                            <span>Laravel Core:</span>
                            <span class="fw-bold text-slate-900">v{{ $systemStats['laravel_version'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom border-slate-100">
                            <span>PHP Runtime:</span>
                            <span class="fw-bold text-slate-900">{{ PHP_VERSION }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom border-slate-100">
                            <span>Session Driver:</span>
                            <span class="fw-bold text-blue-600">{{ config('session.driver') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Database Driver:</span>
                            <span class="fw-bold text-blue-600">{{ config('database.default') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
