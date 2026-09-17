<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pengambilan - {{ $order->invoice_number }} - Tokobii</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo_Tokobiie.jpeg') }}">

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

    <div class="container" style="max-width: 720px;">

        {{-- Top Action Bar --}}
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-tokobii-secondary btn-tokobii-sm">
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

        {{-- Printable Receipt Card --}}
        <div class="tokobii-card p-4 p-md-5 receipt-card bg-white">

            {{-- Header Store --}}
            <div class="text-center border-bottom border-slate-200 pb-4 mb-4">
                <div class="d-inline-flex align-items-center justify-content-center mb-2">
                    <img src="{{ asset('images/Logo_Tokobiie.jpeg') }}" alt="Tokobii" class="img-fluid rounded-2" style="height: 44px; width: auto; max-width: 160px; object-fit: contain;">
                </div>
                <p class="text-slate-500 small mb-2">Pusat Belanja Alat Tulis Kantor & Perlengkapan Sekolah</p>
                <span class="tokobii-badge tokobii-badge-info text-uppercase px-3 py-1">Struk Pengambilan Pesanan</span>
            </div>

            {{-- Order Meta Info --}}
            <div class="row g-3 mb-4 small">
                <div class="col-6">
                    <span class="text-slate-400 d-block">Nomor Invoice:</span>
                    <strong class="text-blue-600 font-monospace fs-6">{{ $order->invoice_number }}</strong>
                </div>
                <div class="col-6 text-end">
                    <span class="text-slate-400 d-block">Tanggal Pemesanan:</span>
                    <strong class="text-slate-800">{{ $order->order_date ? $order->order_date->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }} WIB</strong>
                </div>
                <div class="col-6">
                    <span class="text-slate-400 d-block">Nama Pelanggan:</span>
                    <strong class="text-slate-800">{{ $order->user->name ?? Auth::user()->name }}</strong>
                </div>
                <div class="col-6 text-end">
                    <span class="text-slate-400 d-block">Metode Pembayaran:</span>
                    @if($order->payment_method === 'qris')
                        <span class="tokobii-badge tokobii-badge-info">QRIS Tokobii</span>
                    @else
                        <span class="tokobii-badge tokobii-badge-neutral">Tunai di Kasir</span>
                    @endif
                </div>
            </div>

            {{-- Product Items Table --}}
            <div class="table-responsive mb-4">
                <table class="tokobii-table mb-0">
                    <thead>
                        <tr>
                            <th>Item Produk</th>
                            <th class="text-center" style="width: 15%;">Qty</th>
                            <th class="text-end" style="width: 25%;">Harga Satuan</th>
                            <th class="text-end" style="width: 25%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong class="text-slate-900 d-block">{{ $item->product_name }}</strong>
                                </td>
                                <td class="text-center font-monospace">{{ $item->quantity }}</td>
                                <td class="text-end font-monospace text-slate-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-end font-monospace fw-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-top border-slate-200">
                            <td colspan="3" class="text-end fw-bold text-slate-900 py-3">Total Tagihan:</td>
                            <td class="text-end fw-bold text-blue-600 font-monospace fs-5 py-3" style="color: #2563eb;">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pickup QR Code Section --}}
            <div class="p-4 bg-slate-50 rounded-3 border border-slate-200 text-center mb-4">
                <span class="d-block text-slate-500 small mb-2 fw-semibold">Kode QR Pengambilan Pesanan:</span>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($order->invoice_number) }}" 
                     alt="QR Pickup {{ $order->invoice_number }}" 
                     class="img-fluid border border-slate-300 p-2 bg-white rounded-3 shadow-xs mb-2" 
                     style="width: 140px; height: 140px;">
                <div class="fw-bold font-monospace text-slate-800 small">{{ $order->invoice_number }}</div>
            </div>

            {{-- Footer Note --}}
            <div class="text-center text-slate-400 small pt-3 border-top border-slate-100" style="font-size: 0.75rem;">
                <p class="mb-1">Tunjukkan struk ini ke kasir Tokobii saat melakukan pengambilan barang.</p>
                <span>Terima kasih telah berbelanja di Tokobii.</span>
            </div>

        </div>

    </div>

</body>
</html>
