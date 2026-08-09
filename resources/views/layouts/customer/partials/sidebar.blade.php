<aside class="col-md-2 bg-dark text-white min-vh-100 shadow-sm p-0">

    {{-- Sidebar Header --}}
    <div class="p-3 border-bottom border-secondary text-center">

        <h5 class="fw-bold mb-1">
            Tokobii
        </h5>

        <small class="text-secondary">
            Customer Panel
        </small>

    </div>

    {{-- Sidebar Menu --}}
    <div class="list-group list-group-flush">

        {{-- Dashboard --}}
        <a href="{{ route('customer.dashboard') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('customer.dashboard') ? 'active' : 'bg-dark text-white' }}">

            📊 Dashboard

        </a>

        {{-- Shopping --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Shopping

        </div>

        <a href="{{ route('customer.shop.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('customer.shop.*') ? 'active' : 'bg-dark text-white' }}">

            🛍️ Products / Shop

        </a>

        <a href="{{ route('customer.cart.index') }}"
           class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center
           {{ request()->routeIs('customer.cart.*') ? 'active' : 'bg-dark text-white' }}">

            <span>🛒 My Cart</span>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="badge bg-primary rounded-pill">{{ count(session('cart')) }}</span>
            @endif

        </a>

        {{-- Orders --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Orders

        </div>

        <a href="{{ route('customer.orders.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('customer.orders.*') ? 'active' : 'bg-dark text-white' }}">

            📦 My Orders

        </a>

        <a href="{{ route('customer.reviews.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('customer.reviews.*') ? 'active' : 'bg-dark text-white' }}">

            Reviews

        </a>

        {{-- Account --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Account

        </div>

        <a href="{{ route('customer.profile.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('customer.profile.*') ? 'active' : 'bg-dark text-white' }}">

            👤 My Profile

        </a>

    </div>

</aside>
