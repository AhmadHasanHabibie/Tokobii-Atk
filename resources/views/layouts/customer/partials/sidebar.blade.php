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

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            🛍️ Products

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            🛒 My Cart

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            ❤️ Wishlist

        </a>

        {{-- Orders --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Orders

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            📦 My Orders

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            🚚 Order Tracking

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