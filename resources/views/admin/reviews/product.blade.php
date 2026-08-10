@extends('layouts.admin.app')

@section('title', 'Ulasan ' . $product->name . ' - Tokobii')

@section('content')

{{-- Header Halaman --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">

    <div>

        <div class="d-flex align-items-center gap-2 mb-2">

            {{-- Ikon Ulasan --}}
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
                    Ulasan {{ $product->name }}
                </h1>

            </div>

        </div>

        <p class="text-slate-500 mb-0"
           style="
                font-size: 0.875rem;
                line-height: 1.6;
           ">

            @if($product->reviews_count)

                <span style="
                    color: #f59e0b;
                    font-weight: 700;
                ">
                    ★ {{ number_format($product->reviews_avg_rating, 1) }}
                </span>

                <span class="text-slate-400">
                    / 5
                </span>

                <span class="text-slate-500">
                    dari
                </span>

                <span class="fw-semibold"
                      style="color: #334155;">
                    {{ $product->reviews_count }}
                </span>

                <span class="text-slate-500">
                    ulasan pelanggan terverifikasi.
                </span>

            @else

                Belum ada ulasan untuk produk ini.

            @endif

        </p>

    </div>


    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.reviews.categories.show', $product->category) }}"
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


{{-- Filter --}}
<div class="review-filter-card mb-4 p-3">

    <form method="GET">

        <div class="row g-2 align-items-center">

            {{-- Filter Rating --}}
            <div class="col-12 col-sm-5 col-md-4">

                <div class="review-select-wrapper">

                    <svg width="16"
                         height="16"
                         fill="currentColor"
                         viewBox="0 0 24 24">

                        <path d="M12 2.5l2.94 5.95 6.56.95-4.75 4.63 1.12 6.54L12 17.48l-5.87 3.09 1.12-6.54L2.5 9.4l6.56-.95L12 2.5z">
                        </path>

                    </svg>

                    <select class="review-select w-100"
                            name="rating">

                        <option value="">
                            Semua Rating
                        </option>

                        @for($i = 5; $i >= 1; $i--)

                            <option value="{{ $i }}"
                                @selected(request('rating') == $i)>
                                {{ $i }} Bintang
                            </option>

                        @endfor

                    </select>

                </div>

            </div>


            {{-- Urutan --}}
            <div class="col-12 col-sm-5 col-md-4">

                <div class="review-select-wrapper">

                    <svg width="16"
                         height="16"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h10M4 18h6">
                        </path>

                    </svg>

                    <select class="review-select w-100"
                            name="sort">

                        <option value="latest">
                            Ulasan Terbaru
                        </option>

                        <option value="oldest"
                            @selected(request('sort') === 'oldest')>
                            Ulasan Terlama
                        </option>

                    </select>

                </div>

            </div>


            {{-- Tombol Filter --}}
            <div class="col-12 col-sm-2 col-md-2">

                <button type="submit"
                        class="review-filter-button w-100">

                    <svg width="16"
                         height="16"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 4h18M6 10h12M10 16h4M11 22h2">
                        </path>

                    </svg>

                    <span>Filter</span>

                </button>

            </div>

        </div>

    </form>

</div>


{{-- Daftar Ulasan --}}
<div class="d-flex flex-column gap-3 mb-4">

    @forelse($reviews as $review)

        <div class="review-item-card p-4">

            {{-- Header Ulasan --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">

                <div class="d-flex align-items-center gap-2">

                    {{-- Avatar --}}
                    <div class="review-avatar">

                        {{ strtoupper(substr($review->user->name, 0, 1)) }}

                    </div>

                    <div>

                        <div class="fw-bold"
                             style="
                                color: #0f172a;
                                font-size: 0.875rem;
                             ">
                            {{ $review->user->name }}
                        </div>

                        <div class="text-slate-400"
                             style="font-size: 0.75rem;">
                            Pelanggan
                        </div>

                    </div>

                </div>


                {{-- Tanggal --}}
                <span class="review-date">

                    {{ $review->created_at->format('d M Y, H:i') }}

                </span>

            </div>


            {{-- Rating --}}
            <div class="review-rating mb-3">

                <span class="review-stars">

                    {{ str_repeat('★', $review->rating) }}<span class="review-stars-empty">{{ str_repeat('★', 5 - $review->rating) }}</span>

                </span>

                <span class="review-rating-number">
                    {{ $review->rating }}/5
                </span>

            </div>


            {{-- Komentar --}}
            <div class="review-comment mb-3">

                <p class="mb-0">
                    {{ $review->comment }}
                </p>

            </div>


            {{-- Informasi Pesanan --}}
            <div class="d-flex flex-wrap align-items-center gap-2 border-top pt-3"
                 style="
                    border-color: #e5edf7 !important;
                    font-size: 0.8rem;
                 ">

                <span class="review-info-label">

                    <svg width="15"
                         height="15"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                        </path>

                    </svg>

                    Invoice

                </span>

                <code class="review-invoice">
                    {{ $review->order->invoice_number }}
                </code>

            </div>

        </div>

    @empty

        {{-- Empty State --}}
        <div class="review-empty-card text-center py-5 px-4">

            <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                 style="
                    width: 54px;
                    height: 54px;
                    background: #eff6ff;
                    color: #2563eb;
                 ">

                <svg width="23"
                     height="23"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 10h8M8 14h5M19 4H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z">
                    </path>

                </svg>

            </div>

            <h6 class="fw-bold mb-1"
                style="color: #0f172a;">
                Belum Ada Ulasan
            </h6>

            <p class="text-slate-400 mb-0"
               style="font-size: 0.8125rem;">
                Tidak ada ulasan pelanggan yang sesuai dengan filter yang dipilih.
            </p>

        </div>

    @endforelse

</div>


{{-- Pagination --}}
@if($reviews->hasPages())

    <div class="d-flex justify-content-end mt-3">

        {{ $reviews->links('pagination::bootstrap-5') }}

    </div>

@endif


{{-- Styling --}}
<style>

    /* ==============================
       Filter Card
       ============================== */

    .review-filter-card {
        background: #ffffff;
        border: 1px solid #dbe7f5;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.045);
    }


    .review-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }


    .review-select-wrapper > svg {
        position: absolute;
        left: 13px;
        color: #64748b;
        pointer-events: none;
        z-index: 1;
    }


    .review-select {
        min-height: 42px;
        padding: 8px 38px 8px 38px;
        border: 1px solid #dbe7f5;
        border-radius: 10px;
        background: #ffffff;
        color: #334155;
        font-size: 0.8125rem;
        outline: none;
        cursor: pointer;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }


    .review-select:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }


    /* ==============================
       Filter Button
       ============================== */

    .review-filter-button {
        min-height: 42px;
        border: 0;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 0.8125rem;
        font-weight: 600;
        transition:
            background-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .review-filter-button:hover {
        background: #1d4ed8;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(37, 99, 235, 0.18);
    }


    /* ==============================
       Review Card
       ============================== */

    .review-item-card {
        background: #ffffff;
        border: 1px solid #dbe7f5;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.045);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
        position: relative;
        overflow: hidden;
    }


    .review-item-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: #2563eb;
        opacity: 0;
        transition: opacity 0.2s ease;
    }


    .review-item-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.08);
        transform: translateY(-2px);
    }


    .review-item-card:hover::before {
        opacity: 1;
    }


    /* ==============================
       Avatar
       ============================== */

    .review-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }


    /* ==============================
       Date
       ============================== */

    .review-date {
        color: #94a3b8;
        font-size: 0.75rem;
    }


    /* ==============================
       Rating
       ============================== */

    .review-rating {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 7px 10px;
        border-radius: 9px;
        background: #fffbeb;
        border: 1px solid #fef3c7;
    }


    .review-stars {
        color: #f59e0b;
        font-size: 0.95rem;
        letter-spacing: 2px;
        line-height: 1;
    }


    .review-stars-empty {
        color: #cbd5e1;
    }


    .review-rating-number {
        color: #92400e;
        font-size: 0.75rem;
        font-weight: 600;
    }


    /* ==============================
       Comment
       ============================== */

    .review-comment {
        color: #334155;
        font-size: 0.9rem;
        line-height: 1.7;
    }


    /* ==============================
       Invoice
       ============================== */

    .review-info-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-weight: 600;
    }


    .review-invoice {
        padding: 4px 8px;
        border-radius: 6px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 0.75rem;
        font-weight: 600;
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

        .review-back-button {
            width: 100%;
        }

        .review-rating {
            margin-top: 2px;
        }

        .review-item-card {
            padding: 18px !important;
        }

    }

</style>

@endsection