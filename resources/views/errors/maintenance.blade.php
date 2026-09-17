<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mode Pemeliharaan - {{ config('app.name', 'Tokobii') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle animated background elements */
        .bg-glow-1 {
            position: absolute;
            top: -100px;
            left: -100px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .bg-glow-2 {
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .maintenance-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.02);
            max-width: 520px;
            width: 100%;
            padding: 2.5rem 2rem;
            position: relative;
            z-index: 1;
            text-align: center;
            animation: modalFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .gear-badge {
            width: 76px;
            height: 76px;
            border-radius: 20px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
            position: relative;
        }

        .pulse-ring {
            position: absolute;
            inset: -6px;
            border-radius: 24px;
            border: 2px solid rgba(37, 99, 235, 0.3);
            animation: pulseWave 2.5s infinite;
        }

        @keyframes pulseWave {
            0% { transform: scale(0.95); opacity: 0.8; }
            70% { transform: scale(1.12); opacity: 0; }
            100% { transform: scale(0.95); opacity: 0; }
        }

        .custom-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: 9999px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .pulse-dot-red {
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6);
            animation: pulseRed 2s infinite;
        }
        @keyframes pulseRed {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .user-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="maintenance-card">

        {{-- Tokobii Logo --}}
        <div class="mb-4">
            <img src="{{ asset('images/Logo_Tokobiie.jpeg') }}" alt="Tokobii" class="img-fluid rounded-2" style="height: 44px; width: auto; max-width: 160px; object-fit: contain;">
        </div>

        {{-- Flash Alert if redirected here --}}
        @if(session('error'))
            <div class="alert alert-danger border-0 d-flex align-items-center gap-2 text-start p-3 mb-4 rounded-xl" style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca !important; border-radius: 14px; font-size: 0.8125rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Animated Icon --}}
        <div class="gear-badge">
            <span class="pulse-ring"></span>
            <svg width="38" height="38" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>

        {{-- Status Pill --}}
        <div>
            <span class="custom-status-pill">
                <span class="pulse-dot-red"></span> Mode Pemeliharaan Aktif
            </span>
        </div>

        {{-- Title & Explanation --}}
        <h1 class="fw-bold text-slate-900 mb-2" style="font-size: 1.5rem; letter-spacing: -0.02em;">
            Sistem Sedang Diperbarui
        </h1>
        <p class="mb-0" style="font-size: 0.875rem; line-height: 1.6; color: #334155; font-weight: 500;">
            Mohon maaf atas ketidaknyamanannya. Tim Tokobii sedang melakukan pemeliharaan berkala untuk meningkatkan performa dan keandalan toko. Layanan publik akan segera dibuka kembali setelah selesai.
        </p>

        {{-- User Session & Logout Box if already logged in --}}
        @php
            $currentUser = $user ?? Auth::user();
        @endphp

        <div class="mt-4 d-flex flex-column gap-2">
            @if($currentUser)
                <div class="user-box mb-2" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 16px; padding: 1rem; text-align: left;">
                    <div class="d-flex align-items-center gap-2.5 mb-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 34px; height: 34px; font-size: 0.75rem; background: linear-gradient(135deg, #64748b, #475569); flex-shrink: 0;">
                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="fw-bold text-slate-900 text-truncate" style="font-size: 0.8125rem;">
                                {{ $currentUser->name }}
                            </div>
                            <div class="text-truncate" style="font-size: 0.75rem; color: #475569; font-weight: 500;">
                                {{ $currentUser->email }}
                            </div>
                        </div>
                    </div>
                    <div style="font-size: 0.78rem; line-height: 1.45; color: #334155; font-weight: 500;">
                        Sesi akun Anda sedang aktif. Tombol di bawah akan mengeluarkan akun Anda secara aman dan mengembalikan Anda ke tampilan login.
                    </div>
                </div>
            @endif

            {{-- Guaranteed Logout & Return to Login Button (Always clears session) --}}
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 rounded-xl shadow-sm" style="font-size: 0.875rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </button>
            </form>

            <button type="button" onclick="window.location.reload();" class="btn btn-outline-secondary py-2 fw-medium rounded-xl d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.8125rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Coba Muat Ulang Halaman</span>
            </button>
        </div>

        {{-- Brand watermark --}}
        <div class="mt-4 pt-2 border-top border-slate-100">
            <span class="text-slate-400" style="font-size: 0.72rem;">
                © {{ date('Y') }} <strong>Tokobii</strong> • Platform E-Commerce UMKM
            </span>
        </div>

    </div>

</body>
</html>
