@extends('errors.layout')

@section('title')
    @yield('code') - @yield('title')
@endsection

@section('code')
    @yield('code')
@endsection

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border: 1.5px solid #bfdbfe; color: #2563eb;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
    </div>
@endsection

@section('message')
    @yield('message', 'Terjadi Kendala')
@endsection

@section('description')
    @yield('description', 'Halaman atau permintaan yang Anda akses tidak dapat diproses saat ini. Silakan kembali ke beranda atau coba beberapa saat lagi.')
@endsection
