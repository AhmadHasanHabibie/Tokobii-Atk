@extends('layouts.admin.app')

@section('title', 'Dashboard Admin - Tokobii')

@section('content')

<style>
    /* =========================================================
       DASHBOARD ACTION CARDS
       Hanya styling UI - tidak mengubah route / logic
       ========================================================= */

    .dashboard-action-card {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;

        min-height: 42px;
        padding: 0.65rem 1rem;

        border: 1px solid #2563eb;
        border-radius: 10px;

        background: #2563eb;
        color: #ffffff !important;

        font-size: 0.8125rem;
        font-weight: 600;
        line-height: 1.2;

        text-decoration: none;

        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.14);

        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            transform 0.15s ease;
    }

    .dashboard-action-card:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff !important;

        box-shadow: 0 5px 12px rgba(37, 99, 235, 0.20);

        transform: translateY(-1px);
    }

    .dashboard-action-card:active {
        background: #1e40af;
        border-color: #1e40af;

        color: #ffffff !important;

        transform: translateY(0);

        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.15);
    }

    .dashboard-action-card svg {
        flex-shrink: 0;
    }


    /* =========================================================
       SMALL ACTION CARD
       Untuk Detail / Lihat Semua / Kelola Stok
       ========================================================= */

    .dashboard-small-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;

        min-height: 34px;
        padding: 0.45rem 0.75rem;

        border: 1px solid #2563eb;
        border-radius: 8px;

        background: #2563eb;
        color: #ffffff !important;

        font-size: 0.75rem;
        font-weight: 600;

        text-decoration: none;

        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.12);

        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            transform 0.15s ease;
    }

    .dashboard-small-action:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff !important;

        box-shadow: 0 4px 9px rgba(37, 99, 235, 0.18);

        transform: translateY(-1px);
    }

    .dashboard-small-action:active {
        background: #1e40af;
        border-color: #1e40af;

        transform: translateY(0);
    }


    /* =========================================================
       HEADER ACTION GROUP
       ========================================================= */

    .dashboard-header-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 575.98px) {

        .dashboard-header-actions {
            width: 100%;
        }

        .dashboard-header-actions .dashboard-action-card {
            flex: 1;
        }

        .dashboard-action-card {
            min-height: 40px;
            padding: 0.6rem 0.75rem;
        }

    }
</style>


