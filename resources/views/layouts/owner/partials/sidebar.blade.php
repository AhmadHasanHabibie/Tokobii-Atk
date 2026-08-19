<aside class="offcanvas-lg offcanvas-start tokobii-sidebar border-end border-slate-200" id="ownerSidebarMenu" tabindex="-1" aria-labelledby="ownerSidebarMenuLabel">
    {{-- Header with Brand Logo --}}
    <div class="offcanvas-header border-bottom border-slate-100 p-3.5 d-flex align-items-center justify-content-between">
        <a href="{{ route('owner.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2 py-1 shadow-sm" style="background-color: #2563eb;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </span>
            <div class="d-flex flex-column">
                <span class="fw-bold text-slate-900 tracking-tight" style="color: #0f172a; font-size: 1.05rem; line-height: 1.2;">Tokobii</span>
                <span class="text-slate-400 text-uppercase fw-semibold" style="color: #64748b; font-size: 0.65rem; letter-spacing: 0.08em;">Panel Pemilik (Owner)</span>
            </div>
        </a>
        <button type="button" class="btn-close d-lg-none text-reset shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#ownerSidebarMenu" aria-label="Tutup"></button>
    </div>

    {{-- Offcanvas Body / Navigation Menu --}}
    <div class="offcanvas-body p-0 d-flex flex-column h-100" style="overflow-y: auto;">
        <div class="p-3 d-flex flex-column flex-grow-1 gap-1">

            {{-- Dashboard Group --}}
            <a href="{{ route('owner.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span>Dashboard Eksekutif</span>
            </a>

            {{-- Account Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Pengaturan Akun
            </div>

            <a href="{{ route('owner.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('owner.profile.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Saya</span>
            </a>

        </div>

        {{-- Sidebar Footer / Logout --}}
        <div class="p-3 border-top border-slate-100 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-tokobii-secondary w-100 text-danger border-rose-200 hover-bg-rose-50 d-flex align-items-center justify-content-center gap-2" style="font-size: 0.8125rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </div>
</aside>