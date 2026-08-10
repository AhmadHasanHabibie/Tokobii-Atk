@extends('layouts.admin.app')

@section('title', 'Edit Profile - Tokobii')

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.8125rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.profile.index') }}" class="text-decoration-none text-slate-500 hover-text-blue-600">Profile</a></li>
            <li class="breadcrumb-item active text-slate-800 fw-semibold" aria-current="page">Edit</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold text-slate-900 mb-1" style="color: #0f172a;">Edit Profile</h1>
        <p class="text-slate-500 mb-0" style="font-size: 0.875rem;">Update your administrator account name and email address.</p>
    </div>

    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-12 col-lg-8">
            <div class="tokobii-card p-4">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               class="tokobii-input w-100 @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}"
                               required>
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email"
                               name="email"
                               class="tokobii-input w-100 @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="text-danger mt-1" style="font-size: 0.8125rem;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-tokobii-primary">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.profile.index') }}" class="btn btn-tokobii-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection