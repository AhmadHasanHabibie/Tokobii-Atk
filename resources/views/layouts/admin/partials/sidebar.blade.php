<aside class="col-md-2 bg-dark text-white min-vh-100 shadow-sm p-0">

    {{-- Sidebar Header --}}
    <div class="p-3 border-bottom border-secondary text-center">

        <h5 class="fw-bold mb-1">
            Tokobii
        </h5>

        <small class="text-secondary">
            Admin Panel
        </small>

    </div>

    {{-- Sidebar Menu --}}
    <div class="list-group list-group-flush">

        {{-- ================= Dashboard ================= --}}
        <a href="{{ route('admin.dashboard') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.dashboard') ? 'active' : 'bg-dark text-white' }}">

            📊 Dashboard

        </a>

        {{-- ================= Master Data ================= --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Master Data

        </div>

        <a href="{{ route('admin.categories.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.categories.*') ? 'active' : 'bg-dark text-white' }}">

            📂 Categories

        </a>

        <a href="{{ route('admin.products.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.products.*') ? 'active' : 'bg-dark text-white' }}">

            📦 Products

        </a>

        {{-- ================= Customer ================= --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Customer

        </div>

        <a href="{{ route('admin.customers.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.customers.*') ? 'active' : 'bg-dark text-white' }}">

            👥 Customers

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0 disabled">

            ⭐ Reviews

        </a>

        {{-- ================= Transaction ================= --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Transaction

        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.orders.*') ? 'active' : 'bg-dark text-white' }}">

            🛒 Orders & Pickup

        </a>

        {{-- ================= Reports ================= --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Reports

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0 disabled">

            📈 Sales Report

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0 disabled">

            📦 Product Report

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0 disabled">

            👥 Customer Report

        </a>

        {{-- ================= System ================= --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            System

        </div>

        <a href="{{ route('admin.profile.index') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.profile.*') ? 'active' : 'bg-dark text-white' }}">

            🙍 My Profile

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0 disabled">

            ⚙️ Settings

        </a>

    </div>

</aside>