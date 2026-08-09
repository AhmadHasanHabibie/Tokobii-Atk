@extends('layouts.admin.app')
@section('title', 'Reviews - Tokobii')
@section('content')
<h2 class="fw-bold mb-1">Reviews Produk</h2><p class="text-muted mb-4">Pilih kategori untuk melihat review produk.</p><div class="row g-3">@forelse($categories as $category)<div class="col-12 col-md-6 col-lg-4"><a class="card border-0 shadow-sm text-decoration-none text-dark h-100" href="{{ route('admin.reviews.categories.show', $category) }}"><div class="card-body"><h5 class="fw-bold mb-1">{{ $category->name }}</h5><small class="text-muted">{{ $category->products_count }} produk</small></div></a></div>@empty<div class="col-12 text-muted">Belum ada kategori.</div>@endforelse</div>
@endsection
