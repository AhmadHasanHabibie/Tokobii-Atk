@extends('layouts.guest.app')

@section('title', 'Tokobii - Home')

@section('content')

{{-- Hero --}}
<section class="bg-primary text-white py-5">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <h1 class="display-4 fw-bold">

                    Selamat Datang di Tokobii

                </h1>

                <p class="lead my-4">

                    Temukan berbagai kebutuhan alat tulis kantor dengan
                    harga terbaik, kualitas terjamin, dan proses pembelian
                    yang mudah.

                </p>

                <a href="{{ route('shop') }}"
                   class="btn btn-light btn-lg">

                    🛍️ Mulai Belanja

                </a>

            </div>

            <div class="col-lg-6 text-center">

                <img
                    src="https://placehold.co/600x400?text=Tokobii"
                    class="img-fluid rounded shadow"
                    alt="Tokobii">

            </div>

        </div>

    </div>

</section>

{{-- Categories --}}
<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Kategori Produk

            </h2>

            <p class="text-muted">

                Pilih kategori favorit Anda.

            </p>

        </div>

        <div class="row g-4">

            @foreach([
                ['Pensil','✏️'],
                ['Pulpen','🖊️'],
                ['Buku','📚'],
                ['Kertas','📄'],
                ['Map','🗂️'],
                ['Spidol','🖍️']
            ] as $category)

                <div class="col-md-4 col-lg-2">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body text-center">

                            <div class="display-5 mb-3">

                                {{ $category[1] }}

                            </div>

                            <h6 class="fw-bold">

                                {{ $category[0] }}

                            </h6>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

{{-- Featured Products --}}
<section class="bg-light py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Produk Unggulan

            </h2>

            <p class="text-muted">

                Produk yang paling banyak diminati pelanggan.

            </p>

        </div>

        <div class="row g-4">

            @for($i = 1; $i <= 4; $i++)

                <div class="col-md-6 col-lg-3">

                    <div class="card shadow-sm border-0 h-100">

                        <img
                            src="https://placehold.co/300x250"
                            class="card-img-top"
                            alt="Product">

                        <div class="card-body">

                            <h5 class="fw-bold">

                                Produk {{ $i }}

                            </h5>

                            <p class="text-muted">

                                Deskripsi singkat produk.

                            </p>

                            <h5 class="text-primary fw-bold">

                                Rp 25.000

                            </h5>

                        </div>

                        <div class="card-footer bg-white border-0">

                            <a href="#"
                               class="btn btn-outline-primary w-100">

                                Lihat Detail

                            </a>

                        </div>

                    </div>

                </div>

            @endfor

        </div>

    </div>

</section>

{{-- Why Choose Us --}}
<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Kenapa Memilih Tokobii?

            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-3">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body">

                        <div class="display-4">

                            📦

                        </div>

                        <h5 class="fw-bold mt-3">

                            Produk Lengkap

                        </h5>

                        <p class="text-muted">

                            Ribuan produk alat tulis tersedia.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body">

                        <div class="display-4">

                            💰

                        </div>

                        <h5 class="fw-bold mt-3">

                            Harga Terjangkau

                        </h5>

                        <p class="text-muted">

                            Harga bersaing dan ramah di kantong.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body">

                        <div class="display-4">

                            🚚

                        </div>

                        <h5 class="fw-bold mt-3">

                            Pengiriman Cepat

                        </h5>

                        <p class="text-muted">

                            Pesanan diproses dengan cepat.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card border-0 shadow-sm text-center h-100">

                    <div class="card-body">

                        <div class="display-4">

                            ⭐

                        </div>

                        <h5 class="fw-bold mt-3">

                            Pelayanan Terbaik

                        </h5>

                        <p class="text-muted">

                            Kepuasan pelanggan menjadi prioritas.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- CTA --}}
<section class="bg-dark text-white py-5">

    <div class="container text-center">

        <h2 class="fw-bold">

            Siap Memulai Belanja?

        </h2>

        <p class="lead my-3">

            Jelajahi berbagai produk alat tulis kantor sekarang juga.

        </p>

        <a href="{{ route('shop') }}"
           class="btn btn-warning btn-lg">

            🛒 Belanja Sekarang

        </a>

    </div>

</section>

@endsection