@extends('layouts.superadmin.app')

@section('title', 'IP Blacklist Shield - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Page Header Card --}}
    <div class="tokobii-header-card mb-4 animate-fade-in-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge tokobii-badge-danger">Active Threat Defense</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1">Daftar Hitam IP (Blacklist Shield)</h1>
                <p class="text-slate-500 mb-0 small">
                    Daftar alamat IP yang diblokir secara permanen oleh sistem honeypot atau manual oleh Superadmin (return 403 Forbidden).
                </p>
            </div>
            <div>
                <button type="button" class="btn btn-danger btn-sm d-flex align-items-center gap-1.5 fw-semibold shadow-sm px-3 py-2" style="border-radius: 10px; background: #dc2626; border-color: #dc2626;" data-bs-toggle="modal" data-bs-target="#addBlacklistModal">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Blokir IP Baru</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="tokobii-card p-4 mb-4 animate-fade-in-up">
        <form action="{{ route('superadmin.blacklist.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-slate-200 text-slate-400">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari IP address, alasan pemblokiran, atau user agent..." style="border-left: 0;">
            </div>
            <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm">
                Cari
            </button>
            @if($search)
                <a href="{{ route('superadmin.blacklist.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Blacklist Table --}}
    <div class="tokobii-card overflow-hidden animate-fade-in-up">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #f8fafc;">
                    <tr>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Alamat IP</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Alasan Pemblokiran</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Hit Count (403)</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Status</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Diblokir Oleh</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Tanggal</th>
                        <th class="text-uppercase text-slate-400 fw-bold px-4 py-3 text-end" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blacklists as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="font-monospace small fw-bold text-danger">{{ $item->ip_address }}</span>
                            </td>
                            <td class="px-4 py-3 small">
                                <div class="text-slate-800 fw-semibold">{{ $item->reason }}</div>
                                @if($item->user_agent)
                                    <div class="font-monospace text-slate-400 text-truncate" style="font-size: 0.7rem; max-width: 280px;" title="{{ $item->user_agent }}">{{ $item->user_agent }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-amber-50 text-amber-700 border border-amber-200">{{ $item->hit_count }} hits</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->is_permanent)
                                    <span class="tokobii-badge tokobii-badge-danger">PERMANEN</span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-warning">TEMPORER</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 small font-monospace text-slate-600">
                                {{ $item->blocked_by }}
                            </td>
                            <td class="px-4 py-3 small font-monospace text-slate-500 text-nowrap">
                                {{ $item->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                <form action="{{ route('superadmin.blacklist.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuka blokir IP {{ $item->ip_address }}?');" class="m-0 d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-success fw-semibold" style="font-size: 0.75rem; border-radius: 6px;">
                                        Unblock
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-slate-400 small">
                                Belum ada alamat IP yang tercatat dalam daftar blokir.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($blacklists->hasPages())
            <div class="p-3 d-flex justify-content-center border-top border-slate-100">
                {{ $blacklists->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Add Blacklist Modal --}}
<div class="modal fade" id="addBlacklistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom border-slate-100 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-lg bg-red-50 text-red-600 p-1.5 rounded-3">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                    <h5 class="modal-title fw-bold text-slate-900 fs-6">Blokir Alamat IP Secara Manual</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.blacklist.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-slate-700">Alamat IP (IPv4 / IPv6)</label>
                        <input type="text" name="ip_address" class="form-control font-monospace @error('ip_address') is-invalid @enderror" placeholder="cth: 192.168.1.100 atau 103.45.21.9" required>
                        @error('ip_address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-slate-700">Alasan Pemblokiran</label>
                        <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror" placeholder="cth: Percobaan Brute Force / DDoS Scanner" required>
                        @error('reason')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_permanent" value="1" id="isPermanentSwitch" checked>
                        <label class="form-check-label small text-slate-700" for="isPermanentSwitch">
                            Blokir Permanen (Non-kadaluarsa)
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top border-slate-100 px-4 py-3">
                    <button type="button" class="btn btn-tokobii-secondary btn-tokobii-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm fw-semibold" style="border-radius: 8px; background: #dc2626; border-color: #dc2626;">Simpan Blokir IP</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
