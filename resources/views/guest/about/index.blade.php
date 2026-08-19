@extends('layouts.guest.app')

@section('title', 'Tentang Tokobii - Pusat Alat Tulis Kantor & Sekolah')

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Beranda</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Tentang Kami</li>
        </ol>
    </nav>

    {{-- Hero Section --}}
    <div class="tokobii-card p-4 p-md-5 mb-5 overflow-hidden">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-12 col-lg-7">
                <span class="tokobii-badge tokobii-badge-info text-uppercase mb-3">Tentang Tokobii</span>
                <h1 class="display-6 fw-bold text-slate-900 mb-3" style="color: #0f172a;">
                    Solusi Belanja Alat Tulis Kantor & Sekolah Terpercaya
                </h1>
                <p class="text-slate-600 mb-4" style="line-height: 1.8;">
                    Tokobii adalah platform penyedia kebutuhan alat tulis kantor (ATK), buku, kertas, dan perlengkapan kerja modern yang dirancang untuk memberikan kemudahan berbelanja secara cepat, akurat, dan transparan.
                </p>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('shop') }}" class="btn btn-tokobii-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Mulai Belanja</span>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-tokobii-secondary">
                        <span>Hubungi Kami</span>
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-5 text-center">
                <div class="p-5 bg-slate-50 rounded-4 border border-slate-200 d-inline-flex flex-column align-items-center justify-content-center w-100">
                    <div class="rounded-circle bg-blue-600 text-white p-4 mb-3 shadow-md" style="background-color: #2563eb;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h5 class="fw-bold text-slate-900 mb-1">Toko Fisik & Online</h5>
                    <span class="text-slate-500 small">Melayani Pelanggan Perorangan & Korporat</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Vision & Mission --}}
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-6">
            <div class="tokobii-card p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #eff6ff; color: #2563eb;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-slate-900 mb-0" style="font-size: 1.15rem;">Visi Tokobii</h4>
                </div>
                <p class="text-slate-600 small mb-0" style="line-height: 1.8;">
                    Menjadi jaringan toko perlengkapan alat tulis kantor terkemuka di Indonesia yang mengedepankan efisiensi, kualitas barang bermutu tinggi, dan kepuasan pelanggan secara berkesinambungan.
                </p>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="tokobii-card p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #ecfdf5; color: #059669;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-slate-900 mb-0" style="font-size: 1.15rem;">Misi Tokobii</h4>
                </div>
                <ul class="list-unstyled text-slate-600 small d-flex flex-column gap-2 mb-0" style="line-height: 1.6;">
                    <li class="d-flex align-items-start gap-2">
                        <svg class="text-emerald-500 flex-shrink-0 mt-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Menyediakan produk alat tulis kantor dan sekolah berkualitas teruji.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <svg class="text-emerald-500 flex-shrink-0 mt-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Memberikan kemudahan pesanan online dan pengambilan instan di toko.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <svg class="text-emerald-500 flex-shrink-0 mt-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Mendukung transparansi transaksi melalui QRIS dan cetak struk terverifikasi.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Value Pillars --}}
    <div class="mb-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-slate-900 mb-1" style="font-size: 1.35rem;">Keunggulan Berbelanja di Tokobii</h3>
            <p class="text-slate-500 small mb-0">Komitmen kami untuk memberikan pengalaman berbelanja terbaik.</p>
        </div>

        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="tokobii-card p-4 text-center h-100">
                    <div class="rounded-circle bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px; background-color: #eff6ff; color: #2563eb;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-slate-900 mb-1">Produk Lengkap</h6>
                    <p class="text-slate-500 small mb-0">Berbagai merk dan kategori alat tulis kantor terlengkap.</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="tokobii-card p-4 text-center h-100">
                    <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px; background-color: #ecfdf5; color: #059669;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-slate-900 mb-1">Harga Terjangkau</h6>
                    <p class="text-slate-500 small mb-0">Harga kompetitif dengan promo menarik setiap periode.</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="tokobii-card p-4 text-center h-100">
                    <div class="rounded-circle bg-amber-100 text-amber-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px; background-color: #fffbeb; color: #d97706;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-slate-900 mb-1">Pengambilan Cepat</h6>
                    <p class="text-slate-500 small mb-0">Pesanan siap diambil tanpa perlu antre lama di kasir.</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="tokobii-card p-4 text-center h-100">
                    <div class="rounded-circle bg-purple-100 text-purple-600 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px; background-color: #f3e8ff; color: #9333ea;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-slate-900 mb-1">Pelayanan Ramah</h6>
                    <p class="text-slate-500 small mb-0">Tim customer care siap melayani pertanyaan Anda.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection