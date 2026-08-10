@extends('layouts.admin.app')

@section('title', 'Reports & Issues - Tokobii')

@section('content')

<style>
    /* =========================================================
       REPORT ACTION CARD
       UI ONLY - TIDAK MENGUBAH ROUTE / LOGIC
       ========================================================= */

    .report-detail-card {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;

        min-width: 72px;
        min-height: 34px;

        padding: 0.45rem 0.8rem;

        border: 1px solid #2563eb;
        border-radius: 8px;

        background: #2563eb;
        color: #ffffff !important;

        font-size: 0.75rem;
        font-weight: 600;

        text-decoration: none;

        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.12);

        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            transform 0.15s ease;
    }

    .report-detail-card:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff !important;

        box-shadow: 0 4px 9px rgba(37, 99, 235, 0.18);

        transform: translateY(-1px);
    }

    .report-detail-card:active {
        background: #1e40af;
        border-color: #1e40af;

        color: #ffffff !important;

        transform: translateY(0);

        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.15);
    }

    .report-detail-card svg {
        flex-shrink: 0;
    }
</style>


{{-- Page Header --}}
<div class="mb-4">

    <h1 class="h3 fw-bold text-slate-900 mb-1"
        style="color: #0f172a;">

        Reports & Customer Complaints

    </h1>

    <p class="text-slate-500 mb-0"
       style="font-size: 0.875rem;">

        Manage product tickets, sales metrics, and resolution workflows.

    </p>

</div>


{{-- Stats Cards Grid --}}
<div class="row g-3 mb-4">

    @foreach([
        'sales' => 'Total Sales',
        'products' => 'Total Products',
        'customers' => 'Total Customers',
        'orders' => 'Total Orders',
        'reports' => 'Product Reports',
        'pending' => 'Pending',
        'replied' => 'Replied'
    ] as $key => $label)

        <div class="col-6 col-md-3">

            <div class="tokobii-card p-3 h-100">

                <span class="text-slate-400 d-block text-uppercase fw-semibold"
                      style="
                        font-size: 0.6875rem;
                        letter-spacing: 0.05em;
                      ">

                    {{ $label }}

                </span>

                <h4 class="fw-bold text-slate-900 mb-0 mt-1"
                    style="font-size: 1.25rem;">

                    {{
                        $key === 'sales'
                            ? 'Rp '.number_format($stats[$key], 0, ',', '.')
                            : $stats[$key]
                    }}

                </h4>

            </div>

        </div>

    @endforeach

</div>


{{-- Categories Summary --}}
<h5 class="fw-bold text-slate-900 mb-3"
    style="font-size: 1rem;">

    Browse by Category

</h5>


<div class="row g-3 mb-4">

    @forelse($categories as $category)

        <div class="col-12 col-md-4">

            <a href="{{ route('admin.reports.categories.show', $category) }}"
               class="text-decoration-none">

                <div class="tokobii-card p-3 h-100 transition-all hover-shadow-md">

                    <div class="d-flex justify-content-between align-items-center mb-1">

                        <h6 class="fw-bold text-slate-900 mb-0">

                            📁 {{ $category->name }}

                        </h6>

                        <span class="tokobii-badge tokobii-badge-neutral">

                            {{ $category->reports_count }} reports

                        </span>

                    </div>

                </div>

            </a>

        </div>

    @empty

        <div class="col-12 text-slate-400">

            No categories found.

        </div>

    @endforelse

</div>


