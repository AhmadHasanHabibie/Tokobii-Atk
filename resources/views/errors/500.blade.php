@extends('errors.layout')

@section('title', '500 - Terjadi Kesalahan Server')
@section('code', '500')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border: 1.5px solid #cbd5e1; color: #475569;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
    </div>
@endsection

@section('message', 'Terjadi Kendala pada Server')

@section('description')
    Sistem kami sedang mengalami kendala teknis sementara saat memproses permintaan Anda. Tim teknis kami telah diberitahu dan sedang menanganinya.
@endsection
