<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Receipt - {{ $order->invoice_number }} - Tokobii Admin</title>
    
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
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-tokobii-secondary">
                ← Back to Order Details
            </a>
            <button type="button" onclick="window.print();" class="btn btn-tokobii-primary">
                🖨 Print Receipt
            </button>
        </div>

        {{-- Standalone Printable Receipt Card --}}
        <div class="tokobii-card receipt-card bg-white p-4 p-md-5">

            {{-- Store Header & Logo --}}
            <div class="text-center border-bottom border-slate-200 pb-4 mb-4">
                <h2 class="fw-bold text-slate-900 mb-1" style="letter-spacing: -0.02em;">TOKOBII STORE</h2>
                <p class="text-slate-500 small mb-2">Official Stationery & Office Supplies Catalog</p>
                <span class="tokobii-badge tokobii-badge-info text-uppercase px-3 py-1">Order Pickup Receipt</span>
            </div>

            {{-- Meta Info Grid --}}
            <div class="row g-3 mb-4 text-slate-600" style="font-size: 0.875rem;">
                <div class="col-6">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Invoice / Receipt No:</span>
                    <strong class="text-blue-600 font-monospace fs-6">{{ $order->invoice_number }}</strong>
                </div>
                <div class="col-6 text-end">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Order Date:</span>
                    <strong class="text-slate-900">{{ $order->order_date ? $order->order_date->format('d M Y, H:i') : date('d M Y, H:i') }}</strong>
                </div>
                <div class="col-6">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Customer Name:</span>
                    <strong class="text-slate-900">{{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</strong>
                </div>
                <div class="col-6 text-end">
                    <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Payment Method:</span>
                    <strong class="text-uppercase text-slate-900">{{ $order->payment_method }} ({{ ucfirst($order->payment_status) }})</strong>
                </div>
            </div>

            {{-- Order Items Table --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered border-slate-200 align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Product Item</th>
                            <th class="text-center" style="width: 15%;">SKU</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-end" style="width: 18%;">Price</th>
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
                                    <td class="text-center fw-bold text-slate-900">{{ $item->qty }}</td>
                                    <td class="text-end font-monospace text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end font-monospace fw-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50">
                            <td colspan="5" class="text-end fw-bold fs-6 text-slate-800">Grand Total:</td>
                            <td class="text-end font-monospace fw-bold text-blue-600 fs-6">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- QR Code & Mandatory Note --}}
            <div class="d-flex justify-content-between align-items-center border-top border-slate-200 pt-4">
                <div class="p-3 bg-slate-50 rounded-3 border border-slate-200 text-slate-500 me-3" style="max-width: 70%; font-size: 0.8125rem;">
                    <strong>Note:</strong> Present this receipt or scan code when picking up your order at the Tokobii counter.
                </div>
                <div class="text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($order->invoice_number) }}" 
                         alt="QR Code Invoice {{ $order->invoice_number }}" 
                         class="border border-slate-200 rounded p-1 shadow-sm mb-1" style="width: 90px; height: 90px;">
                    <span class="d-block text-slate-400 font-monospace" style="font-size: 0.6875rem;">{{ $order->invoice_number }}</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
