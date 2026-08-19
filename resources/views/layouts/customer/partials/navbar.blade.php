<header class="tokobii-navbar sticky-top d-flex align-items-center justify-content-between px-3 px-md-4 px-xl-5 bg-white border-bottom border-slate-200" style="height: 64px; z-index: 1020;">
    {{-- Left Side: Mobile Sidebar Toggle & Page Title / Quick Search --}}
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-tokobii-secondary d-lg-none p-2 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#customerSidebarMenu" aria-controls="customerSidebarMenu" aria-label="Menu Utama">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <a href="{{ route('customer.shop.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm d-none d-sm-inline-flex align-items-center gap-2">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="text-slate-600">Cari Produk ATK...</span>
        </a>
    </div>

    {{-- Right Side: Cart Quick Link & User Dropdown --}}
    <div class="d-flex align-items-center gap-3">

        {{-- Quick Cart Link --}}
        <a href="{{ route('customer.cart.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm position-relative d-inline-flex align-items-center gap-2">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="d-none d-sm-inline">Keranjang</span>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="tokobii-badge tokobii-badge-info px-1.5 py-0.5" style="font-size: 0.6875rem;">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>

        {{-- User Menu Dropdown --}}
        <div class="dropdown">
            <button class="btn btn-tokobii-secondary d-flex align-items-center gap-2 p-1.5 pe-3 border rounded-pill shadow-none" type="button" id="customerNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="rounded-circle bg-blue-100 text-blue-700 fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8125rem; background-color: #eff6ff; color: #2563eb;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'C', 0, 1)) }}
                </div>
                <div class="d-none d-md-flex flex-column text-start" style="line-height: 1.15;">
                    <span class="fw-semibold text-slate-800" style="font-size: 0.8125rem;">{{ Str::limit(Auth::user()->name, 15) }}</span>
                    <span class="text-slate-400" style="font-size: 0.6875rem;">Pelanggan</span>
                </div>
                <svg class="text-slate-400 ms-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-slate-200 rounded-3 p-2 mt-2" aria-labelledby="customerNavbarDropdown" style="min-width: 220px;">
                <li class="px-3 py-2 border-bottom border-slate-100 mb-1">
                    <div class="fw-bold text-slate-900" style="font-size: 0.875rem;">{{ Auth::user()->name }}</div>
                    <div class="text-slate-400 text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 rounded-2 py-2 text-slate-700 hover-bg-slate-50" href="{{ route('customer.profile.index') }}" style="font-size: 0.8125rem;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 rounded-2 py-2 text-slate-700 hover-bg-slate-50" href="{{ route('customer.orders.index') }}" style="font-size: 0.8125rem;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Riwayat Pesanan</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 rounded-2 py-2 text-slate-700 hover-bg-slate-50" href="{{ route('customer.profile.edit') }}" style="font-size: 0.8125rem;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Profil & Password</span>
                    </a>
                </li>
                <li><hr class="dropdown-divider border-slate-100 my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded-2 py-2 text-danger hover-bg-rose-50" style="font-size: 0.8125rem;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</header>