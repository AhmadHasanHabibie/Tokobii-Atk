@extends('layouts.admin.app')

@section('title', 'Detail Customer Analytics - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.customers.index') }}" class="text-decoration-none text-secondary">Customer Analytics</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Detail Analytics</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Detail Customer Analytics</h2>
            <p class="text-muted mb-0">Informasi profil dan analitik transaksi <strong class="text-dark">{{ $customer->name }}</strong>.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.customers.index', request()->only(['period', 'date', 'month', 'year'])) }}" class="btn btn-secondary px-4 fw-semibold">
                Kembali
            </a>
        </div>
    </div>

    {{-- Analytics Period Filter Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h6 class="fw-bold mb-0 text-dark">📊 Periode Analisis: <span class="text-primary">{{ $periodInfo['label'] }}</span></h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('admin.customers.show', $customer) }}" method="GET" id="analyticsFilterForm" class="row g-3 align-items-center">
                
                {{-- Period Selector --}}
                <div class="col-12 col-md-3">
                    <label for="periodSelect" class="form-label small fw-semibold text-secondary mb-1">Pilih Periode</label>
                    <select name="period" id="periodSelect" class="form-select" aria-label="Pilih Periode Analisis">
                        <option value="day" {{ $periodInfo['period'] === 'day' ? 'selected' : '' }}>Hari Ini (Per Hari)</option>
                        <option value="month" {{ $periodInfo['period'] === 'month' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="year" {{ $periodInfo['period'] === 'year' ? 'selected' : '' }}>Per Tahun</option>
                    </select>
                </div>

                {{-- Day Date Picker --}}
                <div class="col-12 col-md-3 period-input-group" id="dayInputGroup" style="{{ $periodInfo['period'] === 'day' ? '' : 'display: none;' }}">
                    <label for="date" class="form-label small fw-semibold text-secondary mb-1">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ $periodInfo['date'] }}" aria-label="Tanggal Filter">
                </div>

                {{-- Month Selector --}}
                <div class="col-12 col-md-3 period-input-group" id="monthInputGroup" style="{{ $periodInfo['period'] === 'month' ? '' : 'display: none;' }}">
                    <label for="month" class="form-label small fw-semibold text-secondary mb-1">Bulan</label>
                    <select name="month" class="form-select" aria-label="Bulan Filter">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $periodInfo['month'] == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Year Selector --}}
                <div class="col-12 col-md-3 period-input-group" id="yearInputGroup" style="{{ in_array($periodInfo['period'], ['month', 'year']) ? '' : 'display: none;' }}">
                    <label for="year" class="form-label small fw-semibold text-secondary mb-1">Tahun</label>
                    <select name="year" class="form-select" aria-label="Tahun Filter">
                        @for($y = date('Y'); $y >= date('Y') - 4; $y--)
                            <option value="{{ $y }}" {{ $periodInfo['year'] == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Filter Action Buttons --}}
                <div class="col-12 col-md-3 d-flex align-items-end gap-2 ms-auto">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold" id="filterBtn">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="filterSpinner" role="status" aria-hidden="true"></span>
                        <span id="filterBtnText">Terapkan</span>
                    </button>
                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-secondary px-3" title="Reset Filter" aria-label="Reset Filter">
                        ↺
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="row g-4">

        {{-- KIRI: Profile Card & Activity Summary --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 text-center">
                    <h5 class="fw-bold mb-0 text-dark">Profil Customer</h5>
                </div>
                <div class="card-body p-4 text-center">

                    {{-- Avatar Photo --}}
                    <div class="mb-3 d-inline-block">
                        @if(isset($customer->photo) && $customer->photo)
                            <img src="{{ asset('storage/' . $customer->photo) }}" 
                                 alt="Photo {{ $customer->name }}" 
                                 class="rounded-circle shadow-sm border" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif(isset($customer->avatar) && $customer->avatar)
                            <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                 alt="Avatar {{ $customer->name }}" 
                                 class="rounded-circle shadow-sm border" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-inline-flex align-items-center justify-content-center border border-primary-subtle" 
                                 style="width: 100px; height: 100px; font-size: 2.2rem;">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-3">{{ $customer->email }}</p>

                    <div class="d-flex flex-column gap-2 border-top pt-3 text-start">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small fw-semibold">Customer Level</span>
                            <span class="badge bg-light text-secondary border border-secondary-subtle px-2.5 py-1 fw-normal">
                                🌱 {{ $customerLevel }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small fw-semibold">Role Akun</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-normal">
                                {{ ucfirst($customer->role) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small fw-semibold">Status Akun</span>
                            <div>
                                @if($customer->status === 'active')
                                    <span class="badge bg-success px-2.5 py-1 fw-normal">🟢 Active</span>
                                @elseif($customer->status === 'blocked')
                                    <span class="badge bg-danger px-2.5 py-1 fw-normal">🔴 Banned</span>
                                @else
                                    <span class="badge bg-secondary px-2.5 py-1 fw-normal">⚫ Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small fw-semibold">Email Verification</span>
                            <div>
                                @if($customer->email_verified_at)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-normal" title="Terverifikasi pada {{ $customer->email_verified_at->format('d M Y, H:i') }}">
                                        Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-normal">
                                        Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small fw-semibold">Join Date</span>
                            <span class="text-dark small font-monospace">
                                {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Activity Summary Metrics Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0 text-dark">Activity Summary</h6>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="small text-secondary">🛒 Total Transaksi Order</span>
                            <span class="fw-bold text-dark">{{ number_format($totalOrders) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <span class="small text-secondary">⭐ Rating & Ulasan Diberikan</span>
                            <span class="fw-bold text-dark">{{ number_format($ratingCount) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <span class="small text-secondary">🚩 Laporan Masalah Diajukan</span>
                            <span class="fw-bold text-dark">{{ number_format($reportCount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Customer Information & Purchase Summaries --}}
        <div class="col-12 col-md-8">

            {{-- Customer Information Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Customer Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 30%;">Username</th>
                                    <td class="text-dark fw-bold">: {{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Email</th>
                                    <td class="text-dark">: {{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Email Verification</th>
                                    <td>: 
                                        @if($customer->email_verified_at)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-normal" title="Terverifikasi pada {{ $customer->email_verified_at->format('d M Y, H:i') }}">
                                                Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-normal">
                                                Not Verified
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Last Updated</th>
                                    <td class="text-muted">: {{ $customer->updated_at ? $customer->updated_at->format('d F Y, H:i') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Purchase Summary Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Purchase Summary</h5>
                    <span class="badge bg-light text-primary border small">{{ $periodInfo['label'] }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <span class="text-muted small fw-semibold text-uppercase d-block">Total Order</span>
                                <h4 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalOrders) }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <span class="text-muted small fw-semibold text-uppercase d-block">Total Spending</span>
                                <h4 class="fw-bold text-primary mb-0 mt-1">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <span class="text-muted small fw-semibold text-uppercase d-block">Average Order</span>
                                <h4 class="fw-bold text-dark mb-0 mt-1">Rp {{ number_format($averageOrder, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <span class="text-muted small fw-semibold text-uppercase d-block">Largest Order</span>
                                <h4 class="fw-bold text-dark mb-0 mt-1">Rp {{ number_format($largestOrder, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <span class="text-muted small fw-semibold text-uppercase d-block">Latest Order</span>
                                <h5 class="fw-bold text-secondary mb-0 mt-2 font-monospace">
                                    {{ $lastOrderDate ? \Carbon\Carbon::parse($lastOrderDate)->format('d M Y, H:i') : '-' }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Preference Summary Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Product Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 35%;">Most Purchased Product</th>
                                    <td class="text-dark fw-semibold">: {{ $mostPurchasedProduct ?? 'Belum ada data transaksi' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Favorite Category</th>
                                    <td class="text-dark fw-semibold">: {{ $favoriteCategory ?? 'Belum ada data transaksi' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Recent Order Card --}}
            {{-- Recent Order Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Recent Orders</h5>
                    <span class="badge bg-light text-primary border small">{{ $periodInfo['label'] }}</span>
                </div>
                <div class="card-body p-0">
                    @if(isset($recentOrders) && $recentOrders->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 small">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th scope="col" class="ps-4 py-2">Invoice</th>
                                        <th scope="col" class="py-2 text-end">Grand Total</th>
                                        <th scope="col" class="py-2">Status</th>
                                        <th scope="col" class="pe-4 py-2 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $ord)
                                        <tr>
                                            <td class="ps-4">
                                                <code class="text-primary bg-primary-subtle px-2 py-0.5 rounded fw-bold">{{ $ord->invoice_number }}</code>
                                                <small class="text-muted d-block">{{ $ord->created_at ? $ord->created_at->format('d M Y, H:i') : '-' }}</small>
                                            </td>
                                            <td class="text-end font-monospace fw-bold text-dark">
                                                Rp {{ number_format($ord->grand_total, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($ord->status === 'completed')
                                                    <span class="badge bg-dark px-2 py-1 fw-normal">🏁 Completed</span>
                                                @elseif($ord->status === 'ready_for_pickup')
                                                    <span class="badge bg-primary px-2 py-1 fw-normal">📦 Ready</span>
                                                @elseif($ord->status === 'processing')
                                                    <span class="badge bg-info px-2 py-1 fw-normal">⚙️ Processing</span>
                                                @elseif($ord->status === 'paid')
                                                    <span class="badge bg-success-subtle text-success border px-2 py-1 fw-normal">Paid</span>
                                                @elseif($ord->status === 'waiting_verification')
                                                    <span class="badge bg-info-subtle text-info-emphasis border px-2 py-1 fw-normal">Waiting Verification</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border px-2 py-1 fw-normal">Waiting Payment</span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-muted">
                            <span class="fs-1 d-block mb-2">🛒</span>
                            <span class="fw-semibold">Tidak ada transaksi order pada periode {{ $periodInfo['label'] }}.</span>
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
