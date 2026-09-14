@extends('layouts.superadmin.app')

@section('title', 'Dashboard IT Security & SOC - Tokobii')

@section('content')

<style>
    /* Admin Action Button Styling */
    .dashboard-action-card {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 42px;
        padding: 0.65rem 1rem;
        border: 1px solid #2563eb;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff !important;
        font-size: 0.8125rem;
        font-weight: 600;
        line-height: 1.2;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.14);
        transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
    }
    .dashboard-action-card:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff !important;
        box-shadow: 0 5px 12px rgba(37, 99, 235, 0.20);
        transform: translateY(-1px);
    }
    .dashboard-action-card:active {
        background: #1e40af;
        border-color: #1e40af;
        transform: translateY(0);
    }

    .dashboard-small-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        min-height: 32px;
        padding: 0.4rem 0.75rem;
        border: 1px solid #2563eb;
        border-radius: 8px;
        background: #2563eb;
        color: #ffffff !important;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.12);
        transition: all 0.15s ease;
    }
    .dashboard-small-action:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .dashboard-small-action-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        min-height: 32px;
        padding: 0.4rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        color: #475569 !important;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .dashboard-small-action-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #1e293b !important;
    }
</style>

<div class="container-fluid px-0">

    {{-- Page Header Card --}}
    <div class="tokobii-header-card mb-4 animate-fade-in-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge tokobii-badge-info">Security Operations Center (SOC)</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1">Dashboard IT Security & Forensik</h1>
                <p class="text-slate-500 mb-0 small">
                    Selamat datang kembali, <strong class="text-slate-800">{{ Auth::user()->name }}</strong>. Berikut adalah ringkasan telemetri keamanan dan ancaman siber Tokobii.
                </p>
            </div>

            {{-- Header Actions --}}
            <div class="d-flex align-items-center flex-wrap gap-2">
                <a href="{{ route('superadmin.logs.audit') }}" class="dashboard-action-card">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Inspeksi Audit Log</span>
                </a>

                <a href="{{ route('superadmin.maintenance.index') }}" class="dashboard-action-card" style="background: #0284c7; border-color: #0284c7;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    </svg>
                    <span>Health & Cache</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Environment Critical Warnings --}}
    @if(count($envAlerts) > 0)
        @foreach($envAlerts as $alert)
            <div class="alert tokobii-alert tokobii-alert-error mb-4 animate-fade-in-up" role="alert">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <div class="fw-bold">{{ $alert['title'] }}</div>
                    <div class="small">{{ $alert['desc'] }}</div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- 4 Main Stat Cards --}}
    <div class="row g-3 mb-4 animate-fade-in-up">

        {{-- 1. Total IP Terblokir --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            IP Terblokir Permanen
                        </span>
                        <h2 class="fw-bold text-slate-900 mb-0 mt-2" style="font-size: 1.75rem;">
                            {{ number_format($totalBlockedIps) }}
                        </h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background-color: #fef2f2; color: #dc2626;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-slate-100">
                    <a href="{{ route('superadmin.blacklist.index') }}" class="text-decoration-none small text-blue-600 fw-semibold d-inline-flex align-items-center gap-1">
                        <span>Kelola IP Blacklist</span>
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. Honeypot Hits --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Honeypot Decoy Hits
                        </span>
                        <h2 class="fw-bold text-slate-900 mb-0 mt-2" style="font-size: 1.75rem; color: #d97706;">
                            {{ number_format($recentHoneypotHits) }}
                        </h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background-color: #fffbeb; color: #d97706;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-slate-100">
                    <span class="text-slate-400 small">Terdeteksi dalam 7 hari terakhir</span>
                </div>
            </div>
        </div>

        {{-- 3. Critical Threats --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Insiden Kritis / Tinggi
                        </span>
                        <h2 class="fw-bold text-slate-900 mb-0 mt-2" style="font-size: 1.75rem; color: #dc2626;">
                            {{ number_format($criticalThreatsCount) }}
                        </h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background-color: #fef2f2; color: #dc2626;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-slate-100">
                    <span class="text-slate-400 small">Perlu perhatian tim IT security</span>
                </div>
            </div>
        </div>

        {{-- 4. Total Security Logs --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Security Logs
                        </span>
                        <h2 class="fw-bold text-slate-900 mb-0 mt-2" style="font-size: 1.75rem;">
                            {{ number_format($totalSecurityLogs) }}
                        </h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background-color: #eff6ff; color: #2563eb;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top border-slate-100">
                    <span class="text-slate-400 small">Telemetri tersanitasi otomatis</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Row: Recent Security Logs & Top Decoys --}}
    <div class="row g-4 animate-fade-in-up">

        {{-- Recent Logs Table --}}
        <div class="col-lg-8">
            <div class="tokobii-card h-100 overflow-hidden">
                <div class="p-4 border-bottom border-slate-100 d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="h5 fw-bold text-slate-900 mb-0">Insiden & Telemetri Forensik Terbaru</h2>
                        <span class="text-slate-400 small">Aktivitas mencurigakan yang terdeteksi real-time</span>
                    </div>
                    <a href="{{ route('superadmin.logs.audit') }}" class="dashboard-small-action">
                        <span>Lihat Semua</span>
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #f8fafc;">
                            <tr>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Waktu</th>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Severity & Event</th>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">IP & Endpoint</th>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Method</th>
                                <th class="text-uppercase text-slate-400 fw-bold px-4 py-3 text-end" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td class="px-4 py-3 text-nowrap font-monospace text-slate-500" style="font-size: 0.75rem;">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @if($log->severity === 'critical')
                                                <span class="tokobii-badge tokobii-badge-danger">CRITICAL</span>
                                            @elseif($log->severity === 'high')
                                                <span class="tokobii-badge tokobii-badge-warning">HIGH</span>
                                            @elseif($log->severity === 'medium')
                                                <span class="tokobii-badge tokobii-badge-info">MEDIUM</span>
                                            @else
                                                <span class="tokobii-badge tokobii-badge-success">LOW</span>
                                            @endif
                                            <span class="small font-monospace text-slate-700 fw-semibold">{{ $log->event_type }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-monospace fw-bold text-slate-900 small">{{ $log->ip_address }}</div>
                                        <div class="font-monospace text-slate-400 text-truncate" style="font-size: 0.75rem; max-width: 180px;">{{ $log->endpoint }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-slate-100 text-slate-700 border border-slate-200 font-monospace">{{ $log->method }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <button type="button" class="dashboard-small-action-outline" onclick="viewPayload({{ $log->id }})">
                                            Payload
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-slate-400 small">
                                        Belum ada rekaman insiden atau audit keamanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Honeypot Targets & Defense Shield --}}
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4 h-100">

                {{-- Top Decoys --}}
                <div class="tokobii-card p-4 flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h6 fw-bold text-slate-900 mb-0">Target Jebakan Paling Sering Diserang</h2>
                    </div>

                    @if($topAttackedEndpoints->count() > 0)
                        <div class="d-flex flex-column gap-2">
                            @foreach($topAttackedEndpoints as $target)
                                <div class="p-2.5 rounded-3 d-flex align-items-center justify-content-between" style="background: #f8fafc; border: 1px solid #f1f5f9;">
                                    <span class="font-monospace small fw-semibold text-danger">{{ $target->endpoint }}</span>
                                    <span class="badge bg-amber-50 text-amber-700 border border-amber-200">{{ $target->total }} hits</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-4 text-center text-slate-400 small">
                            Belum ada target decoy yang terkena scanning bot.
                        </div>
                    @endif
                </div>

                {{-- Active Defense Info --}}
                <div class="tokobii-card p-4" style="background: linear-gradient(135deg, #f0fdf4, #ffffff); border: 1px solid #bbf7d0;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="pulse-dot-green"></span>
                        <h3 class="h6 fw-bold text-slate-900 mb-0">Tokobii Active Defense</h3>
                    </div>
                    <p class="text-slate-600 small mb-3">
                        Perangkap Honeypot otomatis memblokir IP penyerang secara permanen saat mengakses rute decoy.
                    </p>
                    <a href="{{ route('superadmin.blacklist.index') }}" class="dashboard-action-card w-100" style="background: #dc2626; border-color: #dc2626;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>+ Tambah IP Blacklist</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- Forensic JSON Payload Modal (Clean Admin Light Design) --}}
<div class="modal fade" id="payloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom border-slate-100 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-lg bg-blue-50 text-blue-600 p-1.5 rounded-3">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h5 class="modal-title fw-bold text-slate-900 fs-6">Inspeksi Payload Forensik Tersanitasi</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-2 mb-3 small font-monospace">
                    <div class="col-sm-6 text-slate-500">EVENT: <span id="mEvent" class="text-slate-900 fw-bold"></span></div>
                    <div class="col-sm-6 text-slate-500">IP: <span id="mIp" class="text-blue-600 fw-bold"></span></div>
                    <div class="col-sm-6 text-slate-500">TARGET: <span id="mEndpoint" class="text-slate-900 fw-semibold"></span></div>
                    <div class="col-sm-6 text-slate-500">HTTP CODE: <span id="mStatus" class="text-amber-600 fw-bold"></span></div>
                </div>
                <label class="form-label text-slate-500 small fw-bold text-uppercase">Sanitized Request Payload:</label>
                <pre class="p-3 rounded-3 font-monospace small overflow-auto text-slate-800" id="mPayloadContent" style="background: #f8fafc; border: 1px solid #e2e8f0; max-height: 380px;"></pre>
            </div>
            <div class="modal-footer border-top border-slate-100 px-4 py-3">
                <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function viewPayload(logId) {
        fetch(`/superadmin/logs/audit/${logId}/payload`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('mEvent').innerText = data.event_type;
                document.getElementById('mIp').innerText = data.ip_address;
                document.getElementById('mEndpoint').innerText = `${data.method} ${data.endpoint}`;
                document.getElementById('mStatus').innerText = data.response_status || 'N/A';
                
                let raw = data.raw_payload;
                try {
                    const parsed = JSON.parse(raw);
                    raw = JSON.stringify(parsed, null, 2);
                } catch(e) {}

                document.getElementById('mPayloadContent').innerText = raw || '// [KOSONG] Tidak ada data payload request.';
                const modal = new bootstrap.Modal(document.getElementById('payloadModal'));
                modal.show();
            })
            .catch(err => {
                alert('Gagal mengambil detail payload log forensik.');
            });
    }
</script>
@endpush

@endsection
