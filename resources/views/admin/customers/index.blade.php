@extends('layouts.admin.app')

@section('title', 'Customer Analytics Dashboard - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Customer Analytics</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Customer Analytics Dashboard</h2>
            <p class="text-muted mb-0">Analisis aktivitas, registrasi, dan pertumbuhan customer Tokobii berdasarkan periode.</p>
        </div>
    </div>

    {{-- Analytics Period Filter Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h6 class="fw-bold mb-0 text-dark">📊 Filter Periode Analisis: <span class="text-primary">{{ $periodInfo['label'] }}</span></h6>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('admin.customers.index') }}" method="GET" id="analyticsFilterForm" class="row g-3 align-items-center">
                
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
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary px-3" title="Reset Filter" aria-label="Reset Filter">
                        ↺
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Analytics Dashboard Metric Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Customer Baru</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalNewCustomers) }}</h3>
                    </div>
                    <div class="bg-primary-subtle rounded-circle p-3 text-primary fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        👥
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Active Customer</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($activeCustomers) }}</h3>
                    </div>
                    <div class="bg-success-subtle rounded-circle p-3 text-success fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        🟢
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Verified Customer</span>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($verifiedCustomers) }}</h3>
                    </div>
                    <div class="bg-info-subtle rounded-circle p-3 text-info fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        📧
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Order</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalOrders) }}</h3>
                    </div>
                    <div class="bg-warning-subtle rounded-circle p-3 text-warning-emphasis fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        🛒
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Revenue</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-success-subtle rounded-circle p-3 text-success fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        💰
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Product Purchased</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalProductsPurchased) }}</h3>
                    </div>
                    <div class="bg-light rounded-circle p-3 text-secondary fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        📦
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Rating</span>
                        <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($totalRatings) }} ⭐</h3>
                    </div>
                    <div class="bg-warning-subtle rounded-circle p-3 text-warning fs-4 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        ⭐
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter, Search, and Sort Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="period" value="{{ $periodInfo['period'] }}">
                @if($periodInfo['period'] === 'day')
                    <input type="hidden" name="date" value="{{ $periodInfo['date'] }}">
                @elseif($periodInfo['period'] === 'month')
                    <input type="hidden" name="month" value="{{ $periodInfo['month'] }}">
                    <input type="hidden" name="year" value="{{ $periodInfo['year'] }}">
                @elseif($periodInfo['period'] === 'year')
                    <input type="hidden" name="year" value="{{ $periodInfo['year'] }}">
                @endif

                {{-- Search Input --}}
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama, email, telp..." 
                               value="{{ request('search') }}"
                               aria-label="Cari customer"
                               aria-describedby="search-addon">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <select name="status" class="form-select" aria-label="Filter status customer">
                        <option value="">Status: Semua</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Banned</option>
                    </select>
                </div>

                {{-- Verified Filter --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="verified" class="form-select" aria-label="Filter verifikasi email customer">
                        <option value="">Email: Semua</option>
                        <option value="verified" {{ request('verified') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request('verified') === 'unverified' ? 'selected' : '' }}>Not Verified</option>
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="sort" class="form-select" aria-label="Urutkan customer">
                        <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    </select>
                </div>

                {{-- Filter Action Buttons --}}
                <div class="col-12 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'verified', 'sort']))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary" title="Reset Filter" aria-label="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    {{-- Customer Table Card --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">

            @forelse($customers as $customer)
                @if($loop->first)
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light sticky-top shadow-sm border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 4%;">No</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Photo</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 20%;">Customer</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 20%;">Email</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 12%;">Level</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Status</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Verified</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 10%;">Join Date</th>
                                    <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 4%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif

                <tr>
                    <td class="ps-4 fw-semibold text-secondary">{{ $customers->firstItem() + $loop->index }}</td>
                    <td>
                        @if(isset($customer->photo) && $customer->photo)
                            <img src="{{ asset('storage/' . $customer->photo) }}" 
                                 alt="Photo {{ $customer->name }}" 
                                 class="rounded-circle shadow-sm border" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @elseif(isset($customer->avatar) && $customer->avatar)
                            <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                 alt="Avatar {{ $customer->name }}" 
                                 class="rounded-circle shadow-sm border" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center border border-primary-subtle" 
                                 style="width: 60px; height: 60px; font-size: 1.2rem;" 
                                 title="{{ $customer->name }}">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold text-dark d-inline-block text-truncate" style="max-width: 180px;" title="{{ $customer->name }}">
                            {{ $customer->name }}
                        </span>
                    </td>
                    <td>
                        <span class="text-secondary d-inline-block text-truncate" style="max-width: 200px;" title="{{ $customer->email }}">
                            {{ $customer->email }}
                        </span>
                    </td>
                    <td>
                        @if(($customer->orders_count ?? 0) >= 10)
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fw-normal">
                                🥇 Gold
                            </span>
                        @elseif(($customer->orders_count ?? 0) >= 4)
                            <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle px-2.5 py-1.5 fw-normal">
                                🥈 Silver
                            </span>
                        @elseif(($customer->orders_count ?? 0) >= 1)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-normal">
                                🥉 Bronze ({{ $customer->orders_count }} Order)
                            </span>
                        @else
                            <span class="badge bg-light text-secondary border border-secondary-subtle px-2.5 py-1.5 fw-normal">
                                🌱 New
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($customer->status === 'active')
                            <span class="badge bg-success px-2.5 py-1.5 fw-normal">🟢 Active</span>
                        @elseif($customer->status === 'blocked')
                            <span class="badge bg-danger px-2.5 py-1.5 fw-normal">🔴 Banned</span>
                        @else
                            <span class="badge bg-secondary px-2.5 py-1.5 fw-normal">⚫ Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if($customer->email_verified_at)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-normal">Verified</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fw-normal">Not Verified</span>
                        @endif
                    </td>
                    <td class="text-muted small">
                        {{ $customer->created_at ? $customer->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.customers.show', ['customer' => $customer] + request()->only(['period', 'date', 'month', 'year'])) }}" 
                           class="btn btn-sm btn-outline-info" 
                           title="Detail Customer Analytics" 
                           aria-label="Detail {{ $customer->name }}">
                            👁 Detail
                        </a>
                    </td>
                </tr>

                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                {{-- Empty State --}}
                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="fs-1">📊</span>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Tidak ada aktivitas pada periode ini.</h5>
                    <p class="text-muted mb-4">Belum ada pendaftaran customer atau aktivitas yang terekam pada {{ $periodInfo['label'] }}.</p>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                        Reset Filter Periode
                    </a>
                </div>
            @endforelse

        </div>

        {{-- Pagination Footer --}}
        @if($customers->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $customers->firstItem() }} - {{ $customers->lastItem() }} dari {{ $customers->total() }} customer
                </small>
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
