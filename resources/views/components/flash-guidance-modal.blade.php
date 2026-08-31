@if(session('order_guidance') || session('guidance'))
    @php
        $g = session('order_guidance') ?? session('guidance');
        $type = $g['type'] ?? 'success'; // success, info, warning
        $title = $g['title'] ?? 'Aksi Berhasil!';
        $message = $g['message'] ?? 'Permintaan Anda telah berhasil diproses.';
        $invoice = $g['invoice'] ?? null;
        $amount = $g['amount'] ?? null;
        $method = $g['method'] ?? null;
        $steps = $g['steps'] ?? [];
        $primaryBtnText = $g['primary_btn_text'] ?? 'Mengerti & Lanjutkan';
        $primaryBtnUrl = $g['primary_btn_url'] ?? null;
        $secondaryBtnText = $g['secondary_btn_text'] ?? null;
        $secondaryBtnUrl = $g['secondary_btn_url'] ?? null;
    @endphp

    <div class="tokobii-guidance-backdrop show" id="tokobiiGuidanceModal" tabindex="-1" role="dialog" aria-modal="true" onclick="closeTokobiiGuidanceModal()">
        <div class="tokobii-guidance-box p-4 p-md-5 text-center" onclick="event.stopPropagation()">
            
            {{-- Animated Icon --}}
            <div class="tokobii-guidance-icon-wrap {{ $type }}">
                @if($type === 'success')
                    <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                @elseif($type === 'info')
                    <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @elseif($type === 'warning')
                    <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                @endif
            </div>

            <h3 class="fw-bold text-slate-900 mb-2" style="font-size: 1.35rem; color: #0f172a;">
                {{ $title }}
            </h3>

            <p class="text-slate-600 mb-3" style="font-size: 0.9375rem; line-height: 1.6;">
                {{ $message }}
            </p>

            {{-- Optional Key Info Card (Invoice / Method / Amount) --}}
            @if($invoice || $amount || $method)
                <div class="p-3 mb-3 text-start small" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid #e2e8f0; border-radius: 14px;">
                    <div class="row g-2">
                        @if($invoice)
                            <div class="col-6">
                                <span class="text-slate-400 d-block" style="font-size: 0.75rem;">No. Invoice</span>
                                <span class="fw-bold text-blue-600 font-monospace">{{ $invoice }}</span>
                            </div>
                        @endif
                        @if($method)
                            <div class="col-6 text-end">
                                <span class="text-slate-400 d-block" style="font-size: 0.75rem;">Metode Bayar</span>
                                <span class="fw-bold text-slate-800">{{ $method === 'qris' ? 'QRIS Tokobii' : 'Tunai Kasir' }}</span>
                            </div>
                        @endif
                        @if($amount)
                            <div class="col-12 border-top border-slate-200 pt-1.5 mt-1 d-flex justify-content-between">
                                <span class="text-slate-500 fw-semibold">Total Tagihan:</span>
                                <span class="fw-bold text-slate-900 font-monospace">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Step by Step Instructions if any --}}
            @if(!empty($steps))
                <div class="d-flex flex-column gap-2 mb-4 text-start">
                    <span class="text-slate-400 fw-semibold text-uppercase" style="font-size: 0.6875rem; letter-spacing: 0.05em;">Langkah Selanjutnya:</span>
                    @foreach($steps as $idx => $step)
                        <div class="tokobii-guidance-step-card">
                            <span class="tokobii-guidance-step-num">{{ $idx + 1 }}</span>
                            <div style="font-size: 0.8125rem; color: #334155; line-height: 1.45;">
                                {!! $step !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-3">
                @if($secondaryBtnText && $secondaryBtnUrl)
                    <a href="{{ $secondaryBtnUrl }}" class="btn btn-tokobii-secondary flex-grow-1 order-2 order-sm-1">
                        {{ $secondaryBtnText }}
                    </a>
                @endif

                @if($primaryBtnUrl)
                    <a href="{{ $primaryBtnUrl }}" class="btn btn-tokobii-primary flex-grow-1 order-1 order-sm-2">
                        <span>{{ $primaryBtnText }}</span>
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="ms-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                @else
                    <button type="button" class="btn btn-tokobii-primary flex-grow-1 order-1 order-sm-2" onclick="closeTokobiiGuidanceModal()">
                        <span>{{ $primaryBtnText }}</span>
                    </button>
                @endif
            </div>

        </div>
    </div>

    <script>
        function closeTokobiiGuidanceModal() {
            var modal = document.getElementById('tokobiiGuidanceModal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(function() {
                    modal.remove();
                }, 300);
            }
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTokobiiGuidanceModal();
            }
        });
    </script>
@endif
