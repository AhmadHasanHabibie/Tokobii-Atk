@extends('layouts.customer.app')

@section('title', 'Detail Pesanan ' . $order->invoice_number . ' - Tokobii')

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
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Detail Pesanan</li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>✅ Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>ℹ️ Info:</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>⚠️ Perhatian!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>❌ Terjadi Kesalahan!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <strong>❌ Gagal Upload:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">Detail Pesanan: <span class="text-primary">{{ $order->invoice_number }}</span></h2>
            <p class="text-muted mb-0">Informasi rincian item, status verifikasi pembayaran, dan resi pengambilan pesanan Anda.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            @if(in_array($order->order_status, ['ready_for_pickup', 'completed']) || in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']))
                <a href="{{ route('customer.orders.receipt', $order) }}" target="_blank" class="btn btn-outline-primary fw-semibold shadow-sm">
                    🖨 Cetak Pickup Receipt
                </a>
            @endif
            <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary px-4 fw-semibold shadow-sm">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Customer Status Notification Alerts --}}
    @if($order->payment_status === 'paid' && $order->order_status === 'processing')
        <div class="alert alert-success border-success-subtle shadow-sm mb-4" role="alert">
            ✅ <strong>Pembayaran berhasil diverifikasi.</strong> Pesanan Anda sedang diproses oleh tim Tokobii.
        </div>
    @elseif($order->order_status === 'ready_for_pickup')
        <div class="alert alert-info border-info-subtle shadow-sm mb-4" role="alert">
            📦 <strong>Pesanan siap diambil.</strong> Silakan tunjukkan QR Code atau Struk Pengambilan saat datang ke kasir toko.
        </div>
    @elseif($order->order_status === 'completed')
        <div class="alert alert-success border-success-subtle shadow-sm mb-4" role="alert">
            🏁 <strong>Pesanan telah selesai.</strong> Terima kasih telah berbelanja kebutuhan alat tulis di Tokobii!
        </div>
    @endif

    <div class="row g-4">

        {{-- Left Column: Order Info & Payment Verification Card & Pickup Receipt Card & Timeline & Review --}}
        <div class="col-12 col-md-5">
            
            {{-- Section 1: Order Information Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Informasi Pesanan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 small">
                            <tbody>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold" style="width: 40%;">Invoice Number</th>
                                    <td>: <code class="text-primary bg-primary-subtle px-2 py-1 rounded fw-bold">{{ $order->invoice_number }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Tanggal Pesanan</th>
                                    <td class="text-dark">: {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Metode Pembayaran</th>
                                    <td>: 
                                        @if($order->payment_method === 'qris')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-normal">📱 QRIS</span>
                                        @else
                                            <span class="badge bg-light text-dark border border-dark-subtle px-2.5 py-1 fw-normal">💵 Cash</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Status Pembayaran</th>
                                    <td>: 
                                        @if($order->payment_status === 'paid' || in_array($order->order_status, ['ready_for_pickup', 'completed']))
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-normal">Paid</span>
                                        @elseif($order->payment_status === 'waiting_verification')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1 fw-normal">Waiting Verification</span>
                                        @elseif($order->payment_status === 'rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fw-normal">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 fw-normal">Waiting Payment</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-secondary fw-semibold">Status Pesanan</th>
                                    <td>: 
                                        @if($order->order_status === 'completed')
                                            <span class="badge bg-dark px-2.5 py-1 fw-normal">🏁 Completed</span>
                                        @elseif($order->order_status === 'ready_for_pickup')
                                            <span class="badge bg-primary px-2.5 py-1 fw-normal">📦 Ready for Pickup</span>
                                        @elseif($order->order_status === 'processing')
                                            <span class="badge bg-info px-2.5 py-1 fw-normal">⚙️ Processing</span>
                                        @elseif($order->order_status === 'cancelled')
                                            <span class="badge bg-danger px-2.5 py-1 fw-normal">🔴 Cancelled</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2.5 py-1 fw-normal">⏳ Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PAYMENT VERIFICATION CARD (QRIS vs CASH) --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">
                        {{ $order->payment_method === 'qris' ? 'PAYMENT VERIFICATION' : '💵 Pembayaran Tunai (Cash)' }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($order->payment_method === 'qris')
                        {{-- QRIS Payment Flow --}}

                        @if($order->payment_status === 'pending')
                            {{-- State 1: Pending Payment (Prompt to go to Lakukan Pembayaran page) --}}
                            <div class="p-3 bg-light rounded border text-center">
                                <span class="fs-2 d-block mb-2">📱</span>
                                <h6 class="fw-bold text-dark mb-2">Belum Menyelesaikan Pembayaran QRIS</h6>
                                <p class="text-muted small mb-3">Pesanan telah dibuat tetapi Anda belum mengunggah bukti pembayaran QRIS.</p>
                                <a href="{{ route('customer.orders.pay', $order) }}" class="btn btn-primary fw-bold px-4 py-2 shadow-sm">
                                    👉 Lakukan Pembayaran QRIS Sekarang
                                </a>
                            </div>

                        @elseif($order->payment_status === 'waiting_verification')
                            {{-- State 2: Waiting Verification Card --}}
                            <div class="p-3 bg-light rounded border mb-3">
                                <div class="mb-3">
                                    <span class="text-secondary small d-block">Status:</span>
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-3 py-1.5 fs-6 fw-normal">Waiting Verification</span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-secondary small d-block">Upload Time:</span>
                                    <strong class="text-dark small">{{ $order->payment && $order->payment->payment_date ? $order->payment->payment_date->format('d M Y, H:i:s') : '-' }}</strong>
                                </div>
                                <div>
                                    <span class="text-secondary small d-block mb-1">Preview Bukti Pembayaran:</span>
                                    @if($order->payment && $order->payment->proof_of_payment)
                                        @if(\Illuminate\Support\Str::endsWith(strtolower($order->payment->proof_of_payment), '.pdf'))
                                            <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" target="_blank" class="btn btn-sm btn-outline-primary fw-semibold w-100 py-2">
                                                📄 Lihat PDF Bukti Pembayaran
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}" alt="Preview Bukti Transfer" class="img-fluid rounded border shadow-sm" style="max-height: 160px; object-fit: contain;">
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="alert alert-info border-info-subtle text-center p-3 mb-0 small" role="alert">
                                💬 Pembayaran Anda sedang diperiksa Admin. Proses verifikasi maksimal 1 x 24 jam.
                            </div>

                        @elseif($order->payment_status === 'rejected')
                            {{-- State 3: Rejected State with Alert & Re-Upload Form --}}
                            <div class="alert alert-danger border-danger-subtle p-3 mb-3 small" role="alert">
                                <h6 class="fw-bold mb-1">❌ Pembayaran ditolak.</h6>
                                <p class="mb-0 mt-1">
                                    <strong>Alasan Penolakan:</strong><br>
                                    <span class="font-monospace text-dark bg-white p-2 rounded border border-danger-subtle d-block mt-1">
                                        {{ $order->payment->reject_reason ?? 'Bukti pembayaran buram atau nominal tidak sesuai.' }}
                                    </span>
                                </p>
                            </div>

                            <div class="p-3 bg-light rounded border border-warning">
                                <h6 class="fw-bold text-dark mb-2">🔄 Upload Bukti Pembayaran Baru</h6>
                                <p class="text-muted small mb-3">Silakan unggah kembali file bukti pembayaran baru yang valid untuk mengubah status menjadi Waiting Verification.</p>
                                
                                <form action="{{ route('customer.orders.upload-proof', $order) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="proof_of_payment_re" class="form-label text-secondary small fw-semibold">Upload Bukti Pembayaran Baru (JPG, PNG, PDF, Maks 2MB):</label>
                                        <input type="file" name="proof_of_payment" id="proof_of_payment_re" class="form-control @error('proof_of_payment') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" required>
                                        @error('proof_of_payment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-warning fw-bold text-dark w-100 shadow-sm">
                                        🔄 Upload Bukti Pembayaran Baru
                                    </button>
                                </form>
                            </div>

                        @elseif(in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']))
                            {{-- State 4: Paid / Verified State --}}
                            <div class="alert alert-success border-success-subtle text-center p-3 mb-0 small" role="alert">
                                ✅ <strong>Pembayaran berhasil diverifikasi.</strong>
                            </div>
                        @endif

                    @else
                        {{-- Cash Section --}}
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="fs-2 d-block mb-2">💵</span>
                            <h6 class="fw-bold text-dark mb-1">Pembayaran Tunai di Kasir</h6>
                            <p class="text-muted small mb-0">Anda memilih metode Cash. Lakukan pembayaran secara tunai di kasir toko Tokobii saat mengambil pesanan Anda.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Pickup Receipt Card (If Paid / Ready for Pickup / Completed) --}}
            @if(in_array($order->order_status, ['ready_for_pickup', 'completed']) || in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']))
                <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-primary border-4">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">🖨 Struk Pengambilan (Pickup Receipt)</h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">Ready</span>
                    </div>
                    <div class="card-body p-4 text-center">
                        <p class="text-muted small mb-3">Tunjukkan QR Code / Invoice di bawah ini kepada kasir saat mengambil pesanan Anda:</p>
                        <div class="mb-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($order->invoice_number) }}" 
                                 alt="QR Code {{ $order->invoice_number }}" 
                                 class="border rounded p-2 shadow-sm bg-white mb-2" style="width: 110px; height: 110px;">
                            <code class="d-block fs-6 font-monospace fw-bold text-primary">{{ $order->invoice_number }}</code>
                        </div>
                        <a href="{{ route('customer.orders.receipt', $order) }}" target="_blank" class="btn btn-primary fw-semibold btn-sm w-100 shadow-sm">
                            🖨 Buka Halaman Cetak Receipt
                        </a>
                    </div>
                </div>
            @endif

            {{-- 7-Step Active Timeline Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">⏳ Timeline Pesanan</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="timeline list-unstyled mb-0 position-relative">
                        {{-- 1. Order Created --}}
                        <li class="mb-3 d-flex align-items-start">
                            <span class="badge bg-success rounded-circle p-2 me-3">✓</span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Order Created</h6>
                                <small class="text-muted">{{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}</small>
                            </div>
                        </li>

                        {{-- 2. Waiting Payment --}}
                        <li class="mb-3 d-flex align-items-start {{ $order->payment_status !== 'pending' || $order->order_status !== 'pending' ? '' : 'opacity-75' }}">
                            <span class="badge {{ $order->payment_status !== 'pending' ? 'bg-success' : 'bg-warning text-dark' }} rounded-circle p-2 me-3">
                                {{ $order->payment_status !== 'pending' ? '✓' : '💳' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Waiting Payment</h6>
                                <small class="text-muted">Menunggu pembayaran / unggah bukti</small>
                            </div>
                        </li>

                        {{-- 3. Waiting Verification --}}
                        <li class="mb-3 d-flex align-items-start {{ in_array($order->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($order->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($order->payment_status, ['waiting_verification', 'paid', 'ready_for_pickup', 'completed']) ? '✓' : '🔍' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Waiting Verification</h6>
                                <small class="text-muted">Proses verifikasi oleh Admin Tokobii</small>
                            </div>
                        </li>

                        {{-- 4. Paid --}}
                        <li class="mb-3 d-flex align-items-start {{ in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($order->payment_status, ['paid', 'ready_for_pickup', 'completed']) ? '✓' : '🟢' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Paid</h6>
                                <small class="text-muted">Pembayaran dikonfirmasi Lunas</small>
                            </div>
                        </li>

                        {{-- 5. Ready for Pickup --}}
                        <li class="mb-3 d-flex align-items-start {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? '' : 'opacity-50' }}">
                            <span class="badge {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                {{ in_array($order->order_status, ['ready_for_pickup', 'completed']) ? '✓' : '📦' }}
                            </span>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Ready for Pickup</h6>
                                <small class="text-muted">Pesanan siap diambil di toko</small>
                            </div>
                        </li>

                        {{-- 6. Completed or Rejected --}}
                        @if($order->payment_status === 'rejected' || $order->order_status === 'cancelled')
                            <li class="d-flex align-items-start">
                                <span class="badge bg-danger rounded-circle p-2 me-3">🔴</span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-danger">Rejected</h6>
                                    <small class="text-muted">Pembayaran/Pesanan ditolak Admin</small>
                                </div>
                            </li>
                        @else
                            <li class="d-flex align-items-start {{ $order->order_status === 'completed' ? '' : 'opacity-50' }}">
                                <span class="badge {{ $order->order_status === 'completed' ? 'bg-success' : 'bg-secondary' }} rounded-circle p-2 me-3">
                                    {{ $order->order_status === 'completed' ? '🏁' : '🏁' }}
                                </span>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Completed</h6>
                                    <small class="text-muted">Pesanan telah diserahkan & selesai</small>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Module 5: Rating & Review Card (Only if Completed) --}}
            @if(false)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">⭐ Rating & Ulasan Produk</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(str_contains($order->notes ?? '', '[RATING_REVIEW]'))
                            <div class="p-3 bg-light rounded border text-dark">
                                <span class="badge bg-warning text-dark mb-2 fs-6">Sudah Diulas</span>
                                <p class="mb-0 text-secondary small font-monospace">
                                    {{ Str::after($order->notes, '[RATING_REVIEW]') }}
                                </p>
                            </div>
                        @else
                            <form action="{{ route('customer.orders.review', $order) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-secondary small fw-semibold">Beri Rating Pesanan <span class="text-danger">*</span></label>
                                    <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                        <option value="">-- Pilih Rating Bintang --</option>
                                        <option value="5">★★★★★ (5 Bintang - Sangat Memuaskan)</option>
                                        <option value="4">★★★★☆ (4 Bintang - Bagus)</option>
                                        <option value="3">★★★☆☆ (3 Bintang - Cukup)</option>
                                        <option value="2">★★☆☆☆ (2 Bintang - Kurang)</option>
                                        <option value="1">★☆☆☆☆ (1 Bintang - Buruk)</option>
                                    </select>
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="review" class="form-label text-secondary small fw-semibold">Ulasan & Kesan Anda <span class="text-danger">*</span></label>
                                    <textarea name="review" 
                                              id="review" 
                                              rows="3" 
                                              class="form-control @error('review') is-invalid @enderror" 
                                              placeholder="Tulis ulasan Anda mengenai produk dan pelayanan Tokobii..." 
                                              required></textarea>
                                    @error('review')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-warning fw-bold text-dark w-100 shadow-sm">
                                    🌟 Kirim Rating & Review
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Items Table & Order Summary --}}
        <div class="col-12 col-md-7">
            
            {{-- Order Items Table Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Daftar Item Pesanan</h5>
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
                                    @if($order->order_status === 'completed')<th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end">Review</th>@endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" 
                                                     alt="Thumbnail {{ $item->product_name }}" 
                                                     class="rounded border shadow-sm" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
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
                                        <td class="text-end font-monospace text-dark">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="pe-4 text-end font-monospace fw-bold text-primary">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        @if($order->order_status === 'completed')<td class="pe-4 text-end">@if($item->review)<span class="badge bg-success">Sudah Direview</span>@if($item->review->canBeEdited())<a class="btn btn-sm btn-outline-secondary mt-1" href="{{ route('customer.reviews.edit', $item->review) }}">Edit Review</a><small class="d-block text-muted">Edit tersedia sampai {{ $item->review->created_at->copy()->addHours(24)->format('d M Y H:i') }}</small>@else<button class="btn btn-sm btn-secondary mt-1" disabled>Edit Terkunci</button><small class="d-block text-muted">Periode edit 24 jam telah berakhir.</small>@endif @elseif($item->product_id)<a class="btn btn-sm btn-outline-primary" href="{{ route('customer.orders.reviews.create', [$order, $item]) }}">Review Produk</a>@endif</td>@endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Rincian Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0 text-secondary fw-normal">Subtotal Item:</th>
                                            <td class="text-end font-monospace text-dark fw-bold">: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0 text-secondary fw-normal">Biaya Layanan/Kirim:</th>
                                            <td class="text-end font-monospace text-success fw-bold">: Rp 0</td>
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
@endsection
