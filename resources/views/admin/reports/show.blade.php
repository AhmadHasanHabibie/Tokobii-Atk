@extends('layouts.admin.app')

@section('title', 'Report Details - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Reports</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Details</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Report Details</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Review product complaint ticket and send administrator reply.</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-tokobii-secondary">Back to Reports</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4">{{ session('warning') }}</div>
    @endif

    <div class="row g-4">
        {{-- Report Overview --}}
        <div class="col-lg-7">
            <div class="tokobii-card h-100">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1.1rem;">{{ $report->product?->name ?? '-' }}</h5>
                        @if($report->status === 'pending')
                            <span class="tokobii-badge tokobii-badge-warning">Pending</span>
                        @elseif($report->status === 'resolved')
                            <span class="tokobii-badge tokobii-badge-success">Resolved</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-info">Replied</span>
                        @endif
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Customer</th>
                                    <td>: <strong class="text-slate-900">{{ $report->user?->name }}</strong> <span class="text-slate-400 font-monospace">({{ $report->user?->email }})</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Invoice</th>
                                    <td>: <code class="text-blue-600 font-monospace fw-bold">{{ $report->order?->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Order Date</th>
                                    <td class="text-slate-800">: {{ $report->order?->order_date?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Order Status</th>
                                    <td class="text-slate-800">: {{ str($report->order?->order_status ?? '-')->replace('_', ' ')->title() }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">SKU / Category</th>
                                    <td class="text-slate-800">: {{ $report->product?->sku ?? '-' }} / {{ $report->product?->category?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Report Date</th>
                                    <td class="text-slate-500">: {{ $report->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top border-slate-100 pt-3">
                        <h6 class="fw-bold text-slate-900 mb-2" style="font-size: 0.9375rem;">Complaint Message</h6>
                        <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-700" style="font-size: 0.875rem; line-height: 1.6;">
                            {{ $report->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Reply Form --}}
        <div class="col-lg-5">
            <div class="tokobii-card h-100">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Administrator Reply</h5>
                </div>
                <div class="p-4">
                    @if($report->admin_reply)
                        <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 mb-3 text-slate-700" style="font-size: 0.875rem; line-height: 1.6;">
                            {{ $report->admin_reply }}
                            <span class="d-block text-slate-400 mt-2" style="font-size: 0.75rem;">Replied by {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at?->format('d M Y H:i') }}</span>
                        </div>
                        <textarea class="tokobii-input w-100 mb-3" rows="5" disabled>{{ $report->admin_reply }}</textarea>
                        <button class="btn btn-tokobii-secondary w-100" disabled>Reply Sent</button>
                    @else
                        <form method="POST" action="{{ route('admin.reports.reply', $report) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="admin_reply" class="form-label">Response Message <span class="text-danger">*</span></label>
                                <textarea name="admin_reply" 
                                          id="admin_reply" 
                                          rows="5" 
                                          maxlength="2000" 
                                          class="tokobii-input w-100 @error('admin_reply') is-invalid @enderror" 
                                          placeholder="e.g. Please contact our support team at 0812-3456-7890 to process your request." 
                                          required>{{ old('admin_reply') }}</textarea>
                                @error('admin_reply')
                                    <div class="text-danger mt-1" style="font-size: 0.8125rem;">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-tokobii-primary w-100">Send Response</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
