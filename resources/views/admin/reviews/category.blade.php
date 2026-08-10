@extends('layouts.admin.app')

@section('title', $category->name . ' - Ulasan Produk - Tokobii')

@section('content')

{{-- Header Halaman --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">

    <div>

        <div class="d-flex align-items-center gap-2 mb-2">

            {{-- Ikon --}}
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

                <h1 class="h3 fw-bold mb-0"
                    style="color: #0f172a;">
                    Ulasan {{ $category->name }}
                </h1>

            </div>

        </div>

        <p class="text-slate-500 mb-0"
           style="
                font-size: 0.875rem;
                line-height: 1.6;
           ">
            Daftar produk dan ringkasan rating pelanggan pada kategori
            {{ strtolower($category->name) }}.
        </p>

    </div>


    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.reviews.index') }}"
       class="review-back-button text-decoration-none">

        <svg width="16"
             height="16"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7">
            </path>

        </svg>

        <span>Kembali ke Kategori</span>

    </a>

</div>


{{-- Produk Grid --}}
<div class="row g-3 mb-4">

    @forelse($products as $product)

        <div class="col-12 col-md-6">

            <div class="review-product-card h-100 p-4">

                {{-- Bagian Atas --}}
                <div>

                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">

                        <div class="d-flex align-items-center gap-3">

                            {{-- Ikon Produk --}}
                            <div class="review-product-icon d-flex align-items-center justify-content-center rounded-3">

                                <svg width="19"
                                     height="19"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                    </path>

                                </svg>

                            </div>

                            <div>

                                <h5 class="fw-bold mb-1"
                                    style="
                                        font-size: 1rem;
                                        color: #0f172a;
                                    ">
                                    {{ $product->name }}
                                </h5>

                                <span class="text-slate-400"
                                      style="font-size: 0.75rem;">
                                    Ringkasan ulasan produk
                                </span>

                            </div>

                        </div>


                        {{-- Jumlah Ulasan --}}
                        <span class="review-product-count">

                            {{ $product->reviews_count }} Ulasan

                        </span>

                    </div>


                    {{-- Rating --}}
                    <div class="review-rating-box mb-3">

                        @if($product->reviews_count)

                            <div class="d-flex align-items-center gap-2">

                                <div class="review-star">

                                    <svg width="17"
                                         height="17"
                                         fill="currentColor"
                                         viewBox="0 0 24 24">

                                        <path d="M12 2.5l2.94 5.95 6.56.95-4.75 4.63 1.12 6.54L12 17.48l-5.87 3.09 1.12-6.54L2.5 9.4l6.56-.95L12 2.5z">
                                        </path>

                                    </svg>

                                </div>

                                <div>

                                    <span class="fw-bold"
                                          style="
                                            color: #f59e0b;
                                            font-size: 1rem;
                                          ">
                                        {{ number_format($product->reviews_avg_rating, 1) }}
                                    </span>

                                    <span class="text-slate-400"
                                          style="font-size: 0.8rem;">
                                        / 5
                                    </span>

                                </div>

                            </div>

                            <span class="text-slate-500"
                                  style="font-size: 0.8rem;">
                                Berdasarkan {{ $product->reviews_count }} ulasan pelanggan
                            </span>

                        @else

                            <div class="d-flex align-items-center gap-2">

                                <div class="review-star review-star-empty">

                                    <svg width="17"
                                         height="17"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M12 2.5l2.94 5.95 6.56.95-4.75 4.63 1.12 6.54L12 17.48l-5.87 3.09 1.12-6.54L2.5 9.4l6.56-.95L12 2.5z">
                                        </path>

                                    </svg>

                                </div>

                                <span class="text-slate-400"
                                      style="font-size: 0.8125rem;">
                                    Belum ada rating
                                </span>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Action --}}
                <div class="pt-3 border-top">

                    <a href="{{ route('admin.reviews.products.show', $product) }}"
                       class="review-view-button text-decoration-none">

                        <span>
                            Lihat Ulasan
                        </span>

                        <svg width="16"
                             height="16"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 12h14M13 6l6 6-6 6">
                            </path>

                        </svg>

                    </a>

                </div>

            </div>

        </div>

    @empty

        {{-- Empty State --}}
        <div class="col-12">

            <div class="review-empty-card text-center py-5 px-4">

                <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                     style="
                        width: 52px;
                        height: 52px;
                        background: #eff6ff;
                        color: #2563eb;
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

                <h6 class="fw-bold mb-1"
                    style="color: #0f172a;">
                    Belum Ada Produk
                </h6>

                <p class="text-slate-400 mb-0"
                   style="font-size: 0.8125rem;">
                    Belum ada produk yang tersedia dalam kategori ini.
                </p>

            </div>

        </div>

    @endforelse

</div>


{{-- Pagination --}}
@if($products->hasPages())

    <div class="d-flex justify-content-end mt-3">

        {{ $products->links('pagination::bootstrap-5') }}

    </div>

@endif


{{-- Styling --}}
<style>

    /* ==============================
       Product Review Card
       ============================== */

    .review-product-card {
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


    .review-product-card::before {
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


    .review-product-card:hover {
        transform: translateY(-3px);
        border-color: #93c5fd;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.10);
    }


    .review-product-card:hover::before {
        opacity: 1;
    }


    /* ==============================
       Product Icon
       ============================== */

    .review-product-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        background: #eff6ff;
        color: #2563eb;
        transition:
            background-color 0.2s ease,
            color 0.2s ease;
    }


    .review-product-card:hover .review-product-icon {
        background: #2563eb;
        color: #ffffff;
    }


    /* ==============================
       Product Count
       ============================== */

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
        font-size: 0.72rem;
        font-weight: 600;
    }


    /* ==============================
       Rating Box
       ============================== */

    .review-rating-box {
        min-height: 58px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e5edf7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }


    .review-star {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #fff7ed;
        color: #f59e0b;
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .review-star-empty {
        background: #f1f5f9;
        color: #94a3b8;
    }


    /* ==============================
       Back Button
       ============================== */

    .review-back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 8px 15px;
        border: 1px solid #dbe7f5;
        border-radius: 10px;
        background: #ffffff;
        color: #475569;
        font-size: 0.8125rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.035);
        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            border-color 0.2s ease;
    }


    .review-back-button:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }


    /* ==============================
       View Reviews Button
       ============================== */

    .review-view-button {
        width: 100%;
        min-height: 40px;
        padding: 9px 14px;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        transition:
            background-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .review-view-button:hover {
        background: #1d4ed8;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(37, 99, 235, 0.20);
    }


    /* ==============================
       Empty State
       ============================== */

    .review-empty-card {
        background: #ffffff;
        border: 1px solid #dbe7f5;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.045);
    }


    /* ==============================
       Responsive
       ============================== */

    @media (max-width: 576px) {

        .review-rating-box {
            flex-direction: column;
            align-items: flex-start;
        }

        .review-product-card {
            padding: 18px !important;
        }

        .review-back-button {
            width: 100%;
        }

    }

</style>

@endsection