@extends('layouts.admin.app')

@section('title', 'Admin Profile')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Profile Administrator
            </h2>

            <p class="text-muted mb-0">
                Informasi akun Administrator Tokobii.
            </p>

        </div>

        <a href="{{ route('admin.profile.edit') }}"
           class="btn btn-primary">

            Edit Profile

        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-3 fw-semibold">
                    Nama
                </div>

                <div class="col-md-9">
                    {{ $user->name }}
                </div>

            </div>

            <hr>

            <div class="row mb-3">

                <div class="col-md-3 fw-semibold">
                    Email
                </div>

                <div class="col-md-9">
                    {{ $user->email }}
                </div>

            </div>

            <hr>

            <div class="row mb-3">

                <div class="col-md-3 fw-semibold">
                    Role
                </div>

                <div class="col-md-9">

                    <span class="badge bg-primary">

                        {{ ucfirst($user->role) }}

                    </span>

                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-3 fw-semibold">
                    Status
                </div>

                <div class="col-md-9">

                    <span class="badge bg-success">

                        {{ ucfirst($user->status) }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection