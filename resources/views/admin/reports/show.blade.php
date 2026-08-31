@extends('layouts.admin.app')

@section('title', 'Detail Laporan Masalah - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Laporan Masalah</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Detail Laporan</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Detail Tiket Laporan Masalah</h1>
                <p class="text-slate-500 mb-0 small">Tinjau keluhan produk dari pelanggan dan kirimkan tanggapan resmi administrator.</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Report Overview Card --}}
        <div class="col-lg-7">
            <div class="tokobii-card h-100">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1.1rem;">{{ $report->product?->name ?? '-' }}</h5>
                        <span class="tokobii-badge {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 35%;">Pelanggan</th>
                                    <td>: <strong class="text-slate-900">{{ $report->user?->name }}</strong> <span class="text-slate-400 font-monospace">({{ $report->user?->email }})</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">No. Invoice</th>
                                    <td>: <span class="text-blue-600 font-monospace fw-bold">{{ $report->order?->invoice_number }}</span></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Tanggal Pesanan</th>
                                    <td class="text-slate-800">: {{ $report->order?->order_date?->format('d M Y, H:i') ?? '-' }} WIB</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Status Pesanan</th>
                                    <td class="text-slate-800">: 
                                        <span class="tokobii-badge {{ $report->order?->status_badge_class ?? 'tokobii-badge-neutral' }}">
                                            {{ $report->order?->status_label ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">SKU / Kategori</th>
                                    <td class="text-slate-800">: {{ $report->product?->sku ?? '-' }} / {{ $report->product?->category?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Tanggal Laporan</th>
                                    <td class="text-slate-500">: {{ $report->created_at->format('d M Y, H:i') }} WIB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top border-slate-100 pt-3">
                        <h6 class="fw-bold text-slate-900 mb-2" style="font-size: 0.9375rem;">Deskripsi Keluhan Pelanggan:</h6>
                        <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-700" style="font-size: 0.875rem; line-height: 1.6;">
                            {{ $report->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Reply & Actions Card --}}
        <div class="col-lg-5">
            <div class="tokobii-card h-100">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Tanggapan Administrator</h5>
                </div>
                <div class="p-4">

                    {{-- Case 1: Status = pending (Menunggu Tanggapan) --}}
                    @if($report->status === 'pending')
                        <p class="text-slate-500 small mb-3">Tuliskan solusi atau tindak lanjut resmi dari toko untuk menyelesaikan keluhan pelanggan ini.</p>
                        <form action="{{ route('admin.reports.reply', $report) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="admin_reply" class="form-label fw-semibold text-slate-700 small">Tulis Tanggapan: <span class="text-rose-600">*</span></label>
                                <textarea name="admin_reply" id="admin_reply" rows="5" class="form-control tokobii-input w-100 @error('admin_reply') is-invalid @enderror" placeholder="Tuliskan solusi atau instruksi penggantian barang bagi pelanggan..." required>{{ old('admin_reply') }}</textarea>
                                @error('admin_reply')
                                    <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-tokobii-primary w-100 shadow-sm">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>Kirim Tanggapan</span>
                            </button>
                        </form>

                    {{-- Case 2: Status = replied (Sudah Dibalas) --}}
                    @elseif($report->status === 'replied')
                        <div class="p-3 bg-blue-50 bg-opacity-60 rounded-3 border border-blue-200 mb-4 text-slate-800" style="font-size: 0.875rem; line-height: 1.6;">
                            <strong class="text-blue-900 d-block mb-1">Tanggapan Terkirim:</strong>
                            <p class="mb-2 text-slate-700">{{ $report->admin_reply }}</p>
                            <span class="d-block text-slate-400" style="font-size: 0.75rem;">
                                Dibalas oleh {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at?->format('d M Y, H:i') }} WIB
                            </span>
                        </div>

                        <p class="text-slate-500 small mb-3">Jika kendala pelanggan telah tuntas atau barang pengganti telah diserahkan, tandai laporan ini sebagai selesai.</p>
                        
                        <form action="{{ route('admin.reports.resolve', $report) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-tokobii-primary w-100 shadow-sm">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Selesaikan Laporan</span>
                            </button>
                        </form>

                    {{-- Case 3: Status = resolved (Selesai) --}}
                    @elseif($report->status === 'resolved')
                        <div class="p-3 bg-emerald-50 bg-opacity-60 rounded-3 border border-emerald-200 text-slate-800" style="font-size: 0.875rem; line-height: 1.6;">
                            <div class="d-flex align-items-center gap-1.5 mb-2 text-emerald-800 fw-bold">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Laporan Telah Diselesaikan</span>
                            </div>
                            <p class="mb-2 text-slate-700">{{ $report->admin_reply }}</p>
                            <span class="d-block text-slate-400" style="font-size: 0.75rem;">
                                Ditangani oleh {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at?->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
