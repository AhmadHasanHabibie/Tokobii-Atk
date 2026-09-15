<nav class="navbar navbar-expand-lg bg-white border-bottom border-slate-200 sticky-top py-2.5 shadow-sm" style="z-index: 1030;">
    <div class="container d-flex align-items-center justify-content-between">
        {{-- Brand Logo --}}
        <a href="{{ route('shop') }}" class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2 m-0 text-decoration-none">
            <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2.5 py-1.5 shadow-sm" style="background-color: #2563eb;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </span>
            <div class="d-flex flex-column">
                <span class="tracking-tight text-slate-900 fw-bold" style="font-size: 1.25rem; line-height: 1.1;">Tokobii</span>
                <span class="text-slate-400 fw-medium" style="font-size: 0.65rem; letter-spacing: 0.05em;">Toko Alat Tulis Kantor</span>
            </div>
        </a>

        {{-- Center Nav Links (Desktop) --}}
        <div class="d-none d-md-flex align-items-center gap-4">
            <a href="{{ route('shop') }}" class="text-decoration-none fw-semibold text-sm {{ request()->routeIs('shop*') ? 'text-blue-600' : 'text-slate-600 hover-text-blue-600' }}">
                Katalog Produk
            </a>
            <a href="{{ route('about') }}" class="text-decoration-none fw-semibold text-sm {{ request()->routeIs('about') ? 'text-blue-600' : 'text-slate-600 hover-text-blue-600' }}">
                Tentang Tokobii
            </a>
        </div>

        {{-- Auth Action Buttons --}}
        <div class="d-flex align-items-center gap-2">
            @guest
                <a href="{{ route('login') }}" class="btn btn-tokobii-secondary btn-tokobii-sm fw-semibold">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Masuk</span>
                </a>
                <a href="{{ route('register') }}" class="btn btn-tokobii-primary btn-tokobii-sm fw-semibold">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span>Daftar Akun</span>
                </a>
            @else
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-tokobii-primary btn-tokobii-sm fw-semibold">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span>Panel Admin</span>
                    </a>
                @elseif(auth()->user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-tokobii-success btn-tokobii-sm fw-semibold">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Panel Pemilik</span>
                    </a>
                @elseif(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-tokobii-primary btn-tokobii-sm fw-semibold">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Panel Pelanggan</span>
                    </a>
                @endif
            @endguest
        </div>
    </div>
</nav>