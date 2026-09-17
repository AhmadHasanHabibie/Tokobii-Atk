<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Terjadi Kendala') - {{ config('app.name', 'Tokobii') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-sans);
            background: radial-gradient(circle at 50% 0%, #eff6ff 0%, #f8fafc 50%, #f1f5f9 100%);
            min-height: 100vh;
            color: #0f172a;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .error-mesh-bg {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .mesh-circle-1 {
            position: absolute;
            top: -15%;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, rgba(99, 102, 241, 0.05) 50%, transparent 70%);
            filter: blur(50px);
            border-radius: 50%;
            animation: floatSlow 10s ease-in-out infinite alternate;
        }

        .mesh-circle-2 {
            position: absolute;
            bottom: -10%;
            right: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
            filter: blur(60px);
            border-radius: 50%;
        }

        @keyframes floatSlow {
            0% { transform: translateX(-50%) translateY(0px) scale(1); }
            100% { transform: translateX(-50%) translateY(25px) scale(1.08); }
        }

        .error-card-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08), 0 4px 12px rgba(15, 23, 42, 0.03);
            position: relative;
            z-index: 10;
        }

        .error-code-badge {
            font-family: var(--font-mono);
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: clamp(4.5rem, 12vw, 8rem);
            font-weight: 800;
            line-height: 1;
            text-shadow: 0 10px 30px rgba(37, 99, 235, 0.15);
            position: relative;
            display: inline-block;
        }

        .icon-floating-circle {
            width: 88px;
            height: 88px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .icon-floating-circle:hover {
            transform: scale(1.06) rotate(3deg);
        }
    </style>
</head>
<body class="antialiased">

    {{-- Background Glow Mesh --}}
    <div class="error-mesh-bg">
        <div class="mesh-circle-1"></div>
        <div class="mesh-circle-2"></div>
    </div>

    {{-- Simple Header Brand --}}
    <header class="py-4 px-4 px-sm-5 position-relative" style="z-index: 20;">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-2.5 text-decoration-none">
                <div class="p-1 bg-white rounded-2 shadow-xs border border-slate-200 d-inline-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/Logo_Tokobiie.jpeg') }}" alt="Tokobii" class="img-fluid rounded-1" style="height: 34px; width: auto; max-width: 120px; object-fit: contain;">
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                            <span>Dashboard Admin</span>
                        </a>
                    @elseif(auth()->user()->role === 'owner')
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                            <span>Dashboard Owner</span>
                        </a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                            <span>Dashboard Pelanggan</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('shop') }}" class="btn btn-tokobii-secondary btn-tokobii-sm d-none d-sm-inline-flex">
                        <span>Katalog Produk</span>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <span>Masuk</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Error Content Container --}}
    <main class="flex-grow-1 d-flex align-items-center justify-content-center px-3 py-4 position-relative" style="z-index: 10;">
        <div class="container" style="max-width: 720px;">
            
            <div class="error-card-glass p-4 p-sm-5 text-center">

                {{-- Themed Floating Icon --}}
                <div>
                    @yield('icon')
                </div>

                {{-- Error Code Display --}}
                <div class="error-code-badge mb-1">
                    @yield('code', 'Error')
                </div>

                {{-- Error Title / Heading --}}
                <h1 class="h3 fw-bold text-slate-900 mb-2" style="font-size: clamp(1.35rem, 3vw, 1.85rem);">
                    @yield('message', 'Terjadi Kesalahan')
                </h1>

                {{-- Error Subtext & Guidance --}}
                <p class="text-slate-600 mb-4 mx-auto" style="max-width: 520px; font-size: 0.95rem; line-height: 1.65;">
                    @yield('description', 'Halaman atau permintaan yang Anda akses tidak dapat diproses saat ini.')
                </p>

                {{-- Action Buttons --}}
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2.5 pt-2">
                    <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}';" class="btn btn-tokobii-secondary btn-tokobii-lg w-100 w-sm-auto shadow-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Kembali ke Halaman Sebelumnya</span>
                    </button>

                    <a href="{{ route('home') }}" class="btn btn-tokobii-primary btn-tokobii-lg w-100 w-sm-auto shadow-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Ke Beranda Utama</span>
                    </a>
                </div>

                {{-- Extra Help / Links --}}
                <div class="mt-4 pt-3 border-top border-slate-100 text-slate-400 small d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('shop') }}" class="text-slate-500 text-decoration-none hover-text-blue-600">Belanja ATK</a>
                    <span>·</span>
                    <a href="{{ route('about') }}" class="text-slate-500 text-decoration-none hover-text-blue-600">Tentang Kami</a>
                    <span>·</span>
                    <a href="{{ route('contact') }}" class="text-slate-500 text-decoration-none hover-text-blue-600">Bantuan & Kontak</a>
                </div>

            </div>

        </div>
    </main>

    {{-- Simple Footer --}}
    <footer class="py-3 text-center text-slate-400 small position-relative" style="z-index: 10; font-size: 0.75rem;">
        &copy; {{ date('Y') }} {{ config('app.name', 'Tokobii') }} — Sistem Pengelolaan & Toko Alat Tulis Kantor.
    </footer>

</body>
</html>