{{-- All Product Reports Table Card --}}
<div class="tokobii-table-container">


    {{-- Table Header --}}
    <div class="p-3 border-bottom border-slate-100 bg-white">

        <h5 class="fw-bold mb-0 text-slate-900"
            style="font-size: 1rem;">

            All Product Reports

        </h5>

    </div>


    {{-- Filter --}}
    <div class="p-3 bg-slate-50 border-bottom border-slate-100">

        <form class="row g-2 align-items-center">

            {{-- Category --}}
            <div class="col-12 col-md-3">

                <select class="tokobii-select w-100"
                        name="category">

                    <option value="">
                        All Categories
                    </option>

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            @selected(request('category') == $category->id)>

                            {{ $category->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Product --}}
            <div class="col-12 col-md-3">

                <select class="tokobii-select w-100"
                        name="product">

                    <option value="">
                        All Products
                    </option>

                    @foreach($products as $product)

                        <option value="{{ $product->id }}"
                            @selected(request('product') == $product->id)>

                            {{ $product->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Status --}}
            <div class="col-12 col-md-2">

                <select class="tokobii-select w-100"
                        name="status">

                    <option value="">
                        All Statuses
                    </option>

                    @foreach([
                        'pending',
                        'replied',
                        'resolved'
                    ] as $status)

                        <option value="{{ $status }}"
                            @selected(request('status') === $status)>

                            {{ ucfirst($status) }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Search --}}
            <div class="col-12 col-md-3">

                <input
                    class="tokobii-input w-100"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search customer, invoice, product..."
                >

            </div>


            {{-- Filter Button --}}
            <div class="col-12 col-md-1">

                <button
                    type="submit"
                    class="btn btn-tokobii-primary w-100">

                    Filter

                </button>

            </div>

        </form>

    </div>


    {{-- Reports Table --}}
    <div class="table-responsive">

        <table class="tokobii-table">

            <thead>

                <tr>

                    <th>
                        Customer
                    </th>

                    <th>
                        Product / Category
                    </th>

                    <th>
                        Invoice
                    </th>

                    <th>
                        Issue Detail
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Date
                    </th>

                    <th class="text-end">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($reports as $report)

                    <tr>


                        {{-- Customer --}}
                        <td>

                            <span class="fw-bold text-slate-900 d-block">

                                {{ $report->user->name }}

                            </span>

                            <span
                                class="text-slate-400 font-monospace d-block"
                                style="font-size: 0.75rem;">

                                {{ $report->user->email }}

                            </span>

                        </td>


                        {{-- Product / Category --}}
                        <td>

                            <span class="fw-semibold text-slate-800 d-block">

                                {{ $report->product->name }}

                            </span>

                            <span
                                class="text-slate-400 d-block"
                                style="font-size: 0.75rem;">

                                {{ $report->product->category->name }}

                            </span>

                        </td>


                        {{-- Invoice --}}
                        <td>

                            <code
                                class="text-blue-600 font-monospace fw-bold">

                                {{ $report->order->invoice_number }}

                            </code>

                        </td>


                        {{-- Issue Detail --}}
                        <td style="max-width: 240px;">

                            <span
                                class="text-slate-700 d-block text-truncate"
                                title="{{ $report->description }}">

                                {{ \Illuminate\Support\Str::limit($report->description, 60) }}

                            </span>

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($report->status === 'pending')

                                <span class="tokobii-badge tokobii-badge-warning">
                                    Pending
                                </span>

                            @elseif($report->status === 'resolved')

                                <span class="tokobii-badge tokobii-badge-success">
                                    Resolved
                                </span>

                            @else

                                <span class="tokobii-badge tokobii-badge-info">
                                    Replied
                                </span>

                            @endif

                        </td>


                        {{-- Date --}}
                        <td
                            class="text-slate-500"
                            style="font-size: 0.8125rem;">

                            {{ $report->created_at->format('d M Y') }}

                        </td>


                        {{-- Action --}}
                        <td class="text-end">

                            {{-- Detail Card --}}
                            <a
                                href="{{ route('admin.reports.show', $report) }}"
                                class="report-detail-card">

                                <svg
                                    width="14"
                                    height="14"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>

                                </svg>

                                <span>
                                    Detail
                                </span>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-slate-400 py-4">

                            No product reports recorded.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($reports->hasPages())

        <div
            class="px-4 py-3 border-top border-slate-100 d-flex justify-content-end">

            {{ $reports->links('pagination::bootstrap-5') }}

        </div>

    @endif

</div>

@endsection