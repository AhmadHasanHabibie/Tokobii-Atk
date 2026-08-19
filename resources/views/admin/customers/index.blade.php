@extends('layouts.admin.app')

@section('title', 'Dasbor Analisis Pelanggan - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">
                            Pelanggan
                        </li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">
                    Analisis & Data Pelanggan
                </h1>
                <p class="text-slate-500 mb-0 small">
                    Akuisisi pelanggan, riwayat transaksi pesanan, dan pemantauan aktivitas akun Tokobii.
                </p>
            </div>
        </div>
    </div>


    {{-- Period Filter Card --}}
    <div class="tokobii-card mb-4">

        <div class="tokobii-card-header">

            <h6 class="fw-bold mb-0 text-slate-900"
                style="font-size: 0.9375rem;">

                Periode Analisis:
                <span class="text-blue-600">
                    {{ $periodInfo['label'] }}
                </span>

            </h6>

        </div>


        <div class="p-3">

            <form action="{{ route('admin.customers.index') }}"
                  method="GET"
                  id="analyticsFilterForm"
                  class="row g-3 align-items-center">

                {{-- Period Selector --}}
                <div class="col-12 col-md-3">

                    <label for="periodSelect"
                           class="form-label">
                        Periode
                    </label>

                    <select name="period"
                            id="periodSelect"
                            class="tokobii-select w-100">

                        <option value="day"
                            {{ $periodInfo['period'] === 'day' ? 'selected' : '' }}>
                            Harian (Hari Ini)
                        </option>

                        <option value="month"
                            {{ $periodInfo['period'] === 'month' ? 'selected' : '' }}>
                            Bulanan
                        </option>

                        <option value="year"
                            {{ $periodInfo['period'] === 'year' ? 'selected' : '' }}>
                            Tahunan
                        </option>

                    </select>

                </div>


                {{-- Day Date Picker --}}
                <div class="col-12 col-md-3 period-input-group"
                     id="dayInputGroup"
                     style="{{ $periodInfo['period'] === 'day' ? '' : 'display: none;' }}">

                    <label for="date"
                           class="form-label">
                        Tanggal
                    </label>

                    <input type="date"
                           name="date"
                           class="tokobii-input w-100"
                           value="{{ $periodInfo['date'] }}">

                </div>


                {{-- Month Selector --}}
                <div class="col-12 col-md-3 period-input-group"
                     id="monthInputGroup"
                     style="{{ $periodInfo['period'] === 'month' ? '' : 'display: none;' }}">

                    <label for="month"
                           class="form-label">
                        Bulan
                    </label>

                    <select name="month"
                            class="tokobii-select w-100">

                        @php
                            $months = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ];
                        @endphp

                        @foreach($months as $m => $mName)

                            <option value="{{ $m }}"
                                {{ $periodInfo['month'] == $m ? 'selected' : '' }}>

                                {{ $mName }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Year Selector --}}
                <div class="col-12 col-md-3 period-input-group"
                     id="yearInputGroup"
                     style="{{ in_array($periodInfo['period'], ['month', 'year']) ? '' : 'display: none;' }}">

                    <label for="year"
                           class="form-label">
                        Tahun
                    </label>

                    <select name="year"
                            class="tokobii-select w-100">

                        @for($y = date('Y'); $y >= date('Y') - 4; $y--)

                            <option value="{{ $y }}"
                                {{ $periodInfo['year'] == $y ? 'selected' : '' }}>

                                {{ $y }}

                            </option>

                        @endfor

                    </select>

                </div>


                {{-- Filter Action Buttons --}}
                <div class="col-12 col-md-3 d-flex align-items-end gap-2 ms-auto pt-2">

                    {{-- Tombol Terapkan Filter --}}
                    <button type="submit"
                            class="btn w-100 d-inline-flex align-items-center justify-content-center gap-2 rounded-3 fw-semibold"
                            id="filterBtn"
                            style="
                                background-color: #2563eb;
                                border: 1px solid #2563eb;
                                color: #ffffff;
                                min-height: 42px;
                                padding: 9px 16px;
                            ">

                        <span class="spinner-border spinner-border-sm me-1 d-none"
                              id="filterSpinner"
                              role="status"
                              aria-hidden="true">
                        </span>

                        <svg width="16"
                             height="16"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 4h18M6 12h12M10 20h4">
                            </path>

                        </svg>

                        <span id="filterBtnText">
                            Terapkan Filter
                        </span>

                    </button>


                    {{-- Reset --}}
                    <a href="{{ route('admin.customers.index') }}"
                       class="btn btn-tokobii-secondary px-3"
                       title="Reset">
                        ↺
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- Analytics Dashboard Metric Cards --}}
    <div class="row g-3 mb-4">

        {{-- Pelanggan Baru --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Pelanggan Baru
                        </span>

                        <h3 class="fw-bold text-slate-900 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($totalNewCustomers) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #eff6ff;
                            color: #2563eb;
                         ">

                        <svg width="20"
                             height="20"
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


        {{-- Pelanggan Aktif --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Pelanggan Aktif
                        </span>

                        <h3 class="fw-bold text-emerald-600 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($activeCustomers) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #f0fdf4;
                            color: #16a34a;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M5 13l4 4L19 7">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Email Terverifikasi --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Email Terverifikasi
                        </span>

                        <h3 class="fw-bold text-blue-600 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($verifiedCustomers) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #f0f9ff;
                            color: #0284c7;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Pesanan --}}
        <div class="col-12 col-sm-6 col-md-3">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Total Pesanan
                        </span>

                        <h3 class="fw-bold text-slate-900 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($totalOrders) }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #fefce8;
                            color: #ca8a04;
                         ">

                        <svg width="20"
                             height="20"
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


        {{-- Total Pendapatan --}}
        <div class="col-12 col-sm-6 col-md-4">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Total Pendapatan
                        </span>

                        <h3 class="fw-bold text-emerald-600 mb-0 mt-1 font-monospace"
                            style="font-size: 1.35rem;">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #f0fdf4;
                            color: #16a34a;
                         ">

                        <svg width="20"
                             height="20"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>

                        </svg>

                    </div>

                </div>

            </div>

        </div>


        {{-- Item Dibeli --}}
        <div class="col-12 col-sm-6 col-md-4">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Item Dibeli
                        </span>

                        <h3 class="fw-bold text-slate-900 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($totalProductsPurchased) }} Pcs
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #f8fafc;
                            color: #64748b;
                         ">

                        <svg width="20"
                             height="20"
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


        {{-- Total Ulasan Rating --}}
        <div class="col-12 col-sm-6 col-md-4">

            <div class="tokobii-card p-3 h-100">

                <div class="d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-slate-400 text-uppercase fw-bold"
                              style="
                                font-size: 0.6875rem;
                                letter-spacing: 0.05em;
                              ">
                            Total Ulasan Rating
                        </span>

                        <h3 class="fw-bold text-amber-500 mb-0 mt-1"
                            style="font-size: 1.5rem;">
                            {{ number_format($totalRatings) }} Ulasan
                        </h3>

                    </div>

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="
                            width: 44px;
                            height: 44px;
                            background-color: #fffbeb;
                            color: #d97706;
                         ">

                        <svg width="20"
                             height="20"
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

                </div>

            </div>

        </div>

    </div>


    {{-- Filter Bar --}}
    <div class="tokobii-card p-3 mb-4">

        <form action="{{ route('admin.customers.index') }}"
              method="GET"
              class="row g-2 align-items-center">

            <input type="hidden"
                   name="period"
                   value="{{ $periodInfo['period'] }}">

            @if($periodInfo['period'] === 'day')

                <input type="hidden"
                       name="date"
                       value="{{ $periodInfo['date'] }}">

            @elseif($periodInfo['period'] === 'month')

                <input type="hidden"
                       name="month"
                       value="{{ $periodInfo['month'] }}">

                <input type="hidden"
                       name="year"
                       value="{{ $periodInfo['year'] }}">

            @elseif($periodInfo['period'] === 'year')

                <input type="hidden"
                       name="year"
                       value="{{ $periodInfo['year'] }}">

            @endif


            {{-- Search --}}
            <div class="col-12 col-md-4">

                <input type="text"
                       name="search"
                       class="tokobii-input w-100"
                       placeholder="Cari nama, email, telepon..."
                       value="{{ request('search') }}">

            </div>


            {{-- Status --}}
            <div class="col-12 col-sm-6 col-md-3">

                <select name="status"
                        class="tokobii-select w-100">

                    <option value="">
                        Status: Semua
                    </option>

                    <option value="active"
                        {{ request('status') === 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="inactive"
                        {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                    <option value="blocked"
                        {{ request('status') === 'blocked' ? 'selected' : '' }}>
                        Diblokir
                    </option>

                </select>

            </div>


            {{-- Email --}}
            <div class="col-12 col-sm-6 col-md-2">

                <select name="verified"
                        class="tokobii-select w-100">

                    <option value="">
                        Email: Semua
                    </option>

                    <option value="verified"
                        {{ request('verified') === 'verified' ? 'selected' : '' }}>
                        Terverifikasi
                    </option>

                    <option value="unverified"
                        {{ request('verified') === 'unverified' ? 'selected' : '' }}>
                        Belum Terverifikasi
                    </option>

                </select>

            </div>


            {{-- Sort --}}
            <div class="col-12 col-sm-6 col-md-2">

                <select name="sort"
                        class="tokobii-select w-100">

                    <option value="latest"
                        {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                        Terbaru
                    </option>

                    <option value="oldest"
                        {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                        Terlama
                    </option>

                    <option value="name_asc"
                        {{ request('sort') === 'name_asc' ? 'selected' : '' }}>
                        Nama (A-Z)
                    </option>

                    <option value="name_desc"
                        {{ request('sort') === 'name_desc' ? 'selected' : '' }}>
                        Nama (Z-A)
                    </option>

                </select>

            </div>


            {{-- Filter Button --}}
            <div class="col-12 col-md-1 d-flex gap-1">

                <button type="submit"
                        class="btn w-100 d-inline-flex align-items-center justify-content-center gap-2 rounded-3 fw-semibold"
                        style="
                            background-color: #2563eb;
                            border: 1px solid #2563eb;
                            color: #ffffff;
                            min-height: 42px;
                            padding: 9px 14px;
                        ">

                    <svg width="15"
                         height="15"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 4h18M6 12h12M10 20h4">
                        </path>

                    </svg>

                    <span>
                        Filter
                    </span>

                </button>


                @if(request()->hasAny([
                    'search',
                    'status',
                    'verified',
                    'sort'
                ]))

                    <a href="{{ route('admin.customers.index') }}"
                       class="btn btn-tokobii-secondary px-3"
                       title="Reset">
                        ↺
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Customer Table --}}
    <div class="tokobii-table-container">

        @forelse($customers as $customer)

            @if($loop->first)

                <div class="table-responsive">

                    <table class="tokobii-table">

                        <thead>

                            <tr>

                                <th style="width: 4%;">
                                    No
                                </th>

                                <th style="width: 8%;">
                                    Foto
                                </th>

                                <th style="width: 20%;">
                                    Nama Pelanggan
                                </th>

                                <th style="width: 22%;">
                                    Email
                                </th>

                                <th style="width: 14%;">
                                    Tingkat Tier
                                </th>

                                <th style="width: 10%;">
                                    Status
                                </th>

                                <th style="width: 10%;">
                                    Verifikasi Email
                                </th>

                                <th style="width: 12%;">
                                    Bergabung
                                </th>

                                <th class="text-end"
                                    style="width: 6%;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

            @endif


            <tr>

                {{-- No --}}
                <td class="fw-semibold text-slate-400">
                    {{ $customers->firstItem() + $loop->index }}
                </td>


                {{-- Foto --}}
                <td>

                    @if(isset($customer->photo) && $customer->photo)

                        <img src="{{ asset('storage/' . $customer->photo) }}"
                             alt="{{ $customer->name }}"
                             class="rounded-circle border border-slate-200"
                             style="
                                width: 42px;
                                height: 42px;
                                object-fit: cover;
                             ">

                    @elseif(isset($customer->avatar) && $customer->avatar)

                        <img src="{{ asset('storage/' . $customer->avatar) }}"
                             alt="{{ $customer->name }}"
                             class="rounded-circle border border-slate-200"
                             style="
                                width: 42px;
                                height: 42px;
                                object-fit: cover;
                             ">

                    @else

                        <div class="rounded-circle bg-blue-100 text-blue-600 fw-bold d-flex align-items-center justify-content-center"
                             style="
                                width: 42px;
                                height: 42px;
                                font-size: 0.875rem;
                                background-color: #eff6ff;
                                color: #2563eb;
                             ">

                            {{ strtoupper(substr($customer->name, 0, 2)) }}

                        </div>

                    @endif

                </td>


                {{-- Nama --}}
                <td>

                    <span class="fw-semibold text-slate-900 d-inline-block text-truncate"
                          style="max-width: 200px;"
                          title="{{ $customer->name }}">

                        {{ $customer->name }}

                    </span>

                </td>


                {{-- Email --}}
                <td>

                    <span class="text-slate-600 font-monospace"
                          style="font-size: 0.8125rem;">

                        {{ $customer->email }}

                    </span>

                </td>


                {{-- Tier --}}
                <td>

                    @if(($customer->orders_count ?? 0) >= 10)

                        <span class="tokobii-badge tokobii-badge-warning">
                            Gold Tier
                        </span>

                    @elseif(($customer->orders_count ?? 0) >= 4)

                        <span class="tokobii-badge tokobii-badge-neutral">
                            Silver Tier
                        </span>

                    @elseif(($customer->orders_count ?? 0) >= 1)

                        <span class="tokobii-badge tokobii-badge-info">
                            Bronze ({{ $customer->orders_count }} Pesanan)
                        </span>

                    @else

                        <span class="tokobii-badge tokobii-badge-neutral">
                            Baru
                        </span>

                    @endif

                </td>


                {{-- Status --}}
                <td>

                    @if($customer->status === 'active')

                        <span class="tokobii-badge tokobii-badge-success">
                            Aktif
                        </span>

                    @elseif($customer->status === 'blocked')

                        <span class="tokobii-badge tokobii-badge-danger">
                            Diblokir
                        </span>

                    @else

                        <span class="tokobii-badge tokobii-badge-neutral">
                            Tidak Aktif
                        </span>

                    @endif

                </td>


                {{-- Verifikasi Email --}}
                <td>

                    @if($customer->email_verified_at)

                        <span class="tokobii-badge tokobii-badge-success">
                            Terverifikasi
                        </span>

                    @else

                        <span class="tokobii-badge tokobii-badge-warning">
                            Belum Terverifikasi
                        </span>

                    @endif

                </td>


                {{-- Bergabung --}}
                <td class="text-slate-500"
                    style="font-size: 0.8125rem;">

                    {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}

                </td>


                {{-- Aksi --}}
                <td class="text-end">

                    {{-- Tombol Detail Biru --}}
                    <a href="{{ route('admin.customers.show', ['customer' => $customer] + request()->only(['period', 'date', 'month', 'year'])) }}"
                       class="btn btn-sm d-inline-flex align-items-center justify-content-center gap-2 rounded-3 fw-semibold"
                       style="
                            background-color: #2563eb;
                            border: 1px solid #2563eb;
                            color: #ffffff;
                            min-height: 36px;
                            padding: 7px 14px;
                       ">

                        <svg width="14"
                             height="14"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z">
                            </path>

                        </svg>

                        <span>
                            Detail
                        </span>

                    </a>

                </td>

            </tr>


            @if($loop->last)

                        </tbody>

                    </table>

                </div>

            @endif

        @empty

            <div class="text-center py-5 px-4">

                <p class="text-slate-400 mb-3">
                    Tidak ada aktivitas pelanggan yang tercatat untuk periode ini.
                </p>

                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-tokobii-secondary">
                    Reset Filter
                </a>

            </div>

        @endforelse


        {{-- Pagination --}}
        @if($customers->hasPages())

            <div class="p-3 border-top border-slate-100 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

                <span class="text-slate-400"
                      style="font-size: 0.8125rem;">

                    Menampilkan
                    {{ $customers->firstItem() }}
                    -
                    {{ $customers->lastItem() }}
                    dari
                    {{ $customers->total() }}
                    pelanggan

                </span>

                <div>
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>

            </div>

        @endif

    </div>

</div>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const periodSelect = document.getElementById('periodSelect');
        const dayInputGroup = document.getElementById('dayInputGroup');
        const monthInputGroup = document.getElementById('monthInputGroup');
        const yearInputGroup = document.getElementById('yearInputGroup');

        const analyticsFilterForm =
            document.getElementById('analyticsFilterForm');

        const filterBtn =
            document.getElementById('filterBtn');

        const filterSpinner =
            document.getElementById('filterSpinner');

        const filterBtnText =
            document.getElementById('filterBtnText');


        if (periodSelect) {

            periodSelect.addEventListener('change', function () {

                const val = this.value;

                if (dayInputGroup) {
                    dayInputGroup.style.display =
                        (val === 'day') ? 'block' : 'none';
                }

                if (monthInputGroup) {
                    monthInputGroup.style.display =
                        (val === 'month') ? 'block' : 'none';
                }

                if (yearInputGroup) {
                    yearInputGroup.style.display =
                        (val === 'month' || val === 'year')
                            ? 'block'
                            : 'none';
                }

            });

        }


        if (analyticsFilterForm && filterBtn) {

            analyticsFilterForm.addEventListener('submit', function () {

                filterBtn.disabled = true;

                if (filterSpinner) {
                    filterSpinner.classList.remove('d-none');
                }

                if (filterBtnText) {
                    filterBtnText.textContent = 'Memuat...';
                }

            });

        }

    });
</script>
@endpush