@extends('layouts.admin.app')

@section('title', $category->name . ' Reports - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Reports</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">{{ $category->name }} Reports</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Select a product to view customer complaints in {{ strtolower($category->name) }}.</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-tokobii-secondary">Back to Reports</a>
    </div>

    {{-- Products Grid --}}
    <div class="row g-3 mb-4">
        @forelse($products as $product)
            <div class="col-12 col-md-6">
                <div class="tokobii-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-slate-900 mb-1" style="font-size: 1.05rem;">{{ $product->name }}</h5>
                        <p class="text-slate-500 mb-3" style="font-size: 0.875rem;">{{ $product->reports_count }} report tickets</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.reports.products.show', $product) }}" class="btn btn-tokobii-secondary btn-sm">
                            View Reports →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-slate-400 py-4">No products found in this category.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="d-flex justify-content-end">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
