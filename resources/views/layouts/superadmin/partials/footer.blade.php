<footer class="mt-auto py-4 px-4" style="background: transparent; border-top: 1px solid #f1f5f9;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span class="text-slate-400" style="font-size: 0.78125rem;">
            © {{ date('Y') }} <strong class="text-slate-600 fw-semibold">Tokobii</strong>. Panel Kontrol Superadmin.
        </span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-slate-400" style="font-size: 0.71875rem;">
                Tokobii System Management
            </span>
            <span class="text-slate-300 font-monospace" style="font-size: 0.71875rem;">|</span>
            <span class="text-slate-400 font-monospace" style="font-size: 0.71875rem;">
                WIB: <span id="socLiveClock">{{ now()->format('H:i:s') }}</span>
            </span>
        </div>
    </div>
</footer>

<script>
    setInterval(() => {
        const el = document.getElementById('socLiveClock');
        if (el) {
            const d = new Date();
            el.innerText = d.toTimeString().split(' ')[0];
        }
    }, 1000);
</script>
