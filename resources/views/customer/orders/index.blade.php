@extends('layouts.customer.app')

@section('title', 'Riwayat Pesanan Saya - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a>
            </li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Riwayat Pesanan</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark">📦 Riwayat Pesanan Saya</h2>
            <p class="text-muted mb-0">Lacak status transaksi, pembayaran, dan pengambilan pesanan Anda di Tokobii.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold shadow-sm" title="Refresh Data" aria-label="Refresh">
                🔄 Refresh
            </a>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('customer.orders.index') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">🔍</span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari berdasarkan nomor invoice atau nama produk..." 
                               value="{{ request('search') }}"
                               aria-label="Cari Pesanan"
                               aria-describedby="search-addon"
                               autofocus>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <select name="status" class="form-select" aria-label="Filter Status" onchange="this.form.submit()">
                        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Status: Semua Status Pesanan</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Sudah Dibayar)</option>
                        <option value="ready_for_pickup" {{ request('status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup (Siap Diambil)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                    </select>
                </div>

                {{-- Submit Button --}}
                <div class="col-12 col-sm-6 col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Cari Pesanan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Order List Table / Cards --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            @forelse($orders as $order)
                @if($loop->first)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary small text-uppercase" style="width: 5%;">No</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 18%;">Invoice</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 15%;">Tanggal</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 12%;">Total Item</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-center" style="width: 12%;">Metode</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase text-end" style="width: 14%;">Grand Total</th>
                                    <th scope="col" class="py-3 text-secondary small text-uppercase" style="width: 14%;">Status</th>
                                    <th scope="col" class="pe-4 py-3 text-secondary small text-uppercase text-end" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif

                <tr>
                    <td class="ps-4 fw-semibold text-secondary">{{ $orders->firstItem() + $loop->index }}</td>
                    <td>
                        <code class="text-primary bg-primary-subtle px-2 py-1 rounded fw-bold">{{ $order->invoice_number }}</code>
                    </td>
                    <td class="small text-muted">
                        {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="text-center font-monospace fw-bold text-dark">
                        {{ $order->items->sum('qty') }} Pcs
                    </td>
                    <td class="text-center">
                        @if($order->payment_method === 'qris')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-normal">📱 QRIS</span>
                        @else
                            <span class="badge bg-light text-dark border border-dark-subtle px-2 py-1 fw-normal">💵 Cash</span>
                        @endif
                    </td>
                    <td class="text-end font-monospace fw-bold text-dark">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($order->order_status === 'completed')
                            <span class="badge bg-dark px-2.5 py-1.5 fw-normal">🏁 Completed</span>
                        @elseif($order->order_status === 'ready_for_pickup')
                            <span class="badge bg-primary px-2.5 py-1.5 fw-normal">📦 Ready for Pickup</span>
                        @elseif($order->order_status === 'processing')
                            <span class="badge bg-info px-2.5 py-1.5 fw-normal">⚙️ Processing</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-normal">Paid</span>
                        @elseif($order->payment_status === 'rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-normal">Rejected</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fw-normal">Waiting Verification</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-info fw-semibold" title="Lihat Detail Pesanan">
                            👁 Detail
                        </a>
                    </td>
                </tr>

                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                {{-- Empty State --}}
                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <span class="fs-1">📦</span>
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'status']))
                        <h5 class="fw-bold text-dark mb-1">Pesanan tidak ditemukan.</h5>
                        <p class="text-muted mb-4">Tidak ada data riwayat pesanan yang sesuai dengan kata kunci atau filter Anda.</p>
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                            Reset Filter
                        </a>
                    @else
                        <h5 class="fw-bold text-dark mb-1">Belum Ada Riwayat Pesanan.</h5>
                        <p class="text-muted mb-4">Anda belum pernah melakukan pemesanan produk di Tokobii.</p>
                        <a href="{{ route('customer.shop.index') }}" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            🛍️ Mulai Belanja Sekarang
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                </small>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