<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h1 class="h3 fw-bold text-slate-900 mb-1"
                style="color: #0f172a;">
                Dashboard
            </h1>

            <p class="text-slate-500 mb-0"
               style="font-size: 0.875rem;">

                Selamat datang kembali,
                <strong class="text-slate-800">
                    {{ Auth::user()->name }}
                </strong>.

                Berikut adalah ringkasan operasional Tokobii.

            </p>

        </div>


        {{-- Header Actions --}}
        <div class="dashboard-header-actions">

            {{-- Pesanan & Pengambilan --}}
            <a href="{{ route('admin.orders.index') }}"
               class="dashboard-action-card">

                <svg width="16"
                     height="16"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                    </path>

                </svg>

                <span>
                    Pesanan & Pengambilan
                </span>

            </a>


            {{-- Scan QR --}}
            <a href="{{ route('admin.orders.scan') }}"
               class="dashboard-action-card">

                <svg width="16"
                     height="16"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>

                </svg>

                <span>
                    Scan QR
                </span>

            </a>

        </div>

    </div>


    {{-- 4 Main Stat Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Kategori --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="tokobii-card p-4 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Kategori
                        </span>

                        <h2 class="fw-bold text-slate-900 mb-0 mt-2"
                            style="font-size: 1.75rem;">
                            {{ number_format($totalCategories) }}
                        </h2>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 48px;
                            height: 48px;
                            background-color: #eff6ff;
                            color: #2563eb;
                         ">

                        <svg width="24"
                             height="24"
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

                </div>

            </div>

        </div>


        {{-- Total Produk --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="tokobii-card p-4 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Produk
                        </span>

                        <h2 class="fw-bold text-slate-900 mb-0 mt-2"
                            style="font-size: 1.75rem;">
                            {{ number_format($totalProducts) }}
                        </h2>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 48px;
                            height: 48px;
                            background-color: #f0f9ff;
                            color: #0284c7;
                         ">

                        <svg width="24"
                             height="24"
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

                </div>

            </div>

        </div>


        {{-- Total Pelanggan --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="tokobii-card p-4 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Pelanggan
                        </span>

                        <h2 class="fw-bold text-slate-900 mb-0 mt-2"
                            style="font-size: 1.75rem;">
                            {{ number_format($totalCustomers) }}
                        </h2>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 48px;
                            height: 48px;
                            background-color: #fefce8;
                            color: #ca8a04;
                         ">

                        <svg width="24"
                             height="24"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Pesanan --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="tokobii-card p-4 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Pesanan
                        </span>

                        <h2 class="fw-bold text-slate-900 mb-0 mt-2"
                            style="font-size: 1.75rem;">
                            {{ number_format($totalOrders) }}
                        </h2>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 48px;
                            height: 48px;
                            background-color: #f0fdf4;
                            color: #16a34a;
                         ">

                        <svg width="24"
                             height="24"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Order Status Breakdown & Revenue Banner --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-8">

            <div class="tokobii-card h-100">

                <div class="tokobii-card-header d-flex justify-content-between align-items-center">

                    <h5 class="fw-bold mb-0 text-slate-900"
                        style="font-size: 1rem;">
                        Ringkasan Status Transaksi
                    </h5>

                    {{-- Lihat Semua Pesanan --}}
                    <a href="{{ route('admin.orders.index') }}"
                       class="dashboard-small-action">

                        Lihat Semua Pesanan →

                    </a>

                </div>


                <div class="p-4">

                    <div class="row g-3 text-center">

                        <div class="col-6 col-sm-3">

                            <div class="p-3 rounded-3"
                                 style="
                                    background-color: #fffbeb;
                                    border: 1px solid #fef08a;
                                 ">

                                <span class="text-amber-800 fw-semibold d-block"
                                      style="font-size: 0.75rem;">
                                    Verifikasi
                                </span>

                                <span class="fw-bold text-amber-900"
                                      style="font-size: 1.5rem;">
                                    {{ number_format($waitingVerificationOrders) }}
                                </span>

                            </div>

                        </div>


                        <div class="col-6 col-sm-3">

                            <div class="p-3 rounded-3"
                                 style="
                                    background-color: #f0fdf4;
                                    border: 1px solid #bbf7d0;
                                 ">

                                <span class="text-emerald-800 fw-semibold d-block"
                                      style="font-size: 0.75rem;">
                                    Pesanan Dibayar
                                </span>

                                <span class="fw-bold text-emerald-900"
                                      style="font-size: 1.5rem;">
                                    {{ number_format($paidOrders) }}
                                </span>

                            </div>

                        </div>


                        <div class="col-6 col-sm-3">

                            <div class="p-3 rounded-3"
                                 style="
                                    background-color: #eff6ff;
                                    border: 1px solid #bfdbfe;
                                 ">

                                <span class="text-blue-800 fw-semibold d-block"
                                      style="font-size: 0.75rem;">
                                    Siap Diambil
                                </span>

                                <span class="fw-bold text-blue-900"
                                      style="font-size: 1.5rem;">
                                    {{ number_format($readyForPickupOrders) }}
                                </span>

                            </div>

                        </div>


                        <div class="col-6 col-sm-3">

                            <div class="p-3 rounded-3"
                                 style="
                                    background-color: #f8fafc;
                                    border: 1px solid #e2e8f0;
                                 ">

                                <span class="text-slate-600 fw-semibold d-block"
                                      style="font-size: 0.75rem;">
                                    Selesai
                                </span>

                                <span class="fw-bold text-slate-800"
                                      style="font-size: 1.5rem;">
                                    {{ number_format($completedOrders) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Revenue --}}
        <div class="col-12 col-md-4">

            <div class="tokobii-card h-100 p-4 bg-slate-900 text-white d-flex flex-column justify-content-between"
                 style="
                    background: linear-gradient(
                        135deg,
                        #0f172a 0%,
                        #1e293b 100%
                    );
                 ">

                <div>

                    <span class="text-slate-400 text-uppercase fw-bold"
                          style="
                            font-size: 0.6875rem;
                            letter-spacing: 0.08em;
                          ">
                        Total Pendapatan
                    </span>

                    <h2 class="fw-bold text-white mt-2 mb-0"
                        style="font-size: 1.875rem;">

                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}

                    </h2>

                </div>


                <div class="mt-4 pt-3 border-top border-slate-700">

                    <p class="text-slate-400 mb-0"
                       style="font-size: 0.8125rem;">

                        Akumulasi pembayaran terverifikasi dari pesanan Tokobii yang telah selesai & dibayar.

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Tables Row: Recent Orders & Low Stock Alert --}}
    <div class="row g-4 mb-4">


        {{-- Recent Orders Table --}}
        <div class="col-12 col-lg-7">

            <div class="tokobii-card h-100">

                <div class="tokobii-card-header d-flex justify-content-between align-items-center">

                    <h5 class="fw-bold mb-0 text-slate-900"
                        style="font-size: 1rem;">
                        Transaksi Terbaru
                    </h5>


                    {{-- Lihat Semua --}}
                    <a href="{{ route('admin.orders.index') }}"
                       class="dashboard-small-action">

                        Lihat Semua

                    </a>

                </div>


                <div class="p-0">

                    <div class="table-responsive">

                        <table class="tokobii-table">

                            <thead>

                                <tr>

                                    <th>
                                        Invoice
                                    </th>

                                    <th>
                                        Pelanggan
                                    </th>

                                    <th class="text-end">
                                        Total
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($recentOrders as $order)

                                    <tr>

                                        <td>

                                            <span class="fw-bold text-blue-600"
                                                  style="font-family: monospace;">

                                                {{ $order->invoice_number }}

                                            </span>

                                        </td>


                                        <td>

                                            <span class="fw-semibold text-slate-800">

                                                {{ $order->user->name ?? '-' }}

                                            </span>

                                        </td>


                                        <td class="text-end fw-semibold text-slate-900"
                                            style="font-family: monospace;">

                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}

                                        </td>


                                        <td>

                                            @if($order->status === 'completed')

                                                <span class="tokobii-badge tokobii-badge-neutral">
                                                    Selesai
                                                </span>

                                            @elseif($order->status === 'ready_for_pickup')

                                                <span class="tokobii-badge tokobii-badge-info">
                                                    Siap Diambil
                                                </span>

                                            @elseif($order->status === 'processing')

                                                <span class="tokobii-badge tokobii-badge-info">
                                                    Diproses
                                                </span>

                                            @elseif($order->status === 'paid')

                                                <span class="tokobii-badge tokobii-badge-success">
                                                    Dibayar
                                                </span>

                                            @elseif($order->status === 'waiting_verification')

                                                <span class="tokobii-badge tokobii-badge-warning">
                                                    Verifikasi
                                                </span>

                                            @else

                                                <span class="tokobii-badge tokobii-badge-warning">
                                                    Menunggu Pembayaran
                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-end">

                                            {{-- Detail --}}
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                               class="dashboard-small-action">

                                                Detail

                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center py-4 text-slate-400">

                                            Belum ada transaksi yang tercatat.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Low Stock Products Alert Table --}}
        <div class="col-12 col-lg-5">

            <div class="tokobii-card h-100">

                <div class="tokobii-card-header d-flex justify-content-between align-items-center">

                    <h5 class="fw-bold mb-0 text-slate-900"
                        style="font-size: 1rem;">

                        Peringatan Stok Menipis

                    </h5>


                    {{-- Kelola Stok --}}
                    <a href="{{ route('admin.products.index') }}"
                       class="dashboard-small-action">

                        Kelola Stok

                    </a>

                </div>


                <div class="p-0">

                    <div class="table-responsive">

                        <table class="tokobii-table">

                            <thead>

                                <tr>

                                    <th>
                                        Nama Produk
                                    </th>

                                    <th>
                                        Kategori
                                    </th>

                                    <th class="text-end">
                                        Stok
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($lowStockProducts as $prod)

                                    <tr>

                                        <td class="fw-semibold text-slate-800">

                                            {{ $prod->name }}

                                        </td>


                                        <td class="text-slate-500">

                                            {{ $prod->category->name ?? '-' }}

                                        </td>


                                        <td class="text-end">

                                            @if($prod->stock == 0)

                                                <span class="tokobii-badge tokobii-badge-danger">
                                                    Stok habis (0)
                                                </span>

                                            @else

                                                <span class="tokobii-badge tokobii-badge-warning">
                                                    {{ $prod->stock }} Pcs
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="3"
                                            class="text-center py-4 text-slate-400">

                                            Semua stok produk mencukupi.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection