@extends('layouts.guest.app')

@section('title', 'Contact Us')

@section('content')

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="fw-bold">

                Contact Us

            </h1>

            <p class="text-muted">

                Hubungi kami jika memiliki pertanyaan, kritik, atau saran.

            </p>

        </div>

        <div class="row g-5">

            {{-- Contact Information --}}
            <div class="col-lg-5">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <h3 class="fw-bold mb-4">

                            Informasi Kontak

                        </h3>

                        <div class="mb-4">

                            <h6 class="fw-bold">

                                📍 Alamat

                            </h6>

                            <p class="text-muted mb-0">

                                Jakarta, Indonesia

                            </p>

                        </div>

                        <div class="mb-4">

                            <h6 class="fw-bold">

                                📧 Email

                            </h6>

                            <p class="text-muted mb-0">

                                support@tokobii.test

                            </p>

                        </div>

                        <div class="mb-4">

                            <h6 class="fw-bold">

                                ☎ Telepon

                            </h6>

                            <p class="text-muted mb-0">

                                +62 812-3456-7890

                            </p>

                        </div>

                        <div>

                            <h6 class="fw-bold">

                                🕒 Jam Operasional

                            </h6>

                            <p class="text-muted mb-0">

                                Senin - Jumat

                                <br>

                                08.00 - 17.00 WIB

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Contact Form --}}
            <div class="col-lg-7">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h3 class="fw-bold mb-4">

                            Kirim Pesan

                        </h3>

                        <form action="#"
                              method="POST">

                            @csrf

                            <div class="mb-3">

                                <label
                                    for="name"
                                    class="form-label">

                                    Nama

                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    class="form-control"
                                    placeholder="Masukkan nama Anda">

                            </div>

                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    class="form-control"
                                    placeholder="Masukkan email Anda">

                            </div>

                            <div class="mb-3">

                                <label
                                    for="subject"
                                    class="form-label">

                                    Subjek

                                </label>

                                <input
                                    type="text"
                                    id="subject"
                                    class="form-control"
                                    placeholder="Masukkan subjek">

                            </div>

                            <div class="mb-4">

                                <label
                                    for="message"
                                    class="form-label">

                                    Pesan

                                </label>

                                <textarea
                                    id="message"
                                    rows="5"
                                    class="form-control"
                                    placeholder="Tulis pesan Anda..."></textarea>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Kirim Pesan

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection