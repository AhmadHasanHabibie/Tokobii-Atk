@extends('errors.layout')

@section('title', '419 - Sesi Kedaluwarsa')
@section('code', '419')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%); border: 1.5px solid #fde68a; color: #d97706;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
@endsection

@section('message', 'Sesi Halaman Telah Kedaluwarsa')

@section('description')
    Sesi keamanan formulir Anda telah berakhir karena tidak ada aktivitas dalam waktu lama. Silakan muat ulang (refresh) halaman atau kembali ke formulir awal.
@endsection
