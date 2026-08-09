@extends('layouts.admin.app')

@section('title', 'Scan QR Pesanan - Tokobii')

@section('content')
<div class="container-fluid px-0">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-secondary">Orders</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Scan QR</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                <div>
                    <h2 class="fw-bold mb-1 text-dark">Scan QR Pesanan</h2>
                    <p class="text-muted mb-0">Arahkan kamera ke QR pada Struk Pengambilan Pesanan.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Kembali ke Orders</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="ratio ratio-1x1 bg-dark rounded overflow-hidden position-relative">
                        <video id="qrScannerVideo" class="w-100 h-100 object-fit-cover" autoplay muted playsinline aria-label="Pratinjau kamera scanner QR"></video>
                        <div class="position-absolute top-50 start-50 translate-middle border border-3 border-white rounded" style="width: 62%; height: 62%; pointer-events: none;"></div>
                    </div>

                    <div id="scannerAlert" class="alert alert-secondary mt-3 mb-0" role="status" aria-live="polite">Menunggu QR Code...</div>

                    <div class="row g-2 mt-2">
                        <div class="col-12 col-sm-7">
                            <label for="cameraSelect" class="form-label fw-semibold">Pilih Kamera</label>
                            <select id="cameraSelect" class="form-select" disabled><option>Memuat kamera...</option></select>
                        </div>
                        <div class="col-12 col-sm-5 d-flex align-items-end">
                            <button id="restartScanner" type="button" class="btn btn-outline-primary w-100">Mulai Ulang Scanner</button>
                        </div>
                    </div>

                    <p class="small text-muted mb-0 mt-3">Izinkan akses kamera saat browser memintanya. Scanner membaca nomor invoice dari QR pickup receipt Tokobii.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.tokobiiOrderScanner = {
            lookupUrl: @json(route('admin.orders.scan.lookup')),
            csrfToken: @json(csrf_token()),
        };
    </script>
    @vite('resources/js/order-scanner.js')
@endpush
