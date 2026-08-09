@extends('layouts.admin.app')

@section('title', 'Admin Dashboard - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">📊 Dashboard Admin</h2>
            <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan operasional Tokobii.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary px-3 py-2 fw-semibold shadow-sm">
                🛒 Kelola Transaksi
            </a>
            <a href="{{ route('admin.orders.scan') }}" class="btn btn-outline-dark px-3 py-2 fw-semibold shadow-sm">
                📱 Scan QR Pickup
            </a>
        </div>
    </div>

    {{-- 4 Main Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-primary border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Categories</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalCategories) }}</h3>
                    </div>
                    <div class="bg-primary-subtle text-primary rounded-circle p-3 fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        📂
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-info border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Products</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalProducts) }}</h3>
                    </div>
                    <div class="bg-info-subtle text-info rounded-circle p-3 fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        📦
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-warning border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Customers</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalCustomers) }}</h3>
                    </div>
                    <div class="bg-warning-subtle text-warning rounded-circle p-3 fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        👥
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-success border-4">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Orders</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalOrders) }}</h3>
                    </div>
                    <div class="bg-success-subtle text-success rounded-circle p-3 fs-4 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        🛒
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status Breakdown & Revenue Banner --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">📋 Ringkasan Status Transaksi</h5>
                    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none small fw-semibold">Lihat Semua</a>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 text-center">
                        <div class="col-6 col-sm-3">
                            <div class="p-3 bg-warning-subtle rounded-3 border border-warning-subtle">
                                <small class="text-warning-emphasis fw-semibold d-block">Waiting Verification</small>
                                <span class="fs-4 fw-bold text-warning-emphasis">{{ number_format($waitingVerificationOrders) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle">
                                <small class="text-success fw-semibold d-block">Paid</small>
                                <span class="fs-4 fw-bold text-success">{{ number_format($paidOrders) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 bg-primary-subtle rounded-3 border border-primary-subtle">
                                <small class="text-primary fw-semibold d-block">Ready for Pickup</small>
                                <span class="fs-4 fw-bold text-primary">{{ number_format($readyForPickupOrders) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="p-3 bg-dark-subtle rounded-3 border border-dark-subtle">
                                <small class="text-dark fw-semibold d-block">Completed</small>
                                <span class="fs-4 fw-bold text-dark">{{ number_format($completedOrders) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-primary text-white p-2">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <span class="text-white-50 small fw-semibold text-uppercase">Est. Revenue (Completed/Paid)</span>
                        <h2 class="fw-bold font-monospace mt-2 mb-0">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h2>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">Total akumulasi pembayaran terverifikasi dari transaksi Tokobii.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row: Recent Orders & Low Stock Alert --}}
    <div class="row g-4 mb-4">
        {{-- Recent Orders Table --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">📦 Transaksi Terbaru</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-2">Invoice</th>
                                    <th scope="col" class="py-2">Customer</th>
                                    <th scope="col" class="py-2 text-end">Total</th>
                                    <th scope="col" class="py-2">Status</th>
                                    <th scope="col" class="pe-4 py-2 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <code class="text-primary bg-primary-subtle px-2 py-0.5 rounded fw-bold">{{ $order->invoice_number }}</code>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark d-block">{{ $order->user->name ?? '-' }}</span>
                                        </td>
                                        <td class="text-end font-monospace fw-bold text-dark">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($order->status === 'completed')
                                                <span class="badge bg-dark px-2 py-1 fw-normal">🏁 Completed</span>
                                            @elseif($order->status === 'ready_for_pickup')
                                                <span class="badge bg-primary px-2 py-1 fw-normal">📦 Ready</span>
                                            @elseif($order->status === 'processing')
                                                <span class="badge bg-info px-2 py-1 fw-normal">⚙️ Processing</span>
                                            @elseif($order->status === 'paid')
                                                <span class="badge bg-success-subtle text-success border px-2 py-1 fw-normal">Paid</span>
                                            @elseif($order->status === 'waiting_verification')
                                                <span class="badge bg-info-subtle text-info-emphasis border px-2 py-1 fw-normal">Waiting Verification</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis border px-2 py-1 fw-normal">Waiting Payment</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi.</td>
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
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">⚠️ Peringatan Stok Rendah</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning text-dark fw-semibold">Kelola Stok</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-2">Nama Produk</th>
                                    <th scope="col" class="py-2">Kategori</th>
                                    <th scope="col" class="pe-4 py-2 text-end">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $prod)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $prod->name }}</td>
                                        <td class="text-muted">{{ $prod->category->name ?? '-' }}</td>
                                        <td class="pe-4 text-end font-monospace">
                                            @if($prod->stock == 0)
                                                <span class="badge bg-danger">Habis (0)</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $prod->stock }} Pcs</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Semua stok produk mencukupi (>= 10).</td>
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