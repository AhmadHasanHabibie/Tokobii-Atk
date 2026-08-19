<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Owner Panel - ' . config('app.name', 'Tokobii'))</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    {{-- Sidebar Drawer --}}
    @include('layouts.owner.partials.sidebar')

    {{-- Main Content Wrapper (offset on desktop by 250px) --}}
    <div class="tokobii-main-wrapper">

        {{-- Top Navbar Header --}}
        @include('layouts.owner.partials.navbar')

        {{-- Main Viewport --}}
        <main class="flex-grow-1 px-3 px-md-4 px-xl-5 py-4 bg-slate-50">

            {{-- Flash Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <svg class="text-emerald-600 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="fw-semibold text-sm">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 bg-rose-50 text-rose-800 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <svg class="text-rose-600 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="fw-semibold text-sm">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')

        </main>

        {{-- Footer --}}
        @include('layouts.owner.partials.footer')

    </div>

    {{-- Global Interactive Guidance Modal --}}
    @include('components.flash-guidance-modal')

    @stack('scripts')

</body>

</html>