@extends('errors.layout')

@section('title', '403 - Akses Terbatas / Dilarang')
@section('code', '403')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #fee2e2 0%, #fff1f2 100%); border: 1.5px solid #fecdd3; color: #e11d48;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
    </div>
@endsection

@section('message', 'Akses Tidak Diizinkan')

@section('description')
    {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses yang memadai untuk membuka halaman atau data ini. Pastikan Anda telah login dengan akun yang sesuai.' }}
@endsection
