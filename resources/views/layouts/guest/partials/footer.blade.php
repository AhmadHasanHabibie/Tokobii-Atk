<footer class="bg-white border-top border-slate-200 mt-auto pt-5 pb-4" style="border-color: #e2e8f0;">
    <div class="container">
        <div class="row g-4 mb-5">
            {{-- Col 1: Brand Info --}}
            <div class="col-12 col-md-5 col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2.5 py-1.5 shadow-sm" style="background-color: #2563eb;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </span>
                    <span class="fw-bold text-slate-900 fs-4 tracking-tight">Tokobii</span>
                </div>
                <p class="text-slate-500 small mb-4" style="line-height: 1.6;">
                    Platform belanja perlengkapan alat tulis kantor (ATK) dan kebutuhan sekolah modern, cepat, aman, dan terpercaya.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <span class="tokobii-badge tokobii-badge-success">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Layanan Buka Setiap Hari
                    </span>
                </div>
            </div>

            {{-- Col 2: Navigasi Cepat --}}
            <div class="col-6 col-md-3 col-lg-2 offset-lg-1">
                <h6 class="fw-bold text-slate-900 mb-3" style="font-size: 0.875rem;">Navigasi</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small">
                    <li><a href="{{ route('shop') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Katalog Produk</a></li>
                    <li><a href="{{ route('about') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Hubungi Kami</a></li>
                    <li><a href="{{ route('login') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Masuk Akun</a></li>
                    <li><a href="{{ route('register') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Daftar Pelanggan</a></li>
                </ul>
            </div>

            {{-- Col 3: Layanan Pelanggan --}}
            <div class="col-6 col-md-4 col-lg-2">
                <h6 class="fw-bold text-slate-900 mb-3" style="font-size: 0.875rem;">Layanan</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small">
                    <li class="text-slate-500">Pengambilan di Toko</li>
                    <li class="text-slate-500">Pembayaran QRIS & Tunai</li>
                    <li class="text-slate-500">Struk Verifikasi QR</li>
                    <li class="text-slate-500">Pelaporan Produk</li>
                </ul>
            </div>

            {{-- Col 4: Kontak & Alamat --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="fw-bold text-slate-900 mb-3" style="font-size: 0.875rem;">Kontak Tokobii</h6>
                <div class="d-flex flex-column gap-2.5 small">
                    <div class="d-flex align-items-start gap-2 text-slate-500">
                        <svg class="text-blue-600 flex-shrink-0 mt-0.5" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Jakarta, Indonesia</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-slate-500">
                        <svg class="text-emerald-600 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>support@tokobii.test</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-slate-500">
                        <svg class="text-amber-600 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>+62 812-3456-7890</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div class="pt-4 border-top border-slate-100 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <span class="text-slate-400 small">
                © {{ date('Y') }} <strong class="text-slate-700">Tokobii</strong>. Seluruh Hak Cipta Dilindungi.
            </span>
            <span class="text-slate-400 small font-monospace">
                Pusat Alat Tulis & Perlengkapan Kantor
            </span>
        </div>
    </div>
</footer>