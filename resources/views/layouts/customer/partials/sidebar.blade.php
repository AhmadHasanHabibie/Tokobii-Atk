<aside class="col-12 col-md-3 col-lg-2 bg-dark text-white min-vh-100 shadow p-0 sticky-top">
    {{-- Sidebar Header --}}
    <div class="p-3 border-bottom border-secondary text-center bg-dark">
        <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-white d-flex align-items-center justify-content-center gap-2">
            <span class="bg-primary text-white rounded-3 px-2 py-0.5 fs-5 fw-bold shadow-sm">🛍️</span>
            <span class="fw-bold fs-4 tracking-tight">Tokobii</span>
        </a>
        <small class="text-secondary font-monospace d-block mt-1">Customer Panel</small>
    </div>

    {{-- Sidebar Menu --}}
    <div class="list-group list-group-flush py-2">
        {{-- Dashboard --}}
        <a href="{{ route('customer.dashboard') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2.5 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('customer.dashboard') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>📊</span> <span>Dashboard</span>
        </a>

        {{-- Shopping Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Belanja & Katalog
        </div>

        <a href="{{ route('customer.shop.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('customer.shop.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>🛍️</span> <span>Katalog Produk</span>
        </a>

        <a href="{{ route('customer.cart.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center justify-content-between
           {{ request()->routeIs('customer.cart.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <div class="d-flex align-items-center gap-2.5">
                <span>🛒</span> <span>Keranjang Saya</span>
            </div>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="badge bg-primary rounded-pill font-monospace">{{ count(session('cart')) }}</span>
            @endif
        </a>

        {{-- Orders & Activity Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Transaksi & Aktivitas
        </div>

        <a href="{{ route('customer.orders.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('customer.orders.*') && !request()->routeIs('customer.orders.reports.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>📦</span> <span>Riwayat Pesanan</span>
        </a>

        <a href="{{ route('customer.reviews.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('customer.reviews.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>⭐</span> <span>Ulasan Saya</span>
        </a>

        <a href="{{ route('customer.reports.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('customer.reports.*', 'customer.orders.reports.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>🚩</span> <span>Laporan Produk</span>
        </a>

        {{-- Account Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Pengaturan Akun
        </div>

        <a href="{{ route('customer.profile.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('customer.profile.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>👤</span> <span>Profil Saya</span>
        </a>
    </div>

    {{-- Sidebar Footer User Quick Logout --}}
    <div class="p-3 border-top border-secondary mt-auto bg-dark">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                <span>🚪</span> <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
