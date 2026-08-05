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
<body class="bg-light py-4">

    <div class="container" style="max-width: 780px;">

        {{-- Top Action Bar (Screen Only) --}}
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                ← Kembali ke Detail Order
            </a>
            <button type="button" onclick="window.print();" class="btn btn-primary btn-sm fw-bold shadow-sm">
                🖨 Cetak Struk Sekarang
            </button>
        </div>

        {{-- Standalone Printable Receipt Card --}}
        <div class="card border-0 shadow-lg rounded-3 receipt-card bg-white">
            <div class="card-body p-4 p-md-5">

                {{-- Store Header & Logo --}}
                <div class="text-center border-bottom pb-4 mb-4">
                    <h2 class="fw-bold text-dark mb-1">TOKOBII STORE</h2>
                    <p class="text-muted small mb-1">Pusat Alat Tulis & Perlengkapan Kantor Resmi</p>
                    <span class="badge bg-primary px-3 py-1 text-uppercase">Struk Pengambilan Pesanan (Pickup Receipt)</span>
                </div>

                {{-- Meta Info Grid --}}
                <div class="row g-3 mb-4 small">
                    <div class="col-6">
                        <span class="text-secondary d-block">No. Invoice / Receipt:</span>
                        <strong class="text-primary font-monospace fs-6">{{ $order->invoice_number }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-secondary d-block">Tanggal Order:</span>
                        <strong class="text-dark">{{ $order->order_date ? $order->order_date->format('d M Y, H:i') : date('d M Y, H:i') }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-secondary d-block">Nama Customer:</span>
                        <strong class="text-dark">{{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-secondary d-block">Metode Pembayaran:</span>
                        <strong class="text-uppercase text-dark">{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }})</strong>
                    </div>
                </div>

                {{-- Order Items Table --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama Produk</th>
                                <th class="text-center" style="width: 15%;">SKU</th>
                                <th class="text-center" style="width: 10%;">Qty</th>
                                <th class="text-end" style="width: 18%;">Harga</th>
                                <th class="text-end" style="width: 20%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($order->items)
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-dark">{{ $item->product_name }}</td>
                                        <td class="text-center font-monospace">{{ $item->product->sku ?? '-' }}</td>
                                        <td class="text-center fw-bold">{{ $item->qty }}</td>
                                        <td class="text-end font-monospace">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end font-monospace fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold fs-6">Grand Total Pembayaran:</td>
                                <td class="text-end font-monospace fw-bold text-primary fs-6">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- QR Code & Mandatory Note --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                    <div class="alert alert-light border border-secondary-subtle p-3 mb-0 small text-muted me-3" style="max-width: 70%;">
                        💬 <strong>Catatan:</strong> "Bawa resi ini saat mengambil pesanan."
                    </div>
                    <div class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($order->invoice_number) }}" 
                             alt="QR Code Invoice {{ $order->invoice_number }}" 
                             class="border rounded p-1 shadow-sm mb-1" style="width: 90px; height: 90px;">
                        <small class="d-block text-muted font-monospace" style="font-size: 0.7rem;">{{ $order->invoice_number }}</small>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
