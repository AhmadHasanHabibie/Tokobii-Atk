<nav class="navbar navbar-expand-lg navbar-dark bg-warning shadow-sm">

    <div class="container-fluid">

        {{-- Brand --}}
        <a href="{{ route('customer.dashboard') }}"
           class="navbar-brand fw-bold text-dark me-4">

            Tokobii Customer

        </a>

        {{-- Top Nav Links --}}
        <div class="d-flex align-items-center gap-3 ms-auto">
            
            {{-- Cart Quick Link --}}
            <a href="{{ route('customer.cart.index') }}" class="btn btn-outline-dark btn-sm position-relative fw-semibold me-2">
                🛒 Keranjang
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count(session('cart')) }}
                        <span class="visually-hidden">jumlah item</span>
                    </span>
                @endif
            </a>

            {{-- Right Menu Dropdown --}}
            <div class="dropdown">

                <button
                    class="btn btn-warning dropdown-toggle text-dark border"
                    type="button"
                    id="customerDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    {{ Auth::user()->name }}

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>

                        <h6 class="dropdown-header fw-bold">

                            {{ Auth::user()->name }}

                        </h6>

                    </li>

                    <li>

                        <span class="dropdown-item-text text-muted small">

                            {{ Auth::user()->email }}

                        </span>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- My Orders --}}
                    <li>

                        <a
                            class="dropdown-item"
                            href="{{ route('customer.orders.index') }}">

                            📦 Riwayat Pesanan

                        </a>

                    </li>

                    {{-- My Profile --}}
                    <li>

                        <a
                            class="dropdown-item"
                            href="{{ route('customer.profile.index') }}">

                            👤 My Profile

                        </a>

                    </li>

                    {{-- Edit Profile --}}
                    <li>

                        <a
                            class="dropdown-item"
                            href="{{ route('customer.profile.edit') }}">

                            ✏️ Edit Profile

                        </a>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- Logout --}}
                    <li>

                        <form
                            method="POST"
                            action="{{ route('logout') }}">

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item text-danger">

                                🚪 Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </div>
        </div>

    </div>

</nav>