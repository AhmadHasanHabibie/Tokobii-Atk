@extends('layouts.customer.app')
@section('title', 'Laporkan Produk - Tokobii')
@section('content')
<div class="container-fluid px-0"><div class="row justify-content-center"><div class="col-lg-8">
<a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm mb-3">Kembali ke Pesanan</a>
<div class="card border-0 shadow-sm"><div class="card-body p-4"><h3 class="fw-bold">Laporkan Produk</h3><p class="text-muted">Sampaikan masalah produk yang Anda beli.</p>
<div class="bg-light border rounded p-3 mb-4"><div class="fw-bold">{{ $item->product_name }}</div><small class="text-muted d-block">SKU: {{ $item->product?->sku ?? '-' }} · Kategori: {{ $item->product?->category?->name ?? '-' }}</small><small class="text-muted">Invoice: {{ $order->invoice_number }} · Dibeli: {{ $order->order_date?->format('d M Y') }}</small></div>
<form method="POST" action="{{ route('customer.orders.reports.store', [$order, $item]) }}">@csrf
<div class="mb-3"><label class="form-label fw-semibold">Jenis Laporan</label><select name="report_type" class="form-select @error('report_type') is-invalid @enderror" required><option value="">Pilih jenis masalah</option><option value="damaged" @selected(old('report_type') === 'damaged')>Produk rusak</option><option value="not_as_described" @selected(old('report_type') === 'not_as_described')>Produk tidak sesuai</option><option value="missing" @selected(old('report_type') === 'missing')>Produk kurang</option><option value="wrong_item" @selected(old('report_type') === 'wrong_item')>Produk salah</option><option value="other" @selected(old('report_type') === 'other')>Masalah lainnya</option></select>@error('report_type')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label for="description" class="form-label fw-semibold">Deskripsi Masalah</label><textarea id="description" name="description" rows="6" maxlength="2000" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><button class="btn btn-danger">Kirim Laporan</button></form>
</div></div></div></div></div>
@endsection
