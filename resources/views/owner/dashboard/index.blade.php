@extends('layouts.owner.app')

@section('title', 'Dashboard Penjualan Owner - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="tokobii-badge" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: #ffffff;">
                        Panel Khusus Owner
                    </span>
                    <span class="tokobii-badge tokobii-badge-info">
                        {{ $periodTitle }}
                    </span>
                    <span class="text-slate-400 small d-none d-sm-inline">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Dashboard & Laporan Penjualan</h1>
                <p class="text-slate-500 mb-0 small">
                    Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Pantau penjualan <strong>harian, mingguan, bulanan</strong> dan unduh berkas laporan PDF resmi.
                </p>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Download PDF Button for active period --}}
                <a href="{{ route('owner.sales.pdf', array_merge(['period' => $period], request()->query())) }}" 
                   class="btn d-inline-flex align-items-center gap-2 fw-semibold shadow-sm text-white"
                   style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 10px; padding: 0.55rem 1.15rem; font-size: 0.875rem;"
                   title="Unduh laporan periode ini sebagai berkas PDF resmi">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Download PDF Laporan</span>
                </a>

                <a href="{{ route('owner.sales.index', request()->query()) }}" 
                   class="btn d-inline-flex align-items-center gap-1.5 fw-semibold shadow-sm text-purple-700 bg-purple-50 border border-purple-200 hover-bg-purple-100"
                   style="border-radius: 10px; padding: 0.55rem 1.05rem; font-size: 0.875rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Halaman Laporan Penuh</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Filter Periode Penjualan: Harian, Mingguan, Bulanan --}}
    <div class="tokobii-card p-3 p-md-4 mb-4 shadow-sm">
        <form action="{{ route('owner.dashboard') }}" method="GET" id="dashboardFilterForm">
            <div class="row g-3 align-items-end">
                
                {{-- Periode Switcher Tabs --}}
                <div class="col-12 col-lg-5">
                    <label class="form-label fw-bold text-slate-800 small mb-2 d-flex align-items-center gap-1.5">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-purple-600">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Pilih Periode Penjualan:</span>
                    </label>
                    <div class="btn-group w-100 shadow-sm" role="group">
                        <input type="radio" class="btn-check" name="period" id="dashPeriodDaily" value="daily" 
                               {{ $period === 'daily' ? 'checked' : '' }} onchange="toggleDashPeriodInputs()">
                        <label class="btn btn-outline-primary py-2 small fw-semibold" for="dashPeriodDaily">
                            📅 Harian
                        </label>

                        <input type="radio" class="btn-check" name="period" id="dashPeriodWeekly" value="weekly" 
                               {{ $period === 'weekly' ? 'checked' : '' }} onchange="toggleDashPeriodInputs()">
                        <label class="btn btn-outline-primary py-2 small fw-semibold" for="dashPeriodWeekly">
                            📊 Mingguan
                        </label>

                        <input type="radio" class="btn-check" name="period" id="dashPeriodMonthly" value="monthly" 
                               {{ $period === 'monthly' ? 'checked' : '' }} onchange="toggleDashPeriodInputs()">
                        <label class="btn btn-outline-primary py-2 small fw-semibold" for="dashPeriodMonthly">
                            🗓️ Bulanan
                        </label>
                    </div>
                </div>

                {{-- Dynamic Date Input based on period --}}
                <div class="col-12 col-sm-8 col-lg-4">
                    {{-- Daily Picker --}}
                    <div id="dashDailyInputGroup" style="{{ $period === 'daily' ? 'display: block;' : 'display: none;' }}">
                        <label for="dashDateInput" class="form-label fw-bold text-slate-800 small mb-2">Pilih Tanggal:</label>
                        <input type="date" class="form-control form-control-sm rounded-3" id="dashDateInput" name="date" 
                               value="{{ $dateInput }}" max="{{ now()->toDateString() }}" onchange="submitDashFilter()">
                    </div>

                    {{-- Weekly Picker --}}
                    <div id="dashWeeklyInputGroup" style="{{ $period === 'weekly' ? 'display: block;' : 'display: none;' }}">
                        <label for="dashWeekInput" class="form-label fw-bold text-slate-800 small mb-2">Pilih Tanggal dalam Minggu:</label>
                        <input type="date" class="form-control form-control-sm rounded-3" id="dashWeekInput" name="week_date" 
                               value="{{ $weekDateInput }}" max="{{ now()->toDateString() }}" onchange="submitDashFilter()">
                    </div>

                    {{-- Monthly Picker --}}
                    <div id="dashMonthlyInputGroup" style="{{ $period === 'monthly' ? 'display: block;' : 'display: none;' }}">
                        <label for="dashMonthInput" class="form-label fw-bold text-slate-800 small mb-2">Pilih Bulan & Tahun:</label>
                        <input type="month" class="form-control form-control-sm rounded-3" id="dashMonthInput" name="month" 
                               value="{{ $monthInput }}" max="{{ now()->format('Y-m') }}" onchange="submitDashFilter()">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="col-12 col-sm-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm flex-grow-1 shadow-sm">
                        <span>Terapkan</span>
                    </button>
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary btn-tokobii-sm" title="Reset ke Hari Ini">
                        <span>Reset</span>
                    </a>
                </div>

            </div>
        </form>

        {{-- Current Filter Active Badge (Exact Image 2 Design) --}}
        <div class="mt-3 pt-3 border-top border-slate-100 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-slate-400 small">Periode Aktif:</span>
                <span class="tokobii-badge" style="background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 0.8125rem; font-weight: 600; border-radius: 9999px; padding: 0.35rem 0.85rem;">
                    {{ $periodLabel }}
                </span>
                <span class="text-slate-400 small font-monospace">({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})</span>
            </div>
            <div class="text-slate-500 small">
                Diperbarui pada: <strong>{{ now()->translatedFormat('H:i') }} WIB</strong>
            </div>
        </div>
    </div>

    {{-- Period KPI Stat Cards Grid --}}
    <div class="row g-3 g-md-4 mb-4">

        {{-- Omset Penjualan Periode --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card border-purple-200">
                <div class="tokobii-stat-icon" style="background-color: #f3e8ff; color: #7c3aed;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value text-purple-700 font-monospace" style="font-size: 1.35rem;">
                    Rp {{ number_format($periodRevenue, 0, ',', '.') }}
                </div>
                <div class="tokobii-stat-label fw-bold text-slate-700">Omset {{ ucfirst($period) }}</div>
                <div class="text-slate-400" style="font-size: 0.725rem;">
                    Total Seluruh Waktu: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Total Pesanan Periode --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card border-emerald-200">
                <div class="tokobii-stat-icon" style="background-color: #ecfdf5; color: #059669;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value text-emerald-700 font-monospace" style="font-size: 1.35rem;">
                    {{ number_format($periodOrdersCount) }} Transaksi
                </div>
                <div class="tokobii-stat-label fw-bold text-slate-700">Pesanan Selesai / Dibayar</div>
                <div class="text-slate-400" style="font-size: 0.725rem;">
                    Total Seluruh Order: {{ number_format($totalOrders) }} ({{ $completedOrders }} Selesai)
                </div>
            </div>
        </div>

        {{-- Unit Produk Terjual --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card border-blue-200">
                <div class="tokobii-stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value text-blue-700 font-monospace" style="font-size: 1.35rem;">
                    {{ number_format($periodItemsSold) }} Unit
                </div>
                <div class="tokobii-stat-label fw-bold text-slate-700">Total Item Terjual</div>
                <div class="text-slate-400" style="font-size: 0.725rem;">
                    Katalog: {{ number_format($totalProducts) }} Produk ({{ $totalCategories }} Kategori)
                </div>
            </div>
        </div>

        {{-- Rata-rata Nilai Order (AOV) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card border-amber-200">
                <div class="tokobii-stat-icon" style="background-color: #fffbeb; color: #d97706;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value text-amber-700 font-monospace" style="font-size: 1.35rem;">
                    Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                </div>
                <div class="tokobii-stat-label fw-bold text-slate-700">Rata-rata Transaksi (AOV)</div>
                <div class="text-slate-400" style="font-size: 0.725rem;">
                    Pelanggan Terdaftar: {{ number_format($totalCustomers) }} User
                </div>
            </div>
        </div>

    </div>

    {{-- Detail Breakdown Row: Payment Method Breakdown & Top Selling Products --}}
    <div class="row g-4 mb-4">
        
        {{-- Payment Methods in Period --}}
        <div class="col-12 col-md-6">
            <div class="tokobii-card h-100 p-4">
                <h5 class="fw-bold text-slate-900 mb-3 d-flex align-items-center gap-2" style="font-size: 1rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-purple-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span>Metode Pembayaran ({{ ucfirst($period) }})</span>
                </h5>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3" style="background-color: #f0fdf4; border: 1px solid #bbf7d0;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-success-subtle text-success fw-bold">QRIS</span>
                                <span class="text-slate-500 small">{{ $periodQrisCount }} Order</span>
                            </div>
                            <div class="font-monospace fw-bold text-emerald-800" style="font-size: 1.125rem;">
                                Rp {{ number_format($periodQrisRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary-subtle text-primary fw-bold">Tunai Kasir</span>
                                <span class="text-slate-500 small">{{ $periodCashCount }} Order</span>
                            </div>
                            <div class="font-monospace fw-bold text-blue-800" style="font-size: 1.125rem;">
                                Rp {{ number_format($periodCashRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Selling Products in Period --}}
        <div class="col-12 col-md-6">
            <div class="tokobii-card h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-slate-900 mb-0 d-flex align-items-center gap-2" style="font-size: 1rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-amber-500">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <span>Produk Terlaris ({{ ucfirst($period) }})</span>
                    </h5>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($periodTopProducts as $top)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-slate-50 border border-slate-200">
                            <span class="fw-semibold text-slate-800 text-truncate" style="max-width: 220px; font-size: 0.85rem;">
                                {{ $top->product_name }}
                            </span>
                            <div class="text-end">
                                <span class="tokobii-badge" style="background-color: #f3e8ff; color: #7c3aed; border: 1px solid #e9d5ff; font-size: 0.75rem; font-weight: 600;">{{ $top->total_qty }} Terjual</span>
                                <span class="text-slate-500 font-monospace small ms-2">Rp {{ number_format($top->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-slate-400 small">
                            Belum ada penjualan produk pada periode terpilih.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Period Transaction List & Full Report Link --}}
    <div class="tokobii-card mb-4">
        <div class="tokobii-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Daftar Transaksi Penjualan</h5>
                <span class="text-slate-400 small">{{ $periodLabel }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('owner.sales.pdf', array_merge(['period' => $period], request()->query())) }}" 
                   class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 fw-semibold">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Download PDF</span>
                </a>
                <a href="{{ route('owner.sales.index', request()->query()) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                    <span>Lihat Semua Detail &raquo;</span>
                </a>
            </div>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="tokobii-table mb-0">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Waktu Transaksi</th>
                            <th>Metode Pembayaran</th>
                            <th class="text-end">Total Omset</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodOrdersList as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold text-purple-700 font-monospace">{{ $order->invoice_number }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-slate-800 d-block">{{ $order->user->name ?? 'Pelanggan Umum' }}</span>
                                    <span class="text-slate-400" style="font-size: 0.725rem;">{{ $order->user->email ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="text-slate-700 small">{{ $order->created_at->format('d M Y') }}</span>
                                    <span class="text-slate-400 d-block" style="font-size: 0.725rem;">{{ $order->created_at->format('H:i') }} WIB</span>
                                </td>
                                <td>
                                    @if($order->payment_method === 'qris')
                                        <span class="badge bg-success-subtle text-success fw-bold">QRIS</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary fw-bold">Tunai Kasir</span>
                                    @endif
                                </td>
                                <td class="text-end font-monospace fw-bold text-slate-900">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="tokobii-badge {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-slate-400 small">
                                    Tidak ada transaksi tercatat pada periode ini ({{ $periodLabel }}).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($periodOrdersList) > 0)
            <div class="tokobii-card-footer text-center py-2.5 bg-slate-50 border-top">
                <a href="{{ route('owner.sales.index', request()->query()) }}" class="text-decoration-none fw-semibold small text-purple-700 hover-text-purple-900">
                    Buka Halaman Laporan Penjualan Lengkap untuk Riwayat Lengkap &raquo;
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function toggleDashPeriodInputs() {
        const selected = document.querySelector('input[name="period"]:checked').value;
        const dailyGroup = document.getElementById('dashDailyInputGroup');
        const weeklyGroup = document.getElementById('dashWeeklyInputGroup');
        const monthlyGroup = document.getElementById('dashMonthlyInputGroup');

        dailyGroup.style.display = (selected === 'daily') ? 'block' : 'none';
        weeklyGroup.style.display = (selected === 'weekly') ? 'block' : 'none';
        monthlyGroup.style.display = (selected === 'monthly') ? 'block' : 'none';

        document.getElementById('dashboardFilterForm').submit();
    }

    function submitDashFilter() {
        document.getElementById('dashboardFilterForm').submit();
    }
</script>
@endpush