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
    <style>
        /* Smooth Owner Sidebar Transitions */
        .tokobii-sidebar {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, opacity 0.3s ease !important;
            will-change: transform;
        }

        .tokobii-main-wrapper {
            transition: margin-left 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            will-change: margin-left;
        }

        /* Desktop: Collapsed State */
        @media (min-width: 992px) {
            body.owner-sidebar-collapsed .tokobii-sidebar {
                transform: translateX(-100%) !important;
                box-shadow: none !important;
                visibility: hidden !important;
                pointer-events: none;
            }

            body.owner-sidebar-collapsed .tokobii-main-wrapper {
                margin-left: 0 !important;
            }
        }

        /* Mobile / Tablet: Default hidden, Slide in when open */
        @media (max-width: 991.98px) {
            .tokobii-sidebar {
                transform: translateX(-100%);
                position: fixed !important;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                z-index: 1050;
                box-shadow: none;
                visibility: hidden;
            }

            body.owner-sidebar-open .tokobii-sidebar {
                transform: translateX(0) !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
                visibility: visible !important;
            }

            .owner-sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                z-index: 1045;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.35s ease, visibility 0.35s ease;
            }

            body.owner-sidebar-open .owner-sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }
        }

        /* Hamburger Toggle Button Styling */
        .owner-hamburger-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 0;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background-color: #ffffff;
            color: #64748b;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
        }

        .owner-hamburger-btn:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #7c3aed;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(124, 58, 237, 0.12);
        }

        .owner-hamburger-btn:active {
            transform: scale(0.95);
        }

        /* Navbar Reopen Button: Hidden by default when sidebar is open */
        .owner-navbar-toggle-btn {
            display: none !important;
        }

        /* Only show navbar button when sidebar is collapsed on desktop */
        @media (min-width: 992px) {
            body.owner-sidebar-collapsed .owner-navbar-toggle-btn {
                display: inline-flex !important;
            }
        }

        /* Mobile visibility */
        @media (max-width: 991.98px) {
            .owner-navbar-toggle-btn {
                display: inline-flex !important;
            }
            body.owner-sidebar-open .owner-navbar-toggle-btn {
                display: none !important;
            }
        }
    </style>
</head>

<body class="antialiased font-sans" style="background-color: var(--tokobii-bg);">

    {{-- Mobile Overlay Backdrop --}}
    <div class="owner-sidebar-overlay" id="ownerSidebarOverlay"></div>

    {{-- Sidebar Drawer --}}
    @include('layouts.owner.partials.sidebar')

    {{-- Main Content Wrapper (offset on desktop by 250px) --}}
    <div class="tokobii-main-wrapper">

        {{-- Top Navbar Header --}}
        @include('layouts.owner.partials.navbar')

        {{-- Main Viewport --}}
        <main class="flex-grow-1 tokobii-page-content">

            {{-- Flash Alert Messages --}}
            @if(session('success'))
                <div class="alert tokobii-alert tokobii-alert-success mb-4 animate-fade-in-up" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="fw-medium">{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert tokobii-alert tokobii-alert-error mb-4 animate-fade-in-up" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="fw-medium">{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert tokobii-alert tokobii-alert-warning mb-4 animate-fade-in-up" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="fw-medium">{{ session('warning') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert tokobii-alert tokobii-alert-info mb-4 animate-fade-in-up" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="fw-medium">{{ session('info') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.owner-sidebar-toggle-btn');
            const overlay = document.getElementById('ownerSidebarOverlay');
            const isMobile = () => window.innerWidth < 992;

            // Restore saved desktop state
            const savedState = localStorage.getItem('tokobii_owner_sidebar_collapsed');
            if (!isMobile() && savedState === 'true') {
                document.body.classList.add('owner-sidebar-collapsed');
            }

            toggleButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (isMobile()) {
                        document.body.classList.toggle('owner-sidebar-open');
                    } else {
                        document.body.classList.toggle('owner-sidebar-collapsed');
                        localStorage.setItem('tokobii_owner_sidebar_collapsed', document.body.classList.contains('owner-sidebar-collapsed'));
                    }
                });
            });

            if (overlay) {
                overlay.addEventListener('click', function () {
                    document.body.classList.remove('owner-sidebar-open');
                });
            }

            // Close mobile menu on resize to desktop
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    document.body.classList.remove('owner-sidebar-open');
                }
            });
        });
    </script>

</body>

</html>