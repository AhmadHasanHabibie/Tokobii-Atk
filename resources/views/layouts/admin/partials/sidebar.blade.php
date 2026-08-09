<aside class="col-12 col-md-3 col-lg-2 bg-dark text-white min-vh-100 shadow p-0 sticky-top">
    {{-- Sidebar Header --}}
    <div class="p-3 border-bottom border-secondary text-center bg-dark">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-white d-flex align-items-center justify-content-center gap-2">
            <span class="bg-primary text-white rounded-3 px-2 py-0.5 fs-5 fw-bold shadow-sm">🛍️</span>
            <span class="fw-bold fs-4 tracking-tight">Tokobii</span>
        </a>
        <small class="text-secondary font-monospace d-block mt-1">Admin Operations Center</small>
    </div>

    {{-- Sidebar Menu Links --}}
    <div class="list-group list-group-flush py-2">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2.5 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.dashboard') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85 hover-bg-secondary' }}">
            <span>📊</span> <span>Dashboard</span>
        </a>

        {{-- Master Data Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Master Data
        </div>

        <a href="{{ route('admin.categories.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.categories.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>📂</span> <span>Categories</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.products.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>📦</span> <span>Products</span>
        </a>

        {{-- Customer Monitoring Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Customer Monitoring
        </div>

        <a href="{{ route('admin.customers.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.customers.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>👥</span> <span>Customers</span>
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.reviews.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>⭐</span> <span>Reviews</span>
        </a>

        {{-- Transactions Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Transactions & Pickup
        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.scan') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>🛒</span> <span>Orders & Pickup</span>
        </a>

        <a href="{{ route('admin.orders.scan') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.orders.scan') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>📱</span> <span>Scan Order QR</span>
        </a>

        {{-- Reports Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Reports & Issues
        </div>

        <a href="{{ route('admin.reports.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.reports.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>🚩</span> <span>Reports</span>
        </a>

        {{-- Account & System Group --}}
        <div class="px-3 pt-3 pb-1 text-uppercase small fw-bold text-secondary tracking-wider font-monospace">
            Account & System
        </div>

        <a href="{{ route('admin.profile.index') }}"
           class="list-group-item list-group-item-action border-0 px-3 py-2 text-white d-flex align-items-center gap-2.5
           {{ request()->routeIs('admin.profile.*') ? 'bg-primary fw-bold active' : 'bg-dark opacity-85' }}">
            <span>👤</span> <span>My Profile</span>
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
