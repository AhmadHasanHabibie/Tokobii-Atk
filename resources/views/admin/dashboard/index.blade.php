@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            Dashboard Admin
        </h2>

        <p class="text-muted mb-0">
            Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>.
        </p>

    </div>

    <div class="alert alert-success">

        Login berhasil sebagai <strong>Administrator</strong>.

    </div>

    <div class="row g-4">

        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Category
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
                        Total Product
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
                        Total Customer
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
                        Total Orders
                    </h6>

                    <h3 class="fw-bold mb-0">
                        0
                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection