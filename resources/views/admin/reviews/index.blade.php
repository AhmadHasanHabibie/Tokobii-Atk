@extends('layouts.admin.app')

@section('title', 'Ulasan Produk - Tokobii')

@section('content')

{{-- Header Halaman --}}
<div class="mb-4">

    <div class="d-flex align-items-center gap-2 mb-2">

        <div class="d-flex align-items-center justify-content-center rounded-3"
             style="
                width: 38px;
                height: 38px;
                background: #eff6ff;
                color: #2563eb;
             ">

            <svg width="19"
                 height="19"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                </path>

            </svg>

        </div>

        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-0"
                style="color: #0f172a;">
                Ulasan Produk
            </h1>
        </div>

    </div>

    <p class="text-slate-500 mb-0"
       style="font-size: 0.875rem;">
        Pilih kategori untuk melihat rating dan ulasan produk dari pelanggan.
    </p>

</div>


{{-- Grid Kategori --}}
<div class="row g-3">

    @forelse($categories as $category)

        <div class="col-12 col-md-6 col-lg-4">

            <a href="{{ route('admin.reviews.categories.show', $category) }}"
               class="text-decoration-none d-block h-100">

                <div class="review-category-card h-100 p-4">

                    {{-- Header Card --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <div class="d-flex align-items-center gap-3">

                            {{-- Ikon Kategori --}}
                            <div class="review-category-icon d-flex align-items-center justify-content-center rounded-3">

                                <svg width="19"
                                     height="19"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                    </path>

                                </svg>

                            </div>

                            <h5 class="fw-bold text-slate-900 mb-0"
                                style="
                                    font-size: 1rem;
                                    color: #0f172a;
                                ">
                                {{ $category->name }}
                            </h5>

                        </div>


                        {{-- Jumlah Produk --}}
                        <span class="review-product-count">
                            {{ $category->products_count }} Produk
                        </span>

                    </div>


                    {{-- Deskripsi --}}
                    <p class="text-slate-500 mb-3"
                       style="
                            font-size: 0.8125rem;
                            line-height: 1.6;
                       ">
                        Klik untuk melihat ulasan dari produk
                        {{ strtolower($category->name) }}.
                    </p>


                    {{-- Aksi Bawah --}}
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                        <span class="review-action-text">
                            Lihat Ulasan
                        </span>

                        <div class="review-arrow d-flex align-items-center justify-content-center">

                            <svg width="16"
                                 height="16"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5l7 7-7 7">
                                </path>

                            </svg>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    @empty

        <div class="col-12">

            <div class="review-empty-card text-center py-5 px-4">

                <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                     style="
                        width: 52px;
                        height: 52px;
                        background: #f1f5f9;
                        color: #64748b;
                     ">

                    <svg width="22"
                         height="22"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4">
                        </path>

                    </svg>

                </div>

                <h6 class="fw-bold text-slate-800 mb-1">
                    Belum Ada Kategori
                </h6>

                <p class="text-slate-400 mb-0"
                   style="font-size: 0.8125rem;">
                    Belum ada kategori produk yang tersedia untuk ulasan.
                </p>

            </div>

        </div>

    @endforelse

</div>


{{-- Styling Card Ulasan --}}
<style>

    .review-category-card {
        background: #ffffff;
        border: 1px solid #dbe7f5;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.055);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
        position: relative;
        overflow: hidden;
    }


    .review-category-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #2563eb;
        opacity: 0;
        transition: opacity 0.2s ease;
    }


    .review-category-card:hover {
        transform: translateY(-3px);
        border-color: #93c5fd;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.10);
    }


    .review-category-card:hover::before {
        opacity: 1;
    }


    .review-category-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        background: #eff6ff;
        color: #2563eb;
        transition:
            background-color 0.2s ease,
            color 0.2s ease;
    }


    .review-category-card:hover .review-category-icon {
        background: #2563eb;
        color: #ffffff;
    }


    .review-product-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        padding: 5px 10px;
        border-radius: 999px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #2563eb;
        font-size: 0.75rem;
        font-weight: 600;
    }


    .review-action-text {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        transition: color 0.2s ease;
    }


    .review-category-card:hover .review-action-text {
        color: #2563eb;
    }


    .review-arrow {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #f8fafc;
        color: #64748b;
        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease;
    }


    .review-category-card:hover .review-arrow {
        background: #2563eb;
        color: #ffffff;
        transform: translateX(2px);
    }


    .review-empty-card {
        background: #ffffff;
        border: 1px solid #dbe7f5;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.045);
    }

</style>

@endsection