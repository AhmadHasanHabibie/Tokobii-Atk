@extends('layouts.guest.app')

@section('title', 'Shop')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="fw-bold">

                Shop

            </h1>

            <p class="text-muted">

                Temukan berbagai kebutuhan alat tulis kantor dengan harga terbaik.

            </p>

        </div>

        {{-- Search & Filter --}}
        <div class="card shadow-sm border-0 mb-5">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-5">

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Cari produk...">

                    </div>

                    <div class="col-md-4">

                        <select class="form-select">

                            <option selected>

                                Semua Kategori

                            </option>

                            <option>

                                Pensil

                            </option>

                            <option>

                                Pulpen

                            </option>

                            <option>

                                Buku

                            </option>

                            <option>

                                Kertas

                            </option>

                            <option>

                                Map

                            </option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <button
                            class="btn btn-primary w-100">

                            🔍 Cari

                        </button>

                    </div>

                </div>

            </div>

        </div>

        {{-- Product List --}}
        <div class="row g-4">

            @for ($i = 1; $i <= 12; $i++)

                <div class="col-md-6 col-lg-3">

                    <div class="card border-0 shadow-sm h-100">

                        <img
                            src="https://placehold.co/400x300?text=Product"
                            class="card-img-top"
                            alt="Product">

                        <div class="card-body">

                            <span class="badge bg-primary mb-2">

                                ATK

                            </span>

                            <h5 class="fw-bold">

                                Produk {{ $i }}

                            </h5>

                            <p class="text-muted small">

                                Contoh deskripsi singkat produk.

                            </p>

                            <h5 class="text-primary fw-bold">

                                Rp 25.000

                            </h5>

                        </div>

                        <div class="card-footer bg-white border-0">

                            <div class="d-grid gap-2">

                                <a href="{{ route('product.show', 'produk-'.$i) }}"
                                   class="btn btn-outline-primary">

                                    Lihat Detail

                                </a>

                                @guest

                                    <a href="{{ route('login') }}"
                                       class="btn btn-warning">

                                        Login untuk Membeli

                                    </a>

                                @else

                                    <button
                                        class="btn btn-success">

                                        🛒 Tambah ke Keranjang

                                    </button>

                                @endguest

                            </div>

                        </div>

                    </div>

                </div>

            @endfor

        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">

            <nav>

                <ul class="pagination">

                    <li class="page-item disabled">

                        <a class="page-link">

                            Previous

                        </a>

                    </li>

                    <li class="page-item active">

                        <a class="page-link">

                            1

                        </a>

                    </li>

                    <li class="page-item">

                        <a class="page-link">

                            2

                        </a>

                    </li>

                    <li class="page-item">

                        <a class="page-link">

                            3

                        </a>

                    </li>

                    <li class="page-item">

                        <a class="page-link">

                            Next

                        </a>

                    </li>

                </ul>

            </nav>

        </div>

    </div>

</section>

@endsection