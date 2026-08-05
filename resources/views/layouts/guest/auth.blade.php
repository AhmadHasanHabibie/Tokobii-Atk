<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>

        @yield('title', 'Tokobii Authentication')

    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-light">

    <div class="container-fluid min-vh-100">

        <div class="row min-vh-100">

            {{-- Left Side --}}
            <div
                class="col-lg-6 d-none d-lg-flex bg-primary text-white justify-content-center align-items-center">

                <div
                    class="text-center px-5">

                    <h1
                        class="display-3 fw-bold mb-4">

                        Tokobii

                    </h1>

                    <p
                        class="lead">

                        Platform pembelian alat tulis kantor yang
                        modern, mudah digunakan, aman, dan terpercaya.

                    </p>

                    <img
                        src="https://placehold.co/500x350?text=Tokobii"
                        class="img-fluid rounded shadow mt-4"
                        alt="Tokobii">

                </div>

            </div>

            {{-- Right Side --}}
            <div
                class="col-lg-6 d-flex justify-content-center align-items-center bg-white">

                <div
                    class="w-100"
                    style="max-width: 450px;">

                    <div
                        class="text-center mb-4">

                        <a
                            href="{{ route('home') }}"
                            class="text-decoration-none">

                            <h2
                                class="fw-bold text-primary mb-2">

                                Tokobii

                            </h2>

                        </a>

                        <p
                            class="text-muted">

                            @yield('subtitle', 'Silakan masuk ke akun Anda.')

                        </p>

                    </div>

                    @if(session('success'))

                        <div
                            class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    @if(session('error'))

                        <div
                            class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                    @endif

                    @if($errors->any())

                        <div
                            class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>

                                        {{ $error }}

                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <div
                        class="card shadow border-0">

                        <div
                            class="card-body p-4">

                            @yield('content')

                        </div>

                    </div>

                    <div
                        class="text-center mt-4">

                        <small
                            class="text-muted">

                            © {{ date('Y') }}
                            Tokobii.
                            All Rights Reserved.

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @stack('scripts')

</body>

</html>