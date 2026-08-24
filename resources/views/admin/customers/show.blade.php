@extends('layouts.admin.app')

@section('title', 'Detail Analisis Pelanggan - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.customers.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Pelanggan</a>
                        </li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Detail Analisis</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">{{ $customer->name }}</h1>
                <p class="text-slate-500 mb-0 small">Profil pelanggan, status loyalitas, dan analisis kinerja pembelian di Tokobii.</p>
            </div>
            <div>
                <a href="{{ route('admin.customers.index', request()->only(['period', 'date', 'month', 'year'])) }}" class="btn btn-tokobii-secondary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Period Filter Card --}}
    <div class="tokobii-card mb-4">
        <div class="tokobii-card-header">
            <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.9375rem;">Periode Analisis: <span class="text-blue-600">{{ $periodInfo['label'] }}</span></h6>
        </div>
        <div class="p-3">
            <form action="{{ route('admin.customers.show', $customer) }}" method="GET" id="analyticsFilterForm" class="row g-3 align-items-center">
                
                {{-- Period Selector --}}
                <div class="col-12 col-md-3">
                    <label for="periodSelect" class="form-label">Periode</label>
                    <select name="period" id="periodSelect" class="tokobii-select w-100">
                        <option value="day" {{ $periodInfo['period'] === 'day' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                        <option value="month" {{ $periodInfo['period'] === 'month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="year" {{ $periodInfo['period'] === 'year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                {{-- Day Date Picker --}}
                <div class="col-12 col-md-3 period-input-group" id="dayInputGroup" style="{{ $periodInfo['period'] === 'day' ? '' : 'display: none;' }}">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" name="date" class="tokobii-input w-100" value="{{ $periodInfo['date'] }}">
                </div>

                {{-- Month Selector --}}
                <div class="col-12 col-md-3 period-input-group" id="monthInputGroup" style="{{ $periodInfo['period'] === 'month' ? '' : 'display: none;' }}">
                    <label for="month" class="form-label">Bulan</label>
                    <select name="month" class="tokobii-select w-100">
                        @php
                            $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                        @endphp
                        @foreach($months as $m => $mName)
                            <option value="{{ $m }}" {{ $periodInfo['month'] == $m ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Year Selector --}}
                <div class="col-12 col-md-3 period-input-group" id="yearInputGroup" style="{{ in_array($periodInfo['period'], ['month', 'year']) ? '' : 'display: none;' }}">
                    <label for="year" class="form-label">Tahun</label>
                    <select name="year" class="tokobii-select w-100">
                        @for($y = date('Y'); $y >= date('Y') - 4; $y--)
                            <option value="{{ $y }}" {{ $periodInfo['year'] == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Filter Action Buttons --}}
                <div class="col-12 col-md-3 d-flex align-items-end gap-2 ms-auto pt-2">
                    <button type="submit" class="btn btn-tokobii-primary w-100" id="filterBtn">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="filterSpinner" role="status" aria-hidden="true"></span>
                        <span id="filterBtnText">Terapkan Filter</span>
                    </button>
                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-tokobii-secondary px-2.5" title="Reset">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column: Profile Card & Activity Summary --}}
        <div class="col-12 col-md-4">
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header text-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Profil Pelanggan</h5>
                </div>
                <div class="p-4 text-center">

                    {{-- Avatar --}}
                    <div class="mb-3 d-inline-block">
                        @if(isset($customer->photo) && $customer->photo)
                            <img src="{{ asset('storage/' . $customer->photo) }}" 
                                 alt="{{ $customer->name }}" 
                                 class="rounded-circle border border-slate-200" 
                                 style="width: 84px; height: 84px; object-fit: cover;">
                        @elseif(isset($customer->avatar) && $customer->avatar)
                            <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                 alt="{{ $customer->name }}" 
                                 class="rounded-circle border border-slate-200" 
                                 style="width: 84px; height: 84px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-blue-100 text-blue-600 fw-bold d-inline-flex align-items-center justify-content-center" 
                                 style="width: 84px; height: 84px; font-size: 1.75rem; background-color: #eff6ff; color: #2563eb;">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <h5 class="fw-bold text-slate-900 mb-1">{{ $customer->name }}</h5>
                    <p class="text-slate-500 font-monospace mb-3" style="font-size: 0.8125rem;">{{ $customer->email }}</p>

                    <div class="d-flex flex-column gap-2 border-top border-slate-100 pt-3 text-start">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Tier Pelanggan</span>
                            <span class="tokobii-badge tokobii-badge-neutral">
                                {{ $customerLevel }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Peran Akun</span>
                            <span class="tokobii-badge tokobii-badge-info">
                                {{ ucfirst($customer->role) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Status Akun</span>
                            <div>
                                @if($customer->status === 'active')
                                    <span class="tokobii-badge tokobii-badge-success">Aktif</span>
                                @elseif($customer->status === 'blocked')
                                    <span class="tokobii-badge tokobii-badge-danger">Diblokir</span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-neutral">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Verifikasi Email</span>
                            <div>
                                @if($customer->email_verified_at)
                                    <span class="tokobii-badge tokobii-badge-success">
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-warning">
                                        Belum Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Tanggal Bergabung</span>
                            <span class="text-slate-700 font-monospace" style="font-size: 0.8125rem;">
                                {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Activity Summary Metrics Card --}}
            <div class="tokobii-card">
                <div class="tokobii-card-header">
                    <h6 class="fw-bold mb-0 text-slate-900" style="font-size: 0.9375rem;">Ringkasan Aktivitas</h6>
                </div>
                <div class="p-3">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-slate-100">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Total Pesanan Selesai</span>
                            <span class="fw-bold text-slate-900">{{ number_format($totalOrders) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-slate-100">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Rating & Ulasan Ditulis</span>
                            <span class="fw-bold text-slate-900">{{ number_format($ratingCount) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span class="text-slate-500" style="font-size: 0.8125rem;">Laporan/Masalah Dikirim</span>
                            <span class="fw-bold text-slate-900">{{ number_format($reportCount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Information & Purchase Summaries --}}
        <div class="col-12 col-md-8">

            {{-- Customer Information Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Ikhtisar Pelanggan</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 30%;">Nama Lengkap</th>
                                    <td class="text-slate-900 fw-bold">: {{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Alamat Email</th>
                                    <td class="text-slate-800 font-monospace">: {{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Status Verifikasi</th>
                                    <td>: 
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
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Terakhir Diperbarui</th>
                                    <td class="text-slate-500">: {{ $customer->updated_at ? $customer->updated_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Purchase Summary Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Ringkasan Pembelian</h5>
                    <span class="tokobii-badge tokobii-badge-info">{{ $periodInfo['label'] }}</span>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-slate-50 rounded-3 text-center border border-slate-200">
                                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Total Pesanan</span>
                                <h4 class="fw-bold text-slate-900 mb-0 mt-1" style="font-size: 1.25rem;">{{ number_format($totalOrders) }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-slate-50 rounded-3 text-center border border-slate-200">
                                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Total Pengeluaran</span>
                                <h4 class="fw-bold text-blue-600 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-slate-50 rounded-3 text-center border border-slate-200">
                                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Rata-rata Pesanan</span>
                                <h4 class="fw-bold text-slate-800 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">Rp {{ number_format($averageOrder, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="p-3 bg-slate-50 rounded-3 text-center border border-slate-200">
                                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Pesanan Tunggal Terbesar</span>
                                <h4 class="fw-bold text-slate-800 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">Rp {{ number_format($largestOrder, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-slate-50 rounded-3 text-center border border-slate-200">
                                <span class="text-slate-400 text-uppercase fw-bold d-block" style="font-size: 0.6875rem;">Tanggal Pesanan Terakhir</span>
                                <h5 class="fw-bold text-slate-700 mb-0 mt-2 font-monospace" style="font-size: 0.9375rem;">
                                    {{ $lastOrderDate ? \Carbon\Carbon::parse($lastOrderDate)->format('d M Y, H:i') : '-' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Preference Summary Card --}}
            <div class="tokobii-card mb-4">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Preferensi Produk</h5>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Item Paling Sering Dibeli</th>
                                    <td class="text-slate-900 fw-semibold">: {{ $mostPurchasedProduct ?? 'Tidak ada data transaksi' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Kategori Favorit</th>
                                    <td class="text-slate-900 fw-semibold">: {{ $favoriteCategory ?? 'Tidak ada data transaksi' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Recent Orders Table --}}
            <div class="tokobii-card">
                <div class="tokobii-card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Pesanan Terbaru</h5>
                    <span class="tokobii-badge tokobii-badge-neutral">{{ $periodInfo['label'] }}</span>
                </div>
                <div class="p-0">
                    @if(isset($recentOrders) && $recentOrders->isNotEmpty())
                        <div class="table-responsive">
                            <table class="tokobii-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Faktur</th>
                                        <th class="text-end">Total Harga</th>
                                        <th>Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $ord)
                                        <tr>
                                            <td>
                                                <code class="text-blue-600 font-monospace fw-bold" style="font-size: 0.8125rem;">{{ $ord->invoice_number }}</code>
                                                <span class="text-slate-400 d-block" style="font-size: 0.75rem;">{{ $ord->created_at ? $ord->created_at->format('d M Y, H:i') : '-' }}</span>
                                            </td>
                                            <td class="text-end font-monospace fw-bold text-slate-900">
                                                Rp {{ number_format($ord->grand_total, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="tokobii-badge {{ $ord->status_badge_class }}">
                                                    {{ $ord->status_label }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-sm btn-tokobii-secondary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-slate-400">
                            <span>Tidak ada transaksi pesanan yang tercatat untuk {{ $periodInfo['label'] }}.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

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
        const analyticsFilterForm = document.getElementById('analyticsFilterForm');
        const filterBtn = document.getElementById('filterBtn');
        const filterSpinner = document.getElementById('filterSpinner');
        const filterBtnText = document.getElementById('filterBtnText');

        if (periodSelect) {
            periodSelect.addEventListener('change', function () {
                const val = this.value;
                if (dayInputGroup) dayInputGroup.style.display = (val === 'day') ? 'block' : 'none';
                if (monthInputGroup) monthInputGroup.style.display = (val === 'month') ? 'block' : 'none';
                if (yearInputGroup) yearInputGroup.style.display = (val === 'month' || val === 'year') ? 'block' : 'none';
            });
        }

        if (analyticsFilterForm && filterBtn) {
            analyticsFilterForm.addEventListener('submit', function () {
                filterBtn.disabled = true;
                if (filterSpinner) filterSpinner.classList.remove('d-none');
                if (filterBtnText) filterBtnText.textContent = 'Memuat...';
            });
        }
    });
</script>
@endpush
