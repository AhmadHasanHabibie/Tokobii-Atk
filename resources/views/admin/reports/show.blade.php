@extends('layouts.admin.app')

@section('title', 'Detail Report - Tokobii')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h2 class="fw-bold mb-1">Detail Report</h2><p class="text-muted mb-0">Tinjau keluhan produk dan balasan pelanggan.</p></div>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">Kembali ke Reports</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif
<div class="row g-4">
    <div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body p-4">
        <div class="d-flex justify-content-between gap-3 mb-3"><h4 class="fw-bold mb-0">{{ $report->product?->name ?? '-' }}</h4><span class="badge {{ $report->status === 'pending' ? 'bg-warning text-dark' : ($report->status === 'resolved' ? 'bg-success' : 'bg-primary') }} align-self-start">{{ ucfirst($report->status) }}</span></div>
        <dl class="row mb-0"><dt class="col-sm-4">Customer</dt><dd class="col-sm-8">{{ $report->user?->name }}<small class="d-block text-muted">{{ $report->user?->email }}</small></dd><dt class="col-sm-4">Invoice</dt><dd class="col-sm-8">{{ $report->order?->invoice_number }}</dd><dt class="col-sm-4">Tanggal Order</dt><dd class="col-sm-8">{{ $report->order?->order_date?->format('d M Y H:i') ?? '-' }}</dd><dt class="col-sm-4">Status Order</dt><dd class="col-sm-8">{{ str($report->order?->order_status ?? '-')->replace('_', ' ')->title() }}</dd><dt class="col-sm-4">SKU / Category</dt><dd class="col-sm-8">{{ $report->product?->sku ?? '-' }} / {{ $report->product?->category?->name ?? '-' }}</dd><dt class="col-sm-4">Tanggal Report</dt><dd class="col-sm-8">{{ $report->created_at->format('d M Y H:i') }}</dd></dl>
        <hr><h6 class="fw-bold">Isi Report</h6><p class="mb-0">{{ $report->description }}</p>
    </div></div></div>
    <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body p-4"><h5 class="fw-bold">Balasan Admin</h5>
        @if($report->admin_reply)
            <div class="alert alert-light border mb-3">{{ $report->admin_reply }}<small class="d-block text-muted mt-2">Dibalas oleh {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at?->format('d M Y H:i') }}</small></div>
            <textarea class="form-control mb-3" rows="5" disabled>{{ $report->admin_reply }}</textarea><button class="btn btn-secondary w-100" disabled>Balasan Sudah Dikirim</button>
        @else
            <form method="POST" action="{{ route('admin.reports.reply', $report) }}">@csrf @method('PUT')
                <textarea name="admin_reply" rows="5" maxlength="2000" class="form-control @error('admin_reply') is-invalid @enderror" placeholder="Silahkan hubungi nomor 01617767876, untuk menindak lanjuti masalah anda" required>{{ old('admin_reply') }}</textarea>
                @error('admin_reply')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <button class="btn btn-primary w-100 mt-3">Kirim Balasan</button>
            </form>
        @endif
    </div></div></div>
</div>
@endsection
