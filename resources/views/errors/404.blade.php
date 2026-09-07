@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('code', '404')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border: 1.5px solid #bfdbfe; color: #2563eb;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
@endsection

@section('message', 'Halaman Tidak Ditemukan')

@section('description')
    Maaf, tautan yang Anda tuju mungkin sudah dipindahkan, dihapus, atau Anda salah mengetikkan alamat URL. Silakan kembali ke katalog atau dashboard akun Anda.
@endsection
