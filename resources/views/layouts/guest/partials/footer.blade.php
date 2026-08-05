<footer class="bg-dark text-white mt-5">

    <div class="container py-5">

        <div class="row">

            {{-- Brand --}}
            <div class="col-lg-4 mb-4">

                <h4 class="fw-bold">

                    Tokobii

                </h4>

                <p class="text-light mb-0">

                    Tokobii adalah platform belanja online yang menyediakan
                    berbagai kebutuhan alat tulis kantor dengan mudah,
                    cepat, dan terpercaya.

                </p>

            </div>

            {{-- Navigation --}}
            <div class="col-lg-4 mb-4">

                <h5 class="fw-bold">

                    Navigation

                </h5>

                <ul class="list-unstyled">

                    <li class="mb-2">

                        <a href="{{ route('home') }}"
                           class="text-decoration-none text-light">

                            Home

                        </a>

                    </li>

                    <li class="mb-2">

                        <a href="{{ route('shop') }}"
                           class="text-decoration-none text-light">

                            Shop

                        </a>

                    </li>

                    <li class="mb-2">

                        <a href="{{ route('about') }}"
                           class="text-decoration-none text-light">

                            About

                        </a>

                    </li>

                    <li>

                        <a href="{{ route('contact') }}"
                           class="text-decoration-none text-light">

                            Contact

                        </a>

                    </li>

                </ul>

            </div>

            {{-- Contact --}}
            <div class="col-lg-4 mb-4">

                <h5 class="fw-bold">

                    Contact

                </h5>

                <p class="mb-2">

                    📍 Jakarta, Indonesia

                </p>

                <p class="mb-2">

                    📧 support@tokobii.test

                </p>

                <p class="mb-0">

                    ☎ +62 812-3456-7890

                </p>

            </div>

        </div>

        <hr class="border-secondary">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">

            <small class="text-light">

                © {{ date('Y') }}
                <strong>Tokobii</strong>.
                All Rights Reserved.

            </small>

            <small class="text-secondary mt-2 mt-md-0">

                Built with Laravel 10 & Bootstrap 5

            </small>

        </div>

    </div>

</footer>