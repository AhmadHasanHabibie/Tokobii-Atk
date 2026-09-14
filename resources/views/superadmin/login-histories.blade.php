@extends('layouts.superadmin.app')

@section('title', 'Riwayat Login Pengguna - Superadmin')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Breadcrumbs & Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.8125rem;">
                    <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}" class="text-decoration-none" style="color: #334155; font-weight: 600;">Dasbor</a></li>
                    <li class="breadcrumb-item active" style="color: #0f172a; font-weight: 700;" aria-current="page">Riwayat Login</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-slate-900 mb-1">Riwayat Login Pengguna</h1>
            <p class="mb-0" style="font-size: 0.9375rem; color: #334155; font-weight: 500;">
                Log aktivitas autentikasi masuk (sukses & gagal) terekam otomatis oleh Event Listener Laravel.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-lg d-flex align-items-center gap-1.5 fw-semibold shadow-xs" style="color: #1e293b; border-color: #cbd5e1;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Dasbor</span>
            </a>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-xl p-3 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div style="font-size: 0.8125rem; color: #1e293b; font-weight: 700;" class="mb-1">Total Percobaan Login</div>
                <div class="h4 fw-bold mb-0" style="color: #0f172a; font-weight: 800;">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-xl p-3 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div style="font-size: 0.8125rem; color: #166534; font-weight: 700;" class="mb-1">Login Berhasil (Sukses)</div>
                <div class="h4 fw-bold mb-0" style="color: #15803d; font-weight: 800;">{{ number_format($stats['success']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card border-0 shadow-sm rounded-xl p-3 bg-white" style="border: 1px solid #e2e8f0 !important;">
                <div style="font-size: 0.8125rem; color: #991b1b; font-weight: 700;" class="mb-1">Login Ditolak (Gagal)</div>
                <div class="h4 fw-bold mb-0" style="color: #b91c1c; font-weight: 800;">{{ number_format($stats['failed']) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="card border-0 shadow-sm rounded-2xl mb-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-3 p-sm-4">
            <form method="GET" action="{{ route('superadmin.login-histories') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-slate-500 border-end-0">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Cari berdasarkan IP, User Agent, nama, atau email..." value="{{ request('search') }}" style="color: #0f172a; font-weight: 500;">
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <select name="status" class="form-select form-select-sm" style="color: #0f172a; font-weight: 600;">
                        <option value="">Semua Status Login</option>
                        <option value="sukses" {{ request('status') === 'sukses' ? 'selected' : '' }}>Hanya Sukses</option>
                        <option value="gagal" {{ request('status') === 'gagal' ? 'selected' : '' }}>Hanya Gagal</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-semibold rounded-lg shadow-xs">
                        Terapkan Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('superadmin.login-histories') }}" class="btn btn-outline-secondary btn-sm rounded-lg" title="Reset Filter" style="color: #1e293b; border-color: #cbd5e1;">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card border-0 shadow-sm rounded-2xl bg-white" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
                    <thead style="background-color: #f1f5f9; color: #0f172a; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">
                        <tr style="border-bottom: 2px solid #cbd5e1;">
                            <th scope="col" class="ps-4" style="width: 170px;">Waktu Percobaan</th>
                            <th scope="col">Akun Pengguna</th>
                            <th scope="col">Alamat IP</th>
                            <th scope="col">Perangkat / User Agent</th>
                            <th scope="col" class="text-end pe-4" style="width: 120px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $item)
                            <tr>
                                <td class="ps-4 whitespace-nowrap">
                                    <div class="fw-bold" style="color: #0f172a;">{{ $item->created_at->translatedFormat('d M Y') }}</div>
                                    <div style="color: #334155; font-size: 0.75rem; font-weight: 500;">{{ $item->created_at->format('H:i:s') }} WIB ({{ $item->created_at->diffForHumans() }})</div>
                                </td>
                                <td>
                                    @if($item->user)
                                        <div class="fw-bold" style="color: #000000 !important;">{{ $item->user->name }}</div>
                                        <div style="color: #334155; font-size: 0.75rem; font-weight: 600;">
                                            {{ $item->user->email }}
                                            <span class="badge ms-1" style="background: #f1f5f9; color: #000000 !important; border: 1px solid #cbd5e1; font-weight: 800; font-size: 0.68rem;">{{ ucfirst($item->user->role) }}</span>
                                        </div>
                                    @else
                                        <span class="badge" style="background: #e2e8f0; color: #334155; border: 1px solid #cbd5e1; font-weight: 600; padding: 4px 8px; border-radius: 20px;">
                                            Pengunjung / Tidak Ditemukan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="font-monospace px-2 py-0.5 rounded" style="color: #0f172a; font-weight: 700; background: #e2e8f0;">{{ $item->ip_address }}</span>
                                        @if(\App\Models\BlockedIp::isBlocked($item->ip_address))
                                            <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-weight: 700; font-size: 0.68rem;">Terblokir</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 320px; color: #334155; font-weight: 500;" title="{{ $item->user_agent }}">
                                        {{ $item->user_agent ?: '-' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    @if($item->isSuccess())
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
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mb-2 text-slate-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span style="color: #334155; font-weight: 600;">Belum ada riwayat login yang cocok dengan kriteria pencarian Anda.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($histories->hasPages())
                <div class="p-3 border-top border-slate-100 d-flex justify-content-between align-items-center">
                    <div style="color: #334155; font-weight: 600; font-size: 0.78rem;">
                        Menampilkan {{ $histories->firstItem() ?? 0 }} - {{ $histories->lastItem() ?? 0 }} dari {{ $histories->total() }} catatan
                    </div>
                    <div>
                        {{ $histories->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
