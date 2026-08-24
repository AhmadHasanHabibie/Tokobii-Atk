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

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4">{{ session('warning') }}</div>
    @endif

    <div class="row g-4">
        {{-- Report Overview Card --}}
        <div class="col-lg-7">
            <div class="tokobii-card h-100">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1.1rem;">{{ $report->product?->name ?? '-' }}</h5>
                        @if($report->status === 'pending')
                            <span class="tokobii-badge tokobii-badge-warning">Menunggu Tanggapan</span>
                        @elseif($report->status === 'resolved')
                            <span class="tokobii-badge tokobii-badge-success">Selesai Ditangani</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-info">Sudah Dibalas</span>
                        @endif
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

        {{-- Admin Reply Form Card --}}
        <div class="col-lg-5">
            <div class="tokobii-card h-100">
                <div class="tokobii-card-header">
                    <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Tanggapan Administrator</h5>
                </div>
                <div class="p-4">
                    @if($report->admin_reply)
                        <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 mb-3 text-slate-700" style="font-size: 0.875rem; line-height: 1.6;">
                            {{ $report->admin_reply }}
                            <span class="d-block text-slate-400 mt-2" style="font-size: 0.75rem;">Dibalas oleh {{ $report->repliedBy?->name ?? 'Administrator' }} · {{ $report->replied_at?->format('d M Y, H:i') }} WIB</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.reports.reply', $report) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_reply" class="form-label fw-semibold text-slate-700 small">{{ $report->admin_reply ? 'Perbarui Tanggapan:' : 'Tulis Tanggapan:' }}</label>
                            <textarea name="admin_reply" id="admin_reply" rows="5" class="form-control tokobii-input w-100 @error('admin_reply') is-invalid @enderror" placeholder="Tuliskan solusi atau instruksi penggantian barang bagi pelanggan..." required>{{ old('admin_reply', $report->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <div class="invalid-feedback d-block text-rose-600 small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold text-slate-700 small">Status Laporan:</label>
                            <select name="status" id="status" class="form-select tokobii-select w-100" required>
                                <option value="pending" @selected(old('status', $report->status) === 'pending')>Menunggu Tanggapan (Pending)</option>
                                <option value="replied" @selected(old('status', $report->status) === 'replied')>Sudah Dibalas (Replied)</option>
                                <option value="resolved" @selected(old('status', $report->status) === 'resolved')>Selesai Ditangani (Resolved)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-tokobii-primary w-100 shadow-sm">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Kirim Tanggapan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
