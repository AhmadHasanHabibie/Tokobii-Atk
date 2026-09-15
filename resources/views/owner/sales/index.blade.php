@extends('layouts.owner.app')

@section('title', 'Laporan Penjualan - Panel Pemilik Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-purple-600">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">
                            Laporan Penjualan
                        </li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge" style="background-color: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff;">
                        Laporan Eksekutif Pemilik
                    </span>
                    <span class="tokobii-badge tokobii-badge-info">
                        {{ $periodTitle }}
                    </span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">
                    Laporan Penjualan Tokobii
                </h1>
                <p class="text-slate-500 mb-0 small">
                    Analisis lengkap performa penjualan per periode harian, mingguan, dan bulanan dengan unduhan berkas PDF resmi.
                </p>
            </div>

            {{-- PDF Export Button --}}
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('owner.sales.pdf', request()->query()) }}" 
                   class="btn d-inline-flex align-items-center gap-2 fw-semibold shadow-sm text-white"
                   style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 10px; padding: 0.55rem 1.15rem; font-size: 0.875rem;"
                   title="Unduh laporan ini sebagai berkas PDF resmi">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Download PDF</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Filter & Period Selector Card --}}
    <div class="tokobii-card p-4 mb-4 shadow-sm">
        <form action="{{ route('owner.sales.index') }}" method="GET" id="salesFilterForm">
            <div class="row g-3 align-items-end">
                
                {{-- Period Selector Tabs --}}
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-bold text-slate-700 small mb-2">Pilih Jenis Periode:</label>
                    <div class="d-flex gap-2">
                        <div class="form-check p-0 flex-grow-1">
                            <input type="radio" class="btn-check" name="period" id="periodDaily" value="daily" 
                                   {{ $period === 'daily' ? 'checked' : '' }} onchange="togglePeriodInputs()">
                            <label class="btn btn-outline-primary w-100 py-2 small fw-semibold rounded-3 d-flex align-items-center justify-content-center gap-1.5" for="periodDaily">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Harian</span>
                            </label>
                        </div>

                        <div class="form-check p-0 flex-grow-1">
                            <input type="radio" class="btn-check" name="period" id="periodWeekly" value="weekly" 
                                   {{ $period === 'weekly' ? 'checked' : '' }} onchange="togglePeriodInputs()">
                            <label class="btn btn-outline-primary w-100 py-2 small fw-semibold rounded-3 d-flex align-items-center justify-content-center gap-1.5" for="periodWeekly">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Mingguan</span>
                            </label>
                        </div>

                        <div class="form-check p-0 flex-grow-1">
                            <input type="radio" class="btn-check" name="period" id="periodMonthly" value="monthly" 
                                   {{ $period === 'monthly' ? 'checked' : '' }} onchange="togglePeriodInputs()">
                            <label class="btn btn-outline-primary w-100 py-2 small fw-semibold rounded-3 d-flex align-items-center justify-content-center gap-1.5" for="periodMonthly">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>Bulanan</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Dynamic Date Input based on period --}}
                <div class="col-12 col-sm-8 col-lg-4">
                    {{-- Input Harian --}}
                    <div id="inputDailyContainer" class="{{ $period === 'daily' ? '' : 'd-none' }}">
                        <label for="dateInput" class="form-label fw-bold text-slate-700 small mb-2">Pilih Tanggal Transaksi:</label>
                        <input type="date" class="form-control tokobii-input w-100" id="dateInput" name="date" value="{{ $dateInput }}">
                    </div>

                    {{-- Input Mingguan --}}
                    <div id="inputWeeklyContainer" class="{{ $period === 'weekly' ? '' : 'd-none' }}">
                        <label for="weekDateInput" class="form-label fw-bold text-slate-700 small mb-2">Pilih Tanggal dalam Pekan:</label>
                        <input type="date" class="form-control tokobii-input w-100" id="weekDateInput" name="week_date" value="{{ $weekDateInput }}">
                    </div>

                    {{-- Input Bulanan --}}
                    <div id="inputMonthlyContainer" class="{{ $period === 'monthly' ? '' : 'd-none' }}">
                        <label for="monthInput" class="form-label fw-bold text-slate-700 small mb-2">Pilih Bulan & Tahun:</label>
                        <input type="month" class="form-control tokobii-input w-100" id="monthInput" name="month" value="{{ $monthInput }}">
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="col-12 col-sm-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-tokobii-primary w-100" style="height: 42px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Tampilkan Laporan</span>
                    </button>
                </div>

            </div>
        </form>

        {{-- Current Filter Active Badge --}}
        <div class="mt-3 pt-3 border-top border-slate-100 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-slate-400 small">Periode Aktif:</span>
                <span class="tokobii-badge" style="background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 0.8125rem;">
                    {{ $periodLabel }}
                </span>
                <span class="text-slate-400 small font-monospace">({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})</span>
            </div>
            <div class="text-slate-500 small">
                Diperbarui pada: <strong>{{ now()->translatedFormat('H:i') }} WIB</strong>
            </div>
        </div>
    </div>

    {{-- Mini KPI Metric Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Omset Penjualan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-3.5 h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Omset
                        </span>
                        <h3 class="fw-bold text-slate-900 mb-0 mt-1 font-monospace" style="font-size: 1.45rem; color: #0f172a;">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h3>
                        <span class="text-slate-400 small mt-1 d-block" style="font-size: 0.75rem;">
                            {{ $totalOrders }} transaksi terverifikasi
                        </span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 46px; height: 46px; background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; flex-shrink: 0;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Transaksi Pesanan --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-3.5 h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Total Pesanan
                        </span>
                        <h3 class="fw-bold text-emerald-600 mb-0 mt-1 font-monospace" style="font-size: 1.45rem;">
                            {{ number_format($totalOrders) }} <span class="fs-6 fw-normal text-slate-500">Pesanan</span>
                        </h3>
                        <span class="text-emerald-600 small mt-1 d-block" style="font-size: 0.75rem;">
                            Selesai & Lunas Terbayar
                        </span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 46px; height: 46px; background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #16a34a; flex-shrink: 0;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Barang Terjual --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-3.5 h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Unit Terjual
                        </span>
                        <h3 class="fw-bold text-purple-600 mb-0 mt-1 font-monospace" style="font-size: 1.45rem; color: #9333ea;">
                            {{ number_format($totalItemsSold) }} <span class="fs-6 fw-normal text-slate-500">Pcs</span>
                        </h3>
                        <span class="text-slate-400 small mt-1 d-block" style="font-size: 0.75rem;">
                            Total produk alat tulis
                        </span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 46px; height: 46px; background: linear-gradient(135deg, #faf5ff, #f3e8ff); color: #9333ea; flex-shrink: 0;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rata-Rata Transaksi (AOV) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-card p-3.5 h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-slate-400 text-uppercase fw-bold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                            Rata-Rata Belanja (AOV)
                        </span>
                        <h3 class="fw-bold text-amber-600 mb-0 mt-1 font-monospace" style="font-size: 1.45rem;">
                            Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                        </h3>
                        <span class="text-slate-400 small mt-1 d-block" style="font-size: 0.75rem;">
                            Per transaksi pelanggan
                        </span>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 46px; height: 46px; background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #d97706; flex-shrink: 0;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Breakdown & Top Products Row --}}
    <div class="row g-4 mb-4">
        
        {{-- Payment Method Breakdown --}}
        <div class="col-12 col-lg-6">
            <div class="tokobii-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-slate-100">
                    <div>
                        <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1rem;">Metode Pembayaran Penjualan</h5>
                        <span class="text-slate-400 small">Komposisi penerimaan kas dan nontunai</span>
                    </div>
                    <span class="tokobii-badge tokobii-badge-info">2 Metode</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    {{-- QRIS --}}
                    <div class="p-3 rounded-3 border border-slate-200 bg-slate-50">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-blue-100 text-blue-700 px-2 py-1 rounded">QRIS</span>
                                <span class="fw-bold text-slate-900 small">Pembayaran QRIS Tokobii</span>
                            </div>
                            <span class="fw-bold text-blue-600 font-monospace">
                                Rp {{ number_format($qrisRevenue, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-slate-400 small">
                            <span>{{ $qrisCount }} Transaksi</span>
                            <span>{{ $totalRevenue > 0 ? round(($qrisRevenue / $totalRevenue) * 100) : 0 }}% dari Total Omset</span>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-blue-600" role="progressbar" 
                                 style="width: {{ $totalRevenue > 0 ? ($qrisRevenue / $totalRevenue) * 100 : 0 }}%;"></div>
                        </div>
                    </div>

                    {{-- Tunai / Cash --}}
                    <div class="p-3 rounded-3 border border-slate-200 bg-slate-50">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-emerald-100 text-emerald-700 px-2 py-1 rounded">Tunai</span>
                                <span class="fw-bold text-slate-900 small">Tunai di Kasir Toko</span>
                            </div>
                            <span class="fw-bold text-emerald-600 font-monospace">
                                Rp {{ number_format($cashRevenue, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between text-slate-400 small">
                            <span>{{ $cashCount }} Transaksi</span>
                            <span>{{ $totalRevenue > 0 ? round(($cashRevenue / $totalRevenue) * 100) : 0 }}% dari Total Omset</span>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-emerald-600" role="progressbar" 
                                 style="width: {{ $totalRevenue > 0 ? ($cashRevenue / $totalRevenue) * 100 : 0 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="col-12 col-lg-6">
            <div class="tokobii-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-slate-100">
                    <div>
                        <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1rem;">Produk Terlaris</h5>
                        <span class="text-slate-400 small">Peringkat 5 produk dengan kuantitas tertinggi</span>
                    </div>
                    <span class="tokobii-badge tokobii-badge-success">Top Penjualan</span>
                </div>

                @if($topProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-slate-400 small border-bottom border-slate-100">
                                    <th style="width: 10%;">Rank</th>
                                    <th>Nama Produk</th>
                                    <th class="text-center" style="width: 20%;">Qty</th>
                                    <th class="text-end" style="width: 30%;">Total Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $top)
                                    <tr class="border-bottom border-slate-50">
                                        <td>
                                            <span class="badge {{ $loop->first ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }} rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-slate-900 small d-block text-truncate" style="max-width: 180px;">{{ $top->product_name }}</span>
                                        </td>
                                        <td class="text-center fw-semibold text-slate-700 small">
                                            {{ number_format($top->total_qty) }} pcs
                                        </td>
                                        <td class="text-end fw-bold text-blue-600 font-monospace small">
                                            Rp {{ number_format($top->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-slate-400 small">
                        Tidak ada produk yang terjual pada periode ini.
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Detailed Transactions Table Card --}}
    <div class="tokobii-table-container">
        <div class="p-3.5 bg-white border-bottom border-slate-100 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1rem;">Rincian Transaksi Penjualan</h5>
                <span class="text-slate-400 small">Daftar invoice pesanan yang telah lunas dan diselesaikan</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="tokobii-badge tokobii-badge-info">{{ $orders->total() }} Transaksi</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="tokobii-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 15%;">No. Invoice</th>
                        <th style="width: 13%;">Waktu</th>
                        <th style="width: 15%;">Pelanggan</th>
                        <th style="width: 25%;">Item / Produk</th>
                        <th style="width: 12%;">Metode</th>
                        <th class="text-end" style="width: 16%;">Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-slate-400 fw-semibold">
                                {{ $orders->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <code class="text-blue-600 fw-bold font-monospace bg-blue-50 px-2 py-1 rounded small">
                                    {{ $order->invoice_number }}
                                </code>
                            </td>
                            <td>
                                <span class="d-block text-slate-800 small fw-semibold">
                                    {{ $order->created_at->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-slate-400 small font-monospace">
                                    {{ $order->created_at->format('H:i') }} WIB
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-slate-900 small d-block">{{ $order->user->name ?? 'Pelanggan Tamu' }}</span>
                                <span class="text-slate-400 small d-block text-truncate" style="max-width: 140px;">{{ $order->user->email ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($order->items as $item)
                                        <div class="small text-slate-700 d-flex align-items-center gap-1">
                                            <span class="bullet bullet-dot bg-slate-400"></span>
                                            <span class="fw-medium text-truncate" style="max-width: 180px;">{{ $item->product_name }}</span>
                                            <span class="text-slate-400 font-monospace">({{ $item->qty }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                @if($order->payment_method === 'qris')
                                    <span class="tokobii-badge tokobii-badge-info">QRIS</span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-neutral">Tunai</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-blue-600 font-monospace">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="tokobii-empty-state">
                                    <div class="tokobii-empty-icon">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h6 class="fw-bold text-slate-900 mb-1">Tidak Ada Transaksi Penjualan</h6>
                                    <p class="text-slate-400 small mb-0">Belum ada transaksi pesanan yang lunas atau selesai pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-3 border-top border-slate-100 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span class="text-slate-400" style="font-size: 0.8125rem;">
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} transaksi
                </span>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    function togglePeriodInputs() {
        const periodDaily = document.getElementById('periodDaily').checked;
        const periodWeekly = document.getElementById('periodWeekly').checked;
        const periodMonthly = document.getElementById('periodMonthly').checked;

        const dailyContainer = document.getElementById('inputDailyContainer');
        const weeklyContainer = document.getElementById('inputWeeklyContainer');
        const monthlyContainer = document.getElementById('inputMonthlyContainer');

        dailyContainer.classList.add('d-none');
        weeklyContainer.classList.add('d-none');
        monthlyContainer.classList.add('d-none');

        if (periodDaily) {
            dailyContainer.classList.remove('d-none');
        } else if (periodWeekly) {
            weeklyContainer.classList.remove('d-none');
        } else if (periodMonthly) {
            monthlyContainer.classList.remove('d-none');
        }
    }
</script>
@endpush
@endsection
