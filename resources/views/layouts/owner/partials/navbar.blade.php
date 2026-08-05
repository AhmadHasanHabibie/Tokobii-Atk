<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">

    <div class="container-fluid">

        {{-- Brand --}}
        <a href="{{ route('owner.dashboard') }}"
           class="navbar-brand fw-bold">

            Tokobii Owner

        </a>

        {{-- Right Menu --}}
        <div class="dropdown ms-auto">

            <button
                class="btn btn-success dropdown-toggle"
                type="button"
                id="ownerDropdown"
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

                {{-- Profile --}}
                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('owner.profile.index') }}">

                        👤 Profile

                    </a>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('owner.profile.edit') }}">

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

</nav>