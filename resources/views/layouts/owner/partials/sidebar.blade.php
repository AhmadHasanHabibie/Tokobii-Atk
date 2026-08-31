<aside class="offcanvas-lg offcanvas-start tokobii-sidebar" id="ownerSidebarMenu" tabindex="-1" aria-labelledby="ownerSidebarMenuLabel">

    {{-- Brand Header --}}
    <div class="offcanvas-header px-4 py-3 d-flex align-items-center justify-content-between" style="border-bottom: 1px solid #f1f5f9; height: 64px;">
        <a href="{{ route('owner.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="d-flex align-items-center justify-content-center text-white"
                 style="width: 36px; height: 36px; background: linear-gradient(135deg, #a855f7, #7c3aed); border-radius: 10px; box-shadow: 0 3px 10px rgba(124,58,237,0.35); flex-shrink: 0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <div class="fw-bold text-slate-900" style="font-size: 1.0625rem; line-height: 1.15; letter-spacing: -0.01em;">Tokobii</div>
                <div class="fw-semibold text-slate-400" style="font-size: 0.625rem; letter-spacing: 0.1em; text-transform: uppercase;">Panel Pemilik</div>
            </div>
        </a>
        <button type="button" class="btn-close d-lg-none shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#ownerSidebarMenu" aria-label="Tutup"></button>
    </div>

    {{-- Navigation --}}
    <div class="offcanvas-body p-0 d-flex flex-column h-100" style="overflow-y: auto;">
        <div class="px-3 pt-3 pb-2 d-flex flex-column flex-grow-1 gap-1">

            <a href="{{ route('owner.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span>Dashboard Eksekutif</span>
            </a>

            <div class="sidebar-section-label">Akun</div>

            <a href="{{ route('owner.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('owner.profile.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Saya</span>
            </a>

            {{-- Bottom User Card + Logout --}}
            <div class="mt-auto pt-3 pb-1">
                <div class="rounded-xl p-3 mb-3" style="background: linear-gradient(135deg, #faf5ff, #f3e8ff); border: 1px solid #e9d5ff; border-radius: 14px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 36px; height: 36px; font-size: 0.8rem; background: linear-gradient(135deg, #c084fc, #9333ea); flex-shrink: 0; box-shadow: 0 2px 8px rgba(147,51,234,0.30);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-slate-800 text-truncate" style="font-size: 0.8125rem;">{{ Auth::user()->name }}</div>
                            <div class="text-slate-400 text-truncate" style="font-size: 0.7rem;">Pemilik (Owner)</div>
                        </div>
                    </div>
                </div>
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