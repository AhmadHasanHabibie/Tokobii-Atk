@extends('layouts.customer.app')
@section('title', 'My Reports - Tokobii')
@section('content')
<div class="container-fluid px-0"><h2 class="fw-bold mb-1">My Reports</h2><p class="text-muted mb-4">Riwayat laporan produk dan balasan dari Admin.</p>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@php($types = ['damaged' => 'Produk rusak', 'not_as_described' => 'Produk tidak sesuai', 'missing' => 'Produk kurang', 'wrong_item' => 'Produk salah', 'other' => 'Masalah lainnya'])
@forelse($reports as $report)<div class="card border-0 shadow-sm mb-3"><div class="card-body"><div class="d-flex justify-content-between gap-3"><div><h5 class="fw-bold mb-1">{{ $report->product->name }}</h5><small class="text-muted">Kategori: {{ $report->product->category->name }} · Invoice: {{ $report->order->invoice_number }} · {{ $report->created_at->format('d M Y') }}</small></div><span class="badge align-self-start {{ $report->status === 'pending' ? 'bg-warning text-dark' : ($report->status === 'resolved' ? 'bg-success' : 'bg-primary') }}">{{ ucfirst($report->status) }}</span></div><hr><div><strong>Masalah: {{ $types[$report->report_type] ?? $report->report_type }}</strong><p class="mb-0">{{ $report->description }}</p></div>
@if($report->admin_reply)<div class="alert alert-light border mt-3 mb-0"><strong>Balasan Admin</strong><p class="mb-1">{{ $report->admin_reply }}</p><small class="text-muted">Dibalas oleh {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at?->format('d M Y H:i') }}</small></div>@endif
</div></div>@empty<div class="card border-0 shadow-sm"><div class="card-body text-center py-5"><h5>Belum ada laporan.</h5><a href="{{ route('customer.orders.index') }}" class="btn btn-primary">Lihat Pesanan</a></div></div>@endforelse
{{ $reports->links('pagination::bootstrap-5') }}</div>
@endsection
