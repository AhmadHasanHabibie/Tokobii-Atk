@extends('layouts.admin.app')

@section('title', 'Admin Profile - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Profile</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Administrator Profile</h1>
            <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Account details and permissions for Tokobii admin panel.</p>
        </div>
        <a href="{{ route('admin.profile.edit') }}" class="btn btn-tokobii-primary">
            Edit Profile
        </a>
    </div>

    {{-- Profile Card --}}
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-12 col-lg-8">
            <div class="tokobii-card p-4">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-slate-100">
                    <div class="rounded-circle bg-blue-50 text-blue-600 fw-bold d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.25rem; background-color: #eff6ff; color: #2563eb;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold text-slate-900 mb-0">{{ $user->name }}</h4>
                        <span class="text-slate-400 font-monospace" style="font-size: 0.875rem;">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0" style="font-size: 0.875rem;">
                        <tbody>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold" style="width: 30%;">Full Name</th>
                                <td class="text-slate-900 fw-bold">: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Email Address</th>
                                <td class="text-slate-800 font-monospace">: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Access Role</th>
                                <td>: <span class="tokobii-badge tokobii-badge-info">{{ ucfirst($user->role) }}</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Account Status</th>
                                <td>: <span class="tokobii-badge tokobii-badge-success">{{ ucfirst($user->status) }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection