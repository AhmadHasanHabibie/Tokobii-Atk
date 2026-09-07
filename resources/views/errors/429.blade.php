@extends('errors.layout')

@section('title', '429 - Terlalu Banyak Permintaan')
@section('code', '429')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #ffedd5 0%, #fff7ed 100%); border: 1.5px solid #fed7aa; color: #ea580c;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
    </div>
@endsection

@section('message', 'Terlalu Banyak Permintaan')

@section('description')
    Sistem mendeteksi terlalu banyak permintaan dalam waktu singkat. Demi keamanan dan kenyamanan bersama, mohon tunggu beberapa saat sebelum mencoba kembali.
@endsection
