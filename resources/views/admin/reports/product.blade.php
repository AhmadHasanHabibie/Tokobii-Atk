@extends('layouts.admin.app')
@section('title', 'Reports ' . $product->name . ' - Tokobii')
@section('content')
<a href="{{ route('admin.reports.categories.show', $product->category) }}" class="btn btn-outline-secondary btn-sm mb-3">Kembali ke Kategori</a><h2 class="fw-bold">Reports: {{ $product->name }}</h2><p class="text-muted">{{ $product->reports_count }} laporan · {{ $product->category->name }}</p>
@forelse($reports as $report)<div class="card border-0 shadow-sm mb-3"><div class="card-body d-flex justify-content-between align-items-center"><div><strong>{{ $report->user->name }}</strong><div class="text-muted small">{{ $report->order->invoice_number }} · {{ $report->created_at->format('d M Y') }}</div><div>{{ \Illuminate\Support\Str::limit($report->description, 110) }}</div></div><a class="btn btn-outline-primary btn-sm" href="{{ route('admin.reports.show', $report) }}">Detail</a></div></div>@empty<p class="text-muted">Belum ada laporan untuk produk ini.</p>@endforelse{{ $reports->links('pagination::bootstrap-5') }}
@endsection
