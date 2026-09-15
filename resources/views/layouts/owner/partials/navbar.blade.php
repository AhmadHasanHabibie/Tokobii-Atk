<nav class="navbar navbar-expand tokobii-navbar px-3 px-md-4" style="z-index: 1020;">
    <div class="container-fluid px-0">

        {{-- Sidebar Reopen Hamburger Button (Hanya tampil saat sidebar tertutup) --}}
        <button class="owner-sidebar-toggle-btn owner-navbar-toggle-btn owner-hamburger-btn me-3"
                type="button"
                aria-label="Toggle Menu"
                title="Buka Sidebar">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Badge & Context --}}
        <div class="d-flex align-items-center gap-2">
            <div class="d-none d-sm-flex align-items-center gap-2">
                <span class="tokobii-badge tokobii-badge-success">Mode Pemilik</span>
                <span class="text-slate-400 fw-medium" style="font-size: 0.8125rem;">Ringkasan Eksekutif</span>
            </div>
            <a href="{{ route('owner.sales.index') }}" class="btn btn-sm d-inline-flex align-items-center gap-1.5 fw-semibold {{ request()->routeIs('owner.sales.*') ? 'btn-primary' : 'btn-outline-primary' }} ms-2" style="border-radius: 8px; font-size: 0.8125rem; padding: 0.35rem 0.75rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Laporan Penjualan</span>
            </a>
        </div>

        {{-- Right: User Pill Dropdown --}}
        <div class="dropdown ms-auto">
            <button
                class="user-avatar-pill border-0"
                type="button"
                id="ownerNavbarDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width: 32px; height: 32px; font-size: 0.75rem; background: linear-gradient(135deg, #c084fc, #9333ea); box-shadow: 0 2px 6px rgba(147,51,234,0.35); flex-shrink: 0;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'O', 0, 1)) }}
                </div>

                <div class="d-none d-sm-flex flex-column text-start" style="line-height: 1.2;">
                    <span class="fw-semibold text-slate-700" style="font-size: 0.8125rem;">{{ Str::limit(Auth::user()->name, 16) }}</span>
                    <span class="text-slate-400" style="font-size: 0.6875rem;">Pemilik (Owner)</span>
                </div>

                <svg class="text-slate-400" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <ul class="dropdown-menu dropdown-menu-end tokobii-dropdown-menu border-0 mt-2">
                <li class="px-3 py-2 mb-1">
                    <div class="fw-bold text-slate-900" style="font-size: 0.875rem;">{{ Auth::user()->name }}</div>
                    <div class="text-truncate text-slate-400" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                </li>
                <li><hr class="tokobii-divider my-1 mx-2"></li>
                <li>
                    <a class="dropdown-item tokobii-dropdown-item" href="{{ route('owner.sales.index') }}">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Laporan Penjualan (PDF)</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item tokobii-dropdown-item" href="{{ route('owner.profile.index') }}">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Profil Saya</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item tokobii-dropdown-item" href="{{ route('owner.profile.edit') }}">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Edit Profil & Password</span>
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
</nav>