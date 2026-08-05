<nav class="bg-white shadow-sm sticky-top border-bottom">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center py-3">

            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="text-decoration-none text-dark fw-bold fs-3">

                Tokobii

            </a>

            {{-- Menu --}}
            <div class="d-flex align-items-center gap-4">

                <a href="{{ route('home') }}"
                   class="text-decoration-none {{ request()->routeIs('home') ? 'fw-bold text-primary' : 'text-dark' }}">

                    Home

                </a>

                <a href="{{ route('shop') }}"
                   class="text-decoration-none {{ request()->routeIs('shop') ? 'fw-bold text-primary' : 'text-dark' }}">

                    Shop

                </a>

                <a href="{{ route('about') }}"
                   class="text-decoration-none {{ request()->routeIs('about') ? 'fw-bold text-primary' : 'text-dark' }}">

                    About

                </a>

                <a href="{{ route('contact') }}"
                   class="text-decoration-none {{ request()->routeIs('contact') ? 'fw-bold text-primary' : 'text-dark' }}">

                    Contact

                </a>

            </div>

            {{-- Auth --}}
            <div class="d-flex align-items-center gap-2">

                @guest

                    <a href="{{ route('login') }}"
                       class="btn btn-outline-primary">

                        Login

                    </a>

                    <a href="{{ route('register') }}"
                       class="btn btn-primary">

                        Register

                    </a>

                @else

                    @if(auth()->user()->role === 'admin')

                        <a href="{{ route('admin.dashboard') }}"
                           class="btn btn-primary">

                            Dashboard

                        </a>

                    @elseif(auth()->user()->role === 'owner')

                        <a href="{{ route('owner.dashboard') }}"
                           class="btn btn-success">

                            Dashboard

                        </a>

                    @elseif(auth()->user()->role === 'customer')

                        <a href="{{ route('customer.dashboard') }}"
                           class="btn btn-warning text-dark">

                            Dashboard

                        </a>

                    @endif

                @endguest

            </div>

        </div>

    </div>

</nav>