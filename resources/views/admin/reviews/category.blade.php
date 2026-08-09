@extends('layouts.admin.app')
@section('title', $category->name . ' Reviews - Tokobii')
@section('content')
<a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm mb-3">Kembali</a><h2 class="fw-bold mb-4">{{ $category->name }}</h2><div class="row g-3">@forelse($products as $product)<div class="col-12 col-md-6"><div class="card border-0 shadow-sm"><div class="card-body"><h5 class="fw-bold">{{ $product->name }}</h5><p class="text-muted mb-3">{{ $product->reviews_count ? number_format($product->reviews_avg_rating, 1) . ' / 5 dari ' . $product->reviews_count . ' review' : 'Belum ada rating' }}</p><a href="{{ route('admin.reviews.products.show', $product) }}" class="btn btn-outline-primary">Lihat Reviews</a></div></div></div>@empty<div class="col-12 text-muted">Belum ada produk.</div>@endforelse</div>{{ $products->links('pagination::bootstrap-5') }}
@endsection
