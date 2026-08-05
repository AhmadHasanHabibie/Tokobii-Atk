@extends('layouts.guest.app')

@section('title', 'About Us')

@section('content')

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="fw-bold">

                About Tokobii

            </h1>

            <p class="text-muted">

                Solusi belanja alat tulis kantor yang mudah, cepat, dan terpercaya.

            </p>

        </div>

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <img
                    src="https://placehold.co/600x400"
                    alt="About Tokobii"
                    class="img-fluid rounded shadow">

            </div>

            <div class="col-lg-6">

                <h3 class="fw-bold mb-3">

                    Tentang Kami

                </h3>

                <p class="text-muted">

                    Tokobii merupakan platform penjualan alat tulis kantor
                    yang dirancang untuk memberikan pengalaman belanja yang
                    mudah, cepat, dan aman bagi seluruh pelanggan.

                </p>

                <p class="text-muted">

                    Kami menyediakan berbagai kebutuhan alat tulis seperti
                    pulpen, pensil, buku tulis, map, kertas, perlengkapan
                    kantor, dan berbagai produk lainnya dengan kualitas
                    terbaik.

                </p>

                <a href="{{ route('shop') }}"
                   class="btn btn-primary">

                    Mulai Belanja

                </a>

            </div>

        </div>

    </div>

</section>

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Visi & Misi

            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h4 class="fw-bold">

                            Visi

                        </h4>

                        <p class="text-muted mb-0">

                            Menjadi platform penjualan alat tulis kantor
                            terpercaya yang memberikan pelayanan terbaik
                            bagi seluruh pelanggan di Indonesia.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h4 class="fw-bold">

                            Misi

                        </h4>

                        <ul class="text-muted mb-0">

                            <li>Menyediakan produk berkualitas.</li>

                            <li>Memberikan pelayanan terbaik.</li>

                            <li>Mempermudah proses belanja online.</li>

                            <li>Mengutamakan kepuasan pelanggan.</li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Kenapa Memilih Tokobii?

            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100 text-center">

                    <div class="card-body">

                        <div class="display-5 mb-3">

                            📦

                        </div>

                        <h5 class="fw-bold">

                            Produk Lengkap

                        </h5>

                        <p class="text-muted">

                            Berbagai kebutuhan alat tulis tersedia.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100 text-center">

                    <div class="card-body">

                        <div class="display-5 mb-3">

                            💰

                        </div>

                        <h5 class="fw-bold">

                            Harga Terjangkau

                        </h5>

                        <p class="text-muted">

                            Harga bersaing dengan kualitas terbaik.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100 text-center">

                    <div class="card-body">

                        <div class="display-5 mb-3">

                            🚚

                        </div>

                        <h5 class="fw-bold">

                            Pengiriman Cepat

                        </h5>

                        <p class="text-muted">

                            Pesanan diproses dengan cepat dan aman.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm border-0 h-100 text-center">

                    <div class="card-body">

                        <div class="display-5 mb-3">

                            ⭐

                        </div>

                        <h5 class="fw-bold">

                            Pelayanan Terbaik

                        </h5>

                        <p class="text-muted">

                            Kepuasan pelanggan menjadi prioritas kami.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection