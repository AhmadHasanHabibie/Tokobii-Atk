@extends('layouts.admin.app')

@section('title', 'Detail Payment & Pickup - Tokobii')

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
                <a href="{{ route('admin.payments.index') }}" class="text-decoration-none text-secondary">Payment Management</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Detail Payment & Pickup</li>
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
            <h2 class="fw-bold mb-1 text-dark">Detail Payment & Pickup: <span class="text-primary">{{ $payment->invoice_number }}</span></h2>
            <p class="text-muted mb-0">Informasi verifikasi pembayaran, struk pengambilan, dan status pesanan customer.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2 align-items-center">
            
            {{-- Contextual Action Buttons --}}
            @if($payment->payment_status === 'waiting_verification')
                <button type="button" class="btn btn-success fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#approvePaymentModal" title="Setujui Pembayaran Ini" aria-label="Approve Payment">
                    ✅ Approve Payment
                </button>
                <button type="button" class="btn btn-danger fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal" title="Tolak Pembayaran Ini" aria-label="Reject Payment">
                    ❌ Reject Payment
                </button>
            @elseif($payment->payment_status === 'paid')
                <button type="button" class="btn btn-primary fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#readyForPickupModal" title="Ubah Status ke Ready for Pickup" aria-label="Ready for Pickup">
                    📦 Ready for Pickup
                </button>
            @elseif($payment->payment_status === 'ready_for_pickup')
                <button type="button" class="btn btn-outline-primary fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#printReceiptModal" title="Pratinjau Struk Pengambilan" aria-label="Print Receipt">
                    🖨 Print Pickup Receipt
                </button>
                <button type="button" class="btn btn-dark fw-semibold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#completePaymentModal" title="Tandai Selesai (Completed)" aria-label="Mark as Completed">
                    ✅ Mark as Completed
                </button>
            @endif

            <button type="button" onclick="window.location.reload();" class="btn btn-outline-secondary px-3 fw-semibold shadow-sm" title="Refresh Halaman" aria-label="Refresh Halaman">
                🔄 Refresh
            </button>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary px-4 fw-semibold shadow-sm">
                Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left Column: Section 1 (Payment Info) & Section 2 (Customer Info) & Section 4 (Pickup Info) & Section 7 (Timeline) & Section 6 (Notes) --}}
        <div class="col-12 col-md-5">
            
            {{-- Section 1: Payment Information Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">1. Payment Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 40%;">Invoice Number</th>
                                    <td>: <code class="text-primary bg-primary-subtle px-2 py-1 rounded fw-bold">{{ $payment->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Payment Method</th>
                                    <td>: 
                                        @if($payment->payment_method === 'qris')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-normal">📱 QRIS</span>
                                        @else
                                            <span class="badge bg-light text-dark border border-dark-subtle px-2.5 py-1 fw-normal">💵 Cash</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Payment Status</th>
                                    <td>: 
                                        @if($payment->payment_status === 'paid')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-normal">Paid</span>
                                        @elseif($payment->payment_status === 'ready_for_pickup')
                                            <span class="badge bg-primary px-2.5 py-1 fw-normal">📦 Ready for Pickup</span>
                                        @elseif($payment->payment_status === 'completed')
                                            <span class="badge bg-dark px-2.5 py-1 fw-normal">🏁 Completed</span>
                                        @elseif($payment->payment_status === 'waiting_verification')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1 fw-normal">Waiting Verification</span>
                                        @elseif($payment->payment_status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-normal">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-normal">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Payment Date</th>
                                    <td class="text-dark">: {{ $payment->payment_date ? $payment->payment_date->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Verified By</th>
                                    <td class="text-dark">: {{ $payment->verifiedByAdmin->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Verified At</th>
                                    <td class="text-muted">: {{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section 2: Customer Information Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">2. Customer Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ strtoupper(substr($payment->order->user->name ?? 'G', 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">{{ $payment->order->user->name ?? '-' }}</h6>
                            <small class="text-muted d-block">{{ $payment->order->user->email ?? '-' }}</small>
                        </div>
                    </div>
                    <div class="table-responsive border-top pt-2">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 35%;">Username</th>
                                    <td class="text-dark fw-bold">: {{ $payment->order->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Nama</th>
                                    <td class="text-dark">: {{ $payment->order->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Email</th>
                                    <td class="text-dark">: {{ $payment->order->user->email ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section 4: Pickup Information Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-primary border-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">4. Pickup Information</h5>
                    <span class="badge bg-light text-secondary border">Toko Physical</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <span class="text-secondary small d-block mb-1">QR Code Invoice:</span>
                            <code class="text-primary fw-bold">{{ $payment->invoice_number }}</code>
                        </div>
                        <div class="bg-white p-1 rounded border shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($payment->invoice_number) }}" 
                                 alt="QR Code Invoice {{ $payment->invoice_number }}" 
                                 style="width: 80px; height: 80px;"
                                 title="QR Code Invoice {{ $payment->invoice_number }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 40%;">Pickup Status</th>
                                    <td>: 
                                        @if($payment->payment_status === 'completed')
                                            <span class="badge bg-dark px-2.5 py-1 fw-normal">🏁 Completed (Sudah Diambil)</span>
                                        @elseif($payment->payment_status === 'ready_for_pickup')
                                            <span class="badge bg-primary px-2.5 py-1 fw-normal">📦 Ready for Pickup</span>
                                        @elseif($payment->payment_status === 'paid')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1 fw-normal">Sedang Disiapkan</span>
                                        @else
                                            <span class="badge bg-light text-secondary border px-2.5 py-1 fw-normal">Pending Verification</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Pickup Receipt</th>
                                    <td>: <code class="text-primary bg-primary-subtle px-2 py-1 rounded fw-bold">{{ $payment->pickup_receipt_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Lokasi Pengambilan</th>
                                    <td class="text-dark">: Toko Tokobii (Store Main Hall)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section 7: Payment Timeline Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">7. Payment Timeline</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="timeline list-unstyled mb-0 position-relative">
                        
                        {{-- 1. Order Created --}}
                        <li class="mb-3 d-flex align-items-start">
                            <span class="badge bg-success rounded-circle p-2 me-3">✓</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">1. Order Created</h6>
                                <small class="text-muted">{{ $payment->created_at ? $payment->created_at->format('d M Y, H:i:s') : '-' }}</small>
                            </div>
                        </li>

                        {{-- 2. Waiting Payment --}}
                        <li class="mb-3 d-flex align-items-start {{ $payment->payment_status !== 'pending' ? '' : 'opacity-50' }}">
                            <span class="badge {{ $payment->payment_status !== 'pending' ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ $payment->payment_status !== 'pending' ? '✓' : '💳' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">2. Waiting Payment</h6>
                                <small class="text-muted">Metode: {{ strtoupper($payment->payment_method) }}</small>
                            </div>
                        </li>

                        {{-- 3. Waiting Verification --}}
                        <li class="mb-3 d-flex align-items-start {{ in_array($payment->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($payment->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($payment->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed', 'rejected']) ? '✓' : '🔍' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">3. Waiting Verification</h6>
                                <small class="text-muted">Proses verifikasi oleh Admin</small>
                            </div>
                        </li>

                        {{-- 4. Paid --}}
                        <li class="mb-3 d-flex align-items-start {{ in_array($payment->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($payment->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($payment->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '✓' : '🟢' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">4. Paid</h6>
                                <small class="text-muted">Pembayaran berhasil dikonfirmasi Lunas</small>
                            </div>
                        </li>

                        {{-- 5. Ready for Pickup --}}
                        <li class="mb-3 d-flex align-items-start {{ in_array($payment->payment_status, ['ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($payment->payment_status, ['ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($payment->payment_status, ['ready_for_pickup', 'completed']) ? '✓' : '📦' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">5. Ready for Pickup</h6>
                                <small class="text-muted">Pesanan siap diambil & Struk Stuk Pengambilan tersedia</small>
                            </div>
                        </li>

                        {{-- 6. Completed / Rejected --}}
                        @if($payment->payment_status === 'rejected')
                            <li class="d-flex align-items-start">
                                <span class="badge bg-danger rounded-circle p-2 me-3">🔴</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-danger">Rejected</h6>
                                    <small class="text-muted">Pembayaran telah ditolak Admin.</small>
                                </div>
                            </li>
                        @else
                            <li class="d-flex align-items-start {{ $payment->payment_status === 'completed' ? '' : 'opacity-50' }}">
                                <span class="badge {{ $payment->payment_status === 'completed' ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                    {{ $payment->payment_status === 'completed' ? '🏁' : '🏁' }}
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">6. Completed</h6>
                                    <small class="text-muted">Pesanan telah diserahkan & selesai</small>
                                </div>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>

            {{-- Section 6: Verification Notes Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">6. Verification Notes</h5>
                </div>
                <div class="card-body p-4">
                    @if($payment->reject_reason)
                        <div class="p-3 bg-danger-subtle text-danger rounded border border-danger-subtle">
                            ⚠️ <strong>Catatan Penolakan:</strong> {{ $payment->reject_reason }}
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <span class="small">Tidak ada catatan penolakan.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column: Order Info & Payment Summary --}}
        <div class="col-12 col-md-7">
            
            {{-- Section 3: Order Information Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">3. Order Information</h5>
                    @if($payment->order)
                        <a href="{{ route('admin.orders.show', $payment->order) }}" class="btn btn-sm btn-outline-primary fw-semibold d-print-none">
                            👁 Detail Order
                        </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($payment->order)
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0 small">
                                <tbody>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-semibold" style="width: 35%;">Invoice Order</th>
                                        <td class="text-dark fw-bold">: <code class="text-primary bg-primary-subtle px-2 py-1 rounded">{{ $payment->order->invoice_number }}</code></td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-semibold">Tanggal Order</th>
                                        <td class="text-dark">: {{ $payment->order->order_date ? $payment->order->order_date->format('d M Y, H:i') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-semibold">Total Item</th>
                                        <td class="text-dark fw-bold">: {{ $payment->order->items ? $payment->order->items->sum('qty') : 0 }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0 text-secondary fw-semibold">Grand Total Order</th>
                                        <td class="text-dark font-monospace fw-bold">: Rp {{ number_format($payment->order->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Order tidak ditemukan.</p>
                    @endif
                </div>
            </div>

            {{-- Section 5: Payment Summary Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">5. Payment Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-secondary fw-normal">Subtotal Produk:</th>
                                            <td class="text-end font-monospace text-dark fw-semibold">: Rp {{ number_format($payment->order->subtotal ?? $payment->amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-secondary fw-normal">Diskon:</th>
                                            <td class="text-end font-monospace text-muted">: Rp 0</td>
                                        </tr>
                                        <tr class="border-top fs-5">
                                            <th class="ps-0 text-dark fw-bold pt-3">Grand Total:</th>
                                            <td class="text-end font-monospace text-primary fw-bold pt-3">: Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
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

{{-- Confirmation Modal: Approve Payment --}}
@if($payment->payment_status === 'waiting_verification')
    <div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="approvePaymentModalLabel">✅ Konfirmasi Persetujuan Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="approveForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="approve">

                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span class="fs-1 text-success">✅</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Apakah Anda yakin ingin menyetujui pembayaran ini?</h5>
                        <p class="text-muted small mb-0">
                            Pembayaran invoice <strong class="text-primary">{{ $payment->invoice_number }}</strong> sebesar <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong> akan ditandai sebagai <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-semibold px-4" id="approveBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="approveSpinner" role="status" aria-hidden="true"></span>
                            <span id="approveBtnText">Ya, Setujui Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal: Reject Payment --}}
    <div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="rejectPaymentModalLabel">❌ Konfirmasi Penolakan Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="rejectForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="reject">

                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <span class="fs-1 text-danger">⚠️</span>
                        </div>
                        <h5 class="fw-bold text-dark text-center mb-3">Apakah Anda yakin ingin menolak pembayaran ini?</h5>
                        
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label fw-semibold text-secondary small">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="reject_reason" 
                                      id="reject_reason" 
                                      rows="3" 
                                      class="form-control @error('reject_reason') is-invalid @enderror" 
                                      placeholder="Tuliskan alasan penolakan pembayaran secara jelas..." 
                                      required></textarea>
                            @error('reject_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-semibold px-4" id="rejectBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="rejectSpinner" role="status" aria-hidden="true"></span>
                            <span id="rejectBtnText">Ya, Tolak Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($payment->payment_status === 'paid')
    {{-- Modal Confirmation: Mark Ready for Pickup --}}
    <div class="modal fade" id="readyForPickupModal" tabindex="-1" aria-labelledby="readyForPickupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="readyForPickupModalLabel">📦 Set Ready for Pickup</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="readyForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="ready_for_pickup">

                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span class="fs-1 text-primary">📦</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Tandai pesanan Siap Diambil (Ready for Pickup)?</h5>
                        <p class="text-muted small mb-0">
                            Pesanan invoice <strong class="text-primary">{{ $payment->invoice_number }}</strong> telah disiapkan dan siap diambil customer di toko Tokobii.
                        </p>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold px-4" id="readyBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="readySpinner" role="status" aria-hidden="true"></span>
                            <span id="readyBtnText">Ya, Siap Diambil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if($payment->payment_status === 'ready_for_pickup')
    {{-- Modal Confirmation: Mark Completed --}}
    <div class="modal fade" id="completePaymentModal" tabindex="-1" aria-labelledby="completePaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="completePaymentModalLabel">🏁 Tandai Selesai (Completed)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="completeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="complete">

                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <span class="fs-1 text-dark">🏁</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Selesaikan transaksi pesanan ini?</h5>
                        <p class="text-muted small mb-0">
                            Customer telah mengambil pesanan invoice <strong class="text-primary">{{ $payment->invoice_number }}</strong> dan transaksi dinyatakan <span class="badge bg-dark">Completed</span>.
                        </p>
                    </div>
                    <div class="modal-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark fw-semibold px-4" id="completeBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="completeSpinner" role="status" aria-hidden="true"></span>
                            <span id="completeBtnText">Ya, Tandai Selesai</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- Printable Pickup Receipt Modal UI --}}
<div class="modal fade" id="printReceiptModal" tabindex="-1" aria-labelledby="printReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light d-print-none">
                <h5 class="modal-title fw-bold text-dark" id="printReceiptModalLabel">🖨 Struk Pengambilan Pesanan (Pickup Receipt)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="printableReceiptArea">
                <div class="text-center border-bottom pb-3 mb-3">
                    <h3 class="fw-bold text-dark mb-1">TOKOBII STORE</h3>
                    <p class="text-muted small mb-0">Tokobii E-Commerce - Struk Pengambilan Pesanan Resmi</p>
                </div>

                <div class="row g-3 mb-3 small">
                    <div class="col-6">
                        <span class="text-secondary d-block">No. Invoice / Receipt:</span>
                        <strong class="text-primary font-monospace fs-6">{{ $payment->pickup_receipt_number }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-secondary d-block">Tanggal Struk:</span>
                        <strong class="text-dark">{{ date('d M Y, H:i') }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-secondary d-block">Nama Customer:</span>
                        <strong class="text-dark">{{ $payment->order->user->name ?? '-' }}</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-secondary d-block">Metode Pembayaran:</span>
                        <strong class="text-uppercase text-dark">{{ $payment->payment_method }} ({{ ucfirst($payment->payment_status) }})</strong>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($payment->order && $payment->order->items)
                                @foreach($payment->order->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $item->product_name }}</td>
                                        <td class="text-center font-monospace">{{ $item->product->sku ?? '-' }}</td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td class="text-end font-monospace">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end font-monospace fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Grand Total Pembayaran:</td>
                                <td class="text-end font-monospace fw-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <div class="alert alert-light border border-secondary-subtle p-2 mb-0 small text-muted">
                        💬 <strong>Catatan:</strong> Tunjukkan resi ini saat mengambil pesanan di kasir/toko Tokobii.
                    </div>
                    <div class="text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode($payment->invoice_number) }}" 
                             alt="QR Code Invoice {{ $payment->invoice_number }}" 
                             class="border rounded p-1 shadow-sm mb-1" 
                             style="width: 80px; height: 80px;">
                        <small class="d-block text-muted font-monospace" style="font-size: 0.75rem;">{{ $payment->invoice_number }}</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 d-print-none">
                <button type="button" onclick="window.print();" class="btn btn-primary fw-semibold">
                    🖨 Cetak Struk Ini
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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

        setupSubmitHandler('approveForm', 'approveBtn', 'approveSpinner', 'approveBtnText', 'Memproses...');
        setupSubmitHandler('rejectForm', 'rejectBtn', 'rejectSpinner', 'rejectBtnText', 'Memproses...');
        setupSubmitHandler('readyForm', 'readyBtn', 'readySpinner', 'readyBtnText', 'Memproses...');
        setupSubmitHandler('completeForm', 'completeBtn', 'completeSpinner', 'completeBtnText', 'Memproses...');
    });
</script>
@endpush
