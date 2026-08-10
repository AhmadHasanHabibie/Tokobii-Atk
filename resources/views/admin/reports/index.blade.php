@extends('layouts.admin.app')

@section('title', 'Reports & Issues - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Reports</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Reports & Customer Complaints</h1>
        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Manage product tickets, sales metrics, and resolution workflows.</p>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="row g-3 mb-4">
        @foreach(['sales' => 'Total Sales', 'products' => 'Total Products', 'customers' => 'Total Customers', 'orders' => 'Total Orders', 'reports' => 'Product Reports', 'pending' => 'Pending', 'replied' => 'Replied'] as $key => $label)
            <div class="col-6 col-md-3">
                <div class="tokobii-card p-3 h-100">
                    <span class="text-slate-400 d-block text-uppercase fw-semibold" style="font-size: 0.6875rem; letter-spacing: 0.05em;">{{ $label }}</span>
                    <h4 class="fw-bold text-slate-900 mb-0 mt-1" style="font-size: 1.25rem;">
                        {{ $key === 'sales' ? 'Rp '.number_format($stats[$key], 0, ',', '.') : $stats[$key] }}
                    </h4>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Categories Summary --}}
    <h5 class="fw-bold text-slate-900 mb-3" style="font-size: 1rem;">Browse by Category</h5>
    <div class="row g-3 mb-4">
        @forelse($categories as $category)
            <div class="col-12 col-md-4">
                <a href="{{ route('admin.reports.categories.show', $category) }}" class="text-decoration-none">
                    <div class="tokobii-card p-3 h-100 transition-all hover-shadow-md">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-slate-900 mb-0">📁 {{ $category->name }}</h6>
                            <span class="tokobii-badge tokobii-badge-neutral">{{ $category->reports_count }} reports</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-slate-400">No categories found.</div>
        @endforelse
    </div>

    {{-- All Product Reports Table Card --}}
    <div class="tokobii-table-container">
        <div class="p-3 border-bottom border-slate-100 bg-white">
            <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">All Product Reports</h5>
        </div>
        
        <div class="p-3 bg-slate-50 border-bottom border-slate-100">
            <form class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <select class="tokobii-select w-100" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select class="tokobii-select w-100" name="product">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(request('product') == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <select class="tokobii-select w-100" name="status">
                        <option value="">All Statuses</option>
                        @foreach(['pending','replied','resolved'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <input class="tokobii-input w-100" name="search" value="{{ request('search') }}" placeholder="Search customer, invoice, product...">
                </div>
                <div class="col-12 col-md-1">
                    <button class="btn btn-tokobii-primary w-100">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="tokobii-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Product / Category</th>
                        <th>Invoice</th>
                        <th>Issue Detail</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>
                                <span class="fw-bold text-slate-900 d-block">{{ $report->user->name }}</span>
                                <span class="text-slate-400 font-monospace d-block" style="font-size: 0.75rem;">{{ $report->user->email }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-slate-800 d-block">{{ $report->product->name }}</span>
                                <span class="text-slate-400 d-block" style="font-size: 0.75rem;">{{ $report->product->category->name }}</span>
                            </td>
                            <td>
                                <code class="text-blue-600 font-monospace fw-bold">{{ $report->order->invoice_number }}</code>
                            </td>
                            <td style="max-width: 240px;">
                                <span class="text-slate-700 d-block text-truncate" title="{{ $report->description }}">{{ \Illuminate\Support\Str::limit($report->description, 60) }}</span>
                            </td>
                            <td>
                                @if($report->status === 'pending')
                                    <span class="tokobii-badge tokobii-badge-warning">Pending</span>
                                @elseif($report->status === 'resolved')
                                    <span class="tokobii-badge tokobii-badge-success">Resolved</span>
                                @else
                                    <span class="tokobii-badge tokobii-badge-info">Replied</span>
                                @endif
                            </td>
                            <td class="text-slate-500" style="font-size: 0.8125rem;">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-tokobii-secondary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-4">No product reports recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="px-4 py-3 border-top border-slate-100 d-flex justify-content-end">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
