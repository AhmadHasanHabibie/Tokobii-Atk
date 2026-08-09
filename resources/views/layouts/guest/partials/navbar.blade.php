<nav class="navbar navbar-dark bg-dark shadow-sm sticky-top py-2.5">
    <div class="container d-flex justify-content-between align-items-center">
        {{-- Brand Logo --}}
        <a href="{{ route('shop') }}" class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2 m-0">
            <span class="bg-primary text-white rounded-3 px-2.5 py-1 fs-5 shadow-sm">🛍️</span>
            <span class="tracking-tight text-white">Tokobii</span>
        </a>

        {{-- Auth Action Buttons (Always Visible at Right Corner) --}}
        <div class="d-flex align-items-center gap-2 ms-auto">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light px-3 py-1.5 fw-semibold shadow-sm">
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary px-3 py-1.5 fw-semibold shadow-sm">
                    Daftar
                </a>
            @else
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary px-3 py-1.5 fw-semibold shadow-sm">
                        Dashboard Admin
                    </a>
                @elseif(auth()->user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-success px-3 py-1.5 fw-semibold shadow-sm">
                        Dashboard Owner
                    </a>
                @elseif(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-primary px-3 py-1.5 fw-semibold shadow-sm">
                        Dashboard Customer
                    </a>
                @endif
            @endguest
        </div>
    </div>
</nav>