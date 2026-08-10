@extends('layouts.admin.app')

@section('title', 'Scan QR Order - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Orders</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Scan QR</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                <div>
                    <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Scan QR Order</h1>
                    <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Point your device camera at the customer's pickup QR code.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-tokobii-secondary">Back to Orders</a>
            </div>

            <div class="tokobii-card">
                <div class="p-4">
                    <div class="ratio ratio-1x1 bg-slate-900 rounded-3 overflow-hidden position-relative border border-slate-200">
                        <video id="qrScannerVideo" class="w-100 h-100 object-fit-cover" autoplay muted playsinline aria-label="Camera preview"></video>
                        <div class="position-absolute top-50 start-50 translate-middle border border-3 border-white rounded-3 shadow" style="width: 60%; height: 60%; pointer-events: none; opacity: 0.85;"></div>
                    </div>

                    <div id="scannerAlert" class="alert border-0 bg-slate-100 text-slate-700 rounded-3 mt-3 mb-0" role="status" aria-live="polite" style="font-size: 0.875rem;">Waiting for QR Code...</div>

                    <div class="row g-2 mt-3">
                        <div class="col-12 col-sm-7">
                            <label for="cameraSelect" class="form-label">Select Camera</label>
                            <select id="cameraSelect" class="tokobii-select w-100" disabled><option>Loading camera...</option></select>
                        </div>
                        <div class="col-12 col-sm-5 d-flex align-items-end">
                            <button id="restartScanner" type="button" class="btn btn-tokobii-primary w-100">Restart Scanner</button>
                        </div>
                    </div>

                    <p class="text-slate-400 mb-0 mt-3" style="font-size: 0.75rem;">Please allow camera permission when requested by your browser. Scanner will verify invoice numbers from Tokobii pickup receipts.</p>
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
