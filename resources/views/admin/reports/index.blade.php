@extends('layouts.admin.app')

@section('title', 'Laporan & Komplain Pelanggan - Tokobii')

@section('content')

{{-- Header Card --}}
<div class="tokobii-header-card">
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Laporan Masalah</li>
        </ol>
    </nav>
    <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Laporan & Komplain Pelanggan</h1>
    <p class="text-slate-500 mb-0 small">Kelola tiket keluhan produk, respons admin, dan penyelesaian masalah pelanggan di Tokobii.</p>
</div>

{{-- Stats Cards Grid --}}
<div class="row g-3 mb-4">
    @php
        $statsLabels = [
            'sales' => 'Total Omset',
            'products' => 'Total Produk',
            'customers' => 'Total Pelanggan',
            'orders' => 'Total Pesanan',
            'reports' => 'Laporan Masalah',
            'pending' => 'Menunggu Tanggapan',
            'replied' => 'Sudah Dibalas'
        ];
    @endphp

    @foreach($statsLabels as $key => $label)
        <div class="col-6 col-md-3">
            <div class="tokobii-card p-3 h-100">
                <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                    {{ $label }}
                </span>
                <h4 class="fw-bold text-slate-900 mb-0 mt-1 font-monospace" style="font-size: 1.15rem;">
                    {{ $key === 'sales' ? 'Rp ' . number_format($stats[$key], 0, ',', '.') : number_format($stats[$key]) }}
                </h4>
            </div>
        </div>
    @endforeach
</div>

{{-- Categories Summary --}}
<h5 class="fw-bold text-slate-900 mb-3" style="font-size: 1rem;">Kategori Masalah Produk</h5>

<div class="row g-3 mb-4">
    @forelse($categories as $category)
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.reports.category', $category->slug) }}" class="text-decoration-none">
                <div class="tokobii-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            <h6 class="fw-bold text-slate-900 mb-0 small text-truncate" style="max-width: 140px;">{{ $category->name }}</h6>
                        </div>
                        <span class="tokobii-badge tokobii-badge-neutral font-monospace">
                            {{ $category->reports_count }}
                        </span>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-slate-400 small">
            Belum ada kategori yang tercatat.
        </div>
    @endforelse
</div>

{{-- All Product Reports Table Card --}}
<div class="tokobii-table-container">
    {{-- Table Header --}}
    <div class="p-3 border-bottom border-slate-100 bg-white">
        <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Daftar Semua Laporan Masalah</h5>
    </div>

    {{-- Filter Bar --}}
    <div class="p-3 bg-slate-50 border-bottom border-slate-100">
        <form class="row g-2 align-items-center" method="GET" action="{{ route('admin.reports.index') }}">
            {{-- Category --}}
            <div class="col-12 col-md-3">
                <select class="tokobii-select w-100" name="category" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Product --}}
            <div class="col-12 col-md-3">
                <select class="tokobii-select w-100" name="product" onchange="this.form.submit()">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected(request('product') == $product->id)>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="col-12 col-md-2">
                <select class="tokobii-select w-100" name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu Tanggapan</option>
                    <option value="replied" @selected(request('status') === 'replied')>Sudah Dibalas</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Selesai Ditangani</option>
                </select>
            </div>

            {{-- Search --}}
            <div class="col-12 col-md-3">
                <input class="tokobii-input w-100" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan, invoice, produk...">
            </div>

            {{-- Filter Button --}}
            <div class="col-12 col-md-1">
                <button type="submit" class="btn btn-tokobii-primary w-100">
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Reports Table --}}
    <div class="table-responsive">
        <table class="tokobii-table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Produk / Kategori</th>
                    <th>No. Invoice</th>
                    <th>Kendala Masalah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        {{-- Customer --}}
                        <td>
                            <span class="fw-bold text-slate-900 d-block">{{ $report->user->name }}</span>
                            <span class="text-slate-400 font-monospace d-block" style="font-size: 0.75rem;">{{ $report->user->email }}</span>
                        </td>

                        {{-- Product / Category --}}
                        <td>
                            <span class="fw-semibold text-slate-800 d-block">{{ $report->product->name }}</span>
                            <span class="text-slate-400 d-block" style="font-size: 0.75rem;">{{ $report->product->category->name ?? '-' }}</span>
                        </td>

                        {{-- Invoice --}}
                        <td>
                            <span class="text-blue-600 font-monospace fw-bold">{{ $report->order->invoice_number }}</span>
                        </td>

                        {{-- Issue Detail --}}
                        <td style="max-width: 240px;">
                            <span class="text-slate-700 d-block text-truncate small" title="{{ $report->description }}">
                                {{ \Illuminate\Support\Str::limit($report->description, 50) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($report->status === 'pending')
                                <span class="tokobii-badge tokobii-badge-warning">Menunggu</span>
                            @elseif($report->status === 'replied')
                                <span class="tokobii-badge tokobii-badge-info">Dibalas</span>
                            @elseif($report->status === 'resolved')
                                <span class="tokobii-badge tokobii-badge-success">Selesai</span>
                            @else
                                <span class="tokobii-badge tokobii-badge-neutral">{{ ucfirst($report->status) }}</span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="text-slate-500 small">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </td>

                        {{-- Action --}}
                        <td class="text-end">
                            <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-slate-400 small">
                            Tidak ada data laporan yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($reports->hasPages())
        <div class="p-3 border-top border-slate-100 d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    @endif
</div>

@endsection