@extends('layouts.owner.app')

@section('title', 'Owner Dashboard')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Dashboard Owner
            </h2>

            <p class="text-muted mb-0">
                Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>.
            </p>

        </div>

    </div>

    <div class="alert alert-success">

        Login berhasil sebagai <strong>Owner</strong>.

    </div>

    <div class="row g-4">

        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Produk
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Pesanan
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Pelanggan
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Penjualan
                    </h6>

                    <h3 class="fw-bold mb-0">
                        Rp 0
                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection