@extends('layouts.customer.app')
@section('title', 'Review Produk - Tokobii')
@section('content')
<div class="container-fluid px-0"><div class="row justify-content-center"><div class="col-12 col-lg-7">
<a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm mb-3">Kembali ke Pesanan</a>
<div class="card border-0 shadow-sm"><div class="card-body p-4"><h3 class="fw-bold mb-1">Review Produk</h3><p class="text-muted">{{ $item->product_name }} dari {{ $order->invoice_number }}</p>
<form method="POST" action="{{ route('customer.orders.reviews.store', [$order, $item]) }}">@csrf
<div class="mb-3"><label class="form-label fw-semibold">Rating</label><select name="rating" class="form-select @error('rating') is-invalid @enderror" required><option value="">Pilih rating</option>@for($i = 5; $i >= 1; $i--)<option value="{{ $i }}" @selected(old('rating') == $i)>{{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }})</option>@endfor</select>@error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label fw-semibold" for="comment">Alasan / komentar</label><textarea id="comment" name="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" required>{{ old('comment') }}</textarea>@error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-primary">Kirim Review</button></form></div></div>
</div></div></div>
@endsection
