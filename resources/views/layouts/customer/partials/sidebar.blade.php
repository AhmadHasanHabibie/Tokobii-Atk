<aside class="offcanvas-lg offcanvas-start tokobii-sidebar" id="customerSidebarMenu" tabindex="-1" aria-labelledby="customerSidebarMenuLabel">

    {{-- Brand Header --}}
    <div class="offcanvas-header px-4 py-3 d-flex align-items-center justify-content-between" style="border-bottom: 1px solid #f1f5f9; height: 64px;">
        <a href="{{ route('customer.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="d-flex align-items-center justify-content-center text-white"
                 style="width: 36px; height: 36px; background: linear-gradient(135deg, #34d399, #10b981); border-radius: 10px; box-shadow: 0 3px 10px rgba(16,185,129,0.35); flex-shrink: 0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <div class="fw-bold text-slate-900" style="font-size: 1.0625rem; line-height: 1.15; letter-spacing: -0.01em;">Tokobii</div>
                <div class="fw-semibold text-slate-400" style="font-size: 0.625rem; letter-spacing: 0.1em; text-transform: uppercase;">Pelanggan</div>
            </div>
        </a>
        <button type="button" class="btn-close d-lg-none shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#customerSidebarMenu" aria-label="Tutup"></button>
    </div>

    {{-- Navigation Body --}}
    <div class="offcanvas-body p-0 d-flex flex-column h-100" style="overflow-y: auto;">
        <div class="px-3 pt-3 pb-2 d-flex flex-column flex-grow-1 gap-1">

            <a href="{{ route('customer.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-label">Belanja</div>

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
                    <span class="tokobii-badge tokobii-badge-info" style="font-size: 0.68rem; padding: 0.2rem 0.6rem;">{{ count(session('cart')) }}</span>
                @endif
            </a>

            <div class="sidebar-section-label">Aktivitas</div>

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

            <div class="sidebar-section-label">Akun</div>

            <a href="{{ route('customer.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil Saya</span>
            </a>

            {{-- Bottom User Card + Logout --}}
            <div class="mt-auto pt-3 pb-1">
                <div class="rounded-xl p-3 mb-3" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #a7f3d0; border-radius: 14px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 36px; height: 36px; font-size: 0.8rem; background: linear-gradient(135deg, #34d399, #10b981); flex-shrink: 0; box-shadow: 0 2px 8px rgba(16,185,129,0.30);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-slate-800 text-truncate" style="font-size: 0.8125rem;">{{ Auth::user()->name }}</div>
                            <div class="text-slate-400 text-truncate" style="font-size: 0.7rem;">Pelanggan</div>
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
