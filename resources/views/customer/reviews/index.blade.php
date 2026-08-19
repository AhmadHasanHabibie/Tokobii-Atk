@extends('layouts.customer.app')

@section('title', 'Ulasan Saya - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Ulasan Saya</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Ulasan Produk Saya</h1>
        <p class="text-slate-500 mb-0 small">Riwayat ulasan dan penilaian produk yang telah Anda berikan di Tokobii.</p>
    </div>

    {{-- Reviews List --}}
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($reviews as $review)
            <div class="tokobii-card p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start gap-3 mb-3">
                    <div>
                        <a href="{{ route('customer.shop.show', $review->product->slug) }}" class="h5 fw-bold text-slate-900 text-decoration-none hover-text-blue-600 mb-1 d-inline-block">
                            {{ $review->product->name }}
                        </a>
                        <div class="d-flex align-items-center gap-2 text-slate-400 small">
                            <span>Invoice: <strong class="text-blue-600 font-monospace">{{ $review->order->invoice_number }}</strong></span>
                            <span>·</span>
                            <span>{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Star Rating --}}
                    <div class="d-flex text-amber-500">
                        @for($i = 1; $i <= 5; $i++)
                            <svg width="18" height="18" fill="{{ $i <= $review->rating ? '#f59e0b' : 'none' }}" stroke="#f59e0b" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        @endfor
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-700 small mb-3" style="line-height: 1.6;">
                    {{ $review->comment }}
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('customer.shop.show', $review->product->slug) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>Lihat Produk</span>
                    </a>
                    <a href="{{ route('customer.reviews.edit', $review) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Ulasan</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="tokobii-empty-state">
                <div class="tokobii-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <h5 class="fw-bold text-slate-900 mb-1">Belum Ada Ulasan</h5>
                <p class="text-slate-500 small mb-4">Anda belum memberikan ulasan untuk produk yang telah selesai dibeli.</p>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-tokobii-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Lihat Riwayat Pesanan</span>
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination Card --}}
    @if($reviews->hasPages())
        <div class="tokobii-card p-3 d-flex justify-content-center">
            {{ $reviews->links() }}
        </div>
    @endif

</div>
@endsection
