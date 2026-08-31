<nav class="navbar navbar-expand tokobii-navbar px-3 px-md-4" style="z-index: 1020;">
    <div class="container-fluid px-0">
        
        {{-- Mobile Sidebar Toggle --}}
        <button class="btn d-lg-none me-3 p-2 rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-all" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#adminSidebarMenu" 
                aria-controls="adminSidebarMenu" 
                aria-label="Menu"
                style="border-radius: 12px; transition: all 0.2s ease;">
            <svg class="text-slate-600" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Page Context / Brand --}}
        <div class="d-flex align-items-center gap-2">
            <div class="d-none d-sm-flex align-items-center gap-2">
                <div class="rounded-lg bg-blue-50 text-blue-500 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 8px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <span class="text-slate-500 fw-medium" style="font-size: 0.8125rem;">Panel Admin Tokobii</span>
            </div>
        </div>

        {{-- Right: User Pill Dropdown --}}
        <div class="dropdown ms-auto">
            <button
                class="user-avatar-pill border-0"
                type="button"
                id="adminDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width: 32px; height: 32px; font-size: 0.75rem; background: linear-gradient(135deg, #60a5fa, #2563eb); box-shadow: 0 2px 6px rgba(37,99,235,0.35); flex-shrink: 0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <span class="fw-semibold text-slate-700 d-none d-sm-block" style="font-size: 0.875rem;">{{ Auth::user()->name }}</span>

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
                    <a class="dropdown-item tokobii-dropdown-item" href="{{ route('admin.profile.index') }}">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Lihat Profil</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item tokobii-dropdown-item" href="{{ route('admin.profile.edit') }}">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Edit Akun</span>
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