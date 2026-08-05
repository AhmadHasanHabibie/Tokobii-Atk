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

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="list-group-item list-group-item-action border-0
           {{ request()->routeIs('admin.dashboard') ? 'active' : 'bg-dark text-white' }}">

            📊 Dashboard

        </a>

        {{-- Master --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Master Data

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            📂 Category

        </a>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            📦 Product

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

        {{-- Settings --}}
        <div class="px-3 pt-4 pb-2 text-uppercase small fw-bold text-secondary">

            Settings

        </div>

        <a href="#"
           class="list-group-item list-group-item-action bg-dark text-white border-0">

            ⚙️ Settings

        </a>

    </div>

</aside>