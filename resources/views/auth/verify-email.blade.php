@extends('layouts.guest.auth')

@section('title', 'Verifikasi Email')

@section('subtitle', 'Verifikasi alamat email Anda.')

@section('content')

<div class="mb-4 text-muted">

    Terima kasih telah mendaftar di <strong>Tokobii</strong>.
    Sebelum mulai menggunakan akun Anda, silakan verifikasi alamat email
    dengan mengklik tautan yang telah kami kirimkan.

    <br><br>

    Jika Anda belum menerima email tersebut, Anda dapat mengirim ulang
    tautan verifikasi melalui tombol di bawah ini.

</div>

@if(session('status') === 'verification-link-sent')

    <div class="alert alert-success">

        Link verifikasi baru berhasil dikirim ke alamat email Anda.

    </div>

@endif

<div class="d-grid gap-3">

    {{-- Kirim Ulang Email --}}
    <form method="POST"
          action="{{ route('verification.send') }}">

        @csrf

        <button
            type="submit"
            class="btn btn-primary w-100">

            Kirim Ulang Email Verifikasi

        </button>

    </form>

    {{-- Logout --}}
    <form method="POST"
          action="{{ route('logout') }}">

        @csrf

        <button
            type="submit"
            class="btn btn-outline-danger w-100">

            Logout

        </button>

    </form>

</div>

@endsection