<nav class="navbar navbar-expand bg-white border-bottom border-slate-200 sticky-top py-2.5 shadow-xs" style="border-color: #e2e8f0; z-index: 1020;">
    <div class="container-fluid px-3 px-md-4">
        
        {{-- Mobile Offcanvas Sidebar Toggle Button --}}
        <button class="btn btn-light border border-slate-200 shadow-none d-lg-none me-3 py-1.5 px-2.5 rounded-2" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#adminSidebarMenu" 
                aria-controls="adminSidebarMenu" 
                aria-label="Navigasi Menu">
            <svg class="w-5 h-5 text-slate-700" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Page Context Title / Brand on Mobile --}}
        <div class="d-flex align-items-center gap-2">
            <div class="d-none d-sm-block text-slate-400 font-monospace" style="font-size: 0.8125rem;">
                Sistem Manajemen Admin Tokobii
            </div>
        </div>

        {{-- Right User Profile Dropdown --}}
        <div class="dropdown ms-auto">
            <button
                class="btn bg-slate-100 text-slate-700 hover-bg-slate-200 border-0 rounded-pill px-3 py-1.5 d-flex align-items-center gap-2 fw-medium shadow-none"
                type="button"
                id="adminDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="background-color: #f1f5f9; color: #334155;">

                <div class="rounded-circle bg-blue-600 text-white d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem; background-color: #2563eb;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <span class="text-sm font-semibold" style="font-size: 0.875rem;">{{ Auth::user()->name }}</span>

                <svg class="w-4 h-4 text-slate-400" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3 mt-2" style="border: 1px solid #e2e8f0; min-width: 220px;">
                <li class="px-3 py-2 border-bottom mb-1" style="border-color: #f1f5f9;">
                    <div class="fw-bold text-slate-900" style="color: #0f172a; font-size: 0.875rem;">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-muted text-truncate" style="color: #64748b; font-size: 0.75rem;">
                        {{ Auth::user()->email }}
                    </div>
                </li>

                {{-- Profile Links --}}
                <li>
                    <a class="dropdown-item rounded-2 py-2 px-3 d-flex align-items-center gap-2 text-slate-700" style="font-size: 0.85rem;" href="{{ route('admin.profile.index') }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Lihat Profil</span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item rounded-2 py-2 px-3 d-flex align-items-center gap-2 text-slate-700" style="font-size: 0.85rem;" href="{{ route('admin.profile.edit') }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Edit Akun</span>
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider my-1" style="border-color: #f1f5f9;">
                </li>

                {{-- Logout --}}
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-2 py-2 px-3 d-flex align-items-center gap-2 text-danger" style="font-size: 0.85rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>