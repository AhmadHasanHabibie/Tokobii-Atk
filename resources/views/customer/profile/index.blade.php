@extends('layouts.customer.app')

@section('title', 'Profil Saya - ' . config('app.name', 'Tokobii'))

@section('content')
<div class="container-fluid px-0">

    {{-- Dedicated Header Card --}}
    <div class="tokobii-header-card">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
                        <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Profil Saya</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Profil Pelanggan</h1>
                <p class="text-slate-500 mb-0 small">Informasi rincian akun pelanggan Tokobii Anda.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('customer.profile.edit') }}" class="btn btn-tokobii-primary btn-tokobii-sm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Profil & Password</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-12 col-lg-8">
            <div class="tokobii-card p-4 p-md-5">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-slate-100">
                    <div class="rounded-circle bg-blue-100 text-blue-600 fw-bold d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.25rem; background-color: #eff6ff; color: #2563eb;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold text-slate-900 mb-0">{{ $user->name }}</h4>
                        <span class="text-slate-400 font-monospace small">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0 small">
                        <tbody>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold" style="width: 32%;">Nama Lengkap</th>
                                <td class="text-slate-900 fw-bold">: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Alamat Email</th>
                                <td class="text-slate-800 font-monospace">: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Hak Akses</th>
                                <td>: <span class="tokobii-badge tokobii-badge-info">Pelanggan (Customer)</span></td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Status Akun</th>
                                <td>: 
                                    @if($user->status === 'active')
                                        <span class="tokobii-badge tokobii-badge-success">Aktif</span>
                                    @else
                                        <span class="tokobii-badge tokobii-badge-neutral">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Verifikasi Email</th>
                                <td>: 
                                    @if($user->hasVerifiedEmail())
                                        <span class="tokobii-badge tokobii-badge-success">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1" style="display:inline; vertical-align:-1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Email Terverifikasi
                                        </span>
                                    @else
                                        <div class="d-inline-flex align-items-center gap-2 flex-wrap">
                                            <span class="tokobii-badge tokobii-badge-warning">Belum Terverifikasi</span>
                                            <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary py-0 px-2 small">
                                                    Kirim Ulang Email Verifikasi
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0 text-slate-500 fw-semibold">Terdaftar Sejak</th>
                                <td class="text-slate-800">: {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }} WIB</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection