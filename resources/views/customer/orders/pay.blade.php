@extends('layouts.customer.app')

@section('title', 'Lakukan Pembayaran QRIS - ' . $order->invoice_number . ' - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('customer.orders.index') }}" class="text-decoration-none text-secondary">Riwayat Pesanan</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Lakukan Pembayaran</li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>ℹ️ Info:</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>❌ Terjadi Kesalahan:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>❌ Gagal Upload Bukti:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Lakukan Pembayaran QRIS</h2>
            <p class="text-muted mb-0">Pesanan invoice <strong class="text-primary">{{ $order->invoice_number }}</strong> siap dibayar via QRIS Tokobii.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-secondary px-4 fw-semibold shadow-sm">
                Lihat Detail Pesanan
            </a>
        </div>
    </div>

    <div class="row g-4 justify-content-center">

        {{-- Main QRIS Payment Box --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4 text-center">
                    <h5 class="fw-bold mb-0 text-dark">📱 QR Code Pembayaran QRIS Tokobii</h5>
                </div>
                <div class="card-body p-4 text-center">

                    {{-- Invoice & Amount Highlight --}}
                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <span class="text-secondary small d-block mb-1">Nominal Tagihan Pembayaran:</span>
                        <h2 class="fw-bold text-primary font-monospace mb-1">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</h2>
                        <code class="text-muted small">No. Invoice: {{ $order->invoice_number }}</code>
                    </div>

                    {{-- QR Code Display --}}
                    <div class="mb-4">
                        <div class="d-inline-block bg-white p-3 border rounded-3 shadow-sm mb-2">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=TOKOBII-QRIS-{{ urlencode($order->invoice_number) }}" 
                                 alt="QRIS Tokobii Code {{ $order->invoice_number }}" 
                                 class="img-fluid" style="width: 200px; height: 200px;">
                        </div>
                        <span class="d-block text-muted small">NMK: ID1029384756102 - TOKOBII ATK STORE</span>
                    </div>

                    {{-- 4 Step Payment Instructions --}}
                    <div class="text-start bg-light p-4 rounded-3 border mb-4">
                        <h6 class="fw-bold text-dark mb-3">📋 Langkah-Langkah Pembayaran:</h6>
                        <ol class="mb-0 text-secondary small ps-3">
                            <li class="mb-2">Buka aplikasi E-Wallet (GoPay, OVO, Dana, ShopeePay) atau Mobile Banking Anda.</li>
                            <li class="mb-2">Scan kode **QRIS Tokobii** di atas dan masukkan nominal tepat **Rp {{ number_format($order->grand_total, 0, ',', '.') }}**.</li>
                            <li class="mb-2">Selesaikan transaksi dan simpan foto / screenshot bukti transfer pembayaran.</li>
                            <li class="mb-0">Unggah file bukti pembayaran melalui form di bawah ini lalu klik **Kirim Bukti Pembayaran**.</li>
                        </ol>
                    </div>

                    {{-- Upload Proof Form --}}
                    <div class="card border border-primary border-2 rounded-3 bg-white text-start">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-2">📤 Form Unggah Bukti Pembayaran</h6>
                            <p class="text-muted small mb-3">Pilih foto atau file bukti pembayaran Anda (Format: JPG, JPEG, PNG, PDF | Maksimal 2MB).</p>

                            <form action="{{ route('customer.orders.upload-proof', $order) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="proof_of_payment" class="form-label text-secondary small fw-semibold">Pilih File Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" 
                                           name="proof_of_payment" 
                                           id="proof_of_payment" 
                                           class="form-control form-control-lg @error('proof_of_payment') is-invalid @enderror" 
                                           accept=".jpg,.jpeg,.png,.pdf" 
                                           required>
                                    @error('proof_of_payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg fw-bold w-100 shadow-sm py-2.5">
                                    🚀 Kirim Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Order Items Summary Sidebar --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Rincian Item Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-2 text-secondary">Produk</th>
                                    <th class="text-center py-2 text-secondary">Qty</th>
                                    <th class="pe-4 text-end py-2 text-secondary">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <strong class="text-dark d-block">{{ $item->product_name }}</strong>
                                            <span class="text-muted font-monospace">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-center font-monospace fw-bold">{{ $item->qty }}</td>
                                        <td class="pe-4 text-end font-monospace fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="2" class="ps-4 fw-bold">Grand Total:</td>
                                    <td class="pe-4 text-end font-monospace fw-bold text-primary fs-6">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
