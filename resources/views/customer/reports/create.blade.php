@extends('layouts.customer.app')

@section('title', 'Laporkan Masalah Produk - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.show', $order) }}" class="text-decoration-none text-slate-500 hover-text-blue-600">{{ $order->invoice_number }}</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Laporkan Masalah</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            
            <div class="tokobii-card">
                <div class="tokobii-card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1.05rem;">Laporkan Kendala Produk</h5>
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Kembali ke Pesanan</span>
                    </a>
                </div>
                <div class="p-4 p-md-5">

                    {{-- Product Box --}}
                    <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 mb-4 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-rose-100 text-rose-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #ffe4e6; color: #e11d48;">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h6 class="fw-bold text-slate-900 mb-0" style="font-size: 0.9375rem;">{{ $item->product_name }}</h6>
                            <span class="text-slate-400 small">
                                SKU: {{ $item->product?->sku ?? '-' }} · Kategori: {{ $item->product?->category?->name ?? 'Umum' }} · Invoice: {{ $order->invoice_number }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('customer.orders.reports.store', [$order, $item]) }}">
                        @csrf

                        {{-- Report Type --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-slate-800">Jenis Masalah <span class="text-rose-600">*</span></label>
                            <select name="report_type" class="form-select tokobii-select @error('report_type') is-invalid @enderror" required>
                                <option value="">Pilih jenis kendala yang dialami</option>
                                <option value="damaged" @selected(old('report_type') === 'damaged')>Produk Rusak / Cacat Fisik</option>
                                <option value="not_as_described" @selected(old('report_type') === 'not_as_described')>Produk Tidak Sesuai Deskripsi / Spesifikasi</option>
                                <option value="missing" @selected(old('report_type') === 'missing')>Jumlah Item Produk Kurang</option>
                                <option value="wrong_item" @selected(old('report_type') === 'wrong_item')>Produk Salah / Tertukar dengan Item Lain</option>
                                <option value="other" @selected(old('report_type') === 'other')>Kendala Lainnya</option>
                            </select>
                            @error('report_type')
                                <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-slate-800">Rincian Deskripsi Masalah <span class="text-rose-600">*</span></label>
                            <textarea id="description" name="description" rows="5" maxlength="2000" class="form-control tokobii-input @error('description') is-invalid @enderror" placeholder="Jelaskan secara rinci permasalahan yang terjadi pada produk ini..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-tokobii-danger btn-tokobii-lg w-100 shadow-sm">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span>Kirim Laporan Kendala</span>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
