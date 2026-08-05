<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Tokobii'))</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-light">

    {{-- Navbar --}}
    @include('layouts.admin.partials.navbar')

    <div class="container-fluid">

        <div class="row">

            {{-- Sidebar --}}
            @include('layouts.admin.partials.sidebar')

            {{-- Main Content --}}
            <main class="col-md-10 ms-sm-auto px-md-4 py-4 min-vh-100">

                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">

                        {{ session('success') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                        </button>

                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                        {{ session('error') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                        </button>

                    </div>
                @endif

                {{-- Page Content --}}
                @yield('content')

            </main>

        </div>

    </div>

    {{-- Footer --}}
    @include('layouts.admin.partials.footer')

    @stack('scripts')

</body>

</html>