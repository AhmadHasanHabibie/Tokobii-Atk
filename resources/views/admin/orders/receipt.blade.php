<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pengambilan - {{ $order->invoice_number }} - Tokobii Admin</title>
    
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .receipt-card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 py-4 font-sans text-slate-800">

    <div class="container" style="max-width: 760px;">

        {{-- Top Action Bar (Screen Only) --}}
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Detail Pesanan</span>
            </a>
            <button type="button" onclick="window.print();" class="btn btn-tokobii-primary btn-tokobii-sm">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Struk Sekarang</span>
            </button>
        </div>

        {{-- Standalone Printable Receipt Card --}}
        <div class="tokobii-card receipt-card bg-white p-4 p-md-5">

            {{-- Store Header & Logo --}}
            <div class="text-center border-bottom border-slate-200 pb-4 mb-4">
                <div class="d-inline-flex align-items-center gap-2 mb-1">
                    <span class="d-inline-flex align-items-center justify-content-center bg-blue-600 text-white rounded-2 px-2 py-1 shadow-sm" style="background-color: #2563eb;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </span>
                    <h2 class="fw-bold text-slate-900 mb-0 fs-3">TOKOBII STORE</h2>
                </div>
                <p class="text-slate-500 small mb-2">Pusat Belanja Alat Tulis Kantor & Perlengkapan Sekolah</p>
                <span class="tokobii-badge tokobii-badge-info text-uppercase px-3 py-1">Struk Resmi Pengambilan Barang</span>
            </div>

            {{-- Meta Info Grid --}}
            <div class="row g-3 mb-4 text-slate-600 small">
                <div class="col-6">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">No. Invoice:</span>
                    <strong class="text-blue-600 font-monospace fs-6">{{ $order->invoice_number }}</strong>
                </div>
                <div class="col-6 text-end">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Tanggal Pemesanan:</span>
                    <strong class="text-slate-900">{{ $order->order_date ? $order->order_date->format('d M Y, H:i') : date('d M Y, H:i') }} WIB</strong>
                </div>
                <div class="col-6">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Nama Pelanggan:</span>
                    <strong class="text-slate-900">{{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</strong>
                </div>
                <div class="col-6 text-end">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Metode Pembayaran:</span>
                    <strong class="text-uppercase text-slate-900">{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }})</strong>
                </div>
            </div>

            {{-- Order Items Table --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered border-slate-200 align-middle mb-0 small">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Item Produk</th>
                            <th class="text-center" style="width: 15%;">SKU</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-end" style="width: 18%;">Harga Satuan</th>
                            <th class="text-end" style="width: 20%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($order->items)
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-bold text-slate-900">{{ $item->product_name }}</td>
                                    <td class="text-center font-monospace text-slate-600">{{ $item->product->sku ?? '-' }}</td>
                                    <td class="text-center fw-bold text-slate-900">{{ $item->quantity }}</td>
                                    <td class="text-end font-monospace text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end font-monospace fw-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50">
                            <td colspan="5" class="text-end fw-bold fs-6 text-slate-800">Total Pembayaran:</td>
                            <td class="text-end font-monospace fw-bold text-blue-600 fs-6" style="color: #2563eb;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- QR Code & Mandatory Note --}}
            <div class="d-flex justify-content-between align-items-center border-top border-slate-200 pt-4">
                <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-500 me-3 small" style="max-width: 70%;">
                    <strong>Catatan Kasir:</strong> Pastikan seluruh barang telah dicocokkan sebelum diserahkan kepada pelanggan.
                </div>
                <div class="text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($order->invoice_number) }}" 
                         alt="QR Code Invoice {{ $order->invoice_number }}" 
                         class="border border-slate-200 rounded p-1 shadow-sm mb-1" style="width: 80px; height: 80px;">
                    <span class="d-block text-slate-400 font-monospace" style="font-size: 0.6875rem;">{{ $order->invoice_number }}</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
