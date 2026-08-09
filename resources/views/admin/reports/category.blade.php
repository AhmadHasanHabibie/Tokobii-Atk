@extends('layouts.admin.app')
@section('title', $category->name . ' Reports - Tokobii')
@section('content')
<a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm mb-3">Kembali ke Reports</a><h2 class="fw-bold">{{ $category->name }}</h2><p class="text-muted mb-4">Pilih produk untuk melihat laporan.</p><div class="row g-3">@forelse($products as $product)<div class="col-md-6"><div class="card border-0 shadow-sm"><div class="card-body"><h5 class="fw-bold">{{ $product->name }}</h5><p class="text-muted mb-3">{{ $product->reports_count }} laporan</p><a href="{{ route('admin.reports.products.show', $product) }}" class="btn btn-outline-primary">Lihat Reports</a></div></div></div>@empty<div class="col-12 text-muted">Belum ada produk.</div>@endforelse</div>{{ $products->links('pagination::bootstrap-5') }}
@endsection
