<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Tokobii'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="d-flex flex-column min-vh-100 font-sans antialiased" style="background-color: var(--tokobii-bg);">

    {{-- Guest Top Navbar --}}
    @include('layouts.guest.partials.navbar')

    {{-- Flash Message --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert tokobii-alert tokobii-alert-success mb-3 animate-fade-in-up" role="alert">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="fw-medium">{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert tokobii-alert tokobii-alert-error mb-3 animate-fade-in-up" role="alert">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="fw-medium">{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert tokobii-alert tokobii-alert-warning mb-3 animate-fade-in-up" role="alert">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="fw-medium">{{ session('warning') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert tokobii-alert tokobii-alert-info mb-3 animate-fade-in-up" role="alert">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="fw-medium">{{ session('info') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.guest.partials.footer')

    {{-- Global Interactive Guidance Modal --}}
    @include('components.flash-guidance-modal')

    @stack('scripts')

</body>

</html>