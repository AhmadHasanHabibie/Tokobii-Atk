<aside class="col-md-2 bg-dark text-white min-vh-100 shadow-sm p-0">

    {{-- Sidebar Header --}}
    <div class="p-3 border-bottom border-secondary text-center">

        <h5 class="fw-bold mb-1">
            Tokobii
        </h5>

        <small class="text-secondary">
            Owner Panel
        </small>

    </div>

    {{-- Sidebar Menu --}}
    <div class="list-group list-group-flush">

        {{-- Dashboard --}}
        <a href="{{ route('owner.dashboard') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('owner.dashboard') ? 'active' : 'bg-dark text-white' }}">

            📊 Dashboard

        </a>

        {{-- Product --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Product

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            📦 Products

        </a>

        {{-- Transaction --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Transaction

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            🛒 Orders

        </a>

        {{-- Customer --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Customer

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            👥 Customers

        </a>

        {{-- Reports --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Reports

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            📈 Sales Report

        </a>

        {{-- Account --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Account

        </div>

        <a href="{{ route('owner.profile.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('owner.profile.*') ? 'active' : 'bg-dark text-white' }}">

            👤 My Profile

        </a>

    </div>

</aside>