<footer class="bg-dark text-white mt-auto border-top border-secondary">
    <div class="container py-5">
        <div class="row g-4 justify-content-between align-items-center">
            {{-- Brand Info --}}
            <div class="col-12 col-md-7 col-lg-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="bg-primary text-white rounded-3 px-2.5 py-1 fs-5 shadow-sm">🛍️</span>
                    <h4 class="fw-bold text-white mb-0">Tokobii</h4>
                </div>
                <p class="text-white-50 mb-0 leading-relaxed" style="max-width: 480px;">
                    Platform e-commerce modern terpercaya untuk kebutuhan Alat Tulis Kantor (ATK) berkualitas tinggi dengan proses belanja mudah, cepat, dan transparan.
                </p>
            </div>

            {{-- Contact Info --}}
            <div class="col-12 col-md-5 col-lg-4">
                <h6 class="fw-bold text-uppercase tracking-wider text-primary mb-3">Layanan Customer</h6>
                <div class="d-flex flex-column gap-2 text-white-50 small">
                    <div class="d-flex align-items-center gap-2">
                        <span>📍</span> <span>Jakarta, Indonesia</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>📧</span> <span>support@tokobii.test</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>☎</span> <span>+62 812-3456-7890</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-secondary opacity-25 my-4">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-white-50">
            <div>
                © {{ date('Y') }} <strong class="text-white">Tokobii</strong>. All Rights Reserved.
            </div>
            <div>
                Built with Laravel & Bootstrap 5
            </div>
        </div>
    </div>
</footer>