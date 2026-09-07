@extends('errors.layout')

@section('title', '401 - Diperlukan Autentikasi')
@section('code', '401')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #e0e7ff 0%, #eef2ff 100%); border: 1.5px solid #c7d2fe; color: #4338ca;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
    </div>
@endsection

@section('message', 'Autentikasi Akun Diperlukan')

@section('description')
    Anda perlu masuk (login) ke akun Tokobii Anda terlebih dahulu untuk mengakses halaman atau layanan yang diminta.
@endsection
