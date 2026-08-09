@extends('layouts.customer.app')
@section('title', 'Edit Review - Tokobii')
@section('content')
<div class="container-fluid px-0"><div class="row justify-content-center"><div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body p-4"><h3 class="fw-bold">Edit Review</h3><p class="text-muted">{{ $review->product->name }}</p><form method="POST" action="{{ route('customer.reviews.update', $review) }}">@csrf @method('PUT')<div class="mb-3"><label class="form-label">Rating</label><select name="rating" class="form-select" required>@for($i=5;$i>=1;$i--)<option value="{{ $i }}" @selected(old('rating', $review->rating)==$i)>{{ $i }} Bintang</option>@endfor</select></div><div class="mb-3"><label class="form-label">Komentar</label><textarea name="comment" class="form-control" rows="5" required>{{ old('comment', $review->comment) }}</textarea></div><button class="btn btn-primary">Simpan Perubahan</button></form></div></div></div></div></div>
@endsection
