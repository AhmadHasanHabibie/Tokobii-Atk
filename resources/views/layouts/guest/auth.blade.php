<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tokobii Authentication')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <div class="container-fluid min-vh-100 p-0">
        <div class="row g-0 min-vh-100">

            {{-- Left Side: Brand Banner (Desktop) --}}
            <div class="col-lg-6 d-none d-lg-flex bg-dark text-white justify-content-center align-items-center p-5 position-relative overflow-hidden">
                <div class="position-relative z-1 text-center" style="max-width: 500px;">
                    <div class="mb-4">
                        <span class="bg-primary text-white rounded-4 px-3 py-2 fs-1 shadow-lg d-inline-block">🛍️</span>
                    </div>

                    <h1 class="display-4 fw-bold mb-3 tracking-tight text-white">
                        Tokobii
                    </h1>

                    <p class="lead text-white-50 mb-4">
                        Solusi belanja Alat Tulis Kantor (ATK) modern, cepat, aman, dan terpercaya untuk segala kebutuhan Anda.
                    </p>

                    <div class="d-flex flex-column gap-3 text-start bg-white bg-opacity-10 p-4 rounded-4 border border-white border-opacity-10 backdrop-blur">
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-primary text-white rounded-circle p-2 fs-6">✓</span>
                            <span>Sistem Transaksi Transparan & Cepat</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-primary text-white rounded-circle p-2 fs-6">✓</span>
                            <span>Verifikasi Pembayaran QRIS & Kasir Tunai</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-primary text-white rounded-circle p-2 fs-6">✓</span>
                            <span>Pickup Receipt & Laporan Produk Lengkap</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Auth Form Container --}}
            <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center p-4 p-sm-5 bg-light">
                <div class="w-100" style="max-width: 440px;">
                    
                    {{-- Brand Header on Mobile --}}
                    <div class="text-center mb-4">
                        <a href="{{ route('shop') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                            <span class="bg-primary text-white rounded-3 px-2 py-0.5 fs-4 shadow-sm">🛍️</span>
                            <span class="fw-bold fs-2 text-dark tracking-tight">Tokobii</span>
                        </a>
                        <p class="text-muted small mb-0">
                            @yield('subtitle', 'Silakan masuk ke akun Anda.')
                        </p>
                    </div>

                    {{-- Flash Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <strong>✅ Sukses!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <strong>❌ Kesalahan!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <strong>⚠️ Perhatikan Input:</strong>
                            <ul class="mb-0 mt-1 ps-3 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form Card --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-sm-5">
                            @yield('content')
                        </div>
                    </div>

                    {{-- Footer Copyright --}}
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            © {{ date('Y') }} <strong>Tokobii</strong>. All Rights Reserved.
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @stack('scripts')
</body>
</html>