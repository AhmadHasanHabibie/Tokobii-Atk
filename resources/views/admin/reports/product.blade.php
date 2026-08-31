@extends('layouts.admin.app')

@section('title', 'Laporan Produk: ' . $product->name . ' - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Laporan Masalah</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.categories.show', $product->category) }}" class="text-decoration-none text-slate-500 hover-text-blue-600">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="tokobii-header-card mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Laporan Produk: {{ $product->name }}</h1>
                <p class="text-slate-500 mb-0 small">{{ $product->reports_count ?? $reports->total() }} tiket laporan tercatat untuk produk ini di kategori {{ $product->category->name }}.</p>
            </div>
            <a href="{{ route('admin.reports.categories.show', $product->category) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Kategori</span>
            </a>
        </div>
    </div>

    {{-- Reports List --}}
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($reports as $report)
            <div class="tokobii-card p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-blue-50 text-blue-600 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem;">
                            {{ strtoupper(substr($report->user->name ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <strong class="text-slate-900 d-block" style="font-size: 0.875rem;">{{ $report->user->name }}</strong>
                            <span class="text-slate-400 font-monospace" style="font-size: 0.75rem;">{{ $report->user->email }}</span>
                        </div>
                        <span class="text-slate-300 mx-1">•</span>
                        <span class="text-blue-600 font-monospace fw-semibold" style="font-size: 0.8125rem;">{{ $report->order->invoice_number }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="tokobii-badge {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                        <span class="text-slate-400" style="font-size: 0.75rem;">{{ $report->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
                <div class="p-3 bg-slate-50 rounded-3 border border-slate-100 my-2 text-slate-700 small" style="line-height: 1.6;">
                    {{ $report->description }}
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Tanggapi / Detail</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="tokobii-card p-5 text-center text-slate-400">
                Belum ada tiket laporan yang dikirimkan untuk produk ini.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($reports->hasPages())
        <div class="d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
