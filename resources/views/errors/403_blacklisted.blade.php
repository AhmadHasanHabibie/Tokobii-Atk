@extends('errors.layout')

@section('title', '403 - Akses Diblokir Sistem Keamanan')
@section('code', '403')

@section('icon')
    <div class="icon-floating-circle" style="background: linear-gradient(135deg, #fee2e2 0%, #fff1f2 100%); border: 1.5px solid #fecdd3; color: #dc2626;">
        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
        </svg>
    </div>
@endsection

@section('message', 'Akses Jaringan Diblokir Permanen')

@section('description')
    <div class="mb-3">
        Aktivitas jaringan mencurigakan terdeteksi oleh sistem pertahanan aktif Tokobii Threat Defense.
    </div>
    <div class="p-3 rounded-3 text-start mx-auto mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0; max-width: 480px; font-size: 0.8125rem;">
        <div class="mb-1 text-slate-700"><strong>Alamat IP Anda:</strong> <span class="font-monospace text-danger fw-bold">{{ $ip ?? request()->ip() }}</span></div>
        <div class="mb-1 text-slate-600"><strong>Alasan Pemblokiran:</strong> {{ $reason ?? 'Alamat IP ini tercatat dalam daftar ancaman hitam (Active Defense Decoy Triggered).' }}</div>
        <div class="text-slate-400 font-monospace" style="font-size: 0.7rem;">TIMESTAMP: {{ now()->toIso8601String() }}</div>
    </div>
@endsection
