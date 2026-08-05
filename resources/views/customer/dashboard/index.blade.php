@extends('layouts.customer.app')

@section('title', 'Customer Dashboard')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Dashboard Customer
            </h2>

            <p class="text-muted mb-0">
                Selamat datang kembali,
                <strong>{{ Auth::user()->name }}</strong>.
            </p>

        </div>

    </div>

    <div class="alert alert-success">

        Login berhasil sebagai <strong>Customer</strong>.

    </div>

    <div class="row g-4">

        {{-- Total Orders --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Pesanan
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

        {{-- Waiting Payment --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Menunggu Pembayaran
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

        {{-- Completed Orders --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Pesanan Selesai
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

        {{-- Wishlist --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Wishlist
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white fw-bold">

                    Aktivitas Terbaru

                </div>

                <div class="card-body text-muted text-center py-5">

                    Belum ada aktivitas terbaru.

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white fw-bold">

                    Akses Cepat

                </div>

                <div class="card-body d-grid gap-2">

                    <a href="#"
                       class="btn btn-warning">

                        🛍️ Belanja Sekarang

                    </a>

                    <a href="#"
                       class="btn btn-outline-primary">

                        📦 Pesanan Saya

                    </a>

                    <a href="{{ route('customer.profile.index') }}"
                       class="btn btn-outline-secondary">

                        👤 Profil Saya

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection