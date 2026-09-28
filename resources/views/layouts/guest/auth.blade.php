<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Autentikasi - Tokobii')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        /* Auth-specific premium styles */
        .auth-left-panel {
            background: linear-gradient(160deg, #0f172a 0%, #1a2744 40%, #1e1b4b 100%);
            position: relative;
            overflow: hidden;
        }
        .auth-left-panel::before {
            content: '';
            position: absolute;
            top: -30%; left: -20%;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            animation: authBgPulse 8s ease-in-out infinite;
        }
        .auth-left-panel::after {
            content: '';
            position: absolute;
            bottom: -20%; right: -15%;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 70%);
            animation: authBgPulse 10s ease-in-out infinite reverse;
        }
        @keyframes authBgPulse {
            0%, 100% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.1) translate(20px, -20px); }
        }

        .auth-feature-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem 1rem;
            border-radius: 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(8px);
            transition: all 0.25s ease;
        }
        .auth-feature-item:hover {
            background: rgba(255,255,255,0.10);
            border-color: rgba(59,130,246,0.35);
            transform: translateX(4px);
        }
        .auth-feature-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(59,130,246,0.3), rgba(99,102,241,0.3));
            border: 1px solid rgba(59,130,246,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #93c5fd;
        }

        .auth-right-panel {
            background: linear-gradient(160deg, #f8fafc 0%, #f0f4f8 100%);
        }

        .auth-brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35), 0 2px 8px rgba(37,99,235,0.20);
            transition: all 0.3s ease;
        }
        .auth-brand-logo:hover {
            transform: scale(1.06) rotate(-3deg);
            box-shadow: 0 12px 32px rgba(37,99,235,0.45);
        }

        .auth-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid rgba(226,232,240,0.8);
            box-shadow:
                0 4px 24px rgba(15,23,42,0.08),
                0 1px 4px rgba(15,23,42,0.04),
                0 0 0 1px rgba(255,255,255,0.9) inset;
            padding: 2.25rem;
            animation: tokobiiScaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @media (max-width: 575.98px) {
            .auth-card {
                padding: 1.75rem 1.5rem;
                border-radius: 20px;
            }
        }

        /* Floating animation for decorative elements */
        .auth-floating-el {
            animation: authFloat 6s ease-in-out infinite;
        }
        @keyframes authFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        /* Alert inside auth pages */
        .auth-alert {
            border-radius: 14px;
            border: 1.5px solid;
            padding: 0.875rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.85rem;
            animation: tokobiiFadeInUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
            margin-bottom: 1.25rem;
        }
        .auth-alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-color: #a7f3d0;
            color: #065f46;
        }
        .auth-alert-danger {
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
            border-color: #fecdd3;
            color: #9f1239;
        }
        .auth-alert-info {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-color: #bfdbfe;
            color: #1e40af;
        }
    </style>
</head>

<body class="antialiased font-sans" style="background: #f0f4f8;">

    <div class="min-vh-100 d-flex">

        {{-- Left Panel: Brand Story (Desktop Only) --}}
        <div class="d-none d-lg-flex auth-left-panel flex-column justify-content-center align-items-center p-5" style="width: 46%; min-width: 46%;">
            
            <div class="position-relative z-1 text-center animate-fade-in-up" style="max-width: 440px;">

                {{-- Floating Brand Mark --}}
                <div class="mb-5 auth-floating-el">
                    <div class="d-inline-flex align-items-center justify-content-center p-3 bg-white rounded-4 shadow-lg mb-3" style="max-width: 220px;">
                        <img src="{{ asset('images/Logo_Tokobiie.jpeg') }}" alt="Tokobii" class="img-fluid rounded-2" style="max-height: 54px; object-fit: contain;">
                    </div>
                    <p class="mb-0" style="color: #94a3b8; font-size: 1.0625rem; line-height: 1.6;">
                        Sistem manajemen toko ATK modern, aman, dan terpercaya untuk semua kebutuhan Anda.
                    </p>
                </div>

                {{-- Feature Cards --}}
                <div class="d-flex flex-column gap-3 animate-fade-in-up animate-delay-200" style="text-align: left;">
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="fw-semibold text-white" style="font-size: 0.875rem;">Transaksi Cepat & Real-time</div>
                            <div style="color: #64748b; font-size: 0.78125rem; margin-top: 1px;">Verifikasi pembayaran QRIS & kasir tunai</div>
                        </div>
                    </div>
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="fw-semibold text-white" style="font-size: 0.875rem;">Keamanan Multi-Layer</div>
                            <div style="color: #64748b; font-size: 0.78125rem; margin-top: 1px;">2FA Email OTP & Verifikasi Biometrik Wajah</div>
                        </div>
                    </div>
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="fw-semibold text-white" style="font-size: 0.875rem;">Struk QR Code Pengambilan</div>
                            <div style="color: #64748b; font-size: 0.78125rem; margin-top: 1px;">Scan & verifikasi pesanan dalam hitungan detik</div>
                        </div>
                    </div>
                </div>

                {{-- Bottom tagline --}}
                <div class="mt-5 animate-fade-in-up animate-delay-400">
                    <p style="color: #334155; font-size: 0.78125rem;">
                        Dipercaya untuk mengelola toko ATK secara profesional.
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Panel: Auth Form --}}
        <div class="flex-grow-1 auth-right-panel d-flex flex-column justify-content-center align-items-center px-4 py-5">
            <div class="w-100 animate-fade-in-up" style="max-width: 440px;">

                {{-- Mobile Brand Header --}}
                <div class="text-center mb-5">
                    <a href="{{ route('shop') }}" class="text-decoration-none d-inline-flex align-items-center justify-content-center mb-3">
                        <div class="p-2 bg-white rounded-3 shadow-sm border border-slate-200 d-inline-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/Logo_Tokobiie.jpeg') }}" alt="Tokobii" class="img-fluid rounded-2" style="height: 38px; width: auto; max-width: 140px; object-fit: contain;">
                        </div>
                    </a>
                    <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">
                        @yield('subtitle', 'Silakan masuk ke akun Anda.')
                    </p>
                </div>

                {{-- Flash Alerts --}}
                @if(session('success'))
                    <div class="alert auth-alert auth-alert-success">
                        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="fw-medium flex-grow-1">{{ session('success') }}</span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert auth-alert auth-alert-danger">
                        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="fw-medium flex-grow-1">{{ session('error') }}</span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert auth-alert auth-alert-danger">
                        <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 1px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">Perhatikan formulir:</div>
                            <ul class="mb-0 ps-3" style="font-size: 0.8125rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                {{-- Auth Card --}}
                <div class="auth-card">
                    @yield('content')
                </div>

                {{-- Footer --}}
                <div class="text-center mt-4">
                    <small class="text-slate-400">
                        © {{ date('Y') }} <strong class="text-slate-500 fw-semibold">Tokobii</strong>. Hak Cipta Dilindungi.
                    </small>
                </div>

            </div>
        </div>

    </div>

    @stack('scripts')
</body>
</html>