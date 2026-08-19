<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Autentikasi - Tokobii')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-slate-50 text-slate-800 d-flex flex-column min-vh-100 font-sans antialiased">

    <div class="container-fluid min-vh-100 p-0">
        <div class="row g-0 min-vh-100">

            {{-- Left Side: Brand Banner (Desktop) --}}
            <div class="col-lg-6 d-none d-lg-flex bg-slate-900 text-white justify-content-center align-items-center p-5 position-relative overflow-hidden" style="background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);">
                <div class="position-relative z-1 text-center" style="max-width: 480px;">
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-3 p-3 shadow-lg" style="background-color: #2563eb;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </span>
                    </div>

                    <h1 class="h2 fw-bold mb-3 tracking-tight text-white">
                        Tokobii Store
                    </h1>

                    <p class="text-slate-400 mb-4" style="line-height: 1.6;">
                        Solusi belanja Alat Tulis Kantor (ATK) modern, cepat, aman, dan terpercaya untuk segala kebutuhan Anda.
                    </p>

                    <div class="d-flex flex-column gap-3 text-start bg-slate-800 bg-opacity-50 p-4 rounded-3 border border-slate-700">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-blue-500 bg-opacity-20 text-blue-400 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="small text-slate-300">Sistem Transaksi Transparan & Cepat</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-blue-500 bg-opacity-20 text-blue-400 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="small text-slate-300">Verifikasi Pembayaran QRIS & Kasir Tunai</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-blue-500 bg-opacity-20 text-blue-400 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="small text-slate-300">Struk Pengambilan Pesanan dengan QR Code</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Auth Form Container --}}
            <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center p-4 p-sm-5 bg-slate-50">
                <div class="w-100" style="max-width: 440px;">
                    
                    {{-- Brand Header on Mobile --}}
                    <div class="text-center mb-4">
                        <a href="{{ route('shop') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                            <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2 py-1 shadow-sm" style="background-color: #2563eb;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </span>
                            <span class="fw-bold fs-3 text-slate-900 tracking-tight">Tokobii</span>
                        </a>
                        <p class="text-slate-500 small mb-0">
                            @yield('subtitle', 'Silakan masuk ke akun Anda.')
                        </p>
                    </div>

                    {{-- Flash Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success border-0 bg-emerald-50 text-emerald-800 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <svg class="text-emerald-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="fw-semibold text-sm">{{ session('success') }}</span>
                            </div>
                            <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 bg-rose-50 text-rose-800 rounded-xl p-3.5 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <svg class="text-rose-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="fw-semibold text-sm">{{ session('error') }}</span>
                            </div>
                            <button type="button" class="btn-close text-slate-400 shadow-none" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 bg-rose-50 text-rose-800 rounded-xl p-3.5 mb-4 shadow-sm" role="alert">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <svg class="text-rose-600 flex-shrink-0" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="fw-bold text-sm">Perhatikan Formulir:</span>
                            </div>
                            <ul class="mb-0 ps-3 small text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Card --}}
                    <div class="tokobii-card p-4 p-sm-5">
                        @yield('content')
                    </div>

                    {{-- Footer Copyright --}}
                    <div class="text-center mt-4">
                        <small class="text-slate-400">
                            © {{ date('Y') }} <strong class="text-slate-600">Tokobii</strong>. Hak Cipta Dilindungi.
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @stack('scripts')
</body>
</html>