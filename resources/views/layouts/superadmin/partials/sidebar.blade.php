<aside class="offcanvas-lg offcanvas-start tokobii-sidebar" id="adminSidebarMenu" tabindex="-1" aria-labelledby="adminSidebarMenuLabel">

    {{-- Brand Header --}}
    <div class="offcanvas-header px-4 py-3 d-flex align-items-center justify-content-between" style="border-bottom: 1px solid #f1f5f9; height: 64px;">
        <a href="{{ route('superadmin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="d-flex align-items-center justify-content-center text-white rounded-xl"
                 style="width: 36px; height: 36px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 10px; box-shadow: 0 3px 10px rgba(37,99,235,0.35); flex-shrink: 0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <div class="fw-bold text-slate-900" style="font-size: 1.0625rem; line-height: 1.15; letter-spacing: -0.01em; color: #000000 !important;">Tokobii</div>
                <div class="fw-bold" style="font-size: 0.625rem; letter-spacing: 0.1em; text-transform: uppercase; color: #000000 !important;">Panel Superadmin</div>
            </div>
        </a>
        <button type="button" 
                class="admin-sidebar-toggle-btn border-0 bg-white p-1 rounded-3 shadow-none d-flex align-items-center justify-content-center"
                aria-label="Tutup Sidebar" 
                title="Tutup Sidebar"
                style="width: 34px; height: 34px; color: #475569; cursor: pointer; transition: all 0.2s ease;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    {{-- System Status Pill --}}
    @php
        $isDown = app()->isDownForMaintenance();
    @endphp
    <div class="px-3 pt-3">
        <div class="p-2.5 rounded-3 d-flex align-items-center gap-2" style="background: {{ $isDown ? '#fef2f2' : '#f0fdf4' }}; border: 1px solid {{ $isDown ? '#fca5a5' : '#86efac' }};">
            <span class="{{ $isDown ? 'pulse-dot-red' : 'pulse-dot-green' }}"></span>
            <div class="min-w-0">
                <div style="color: {{ $isDown ? '#991b1b' : '#14532d' }}; font-weight: 800; font-size: 0.6875rem; letter-spacing: 0.05em; text-transform: uppercase;">
                    {{ $isDown ? 'Mode Perbaikan Aktif' : 'Sistem Normal' }}
                </div>
                <div style="color: {{ $isDown ? '#7f1d1d' : '#166534' }}; font-weight: 600; font-size: 0.6875rem;" class="text-truncate">
                    {{ $isDown ? 'Publik dibatasi' : 'Aplikasi aktif & lancar' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation Body --}}
    <div class="offcanvas-body p-0 d-flex flex-column h-100" style="overflow-y: auto;">
        <div class="px-3 pt-2 pb-2 d-flex flex-column flex-grow-1 gap-1">

            {{-- Main Navigation Menu --}}
            <div class="sidebar-section-label" style="color: #475569; font-weight: 700;">Menu Utama</div>

            <a href="{{ route('superadmin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dasbor & Sistem</span>
            </a>

            <a href="{{ route('superadmin.login-histories') }}"
               class="sidebar-link {{ request()->routeIs('superadmin.login-histories*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Riwayat Login</span>
            </a>

            {{-- User Info Card at bottom --}}
            <div class="mt-auto pt-3 pb-1">
                {{-- Mini User Card --}}
                <div class="rounded-xl p-3 mb-3" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid #cbd5e1; border-radius: 14px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 36px; height: 36px; font-size: 0.8rem; background: linear-gradient(135deg, #3b82f6, #1d4ed8); flex-shrink: 0; box-shadow: 0 2px 8px rgba(37,99,235,0.30);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-truncate" style="font-size: 0.8125rem; color: #000000 !important;">{{ Auth::user()->name }}</div>
                            <div class="text-truncate" style="font-size: 0.72rem; color: #000000 !important; font-weight: 700;">Superadministrator</div>
                        </div>
                    </div>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</aside>
