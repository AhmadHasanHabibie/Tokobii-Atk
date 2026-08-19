<aside class="offcanvas-lg offcanvas-start tokobii-sidebar border-end border-slate-200" id="customerSidebarMenu" tabindex="-1" aria-labelledby="customerSidebarMenuLabel">
    {{-- Header with Brand Logo --}}
    <div class="offcanvas-header border-bottom border-slate-100 p-3.5 d-flex align-items-center justify-content-between">
        <a href="{{ route('customer.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2 py-1 shadow-sm" style="background-color: #2563eb;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </span>
            <div class="d-flex flex-column">
                <span class="fw-bold text-slate-900 tracking-tight" style="color: #0f172a; font-size: 1.05rem; line-height: 1.2;">Tokobii</span>
                <span class="text-slate-400 text-uppercase fw-semibold" style="color: #64748b; font-size: 0.65rem; letter-spacing: 0.08em;">Panel Pelanggan</span>
            </div>
        </a>
        <button type="button" class="btn-close d-lg-none text-reset shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#customerSidebarMenu" aria-label="Tutup"></button>
    </div>

    {{-- Offcanvas Body / Navigation Menu --}}
    <div class="offcanvas-body p-0 d-flex flex-column h-100" style="overflow-y: auto;">
        <div class="p-3 d-flex flex-column flex-grow-1 gap-1">

            {{-- Dashboard Group --}}
            <a href="{{ route('customer.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Shopping Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Belanja & Katalog
            </div>

            <a href="{{ route('customer.shop.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.shop.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Katalog Produk</span>
            </a>

            <a href="{{ route('customer.cart.index') }}"
               class="sidebar-link d-flex align-items-center justify-content-between {{ request()->routeIs('customer.cart.*') ? 'active' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Keranjang Saya</span>
                </div>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="tokobii-badge tokobii-badge-info px-2 py-0.5" style="font-size: 0.7rem;">{{ count(session('cart')) }}</span>
                @endif
            </a>

            {{-- Transactions & Activity Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Transaksi & Aktivitas
            </div>

            <a href="{{ route('customer.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.orders.*') && !request()->routeIs('customer.orders.reports.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span>Riwayat Pesanan</span>
            </a>

            <a href="{{ route('customer.reviews.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.reviews.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                <span>Ulasan Saya</span>
            </a>

            <a href="{{ route('customer.reports.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.reports.*') || request()->routeIs('customer.orders.reports.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Laporan Masalah</span>
            </a>

            {{-- Account Group --}}
            <div class="px-3 pt-3 pb-1 text-uppercase text-slate-400 fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.08em;">
                Pengaturan Akun
            </div>

            <a href="{{ route('customer.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
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
