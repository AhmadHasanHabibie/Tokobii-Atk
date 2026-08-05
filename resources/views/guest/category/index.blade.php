@extends('layouts.guest.app')

@section('title', 'Product Categories')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="fw-bold">

                Product Categories

            </h1>

            <p class="text-muted">

                Pilih kategori produk yang ingin Anda jelajahi.

            </p>

        </div>

        <div class="row g-4">

            {{-- Category Card --}}
            @foreach ([
                ['name' => 'Pensil', 'icon' => '✏️'],
                ['name' => 'Pulpen', 'icon' => '🖊️'],
                ['name' => 'Buku Tulis', 'icon' => '📚'],
                ['name' => 'Kertas', 'icon' => '📄'],
                ['name' => 'Map', 'icon' => '🗂️'],
                ['name' => 'Spidol', 'icon' => '🖍️'],
                ['name' => 'Penghapus', 'icon' => '🧽'],
                ['name' => 'Perlengkapan Kantor', 'icon' => '🏢'],
            ] as $category)

                <div class="col-md-3">

                    <div class="card shadow-sm border-0 h-100">

                        <div class="card-body text-center">

                            <div class="display-4 mb-3">

                                {{ $category['icon'] }}

                            </div>

                            <h5 class="fw-bold">

                                {{ $category['name'] }}

                            </h5>

                            <p class="text-muted">

                                Lihat berbagai produk dalam kategori
                                {{ strtolower($category['name']) }}.

                            </p>

                            <a href="#"
                               class="btn btn-outline-primary">

                                Lihat Produk

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endsection