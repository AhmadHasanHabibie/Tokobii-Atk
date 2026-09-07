@extends('layouts.customer.app')

@section('title', 'Laporan Masalah - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Laporan Masalah</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Laporan Masalah Produk</h1>
        <p class="text-slate-500 mb-0 small">Daftar laporan kendala produk pesanan dan balasan resmi dari tim Admin Tokobii.</p>
    </div>

    @php
        $types = [
            'damaged' => 'Produk Rusak / Cacat',
            'not_as_described' => 'Produk Tidak Sesuai Deskripsi',
            'missing' => 'Jumlah Produk Kurang',
            'wrong_item' => 'Produk Salah / Tertukar',
            'other' => 'Kendala Lainnya'
        ];
    @endphp

    {{-- Reports List --}}
    <div class="d-flex flex-column gap-3 mb-4">
        @forelse($reports as $report)
            <div class="tokobii-card p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start gap-3 pb-3 mb-3 border-bottom border-slate-100">
                    <div>
                        <h5 class="fw-bold text-slate-900 mb-1" style="font-size: 1.05rem;">
                            {{ $report->product->name }}
                        </h5>
                        <div class="d-flex align-items-center gap-2 text-slate-400 small flex-wrap">
                            <span>Kategori: <strong class="text-slate-600">{{ $report->product->category->name ?? 'Umum' }}</strong></span>
                            <span>·</span>
                            <span>Invoice: <strong class="text-blue-600 font-monospace">{{ $report->order->invoice_number }}</strong></span>
                            <span>·</span>
                            <span>{{ $report->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        <span class="tokobii-badge {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                    </div>
                </div>

                {{-- Problem Description --}}
                <div class="mb-3">
                    <span class="tokobii-badge tokobii-badge-neutral mb-2">
                        {{ $types[$report->report_type] ?? $report->report_type }}
                    </span>
                    <p class="text-slate-700 small mb-0" style="line-height: 1.6; white-space: pre-line;">
                        {{ $report->description }}
                    </p>
                </div>

                {{-- Admin Reply Box --}}
                @if($report->admin_reply)
                    <div class="p-3 bg-blue-50 bg-opacity-60 rounded-3 border border-blue-200 mt-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            <strong class="text-blue-900 small">Tanggapan Admin Tokobii:</strong>
                        </div>
                        <p class="text-slate-800 small mb-2" style="line-height: 1.6;">
                            {{ $report->admin_reply }}
                        </p>
                        <span class="text-slate-400 font-monospace" style="font-size: 0.7rem;">
                            Dibalas oleh {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at ? $report->replied_at->format('d M Y, H:i') : '' }} WIB
                        </span>
                    </div>
                @endif

                {{-- Customer Reply & Resolve Section --}}
                @if($report->status === 'menunggu_balasan_customer' || $report->status === 'replied')
                    <div class="mt-4 pt-3 border-top border-slate-100">
                        {{-- Reply Form --}}
                        <form method="POST" action="{{ route('customer.reports.reply', $report) }}" class="mb-3">
                            @csrf
                            <div class="mb-2.5">
                                <label for="reply_{{ $report->id }}" class="form-label fw-semibold text-slate-700 small mb-1.5 d-flex align-items-center gap-1.5">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    <span>Balas Tanggapan Admin</span>
                                </label>
                                <textarea
                                    id="reply_{{ $report->id }}"
                                    name="reply"
                                    rows="3"
                                    required
                                    placeholder="Tulis tanggapan atau informasi tambahan untuk Admin..."
                                    class="form-control tokobii-input w-100 @error('reply') is-invalid @enderror"
                                    style="font-size: 0.875rem;"
                                >{{ old('reply') }}</textarea>
                                @error('reply')
                                    <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm shadow-sm d-inline-flex align-items-center gap-1.5">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>Kirim Balasan</span>
                            </button>
                        </form>

                        {{-- Resolve Action Box --}}
                        <div class="p-3 bg-emerald-50 rounded-3 border border-emerald-200 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2.5">
                            <div class="small text-emerald-900">
                                <strong class="d-block">Kendala Anda sudah terselesaikan?</strong>
                                <span class="text-emerald-700">Klik tombol di samping jika kendala telah beres dan ingin menutup laporan ini.</span>
                            </div>
                            <form method="POST" action="{{ route('customer.reports.resolve', $report) }}" onsubmit="return confirm('Apakah Anda yakin kendala ini sudah tuntas dan ingin menyelesaikan laporan?')">
                                @csrf
                                <button type="submit" class="btn btn-tokobii-success btn-tokobii-sm text-nowrap d-inline-flex align-items-center gap-1.5">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Selesaikan Laporan</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="tokobii-empty-state">
                <div class="tokobii-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h5 class="fw-bold text-slate-900 mb-1">Belum Ada Laporan Masalah</h5>
                <p class="text-slate-500 small mb-4">Jika Anda menemukan kendala dengan produk yang diterima, Anda dapat melaporkannya melalui halaman detail pesanan.</p>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-tokobii-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Buka Riwayat Pesanan</span>
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination Card --}}
    @if($reports->hasPages())
        <div class="tokobii-card p-3 d-flex justify-content-center">
            {{ $reports->links() }}
        </div>
    @endif

</div>
@endsection
