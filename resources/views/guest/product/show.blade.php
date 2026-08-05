@extends('layouts.guest.app')

@section('title', 'Product Detail')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="row g-5">

            {{-- Product Image --}}
            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <img
                        src="https://placehold.co/600x500?text=Product+Image"
                        class="img-fluid rounded"
                        alt="Product Image">

                </div>

            </div>

            {{-- Product Information --}}
            <div class="col-lg-6">

                <span class="badge bg-primary mb-3">

                    Alat Tulis

                </span>

                <h2 class="fw-bold">

                    Nama Produk

                </h2>

                <h3 class="text-primary fw-bold my-3">

                    Rp 25.000

                </h3>

                <p class="text-muted">

                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Tempora recusandae ipsa aspernatur voluptate. Produk ini
                    merupakan contoh tampilan detail produk pada halaman Guest
                    sebelum dihubungkan ke database.

                </p>

                <div class="mb-4">

                    <strong>Stok :</strong>

                    <span class="text-success">

                        Tersedia

                    </span>

                </div>

                <div class="d-flex gap-2 mb-4">

                    <input
                        type="number"
                        class="form-control"
                        value="1"
                        min="1"
                        style="width: 120px;">

                    @guest

                        <a href="{{ route('login') }}"
                           class="btn btn-warning">

                            🛒 Login untuk Membeli

                        </a>

                    @else

                        <button
                            class="btn btn-warning">

                            🛒 Tambah ke Keranjang

                        </button>

                    @endguest

                </div>

                <a href="{{ route('shop') }}"
                   class="btn btn-outline-secondary">

                    ← Kembali ke Shop

                </a>

            </div>

        </div>

    </div>

</section>

{{-- Product Description --}}
<section class="pb-5">

    <div class="container">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h4 class="mb-0 fw-bold">

                    Deskripsi Produk

                </h4>

            </div>

            <div class="card-body">

                <p class="text-muted mb-0">

                    Produk ini dibuat sebagai contoh halaman detail produk.
                    Nantinya seluruh informasi seperti nama produk, harga,
                    stok, kategori, gambar, dan deskripsi akan diambil
                    langsung dari database menggunakan Laravel Eloquent.

                </p>

            </div>

        </div>

    </div>

</section>

{{-- Related Products --}}
<section class="pb-5">

    <div class="container">

        <h3 class="fw-bold mb-4">

            Produk Terkait

        </h3>

        <div class="row g-4">

            @for ($i = 1; $i <= 4; $i++)

                <div class="col-md-6 col-lg-3">

                    <div class="card shadow-sm border-0 h-100">

                        <img
                            src="https://placehold.co/300x220?text=Product"
                            class="card-img-top"
                            alt="Product">

                        <div class="card-body">

                            <h6 class="fw-bold">

                                Produk {{ $i }}

                            </h6>

                            <p class="text-primary fw-bold">

                                Rp 20.000

                            </p>

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

@endsection