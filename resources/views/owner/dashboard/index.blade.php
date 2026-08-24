@extends('layouts.owner.app')

@section('title', 'Dashboard Owner - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge tokobii-badge-info">Panel Eksekutif Owner</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Dashboard Owner</h1>
                <p class="text-slate-500 mb-0 small">
                    Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan performa dan pertumbuhan bisnis Tokobii.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('owner.profile.index') }}" class="btn btn-tokobii-primary btn-tokobii-sm shadow-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profil Owner</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Metric Stat Cards Grid --}}
    <div class="row g-3 g-md-4 mb-4">

        {{-- Total Revenue --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card">
                <div class="tokobii-stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value text-blue-600 font-monospace">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <div class="tokobii-stat-label">Total Omset Penjualan</div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card">
                <div class="tokobii-stat-icon" style="background-color: #ecfdf5; color: #059669;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value font-monospace">
                    {{ number_format($totalOrders) }}
                </div>
                <div class="tokobii-stat-label">Total Pesanan ({{ $completedOrders }} Selesai)</div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card">
                <div class="tokobii-stat-icon" style="background-color: #fffbeb; color: #d97706;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value font-monospace">
                    {{ number_format($totalProducts) }}
                </div>
                <div class="tokobii-stat-label">Produk ATK ({{ $totalCategories }} Kategori)</div>
            </div>
        </div>

        {{-- Total Customers --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="tokobii-stat-card">
                <div class="tokobii-stat-icon" style="background-color: #f3e8ff; color: #9333ea;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="tokobii-stat-value font-monospace">
                    {{ number_format($totalCustomers) }}
                </div>
                <div class="tokobii-stat-label">Pelanggan Terdaftar</div>
            </div>
        </div>

    </div>

    {{-- Main Row: Recent Orders & Top Products --}}
    <div class="row g-4 mb-4">
        
        {{-- Recent Orders Card --}}
        <div class="col-12 col-lg-8">
            <div class="tokobii-card h-100">
                <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Transaksi Pesanan Terbaru</h5>
                    <span class="tokobii-badge tokobii-badge-info">{{ count($recentOrders) }} Transaksi Terkini</span>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="tokobii-table mb-0">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Pelanggan</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-blue-600 font-monospace">{{ $order->invoice_number }}</span>
                                            <span class="text-slate-400 d-block" style="font-size: 0.7rem;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-slate-800 d-block">{{ $order->user->name ?? 'Tamu' }}</span>
                                            <span class="text-slate-400" style="font-size: 0.75rem;">{{ $order->payment_method === 'qris' ? 'QRIS' : 'Tunai Kasir' }}</span>
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
                                        <td colspan="4" class="text-center py-4 text-slate-400 small">
                                            Belum ada transaksi tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Products Card --}}
        <div class="col-12 col-lg-4">
            <div class="tokobii-card h-100">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Produk Terpopuler</h5>
                </div>
                <div class="p-4 d-flex flex-column gap-3">
                    @forelse($topProducts as $top)
                        <div class="d-flex align-items-center justify-content-between p-2.5 bg-slate-50 rounded-3 border border-slate-200">
                            <div>
                                <h6 class="fw-bold text-slate-900 mb-0 text-truncate" style="max-width: 170px; font-size: 0.875rem;">{{ $top->name }}</h6>
                                <span class="text-slate-400 small">{{ $top->category->name ?? 'Umum' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-blue-600 font-monospace small d-block">Rp {{ number_format($top->price, 0, ',', '.') }}</span>
                                <span class="text-slate-400" style="font-size: 0.7rem;">Stok: {{ $top->stock }}</span>
                            </div>
                        </div>
                    @empty
                        <span class="text-slate-400 small text-center py-3">Belum ada produk.</span>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection