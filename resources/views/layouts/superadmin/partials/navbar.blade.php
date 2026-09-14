@php
    $isDown = app()->isDownForMaintenance();
@endphp

<nav class="navbar navbar-expand tokobii-navbar px-3 px-md-4" style="z-index: 1020;">
    <div class="container-fluid px-0">
        
        {{-- Sidebar Reopen Hamburger Button --}}
        <button class="admin-sidebar-toggle-btn admin-navbar-toggle-btn admin-hamburger-btn me-3"
                type="button"
                aria-label="Toggle Menu"
                title="Buka Sidebar">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Page Context & Status Indicator --}}
        <div class="d-flex align-items-center gap-2">
            <div class="d-none d-sm-flex align-items-center gap-2">
                <div class="rounded-lg bg-blue-50 text-blue-600 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <span class="text-slate-800 fw-bold" style="font-size: 0.875rem;">Panel Kontrol Superadmin</span>
            </div>

            @if($isDown)
                <span class="badge rounded-pill d-inline-flex align-items-center gap-1.5 px-2.5 py-1 ms-1" style="font-size: 0.75rem; font-weight: 700; background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;">
                    <span class="pulse-dot-red"></span> Mode Pemeliharaan Aktif
                </span>
            @else
                <span class="badge rounded-pill d-inline-flex align-items-center gap-1.5 px-2.5 py-1 ms-1" style="font-size: 0.75rem; font-weight: 700; background: #dcfce7; color: #166534; border: 1px solid #86efac;">
                    <span class="pulse-dot-green"></span> Sistem Normal
                </span>
            @endif
        </div>

        {{-- Right: Actions & User Dropdown --}}
        <div class="d-flex align-items-center gap-2 ms-auto">

            {{-- Quick Toggle Button: Triggers Custom Modal (No Native Popups) --}}
            @if($isDown)
                <button type="button" class="btn btn-sm btn-success fw-semibold px-3 py-1.5 d-flex align-items-center gap-1.5 shadow-xs" style="border-radius: 8px; font-size: 0.8125rem;" data-bs-toggle="modal" data-bs-target="#customMaintenanceModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Matikan Pemeliharaan</span>
                </button>
            @else
                <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold px-3 py-1.5 d-flex align-items-center gap-1.5" style="border-radius: 8px; font-size: 0.8125rem;" data-bs-toggle="modal" data-bs-target="#customMaintenanceModal">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="d-none d-sm-inline">Mode Perbaikan</span>
                </button>
            @endif

            {{-- User Avatar Dropdown --}}
            <div class="dropdown ms-1">
                <button
                    class="user-avatar-pill border-0"
                    type="button"
                    id="superadminDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width: 32px; height: 32px; font-size: 0.75rem; background: linear-gradient(135deg, #3b82f6, #1d4ed8); box-shadow: 0 2px 6px rgba(37,99,235,0.35); flex-shrink: 0;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <span class="fw-bold text-slate-800 d-none d-sm-block" style="font-size: 0.875rem;">{{ Auth::user()->name }}</span>

                    <svg class="text-slate-500" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <ul class="dropdown-menu dropdown-menu-end tokobii-dropdown-menu border-0 mt-2 shadow-lg">
                    <li class="px-3 py-2 mb-1">
                        <div class="fw-bold text-slate-900" style="font-size: 0.875rem; color: #000000 !important;">{{ Auth::user()->name }}</div>
                        <div class="text-truncate" style="font-size: 0.75rem; font-weight: 600; color: #334155;">{{ Auth::user()->email }}</div>
                        <span class="badge mt-1" style="background-color: #f1f5f9; color: #000000 !important; border: 1px solid #cbd5e1; font-weight: 800; font-size: 0.7rem; padding: 4px 10px;">Superadmin</span>
                    </li>
                    <li><hr class="tokobii-divider my-1 mx-2"></li>

                    <li>
                        <a class="dropdown-item tokobii-dropdown-item" href="{{ route('superadmin.dashboard') }}">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>Dasbor & Sistem</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item tokobii-dropdown-item" href="{{ route('superadmin.login-histories') }}">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Riwayat Login</span>
                        </a>
                    </li>
                    <li><hr class="tokobii-divider my-1 mx-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item tokobii-dropdown-item danger w-100 text-left border-0 bg-transparent">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

{{-- Custom Maintenance Mode Modal (Full Custom, zero browser alert/confirm) --}}
<div class="modal fade" id="customMaintenanceModal" tabindex="-1" aria-labelledby="customMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg p-2" style="border-radius: 20px;">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 {{ $isDown ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="width: 64px; height: 64px;">
                    @if($isDown)
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @endif
                </div>

                <h4 class="fw-bold text-slate-900 mb-2" style="font-size: 1.25rem;">
                    {{ $isDown ? 'Matikan Mode Pemeliharaan?' : 'Aktifkan Mode Pemeliharaan?' }}
                </h4>

                <p class="mb-4" style="font-size: 0.875rem; line-height: 1.5; color: #334155; font-weight: 500;">
                    @if($isDown)
                        Sistem toko Tokobii akan dibuka kembali untuk semua pelanggan, admin, dan pemilik toko.
                    @else
                        Pengunjung umum dan akun selain Superadmin akan dialihkan ke halaman pemeliharaan. Anda tetap dapat mengakses aplikasi dengan izin Superadmin.
                    @endif
                </p>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-50 py-2.5 fw-semibold text-slate-700 rounded-xl" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <form action="{{ route('superadmin.maintenance.toggle') }}" method="POST" class="w-50 m-0">
                        @csrf
                        <button type="submit" class="btn {{ $isDown ? 'btn-success' : 'btn-danger' }} w-100 py-2.5 fw-semibold rounded-xl shadow-sm">
                            {{ $isDown ? 'Ya, Buka Toko' : 'Ya, Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
