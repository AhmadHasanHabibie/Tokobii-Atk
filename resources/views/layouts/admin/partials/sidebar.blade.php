<aside class="offcanvas-lg offcanvas-start tokobii-sidebar border-end border-slate-200" id="adminSidebarMenu" tabindex="-1" aria-labelledby="adminSidebarMenuLabel">
    {{-- Header with Brand Logo --}}
    <div class="offcanvas-header border-bottom border-slate-100 p-3.5 d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2 py-1 shadow-sm" style="background-color: #2563eb;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </span>
            <div class="d-flex flex-column">
                <span class="fw-bold text-slate-900 tracking-tight" style="color: #0f172a; font-size: 1.05rem; line-height: 1.2;">Tokobii</span>
                <span class="text-slate-400 text-uppercase fw-semibold" style="color: #64748b; font-size: 0.65rem; letter-spacing: 0.08em;">Panel Admin</span>
            </div>
        </a>
        <button type="button" class="btn-close d-lg-none text-reset shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebarMenu" aria-label="Tutup"></button>
    </div>

    {{-- Offcanvas Body / Navigation Menu --}}
    <div class="offcanvas-body p-0 d-flex flex-column h-100" style="overflow-y: auto;">
        <div class="p-3 d-flex flex-column flex-grow-1 gap-1">

            {{-- Dashboard Group --}}
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Master Data Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Master Data
            </div>

            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span>Produk</span>
            </a>

            {{-- Customer Monitoring Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Pemantauan Pelanggan
            </div>

            <a href="{{ route('admin.customers.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>Pelanggan</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                <span>Ulasan</span>
            </a>

            {{-- Transactions Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Transaksi & Pengambilan
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.scan') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>Pesanan & Pengambilan</span>
            </a>

            <a href="{{ route('admin.orders.scan') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders.scan') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span>Scan QR Pesanan</span>
            </a>

            {{-- Reports Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Laporan & Masalah
            </div>

            <a href="{{ route('admin.reports.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Laporan</span>
            </a>

            {{-- Account & System Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Akun & Sistem
            </div>

            <a href="{{ Route::has('admin.owners.create') ? route('admin.owners.create') : '#' }}"
               class="sidebar-link {{ request()->routeIs('admin.owners.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>Buat Akun Owner</span>
            </a>

            <a href="{{ route('admin.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Saya</span>
            </a>

            {{-- Bottom Sign Out --}}
            <div class="mt-auto pt-4 pb-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn border border-slate-200 text-slate-600 hover-bg-rose-50 hover-text-rose-600 w-100 rounded-3 py-2 px-3 d-flex align-items-center justify-content-center gap-2 font-medium shadow-none" style="font-size: 0.85rem;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</aside>
