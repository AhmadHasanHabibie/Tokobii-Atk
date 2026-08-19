@extends('layouts.guest.app')

@section('title', 'Hubungi Kami - Tokobii')

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Beranda</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Hubungi Kami</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="text-center mb-5">
        <span class="tokobii-badge tokobii-badge-info text-uppercase mb-2">Layanan Bantuan</span>
        <h1 class="display-6 fw-bold text-slate-900 mb-2" style="color: #0f172a;">Hubungi Tim Tokobii</h1>
        <p class="text-slate-500 small" style="max-width: 500px; margin: 0 auto;">
            Punya pertanyaan seputar ketersediaan produk ATK, pesanan grosir, atau bantuan sistem? Kami siap membantu Anda.
        </p>
    </div>

    <div class="row g-4 g-lg-5">

        {{-- Contact Information Cards --}}
        <div class="col-12 col-lg-5">
            <div class="d-flex flex-column gap-3">
                
                {{-- Address --}}
                <div class="tokobii-card p-3.5 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-blue-100 text-blue-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #eff6ff; color: #2563eb;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-slate-400 text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Alamat Toko</span>
                        <h6 class="fw-bold text-slate-900 mb-0" style="font-size: 0.9rem;">Jakarta, Indonesia</h6>
                    </div>
                </div>

                {{-- Email --}}
                <div class="tokobii-card p-3.5 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #ecfdf5; color: #059669;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-slate-400 text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Email Bantuan</span>
                        <h6 class="fw-bold text-slate-900 mb-0 font-monospace" style="font-size: 0.9rem;">support@tokobii.test</h6>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="tokobii-card p-3.5 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-amber-100 text-amber-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #fffbeb; color: #d97706;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-slate-400 text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Telepon & WhatsApp</span>
                        <h6 class="fw-bold text-slate-900 mb-0 font-monospace" style="font-size: 0.9rem;">+62 812-3456-7890</h6>
                    </div>
                </div>

                {{-- Operational Hours --}}
                <div class="tokobii-card p-3.5 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-purple-100 text-purple-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #f3e8ff; color: #9333ea;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-slate-400 text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Jam Operasional</span>
                        <h6 class="fw-bold text-slate-900 mb-0" style="font-size: 0.9rem;">Senin - Minggu: 08.00 - 21.00 WIB</h6>
                    </div>
                </div>

            </div>
        </div>

        {{-- Contact Form --}}
        <div class="col-12 col-lg-7">
            <div class="tokobii-card p-4 p-md-5">
                <h4 class="fw-bold text-slate-900 mb-1" style="font-size: 1.25rem;">Kirim Pesan ke Kami</h4>
                <p class="text-slate-500 small mb-4">Kami akan membalas pesan Anda sesegera mungkin.</p>

                <form onsubmit="alert('Terima kasih! Pesan Anda telah kami terima.'); return false;">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-rose-600">*</span></label>
                            <input type="text" id="name" class="form-control tokobii-input" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="email" class="form-label">Alamat Email <span class="text-rose-600">*</span></label>
                            <input type="email" id="email" class="form-control tokobii-input" placeholder="nama@email.com" required>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="form-label">Subjek Pesan <span class="text-rose-600">*</span></label>
                            <input type="text" id="subject" class="form-control tokobii-input" placeholder="Contoh: Pertanyaan Ketersediaan Stok Buku" required>
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label">Pesan Anda <span class="text-rose-600">*</span></label>
                            <textarea id="message" rows="5" class="form-control tokobii-input" placeholder="Tuliskan pertanyaan atau kebutuhan Anda secara rinci..." required></textarea>
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-tokobii-primary btn-tokobii-lg w-100 shadow-sm">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>Kirim Pesan Sekarang</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection