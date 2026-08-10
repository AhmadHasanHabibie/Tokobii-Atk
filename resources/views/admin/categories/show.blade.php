@extends('layouts.admin.app')

@section('title', 'Detail Kategori - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Kategori</a>
            </li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">{{ $category->name }}</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Ikhtisar detail kategori dan status taksonomi.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-tokobii-primary">
                Edit Kategori
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-tokobii-secondary">
                Kembali ke Kategori
            </a>
        </div>
    </div>

    {{-- Category Detail Card --}}
    <div class="tokobii-card">
        <div class="tokobii-card-header">
            <h5 class="fw-bold mb-0 text-slate-900" style="font-size: 1rem;">Informasi Kategori</h5>
        </div>
        <div class="p-4">

            <div class="row g-4 align-items-start">

                {{-- Left Column: Thumbnail & Quick Status --}}
                <div class="col-12 col-md-4 text-center">
                    <div class="p-3 bg-slate-50 rounded-3 border border-slate-200">
                        @if($category->thumbnail)
                            <img src="{{ asset('storage/' . $category->thumbnail) }}" 
                                 alt="Thumbnail {{ $category->name }}" 
                                 class="img-fluid rounded-3 border border-slate-200 mb-3" 
                                 style="max-height: 220px; width: 100%; object-fit: cover;">
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-slate-400 py-5 bg-white rounded-3 border border-slate-200" style="min-height: 180px;">
                                <span class="small text-slate-400">Tidak Ada Gambar</span>
                            </div>
                        @endif

                        <div class="mt-2">
                            <span class="text-slate-400 d-block mb-1" style="font-size: 0.75rem;">Status Publikasi</span>
                            @if($category->status === 'active')
                                <span class="tokobii-badge tokobii-badge-success">Aktif</span>
                            @else
                                <span class="tokobii-badge tokobii-badge-neutral">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Information Details --}}
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold" style="width: 28%;">Nama Kategori</th>
                                    <td class="fw-bold text-slate-900 fs-5">: {{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Slug URL</th>
                                    <td>: <code class="text-slate-700 bg-slate-100 px-2 py-1 rounded font-monospace">{{ $category->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Total Produk</th>
                                    <td class="fw-semibold text-slate-800">: {{ $category->products_count }} Item</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold align-top">Deskripsi</th>
                                    <td class="text-slate-700">: {{ $category->description ?? 'Tidak ada deskripsi.' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Tanggal Dibuat</th>
                                    <td class="text-slate-500">: {{ $category->created_at ? $category->created_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-slate-500 fw-semibold">Terakhir Diperbarui</th>
                                    <td class="text-slate-500">: {{ $category->updated_at ? $category->updated_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
