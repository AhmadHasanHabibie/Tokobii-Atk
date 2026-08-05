<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>

        @yield('title', config('app.name', 'Tokobii'))

    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-light d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    @include('layouts.guest.partials.navbar')

    {{-- Flash Message --}}
    <div class="container mt-3">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif

    </div>

    {{-- Main Content --}}
    <main class="flex-grow-1">

        @yield('content')

    </main>

    {{-- Footer --}}
    @include('layouts.guest.partials.footer')

    @stack('scripts')

</body>

</html>