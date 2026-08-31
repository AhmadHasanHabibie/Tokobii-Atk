<nav class="navbar navbar-expand tokobii-navbar px-3 px-md-4" style="z-index: 1020;">
    <div class="container-fluid px-0">

        {{-- Left: Mobile Toggle + Search --}}
        <div class="d-flex align-items-center gap-2">
            <button class="btn d-lg-none p-2 border border-slate-200 bg-white shadow-sm"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#customerSidebarMenu"
                    aria-controls="customerSidebarMenu"
                    style="border-radius: 12px;">
                <svg class="text-slate-600" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <a href="{{ route('customer.shop.index') }}"
               class="btn btn-tokobii-secondary btn-tokobii-sm d-none d-sm-inline-flex align-items-center gap-2">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span class="text-slate-500">Cari Produk ATK...</span>
            </a>
        </div>

        {{-- Right: Cart + User --}}
        <div class="d-flex align-items-center gap-2 ms-auto">

            {{-- Cart --}}
            <a href="{{ route('customer.cart.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm position-relative d-inline-flex align-items-center gap-1">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="d-none d-sm-inline">Keranjang</span>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="tokobii-badge tokobii-badge-info" style="font-size: 0.65rem; padding: 0.15rem 0.55rem;">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

            {{-- User Pill --}}
            <div class="dropdown">
                <button class="user-avatar-pill border-0"
                        type="button"
                        id="customerNavbarDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width: 32px; height: 32px; font-size: 0.75rem; background: linear-gradient(135deg, #34d399, #10b981); box-shadow: 0 2px 6px rgba(16,185,129,0.35); flex-shrink: 0;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'C', 0, 1)) }}
                    </div>
                    <div class="d-none d-sm-flex flex-column text-start" style="line-height: 1.2;">
                        <span class="fw-semibold text-slate-700" style="font-size: 0.8125rem;">{{ Str::limit(Auth::user()->name, 15) }}</span>
                        <span class="text-slate-400" style="font-size: 0.6875rem;">Pelanggan</span>
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
                        <a class="dropdown-item tokobii-dropdown-item" href="{{ route('customer.profile.index') }}">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item tokobii-dropdown-item" href="{{ route('customer.orders.index') }}">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <span>Riwayat Pesanan</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item tokobii-dropdown-item" href="{{ route('customer.profile.edit') }}">
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
    </div>
</nav>