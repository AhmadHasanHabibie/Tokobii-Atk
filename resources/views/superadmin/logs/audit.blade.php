@extends('layouts.superadmin.app')

@section('title', 'Audit & Forensik Log - Tokobii')

@section('content')

<style>
    .dashboard-small-action-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        min-height: 32px;
        padding: 0.35rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        color: #2563eb !important;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .dashboard-small-action-outline:hover {
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
                    <span class="tokobii-badge tokobii-badge-info">Deep Forensics Trail</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1">Audit & Forensik Log Keamanan</h1>
                <p class="text-slate-500 mb-0 small">
                    Catatan komprehensif seluruh aktivitas berisiko, jebakan honeypot, dan akses sistem dengan rekaman payload tersanitasi.
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

    {{-- Filter Card --}}
    <div class="tokobii-card p-4 mb-4 animate-fade-in-up">
        <form action="{{ route('superadmin.logs.audit') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-slate-200 text-slate-400">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari IP, endpoint, payload..." style="border-left: 0;">
                </div>
            </div>

            <div class="col-md-3">
                <select name="severity" class="form-select form-select-sm">
                    <option value="">Semua Tingkat Keparahan (Severity)</option>
                    <option value="critical" {{ $severity === 'critical' ? 'selected' : '' }}>CRITICAL</option>
                    <option value="high" {{ $severity === 'high' ? 'selected' : '' }}>HIGH</option>
                    <option value="medium" {{ $severity === 'medium' ? 'selected' : '' }}>MEDIUM</option>
                    <option value="low" {{ $severity === 'low' ? 'selected' : '' }}>LOW</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="event_type" class="form-select form-select-sm">
                    <option value="">Semua Event Type</option>
                    @foreach($eventTypes as $type)
                        <option value="{{ $type }}" {{ $eventType === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm flex-grow-1">
                    Filter
                </button>
                @if($search || $severity || $eventType)
                    <a href="{{ route('superadmin.logs.audit') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Audit Table --}}
    <div class="tokobii-card overflow-hidden animate-fade-in-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc;">
                    <tr>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Waktu</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Severity</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Event Type</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Alamat IP</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">User</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Method & Target</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">HTTP Status</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3 text-end" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Payload</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-4 py-3 text-nowrap font-monospace text-slate-500" style="font-size: 0.75rem;">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-4 py-3">
                                @if($log->severity === 'critical')
                                    <span class="tokobii-badge tokobii-badge-danger">CRITICAL</span>
                                @elseif($log->severity === 'high')
                                    <span class="tokobii-badge tokobii-badge-warning">HIGH</span>
                                @elseif($log->severity === 'medium')
                                    <span class="tokobii-badge tokobii-badge-info">MEDIUM</span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-success">LOW</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-monospace small fw-semibold text-slate-800">{{ $log->event_type }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-monospace small fw-bold text-blue-600">{{ $log->ip_address }}</span>
                            </td>
                            <td class="px-4 py-3 small">
                                @if($log->user)
                                    <div class="fw-semibold text-slate-800">{{ $log->user->name }}</div>
                                    <div class="text-slate-400 font-monospace" style="font-size: 0.7rem;">{{ $log->user->email }}</div>
                                @else
                                    <span class="text-slate-400 font-monospace">[GUEST / BOT]</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="badge bg-slate-100 text-slate-700 border border-slate-200 font-monospace">{{ $log->method }}</span>
                                    <span class="font-monospace small text-slate-700 text-truncate" style="max-width: 200px;" title="{{ $log->endpoint }}">{{ $log->endpoint }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($log->response_status >= 500)
                                    <span class="tokobii-badge tokobii-badge-danger">{{ $log->response_status }}</span>
                                @elseif($log->response_status >= 400)
                                    <span class="tokobii-badge tokobii-badge-warning">{{ $log->response_status }}</span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-success">{{ $log->response_status ?? 200 }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <button type="button" class="dashboard-small-action-outline" onclick="viewPayload({{ $log->id }})">
                                    Inspect
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-slate-400 small">
                                Tidak ada rekaman log audit keamanan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-3 d-flex justify-content-center border-top border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Forensic Modal --}}
<div class="modal fade" id="payloadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom border-slate-100 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-lg bg-blue-50 text-blue-600 p-1.5 rounded-3">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h5 class="modal-title fw-bold text-slate-900 fs-6">Inspeksi Forensik Raw Payload</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-2 mb-3 small font-monospace">
                    <div class="col-sm-6 text-slate-500">EVENT_TYPE: <span id="mEvent" class="text-slate-900 fw-bold"></span></div>
                    <div class="col-sm-6 text-slate-500">IP_ADDRESS: <span id="mIp" class="text-blue-600 fw-bold"></span></div>
                    <div class="col-sm-6 text-slate-500">TARGET: <span id="mEndpoint" class="text-slate-900 fw-semibold"></span></div>
                    <div class="col-sm-6 text-slate-500">STATUS_CODE: <span id="mStatus" class="text-amber-600 fw-bold"></span></div>
                    <div class="col-12 text-slate-500">USER_AGENT: <span id="mUserAgent" class="text-slate-700" style="word-break: break-all;"></span></div>
                </div>
                <label class="form-label text-slate-500 small fw-bold text-uppercase">Sanitized Raw Payload (POST / JSON / Params):</label>
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
                document.getElementById('mUserAgent').innerText = data.user_agent || 'N/A';
                
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
