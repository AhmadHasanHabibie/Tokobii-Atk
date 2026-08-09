@extends('layouts.admin.app')

@section('title', 'Detail Order & Pickup - Tokobii')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden !important;
        }
        #printableReceiptArea, #printableReceiptArea * {
            visibility: visible !important;
        }
        #printableReceiptArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            padding: 20px !important;
            background: #fff !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3 d-print-none">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-secondary">Order Management</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Detail Order</li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm d-print-none" role="alert">
            <strong>✅ Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm d-print-none" role="alert">
            <strong>⚠️ Perhatian!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm d-print-none" role="alert">
            <strong>❌ Terjadi Kesalahan!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm d-print-none" role="alert">
            <strong>❌ Gagal Proses:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header & Actions --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 d-print-none">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Detail Order: <span class="text-primary">{{ $order->invoice_number }}</span></h2>
            <p class="text-muted mb-0">Pusat verifikasi pembayaran, rincian produk, dan alur pengambilan pesanan.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2 align-items-center">
            
            {{-- Workflow Action Buttons --}}
            @if($order->payment_method === 'cash' && $order->order_status === 'pending' && $order->payment_status === 'pending')
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="start_processing">
                    <button type="submit" class="btn btn-info fw-semibold shadow-sm px-3">Mulai Diproses</button>
                </form>
            @elseif($order->payment_status === 'waiting_verification')
                <button type="button" class="btn btn-success fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#approvePaymentModal" title="Setujui Pembayaran" aria-label="Setujui Pembayaran">
                    ✅ Approve Payment
                </button>
                <button type="button" class="btn btn-danger fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal" title="Tolak Pembayaran" aria-label="Tolak Pembayaran">
                    ❌ Reject Payment
                </button>
            @elseif($order->order_status === 'processing')
                <button type="button" class="btn btn-primary fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#readyForPickupModal" title="Tandai Siap Diambil">
                    📦 Ready for Pickup
                </button>
            @elseif($order->order_status === 'ready_for_pickup' && $order->payment_method === 'cash' && $order->payment_status === 'pending')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary fw-semibold shadow-sm px-3">Print Pickup Receipt</a>
                <button type="button" class="btn btn-success fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#cashPaymentModal">Konfirmasi Pembayaran Tunai</button>
            @elseif($order->order_status === 'ready_for_pickup')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary fw-semibold shadow-sm px-3" title="Buka Halaman Cetak Struk Pengambilan">
                    🖨 Print Pickup Receipt
                </a>
                <button type="button" class="btn btn-dark fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#completeOrderModal" title="Tandai Selesai (Completed)">
                    ✅ Completed
                </button>
            @elseif($order->order_status === 'completed')
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary fw-semibold shadow-sm px-3" title="Buka Halaman Cetak Struk Pengambilan">
                    🖨 Print Pickup Receipt
                </a>
            @endif

            <button type="button" onclick="window.location.reload();" class="btn btn-outline-secondary px-3 fw-semibold shadow-sm" title="Refresh Halaman" aria-label="Refresh Halaman">
                🔄 Refresh
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary px-4 fw-semibold shadow-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column: CARD 1 (Order Info), CARD 2 (Customer Info), CARD 4 (Payment Verification Info), CARD 5 (Pickup Info), CARD 6 (Timeline) --}}
        <div class="col-12 col-md-5">
            
            {{-- CARD 1: Order Information --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">📋 Order Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 40%;">Invoice</th>
                                    <td>: <code class="text-primary bg-primary-subtle px-2 py-1 rounded fw-bold">{{ $order->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Tanggal</th>
                                    <td class="text-dark">: {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Status</th>
                                    <td>: 
                                        @if($order->order_status === 'completed')
                                            <span class="badge bg-dark px-2.5 py-1 fw-normal">🏁 Completed</span>
                                        @elseif($order->order_status === 'ready_for_pickup')
                                            <span class="badge bg-primary px-2.5 py-1 fw-normal">📦 Ready for Pickup</span>
                                        @elseif($order->payment_status === 'paid')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-normal">Paid</span>
                                        @elseif($order->payment_status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-normal">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-normal">Waiting Verification</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CARD 2: Customer Information --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">👤 Customer Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 35%;">Nama</th>
                                    <td class="text-dark fw-bold">: {{ $order->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Username</th>
                                    <td class="text-dark">: {{ $order->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Email</th>
                                    <td class="text-dark">: {{ $order->user->email ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CARD 4: Payment Verification & Proof --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">💳 Payment Verification</h5>
                </div>
                <div class="card-body p-4">
                    @if($order->payment_method === 'cash')
                        <table class="table table-borderless align-middle mb-3 small"><tbody>
                            <tr><th class="ps-0 text-secondary" style="width:42%">Total Pesanan</th><td>: <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong></td></tr>
                            <tr><th class="ps-0 text-secondary">Status Pembayaran</th><td>: <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $order->payment_status === 'paid' ? 'Paid' : 'Menunggu Pembayaran Tunai' }}</span></td></tr>
                            @if($order->payment?->received_amount !== null)
                                <tr><th class="ps-0 text-secondary">Uang Diterima</th><td>: Rp {{ number_format($order->payment->received_amount, 0, ',', '.') }}</td></tr>
                                <tr><th class="ps-0 text-secondary">Kembalian</th><td>: Rp {{ number_format($order->payment->change_amount, 0, ',', '.') }}</td></tr>
                                <tr><th class="ps-0 text-secondary">Diterima Oleh</th><td>: {{ $order->payment->verifiedByAdmin?->name ?? '-' }}</td></tr>
                            @endif
                        </tbody></table>
                        <p class="text-muted small mb-0">Pembayaran tunai diterima di kasir saat customer mengambil pesanan; tidak ada upload atau verifikasi bukti.</p>
                    @else
                    <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 42%;">Payment Method</th>
                                    <td>: 
                                        @if($order->payment_method === 'qris')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-normal">📱 QRIS</span>
                                        @else
                                            <span class="badge bg-light text-dark border border-dark-subtle px-2.5 py-1 fw-normal">💵 Cash</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Payment Status</th>
                                    <td>: 
                                        @if($order->payment_status === 'paid' || in_array($order->order_status, ['ready_for_pickup', 'completed']))
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-normal">Paid</span>
                                        @elseif($order->payment_status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-normal">Rejected</span>
                                        @elseif($order->payment_status === 'waiting_verification')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1 fw-normal">Waiting Verification</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-normal">Waiting Payment</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Upload Time</th>
                                    <td class="text-muted">: {{ $order->payment && $order->payment->payment_date ? $order->payment->payment_date->format('d M Y, H:i:s') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Verification Time</th>
                                    <td class="text-muted">: {{ $order->payment && $order->payment->verified_at ? $order->payment->verified_at->format('d M Y, H:i:s') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Verified By</th>
                                    <td class="text-dark">: {{ $order->payment && $order->payment->verifiedByAdmin ? $order->payment->verifiedByAdmin->name : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Transfer Proof Preview (QRIS) --}}
                    @if($order->payment_method === 'qris')
                        <div class="border-top pt-3 text-center">
                            <span class="text-secondary small fw-semibold d-block mb-2">Proof of Payment (Bukti Transfer):</span>
                            @if($order->payment && $order->payment->proof_of_payment)
                                @php
                                    $proofPath = $order->payment->proof_of_payment;
                                    $isPdf = \Illuminate\Support\Str::endsWith(strtolower($proofPath), '.pdf');
                                @endphp

                                @if($isPdf)
                                    <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold w-100 py-2">
                                        📄 Buka / Download PDF Bukti Pembayaran
                                    </a>
                                @else
                                    <button type="button" class="btn p-0 border-0 shadow-sm rounded overflow-hidden" data-bs-toggle="modal" data-bs-target="#proofModal">
                                        <img src="{{ asset('storage/' . $proofPath) }}" 
                                             alt="Bukti Transfer QRIS {{ $order->invoice_number }}" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 180px; object-fit: contain;">
                                    </button>
                                    <small class="text-muted d-block mt-1">(Klik gambar untuk memperbesar)</small>
                                @endif
                            @else
                                <div class="p-3 bg-light rounded text-muted small">
                                    Customer belum mengunggah bukti pembayaran QRIS.
                                </div>
                            @endif
                        </div>
                    @endif
                    @endif
                </div>
            </div>

            {{-- CARD 5: Pickup Information --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-primary border-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">📦 Pickup Information</h5>
                    <span class="badge bg-light text-secondary border">Store Main Hall</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 40%;">Pickup Status</th>
                                    <td>: 
                                        @if($order->order_status === 'completed')
                                            <span class="badge bg-dark px-2.5 py-1 fw-normal">Completed (Sudah Diambil)</span>
                                        @elseif($order->order_status === 'ready_for_pickup')
                                            <span class="badge bg-primary px-2.5 py-1 fw-normal">Ready for Pickup</span>
                                        @elseif($order->payment_status === 'paid')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1 fw-normal">Sedang Disiapkan</span>
                                        @else
                                            <span class="badge bg-light text-secondary border px-2.5 py-1 fw-normal">Waiting Verification</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Pickup Receipt</th>
                                    <td>: <code class="text-primary bg-primary-subtle px-2 py-1 rounded fw-bold">{{ $order->invoice_number }}</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if(in_array($order->order_status, ['ready_for_pickup', 'completed']) || in_array($order->payment_status, ['paid', 'completed']))
                        <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                            🖨 Buka Halaman Cetak Pickup Receipt
                        </a>
                    @endif
                </div>
            </div>

            {{-- CARD 6: Timeline --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">⏳ Timeline</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="timeline list-unstyled mb-0 position-relative">
                        <li class="mb-3 d-flex align-items-start">
                            <span class="badge bg-success rounded-circle p-2 me-3">✓</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Order Created</h6>
                                <small class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</small>
                            </div>
                        </li>
                        @if($order->payment_method === 'cash')
                            <li class="mb-3 d-flex align-items-start {{ in_array($order->order_status, ['processing', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}"><span class="badge {{ in_array($order->order_status, ['processing', 'ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">📦</span><div><h6 class="fw-bold mb-0 text-dark">Processing</h6><small class="text-muted">Pesanan sedang dikemas</small></div></li>
                            <li class="mb-3 d-flex align-items-start {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}"><span class="badge {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">📦</span><div><h6 class="fw-bold mb-0 text-dark">Ready for Pickup</h6><small class="text-muted">Pesanan siap diambil customer</small></div></li>
                            <li class="mb-3 d-flex align-items-start {{ in_array($order->payment_status, ['waiting_verification', 'paid']) || $order->order_status === 'completed' ? '' : 'opacity-50' }}"><span class="badge {{ in_array($order->payment_status, ['waiting_verification', 'paid']) || $order->order_status === 'completed' ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">💵</span><div><h6 class="fw-bold mb-0 text-dark">Waiting Verification</h6><small class="text-muted">Customer hadir dan pembayaran tunai diproses kasir</small></div></li>
                            <li class="mb-3 d-flex align-items-start {{ $order->payment_status === 'paid' ? '' : 'opacity-50' }}"><span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">💵</span><div><h6 class="fw-bold mb-0 text-dark">Paid</h6><small class="text-muted">Pembayaran tunai diterima kasir</small></div></li>
                        @else
                        <li class="mb-3 d-flex align-items-start {{ $order->payment_status !== 'pending' ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($order->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($order->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected']) ? '✓' : '🟡' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Waiting Verification</h6>
                                <small class="text-muted">Verifikasi Pembayaran Tokobii</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start {{ in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '✓' : '🟢' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Paid</h6>
                                <small class="text-muted">Pembayaran Dikonfirmasi Lunas</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? '✓' : '📦' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Ready for Pickup</h6>
                                <small class="text-muted">Pesanan Siap Diambil di toko</small>
                            </div>
                        </li>
                        @endif
                        @if($order->payment_status === 'rejected' || $order->order_status === 'cancelled')
                            <li class="d-flex align-items-start">
                                <span class="badge bg-danger rounded-circle p-2 me-3">🔴</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-danger">Rejected</h6>
                                    <small class="text-muted">Pembayaran ini telah ditolak.</small>
                                </div>
                            </li>
                        @else
                            <li class="d-flex align-items-start {{ $order->order_status === 'completed' ? '' : 'opacity-50' }}">
                                <span class="badge {{ $order->order_status === 'completed' ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                    {{ $order->order_status === 'completed' ? '✅' : '✅' }}
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Completed</h6>
                                    <small class="text-muted">Pesanan Selesai diserahkan</small>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>

        {{-- Right Column: CARD 3 (Order Items) & Grand Total Summary --}}
        <div class="col-12 col-md-7">
            
            {{-- CARD 3: Order Items Table Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">🛒 Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 5%;">No</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 15%;">Thumbnail</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase">Nama Produk</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 10%;">Qty</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-end" style="width: 18%;">Harga</th>
                                    <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 20%;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                     alt="Thumbnail {{ $item->product_name }}" 
                                                     class="rounded shadow-sm border" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border" style="width: 50px; height: 50px;">
                                                    <span class="fs-4">📦</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $item->product_name }}</span>
                                            @if($item->product && $item->product->sku)
                                                <small class="text-muted font-monospace">SKU: {{ $item->product->sku }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold text-dark font-monospace">
                                            {{ $item->qty }}
                                        </td>
                                        <td class="text-end text-dark font-monospace">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="pe-4 text-end fw-bold text-dark font-monospace">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Tidak ada item pada pesanan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Rincian Ringkasan Tagihan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-secondary fw-normal">Subtotal Produk:</th>
                                            <td class="text-end font-monospace text-dark fw-semibold">: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-secondary fw-normal">Ongkos Kirim (Pickup):</th>
                                            <td class="text-end font-monospace text-success fw-semibold">: Rp 0</td>
                                        </tr>
                                        <tr class="border-top fs-5">
                                            <th class="ps-0 text-dark fw-bold pt-3">Grand Total:</th>
                                            <td class="text-end font-monospace text-primary fw-bold pt-3">: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- Modal Image Preview --}}
@if($order->payment && $order->payment->proof_of_payment)
    <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="proofModalLabel">📱 Bukti Pembayaran QRIS {{ $order->invoice_number }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 text-center bg-light">
                    <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}" 
                         alt="Bukti Transfer {{ $order->invoice_number }}" 
                         class="img-fluid rounded shadow border" 
                         style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer bg-light">
                    <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold">
                        🔗 Buka Tab Baru
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Bootstrap Confirmation Modals --}}

@if($order->payment_method === 'cash' && $order->order_status === 'ready_for_pickup' && $order->payment_status === 'pending')
    <div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white"><h5 class="modal-title fw-bold">Konfirmasi Pembayaran Tunai</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="action" value="confirm_cash_payment">
                <div class="modal-body p-4"><p class="mb-3">Total pesanan: <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong></p>
                    <label for="received_amount" class="form-label fw-semibold">Uang Diterima</label>
                    <input id="received_amount" name="received_amount" type="number" min="{{ $order->grand_total }}" step="1" class="form-control @error('received_amount') is-invalid @enderror" value="{{ old('received_amount') }}" required>
                    @error('received_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-3 p-3 bg-light rounded">Kembalian: <strong id="cashChangePreview">Rp 0</strong></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-success">Konfirmasi Pembayaran Tunai</button></div>
            </form>
        </div></div>
    </div>
@endif

@if($order->payment_status === 'waiting_verification')
    {{-- Modal Confirmation: Approve Payment --}}
    <div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="approvePaymentModalLabel">✅ Setujui Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="approveOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="approve_payment">

                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span class="fs-1 text-success">✅</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Konfirmasi Persetujuan Pembayaran</h5>
                        <p class="text-muted small mb-0">
                            Pembayaran invoice <strong class="text-primary">{{ $order->invoice_number }}</strong> sebesar <strong>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong> akan ditandai sebagai <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span> dan pesanan diproses.
                        </p>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-semibold px-4" id="approveSubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="approveSpinner" role="status" aria-hidden="true"></span>
                            <span id="approveBtnText">Ya, Approve Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Confirmation: Reject Payment --}}
    <div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="rejectPaymentModalLabel">❌ Tolak Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="rejectOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject_payment">

                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <span class="fs-1 text-danger">⚠️</span>
                        </div>
                        <h5 class="fw-bold text-dark text-center mb-3">Konfirmasi Penolakan Pembayaran</h5>
                        
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label fw-semibold text-secondary small">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="reject_reason" 
                                      id="reject_reason" 
                                      rows="3" 
                                      class="form-control @error('reject_reason') is-invalid @enderror" 
                                      placeholder="Contoh: Nominal tidak sesuai, Bukti pembayaran buram, QRIS gagal..." 
                                      required></textarea>
                            @error('reject_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-semibold px-4" id="rejectSubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="rejectSpinner" role="status" aria-hidden="true"></span>
                            <span id="rejectBtnText">Ya, Reject Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($order->payment_status === 'paid' || $order->order_status === 'processing')
    {{-- Modal Confirmation: Mark Ready for Pickup --}}
    <div class="modal fade" id="readyForPickupModal" tabindex="-1" aria-labelledby="readyForPickupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="readyForPickupModalLabel">📦 Set Ready for Pickup</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="readyOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="ready_for_pickup">

                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span class="fs-1 text-primary">📦</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Tandai Ready for Pickup?</h5>
                        <p class="text-muted small mb-0">
                            Pesanan invoice <strong class="text-primary">{{ $order->invoice_number }}</strong> akan ditandai Siap Diambil di toko Tokobii.
                        </p>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold px-4" id="readySubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="readySpinner" role="status" aria-hidden="true"></span>
                            <span id="readyBtnText">Ya, Ready for Pickup</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($order->order_status === 'ready_for_pickup')
    {{-- Modal Confirmation: Mark Completed --}}
    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-labelledby="completeOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="completeOrderModalLabel">🏁 Mark as Completed</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="completeOrderForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="complete">

                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span class="fs-1 text-dark">🏁</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Selesaikan pesanan ini?</h5>
                        <p class="text-muted small mb-0">
                            Pesanan invoice <strong class="text-primary">{{ $order->invoice_number }}</strong> telah diserahkan dan dinyatakan <span class="badge bg-dark">Completed</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark fw-semibold px-4" id="completeSubmitBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="completeSpinner" role="status" aria-hidden="true"></span>
                            <span id="completeBtnText">Ya, Completed</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupSubmitHandler(formId, btnId, spinnerId, btnTextId, text) {
            const form = document.getElementById(formId);
            const btn = document.getElementById(btnId);
            const spinner = document.getElementById(spinnerId);
            const btnText = document.getElementById(btnTextId);

            if (form && btn) {
                form.addEventListener('submit', function () {
                    btn.disabled = true;
                    if (spinner) spinner.classList.remove('d-none');
                    if (btnText) btnText.textContent = text;
                });
            }
        }

        setupSubmitHandler('approveOrderForm', 'approveSubmitBtn', 'approveSpinner', 'approveBtnText', 'Memproses...');
        setupSubmitHandler('rejectOrderForm', 'rejectSubmitBtn', 'rejectSpinner', 'rejectBtnText', 'Memproses...');
        setupSubmitHandler('readyOrderForm', 'readySubmitBtn', 'readySpinner', 'readyBtnText', 'Memproses...');
        setupSubmitHandler('completeOrderForm', 'completeSubmitBtn', 'completeSpinner', 'completeBtnText', 'Memproses...');

        const receivedAmount = document.getElementById('received_amount');
        const changePreview = document.getElementById('cashChangePreview');
        if (receivedAmount && changePreview) {
            const total = {{ (float) $order->grand_total }};
            receivedAmount.addEventListener('input', function () {
                const received = Number(this.value || 0);
                changePreview.textContent = 'Rp ' + Math.max(0, received - total).toLocaleString('id-ID');
            });
        }
    });
</script>
@endpush
