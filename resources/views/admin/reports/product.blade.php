@extends('layouts.admin.app')

@section('title', 'Reports ' . $product->name . ' - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Reports</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.categories.show', $product->category) }}" class="text-decoration-none text-slate-500 hover-text-blue-600">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Reports: {{ $product->name }}</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">{{ $product->reports_count }} report tickets recorded for this product in {{ $product->category->name }}.</p>
        </div>
        <a href="{{ route('admin.reports.categories.show', $product->category) }}" class="btn btn-tokobii-secondary">
            Back to Category
        </a>
    </div>

    {{-- Reports List --}}
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($reports as $report)
            <div class="tokobii-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-500">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <strong class="text-slate-900">{{ $report->user->name }}</strong>
                        <span class="text-slate-300">•</span>
                        <span class="text-blue-600 font-monospace" style="font-size: 0.8125rem;">{{ $report->order->invoice_number }}</span>
                    </div>
                    <span class="text-slate-400" style="font-size: 0.8125rem;">{{ $report->created_at->format('d M Y, H:i') }}</span>
                </div>
                <p class="text-slate-700 mb-3" style="font-size: 0.875rem; line-height: 1.6;">{{ \Illuminate\Support\Str::limit($report->description, 120) }}</p>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Lihat Detail</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="tokobii-card p-5 text-center text-slate-400">
                No reports submitted for this product yet.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($reports->hasPages())
        <div class="d-flex justify-content-end">
            {{ $reports->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
