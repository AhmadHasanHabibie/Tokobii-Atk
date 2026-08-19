@extends('layouts.guest.app')

@section('title', 'Tokobii - Pusat Alat Tulis Kantor & Sekolah')

@section('content')
<div class="container py-4">

    {{-- Hero Section --}}
    <div class="tokobii-card mb-5 overflow-hidden border-0 text-white" style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);">
        <div class="p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-4 mb-md-0">
                    <span class="tokobii-badge bg-white text-blue-700 fw-bold px-3 py-1.5 text-uppercase mb-3 shadow-sm" style="font-size: 0.75rem; background-color: #ffffff; color: #1d4ed8;">
                        Selamat Datang di Tokobii
                    </span>
                    <h1 class="fw-bold display-5 mb-3 text-white">Pusat Alat Tulis & Perlengkapan Kantor</h1>
                    <p class="lead mb-4 text-blue-100 small" style="max-width: 580px; opacity: 0.95; line-height: 1.7;">
                        Temukan berbagai kebutuhan perlengkapan kantor dan sekolah dengan kualitas terjamin, harga bersaing, dan proses pemesanan yang mudah.
                    </p>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('shop') }}" class="btn btn-light text-blue-700 fw-bold px-4 py-2.5 shadow-sm rounded-3">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Mulai Belanja
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-outline-light fw-semibold px-4 py-2.5 rounded-3">
                            Tentang Kami
                        </a>
                    </div>
                </div>

                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-20 backdrop-blur d-inline-flex flex-column align-items-center justify-content-center">
                        <div class="rounded-circle bg-white text-blue-600 p-3 mb-2 shadow-sm" style="background-color: #ffffff; color: #2563eb;">
                            <svg width="42" height="42" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="fw-bold text-white fs-6">Tokobii Official</span>
                        <span class="text-blue-100 small" style="font-size: 0.75rem;">Stok Lengkap & Terverifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection