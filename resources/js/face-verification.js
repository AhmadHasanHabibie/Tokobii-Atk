import humanEngine from './face/humanEngine.js';

/**
 * Tokobii Face Verification & High-Performance Enrollment Engine v4.2
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Fitur Utama:
 * 1. Dual-Pipeline Fast Path (Tracking ~20-30 FPS) & Heavy Path (Capture Snapshot).
 * 2. Sudut Kepala Euler Angles dalam satuan DERAJAT mutlak dengan batas toleransi adaptif.
 * 3. Deteksi tolehan kepala (Kiri / Kanan) responsif dalam < 1 detik.
 * 4. Fallback Anti-Macet (Tombol Lewati Langkah setelah 8 detik).
 * 5. Kecerahan Face ROI cerdas (kebal terhadap backlight / latar terang).
 * 6. UX Konfirmasi Password ramah & penyimpanan biometrik terproteksi.
 * 7. Reaktivitas DOM instan (update badge status profil tanpa reload).
 */
class TokobiiFaceVerification {
    static STAGE = Object.freeze({
        INITIALIZING: 'INITIALIZING',
        SEARCHING:    'SEARCHING',
        CHALLENGE:    'CHALLENGE',
        SAMPLING:     'SAMPLING',
        SUBMITTING:   'SUBMITTING',
        FINISHED:     'FINISHED',
    });

    constructor(config = {}) {
        const faceConfig = (typeof window !== 'undefined' && window.FACE_CONFIG)
            ? window.FACE_CONFIG
            : null;

        const modelBase = config.modelsUri || config.modelBasePath || faceConfig?.modelBase || '';
        const wasmBase  = config.wasmPath || faceConfig?.wasmBase || modelBase;
        const debugMode = Boolean(config.debug ?? faceConfig?.debug ?? false);

        this.config = Object.assign({
            mode: 'verify',                 // 'verify' | 'enroll'
            videoElementId: 'faceVideo',
            canvasElementId: 'faceCanvas',
            statusElementId: 'faceStatus',
            instructionElementId: 'faceInstruction',
            progressBarId: 'faceProgress',
            ovalGuideId: 'faceOvalGuide',
            retryButtonId: 'btnRetryFace',
            cameraSelectId: 'faceCameraSelect',
            modelsUri: modelBase,
            wasmUri: wasmBase,
            verifyUrl: faceConfig?.endpoints?.verify || '/verify-face',
            challengeUrl: faceConfig?.endpoints?.challenge || '/verify-face/challenge-data',
            enrollUrl: null,
            csrfToken: faceConfig?.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            thresholds: faceConfig?.thresholds || { match: 0.70, antispoof: 0.35, liveness: 0.35 },
            onSuccess: null,
            onError: null,
            debug: debugMode,
        }, config);

        // ─── DOM Elements ───
        this.video         = document.getElementById(this.config.videoElementId);
        this.canvas        = document.getElementById(this.config.canvasElementId);
        this.statusEl      = document.getElementById(this.config.statusElementId);
        this.instructionEl = document.getElementById(this.config.instructionElementId);
        this.progressBar   = document.getElementById(this.config.progressBarId);
        this.ovalGuide     = document.getElementById(this.config.ovalGuideId);
        this.retryBtn      = document.getElementById(this.config.retryButtonId);
        this.cameraSelect  = document.getElementById(this.config.cameraSelectId);

        // Tombol Bantu UX (Skip & Simpan)
        this.skipBtn       = null;
        this.saveEnrollBtn = null;

        // ─── State Machine ───
        this.currentStage   = TokobiiFaceVerification.STAGE.INITIALIZING;
        this.stream         = null;
        this.isDetecting    = false;
        this.isCapturing    = false; // Flag saat jalur berat aktif
        this.isStopped      = false;
        this.animFrameId    = null;
        this.lastFrameTime  = 0;
        this.frameThrottle  = 50; // ms throttle (~20 FPS optimal untuk WebGL tracking)

        // ─── FPS & Telemetry ───
        this.currentFps     = 0;
        this._fpsCount      = 0;
        this._lastFpsTime   = 0;
        this.lastQGError    = '';

        // ─── Challenge & Liveness State (Mode Verify) ───
        this.challenge = {
            nonce: null,
            action: null,
            prompt: null,
            passed: false,
            baselineEAR: 0.30,
            blinkFrames: 0,
        };

        // ─── Enrollment Steps (Toleran, Adaptif, dan Terkalibrasi dalam DERAJAT) ───
        // Konvensi arah:
        // userYaw < 0 = tolehan ke KIRI (arah kiri layar cermin)
        // userYaw > 0 = tolehan ke KANAN (arah kanan layar cermin)
        this.enrollSteps = [
            {
                id: 'center',
                prompt: 'Posisikan wajah menghadap lurus ke depan',
                hint: 'Hadapkan wajah tenang ke kamera',
                condition: (f) => Math.abs(f.rotation.yaw) <= 10 && Math.abs(f.rotation.pitch) <= 15,
                canSkip: false,
            },
            {
                id: 'left',
                prompt: 'Tengok kepala ke kiri sedikit',
                hint: 'Tengok sekitar 15°–25° ke kiri Anda',
                condition: (f) => f.rotation.yaw <= -12 && f.rotation.yaw >= -38,
                canSkip: true,
            },
            {
                id: 'right',
                prompt: 'Tengok kepala ke kanan sedikit',
                hint: 'Tengok sekitar 15°–25° ke kanan Anda',
                condition: (f) => f.rotation.yaw >= 12 && f.rotation.yaw <= 38,
                canSkip: true,
            },
            {
                id: 'smile',
                prompt: 'Tersenyumlah sedikit ke arah kamera',
                hint: 'Tersenyumlah santai',
                condition: (f) => f.mar >= 0.28,
                canSkip: true,
            },
            {
                id: 'neutral',
                prompt: 'Hadap lurus tenang untuk sampel akhir',
                hint: 'Posisikan wajah lurus dan rileks',
                condition: (f) => Math.abs(f.rotation.yaw) <= 10 && Math.abs(f.rotation.pitch) <= 15,
                canSkip: false,
            },
        ];

        this.currentEnrollStepIndex = 0;
        this.collectedSamples       = [];
        this.poseHoldFrames         = 0;
        this.stepStartTime          = Date.now();

        // ─── Verification Samples Buffer ───
        this.verifyBuffers = {
            embeddings: [],
            antispoofScores: [],
            livenessScores: [],
        };

        // ─── Performance Monitoring ───
        this.startTime = performance.now();
        this.perf = {
            modelLoadMs: 0,
            cameraStartupMs: 0,
            firstDetectionMs: 0,
            decisionMs: 0,
        };

        this._hasRetryListener = false;
        this._hasCameraListener = false;

        if (typeof window !== 'undefined') {
            window.addEventListener('resize', () => this._syncCanvas());
            window.addEventListener('orientationchange', () => {
                setTimeout(() => this._syncCanvas(), 150);
            });
        }

        if (typeof ResizeObserver !== 'undefined' && this.video) {
            this._resizeObserver = new ResizeObserver(() => {
                this._syncCanvas();
            });
            this._resizeObserver.observe(this.video);
        }

        this._setupAuxiliaryButtons();
        this._init();
    }

    /**
     * Siapkan tombol bantuan UI (Skip step & Konfirmasi Simpan).
     * PENTING: Hanya aktif pada mode 'enroll' (Pendaftaran Biometrik),
     * TIDAK PERNAH aktif pada mode 'verify' (Login Verifikasi).
     */
    _setupAuxiliaryButtons() {
        if (this.config.mode !== 'enroll') {
            return;
        }

        const modalFooter = document.querySelector('#enrollFaceModal .modal-footer');
        if (!modalFooter) {
            return;
        }

        // Tombol Lewati Langkah (Fallback Anti-Macet Pose Enrollment)
        this.skipBtn = document.getElementById('btnSkipEnrollStep');
        if (!this.skipBtn) {
            this.skipBtn = document.createElement('button');
            this.skipBtn.id = 'btnSkipEnrollStep';
            this.skipBtn.type = 'button';
            this.skipBtn.className = 'btn btn-outline-secondary btn-sm';
            this.skipBtn.style.display = 'none';
            this.skipBtn.innerHTML = '<i class="bi bi-skip-forward me-1"></i> Lewati Langkah';
            this.skipBtn.addEventListener('click', () => this._skipCurrentStep());
            modalFooter.insertBefore(this.skipBtn, modalFooter.firstChild);
        } else if (!this.skipBtn._boundClick) {
            this.skipBtn.addEventListener('click', () => this._skipCurrentStep());
            this.skipBtn._boundClick = true;
        }

        // Tombol Simpan Biometrik (jika password diisi belakangan)
        this.saveEnrollBtn = document.getElementById('btnSaveEnrollFace');
        if (!this.saveEnrollBtn) {
            this.saveEnrollBtn = document.createElement('button');
            this.saveEnrollBtn.id = 'btnSaveEnrollFace';
            this.saveEnrollBtn.type = 'button';
            this.saveEnrollBtn.className = 'btn btn-primary btn-sm fw-semibold';
            this.saveEnrollBtn.style.display = 'none';
            this.saveEnrollBtn.innerHTML = '<i class="bi bi-shield-lock-fill me-1"></i> Simpan Biometrik';
            this.saveEnrollBtn.addEventListener('click', () => this._manualTriggerSave());
            modalFooter.appendChild(this.saveEnrollBtn);
        } else if (!this.saveEnrollBtn._boundClick) {
            this.saveEnrollBtn.addEventListener('click', () => this._manualTriggerSave());
            this.saveEnrollBtn._boundClick = true;
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  INITIALIZATION & BOOTSTRAP
    // ═══════════════════════════════════════════════════════════════════

    async _init() {
        if (this.retryBtn && !this._hasRetryListener) {
            this.retryBtn.addEventListener('click', () => this.restart());
            this._hasRetryListener = true;
        }
        if (this.cameraSelect && !this._hasCameraListener) {
            this.cameraSelect.addEventListener('change', () => {
                const devId = this.cameraSelect.value;
                try {
                    localStorage.setItem('tokobii_preferred_camera', devId);
                } catch (e) {}
                this._startCamera(devId);
            });
            this._hasCameraListener = true;
        }

        this._setStatus('Menyiapkan AI Biometrik...', 'info');
        this._setInstruction('Memeriksa model GPU dan menginisialisasi kamera...');
        this._setProgress(15);
        this._setOvalGuideState('searching');

        try {
            // Paralel: setup kamera dan load engine Human
            await Promise.all([
                this._loadEngine(),
                this._setupCamera(),
            ]);

            if (this.config.mode === 'verify') {
                await this._fetchChallengeData();
            }

            this.currentStage = (this.config.mode === 'enroll')
                ? TokobiiFaceVerification.STAGE.SAMPLING
                : TokobiiFaceVerification.STAGE.SEARCHING;

            this.stepStartTime = Date.now();
            this._setStatus('Mencari Wajah...', 'info');
            this._updateStepInstruction();
            this._setProgress(20);

            if (this.config.debug) {
                this._renderDebugPanel();
            }

            this._startLoop();
        } catch (err) {
            this._stopCamera();
            this.isStopped = true;
            this.isDetecting = false;

            let displayStatus = 'Gagal Memuat Kamera/Model';
            let displayInstruction = 'Terjadi kendala pada sistem biometrik.';

            if (err.failedModels && err.failedModels.length > 0) {
                const first = err.failedModels[0];
                const statusTxt = first.status ? `(${first.status})` : '(Network Error)';
                displayStatus = `Model '${first.model}' tidak ditemukan ${statusTxt}`;
                displayInstruction = `URL: ${first.url}`;
                console.error(`[TokobiiFace] GAGAL PREFLIGHT: Model ${first.model} ${statusTxt} di ${first.url}`);
            } else if (err.message && err.message.includes('FACE_CONFIG')) {
                displayStatus = 'Konfigurasi Face Tidak Ditemukan';
                displayInstruction = err.message;
            } else {
                displayStatus = err.message || 'Gagal Memuat Kamera/Model';
                displayInstruction = 'Pastikan izin kamera diberikan dan koneksi stabil.';
            }

            this._setStatus(displayStatus, 'danger');
            this._setInstruction(displayInstruction);
            this._setOvalGuideState('danger');
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

            if (this.config.debug) {
                this._renderDebugPanel();
            }

            if (typeof this.config.onError === 'function') {
                this.config.onError(err);
            }
        }
    }

    async _loadEngine() {
        const t0 = performance.now();

        humanEngine.init({
            modelsUri: this.config.modelsUri,
            wasmPath: this.config.wasmUri,
            debug: this.config.debug,
        });

        await humanEngine.load((prog) => {
            if (this.progressBar) {
                this._setProgress(prog.percent || 25);
            }
            if (prog.message) {
                this._setInstruction(prog.message);
            }
        });

        this.perf.modelLoadMs = Math.round(performance.now() - t0);
        this._log(`Human Engine siap dalam ${this.perf.modelLoadMs}ms`);
    }

    async _fetchChallengeData() {
        try {
            const res = await fetch(this.config.challengeUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
            });
            const data = await res.json();
            if (res.ok && data.success) {
                this.challenge.nonce  = data.nonce;
                this.challenge.action = data.action;
                this.challenge.prompt = data.prompt;
                this._log(`Challenge diterima: [${data.action}] - ${data.prompt}`);
            }
        } catch (e) {
            this._log('Warning: Menggunakan challenge default', e);
            this.challenge.action = 'blink';
            this.challenge.prompt = 'Kedipkan kedua mata Anda secara perlahan';
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CAMERA MANAGEMENT
    // ═══════════════════════════════════════════════════════════════════

    async _setupCamera() {
        if (!navigator.mediaDevices?.getUserMedia) {
            throw new Error('Akses kamera WebRTC tidak didukung browser ini.');
        }

        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');

            let savedCameraId = null;
            try {
                savedCameraId = localStorage.getItem('tokobii_preferred_camera');
            } catch (e) {}

            if (this.cameraSelect) {
                this.cameraSelect.innerHTML = '';
                videoDevices.forEach((device, idx) => {
                    const opt = document.createElement('option');
                    opt.value = device.deviceId;
                    let label = device.label || '';
                    if (!label) {
                        label = (idx === 0) ? 'Kamera Utama (Depan)' : `Kamera ${idx + 1}`;
                    } else {
                        const lower = label.toLowerCase();
                        if (lower.includes('front') || lower.includes('user') || lower.includes('depan')) {
                            label = `Kamera Depan (${label})`;
                        } else if (lower.includes('back') || lower.includes('environment') || lower.includes('belakang')) {
                            label = `Kamera Belakang (${label})`;
                        }
                    }
                    opt.text = label;
                    this.cameraSelect.appendChild(opt);
                });
                this.cameraSelect.style.display = videoDevices.length > 1 ? 'block' : 'none';
            }

            let initialId = undefined;
            if (savedCameraId && videoDevices.some(d => d.deviceId === savedCameraId)) {
                initialId = savedCameraId;
                if (this.cameraSelect) this.cameraSelect.value = initialId;
            } else if (videoDevices.length > 0) {
                initialId = videoDevices[0].deviceId;
                if (this.cameraSelect) this.cameraSelect.value = initialId;
            }

            await this._startCamera(initialId);
            return true;
        } catch (err) {
            this._log('Camera setup error:', err);
            throw err;
        }
    }

    async _startCamera(deviceId = undefined) {
        this._stopCamera();
        this.isStopped = false;
        const t0 = performance.now();

        const constraints = {
            video: deviceId
                ? { deviceId: { exact: deviceId }, width: { ideal: 640 }, height: { ideal: 480 }, frameRate: { ideal: 30 } }
                : { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 }, frameRate: { ideal: 30 } },
            audio: false,
        };

        this.stream = await navigator.mediaDevices.getUserMedia(constraints);
        if (!this.video) return;

        this.video.srcObject = this.stream;
        this.video.setAttribute('playsinline', 'true');
        this.video.setAttribute('muted', 'true');

        await new Promise((resolve) => {
            const checkReady = () => {
                if (this.video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA && this.video.videoWidth > 0) {
                    resolve();
                } else {
                    setTimeout(checkReady, 25);
                }
            };
            this.video.onloadedmetadata = () => {
                this.video.play().catch(() => {});
                checkReady();
            };
            setTimeout(checkReady, 300);
        });

        this.perf.cameraStartupMs = Math.round(performance.now() - t0);
        this._syncCanvas();

        if (this.config.debug) {
            console.log('[TokobiiFace] Kamera aktif:', {
                videoWidth: this.video.videoWidth,
                videoHeight: this.video.videoHeight,
                clientWidth: this.video.clientWidth,
                clientHeight: this.video.clientHeight,
                deviceId: deviceId || 'default'
            });
        }
    }

    _stopCamera() {
        if (this.animFrameId) {
            cancelAnimationFrame(this.animFrameId);
            this.animFrameId = null;
        }
        humanEngine.stopStream(this.stream);
        this.stream = null;
        if (this.video) {
            this.video.srcObject = null;
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  FAST-PATH TRACKING LOOP (BlazeFace + FaceMesh ~20 FPS)
    // ═══════════════════════════════════════════════════════════════════

    _startLoop() {
        const loop = async (timestamp) => {
            if (this.isStopped || this.currentStage === TokobiiFaceVerification.STAGE.FINISHED) {
                return;
            }

            // Hitung FPS Riil
            if (this._lastFpsTime) {
                const delta = timestamp - this._lastFpsTime;
                this._fpsCount = (this._fpsCount || 0) + 1;
                if (delta >= 1000) {
                    this.currentFps = Math.round((this._fpsCount * 1000) / delta);
                    this._fpsCount = 0;
                    this._lastFpsTime = timestamp;
                    const fpsEl = document.getElementById('faceDebugFps');
                    if (fpsEl) fpsEl.textContent = `FPS: ${this.currentFps}`;
                }
            } else {
                this._lastFpsTime = timestamp;
                this._fpsCount = 0;
            }

            // Throttle ringan (50ms) untuk mencegah beban berlebih tanpa mengorbankan kehalusan
            if (!this.isDetecting && !this.isCapturing && (timestamp - this.lastFrameTime >= this.frameThrottle)) {
                this.lastFrameTime = timestamp;
                this.isDetecting = true;
                try {
                    await this._processFrame();
                } catch (err) {
                    this._log('Frame error:', err);
                } finally {
                    this.isDetecting = false;
                }
            }

            this.animFrameId = requestAnimationFrame(loop);
        };

        this.animFrameId = requestAnimationFrame(loop);
    }

    async _processFrame() {
        if (!this.video || this.video.paused || this.video.ended || !humanEngine.isLoaded) {
            return;
        }

        // 1. Eksekusi JALUR CEPAT (Tracking Ringan: BlazeFace + FaceMesh)
        const face = await humanEngine.detectTracking(this.video);

        // 2. Jika wajah tidak ditemukan
        if (!face) {
            this._clearCanvas();
            this.poseHoldFrames = 0;
            this.lastQGError = 'Wajah tidak terdeteksi';
            this._setStatus('Mencari Wajah...', 'info');
            this._setOvalGuideState('searching');
            if (this.config.debug) this._renderDebugPanel(face);
            return;
        }

        if (this.perf.firstDetectionMs === 0) {
            this.perf.firstDetectionMs = Math.round(performance.now() - this.startTime);
            this._log(`Wajah pertama terdeteksi dalam ${this.perf.firstDetectionMs}ms`);
        }

        // 3. Render Canvas Landmark & Bounding Box Halus
        this._drawCanvas(face);

        // 4. Quality Gate Ringan (Posisi, Ukuran, Cahaya Wajah)
        const qg = this._evaluateQualityGate(face);
        if (!qg.valid) {
            this.poseHoldFrames = 0;
            this.lastQGError = qg.message;
            this._setStatus(qg.status, qg.state);
            this._setInstruction(qg.message);
            this._setOvalGuideState(qg.state);
            if (this.config.debug) this._renderDebugPanel(face);
            return;
        }

        this.lastQGError = 'Lolos Quality Gate';

        // 5. Eksekusi Sesuai Mode (Enroll vs Verify)
        if (this.config.mode === 'enroll') {
            await this._handleEnrollFrame(face);
        } else {
            await this._handleVerifyFrame(face);
        }

        if (this.config.debug) {
            this._renderDebugPanel(face);
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  QUALITY GATE YANG ADIL & SPESIFIK
    // ═══════════════════════════════════════════════════════════════════

    _evaluateQualityGate(face) {
        // Cek jumlah wajah (Harus tepat 1 orang)
        if (face.allFacesCount > 1) {
            return { valid: false, status: 'Lebih dari 1 Wajah', message: 'Hanya 1 wajah yang diizinkan di depan kamera.', state: 'danger' };
        }

        // Cek confidence deteksi
        if (face.score < 0.45) {
            return { valid: false, status: 'Wajah Kurang Jelas', message: 'Tingkatkan pencahayaan atau dekatkan wajah.', state: 'warning' };
        }

        // Geometri Bounding Box yang dipetakan ke koordinat container tampilan (Display Space)
        const mapped = this._mapVideoToDisplay(face.box.x, face.box.y, face.box.width, face.box.height);
        const relW = mapped.width / mapped.cw;
        const centerX = (mapped.x + mapped.width / 2) / mapped.cw;
        const centerY = (mapped.y + mapped.height / 2) / mapped.ch;

        if (relW < 0.18) return { valid: false, status: 'Terlalu Jauh', message: 'Dekatkan wajah sedikit ke kamera.', state: 'warning' };
        if (relW > 0.85) return { valid: false, status: 'Terlalu Dekat', message: 'Mundur sedikit dari kamera.', state: 'warning' };
        if (centerX < 0.18) return { valid: false, status: 'Geser ke Kanan', message: 'Posisikan wajah di tengah bingkai oval.', state: 'warning' };
        if (centerX > 0.82) return { valid: false, status: 'Geser ke Kiri', message: 'Posisikan wajah di tengah bingkai oval.', state: 'warning' };
        if (centerY < 0.12) return { valid: false, status: 'Turunkan Sedikit', message: 'Posisikan wajah tepat di dalam oval.', state: 'warning' };
        if (centerY > 0.88) return { valid: false, status: 'Naikkan Sedikit', message: 'Posisikan wajah tepat di dalam oval.', state: 'warning' };

        // Cek Pencahayaan Khusus Wajah (Face ROI)
        if (face.faceBrightness < 28) {
            return { valid: false, status: 'Cahaya Wajah Redup', message: 'Nyalakan lampu atau hadap sumber cahaya.', state: 'warning' };
        }

        return { valid: true };
    }

    // ═══════════════════════════════════════════════════════════════════
    //  ENROLLMENT HANDLER DENGAN HEAVY-PATH CAPTURE SNAPSHOT
    // ═══════════════════════════════════════════════════════════════════

    async _handleEnrollFrame(face) {
        const step = this.enrollSteps[this.currentEnrollStepIndex];
        if (!step) return;

        // Cek apakah pose saat ini memenuhi kriteria langkah aktif
        const isPoseSatisfied = step.condition(face);

        // Feedback Dinamis Progresif Saat Menoleh
        this._providePoseFeedback(step, face, isPoseSatisfied);

        // Fitur Tombol Lewati Langkah setelah 8 detik
        this._checkStepTimeout(step);

        if (isPoseSatisfied) {
            this.poseHoldFrames++;
            this._setOvalGuideState('success');

            // Feedback visual: hitung frame penahanan (butuh 3 frame stabil ~150ms)
            if (this.poseHoldFrames < 3) {
                this._setStatus(`Tahan Posisi... (${this.poseHoldFrames}/3)`, 'success');
                return;
            }

            // ─── POSE TERCAPAI! PICU JALUR BERAT (HEAVY PATH CAPTURE) ───
            this.isCapturing = true;
            this._setStatus('Mengambil Sampel Biometrik...', 'info');
            this._setInstruction('Mengekstrak embedding biometrik & anti-spoofing...');

            try {
                // Jalankan FaceRes (1024-D) + AntiSpoof + Liveness SEKALI pada frame saat ini
                const captureResult = await humanEngine.detectCapture(this.video);

                if (captureResult && captureResult.embedding) {
                    const antispoofThresh = this.config.thresholds?.antispoof || 0.35;

                    // Evaluasi Anti-Spoofing
                    if (captureResult.realScore < antispoofThresh) {
                        this._setStatus('Wajah Asli Belum Terkonfirmasi', 'warning');
                        this._setInstruction('Hindari pantulan layar atau foto cetak. Hadapkan wajah asli Anda.');
                        this.poseHoldFrames = 0;
                        this.isCapturing = false;
                        return;
                    }

                    // Sampel Berhasil Diterima!
                    this.collectedSamples.push(captureResult.embedding);
                    this.currentEnrollStepIndex++;
                    this.poseHoldFrames = 0;
                    this.stepStartTime = Date.now();

                    // Efek Visual Sukses (Flash Hijau)
                    this._flashSuccessOval();

                    const progressPct = Math.round((this.currentEnrollStepIndex / this.enrollSteps.length) * 85) + 10;
                    this._setProgress(progressPct);
                    this._setStatus(`Sampel ${this.currentEnrollStepIndex}/${this.enrollSteps.length} Terambil ✓`, 'success');

                    this._log(`✓ Sampel ${this.currentEnrollStepIndex} (${step.id}) berhasil diambil.`);

                    if (this.currentEnrollStepIndex >= this.enrollSteps.length) {
                        // Seluruh 5 sampel lengkap!
                        await this._finishEnrollment();
                    } else {
                        this._updateStepInstruction();
                    }
                } else {
                    this.poseHoldFrames = 0;
                }
            } catch (captureErr) {
                this._log('Capture snapshot error:', captureErr);
                this.poseHoldFrames = 0;
            } finally {
                this.isCapturing = false;
            }
        } else {
            this.poseHoldFrames = 0;
        }
    }

    _providePoseFeedback(step, face, isSatisfied) {
        if (isSatisfied) return;

        const yaw = face.rotation.yaw; // userYaw: - = kiri, + = kanan

        if (step.id === 'left') {
            if (yaw > -12) {
                const remaining = Math.max(0, Math.round(15 - Math.abs(yaw)));
                this._setStatus('Tengok ke Kiri', 'info');
                this._setInstruction(`Tengok sedikit lagi ke kiri (~${remaining}° lagi)...`);
            } else if (yaw < -38) {
                this._setStatus('Terlalu Jauh', 'warning');
                this._setInstruction('Tolehannya terlalu jauh ke kiri. Kembalikan sedikit ke depan.');
            }
        } else if (step.id === 'right') {
            if (yaw < 12) {
                const remaining = Math.max(0, Math.round(15 - yaw));
                this._setStatus('Tengok ke Kanan', 'info');
                this._setInstruction(`Tengok sedikit lagi ke kanan (~${remaining}° lagi)...`);
            } else if (yaw > 38) {
                this._setStatus('Terlalu Jauh', 'warning');
                this._setInstruction('Tolehannya terlalu jauh ke kanan. Kembalikan sedikit ke depan.');
            }
        } else if (step.id === 'smile') {
            this._setStatus('Tersenyumlah', 'info');
            this._setInstruction('Tersenyumlah sedikit lebih lebar ke arah kamera.');
        } else {
            this._setStatus('Posisikan Wajah Lurus', 'info');
            this._setInstruction(step.prompt);
        }

        this._setOvalGuideState('searching');
    }

    _checkStepTimeout(step) {
        if (!step.canSkip || !this.skipBtn) return;

        const elapsedSec = (Date.now() - this.stepStartTime) / 1000;
        if (elapsedSec >= 8 && this.skipBtn.style.display === 'none') {
            this.skipBtn.style.display = 'inline-flex';
            this.skipBtn.textContent = `Lewati ${step.prompt}`;
        }
    }

    _skipCurrentStep() {
        if (this.skipBtn) this.skipBtn.style.display = 'none';

        this.currentEnrollStepIndex++;
        this.poseHoldFrames = 0;
        this.stepStartTime = Date.now();

        this._log(`Langkah dilewati oleh pengguna. Posisi sekarang: ${this.currentEnrollStepIndex}`);

        if (this.currentEnrollStepIndex >= this.enrollSteps.length) {
            this._finishEnrollment();
        } else {
            this._updateStepInstruction();
        }
    }

    _flashSuccessOval() {
        if (!this.ovalGuide) return;
        this.ovalGuide.style.borderColor = '#10b981';
        this.ovalGuide.style.boxShadow = '0 0 25px rgba(16, 185, 129, 0.8), 0 0 0 9999px rgba(15, 23, 42, 0.55)';
        setTimeout(() => {
            if (this.ovalGuide) {
                this.ovalGuide.style.borderColor = 'rgba(59, 130, 246, 0.8)';
                this.ovalGuide.style.boxShadow = '0 0 0 9999px rgba(15, 23, 42, 0.55)';
            }
        }, 350);
    }

    _updateStepInstruction() {
        if (this.skipBtn) this.skipBtn.style.display = 'none';

        if (this.config.mode === 'enroll') {
            const step = this.enrollSteps[this.currentEnrollStepIndex];
            if (step) {
                this._setInstruction(`[Langkah ${this.currentEnrollStepIndex + 1}/${this.enrollSteps.length}] ${step.prompt}`);
            }
        } else if (this.challenge.prompt) {
            this._setInstruction(this.challenge.prompt);
        }
    }

    async _finishEnrollment() {
        // Minimal 3 sampel valid harus terkumpul
        if (this.collectedSamples.length < 3) {
            this._setStatus('Sampel Kurang', 'warning');
            this._setInstruction('Minimal 3 pose sampel wajah dibutuhkan. Silakan ulangi langkah.');
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
            return;
        }

        const passwordInput = document.getElementById('enrollPassword')
            || document.getElementById('enroll_admin_face_password')
            || document.getElementById('enroll_owner_face_password');

        const password = passwordInput?.value?.trim() || '';

        // Jika password sudah diisi, kirim langsung!
        if (password) {
            await this._executeSaveEnrollment(password);
        } else {
            // Jika belum diisi, jangan hentikan kamera atau gagalkan flow! Beri panduan jelas ke user:
            this._setStatus('Perekaman Selesai! ✓', 'success');
            this._setInstruction('Masukkan kata sandi akun Anda di atas, lalu klik "Simpan Biometrik".');
            this._setOvalGuideState('success');
            this._setProgress(95);

            if (passwordInput) {
                passwordInput.classList.add('border-primary', 'shadow-sm');
                passwordInput.focus();
            }

            if (this.saveEnrollBtn) {
                this.saveEnrollBtn.style.display = 'inline-flex';
            }
        }
    }

    async _manualTriggerSave() {
        const passwordInput = document.getElementById('enrollPassword')
            || document.getElementById('enroll_admin_face_password')
            || document.getElementById('enroll_owner_face_password');

        const password = passwordInput?.value?.trim() || '';

        if (!password) {
            this._setStatus('Kata Sandi Wajib Diisi', 'danger');
            this._setInstruction('Ketik kata sandi akun Anda pada kotak input di atas.');
            if (passwordInput) passwordInput.focus();
            return;
        }

        await this._executeSaveEnrollment(password);
    }

    async _executeSaveEnrollment(password) {
        this.currentStage = TokobiiFaceVerification.STAGE.SUBMITTING;
        this._setStatus('Menyimpan Biometrik...', 'info');
        this._setInstruction('Mengenkripsi vektor biometrik dan mendaftarkan ke server...');
        this._setProgress(95);

        if (this.saveEnrollBtn) this.saveEnrollBtn.disabled = true;

        const centroid = this._computeCentroid(this.collectedSamples);

        try {
            const res = await fetch(this.config.enrollUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                body: JSON.stringify({
                    password,
                    descriptor: centroid,
                    samples: this.collectedSamples,
                }),
            });

            const data = await res.json();

            if (res.ok && data.success) {
                this._setStatus('Pendaftaran Berhasil! ✓', 'success');
                this._setInstruction(data.message || 'Biometrik wajah berhasil didaftarkan.');
                this._setOvalGuideState('success');
                this._setProgress(100);
                this.stop();

                // Update DOM profile reaktif tanpa reload paksa
                this._updateProfileBadgeAfterSuccess();

                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(data);
                } else {
                    // Tutup modal secara anggun jika menggunakan Bootstrap Modal
                    setTimeout(() => {
                        const modalEl = document.getElementById('enrollFaceModal');
                        if (modalEl && window.bootstrap?.Modal) {
                            const modal = window.bootstrap.Modal.getInstance(modalEl);
                            modal?.hide();
                        }
                    }, 1200);
                }
            } else {
                this._setStatus(data.message || 'Pendaftaran Gagal', 'danger');
                this._setInstruction(data.message || 'Kata sandi salah atau format biometrik tidak valid.');
                this._setOvalGuideState('danger');
                if (this.saveEnrollBtn) {
                    this.saveEnrollBtn.disabled = false;
                    this.saveEnrollBtn.style.display = 'inline-flex';
                }
                if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

                if (typeof this.config.onError === 'function') {
                    this.config.onError(data);
                }
            }
        } catch (err) {
            this._setStatus('Kesalahan Jaringan', 'danger');
            this._setInstruction('Gagal menghubungi server. Periksa koneksi internet Anda.');
            this._setOvalGuideState('danger');
            if (this.saveEnrollBtn) this.saveEnrollBtn.disabled = false;
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
        }
    }

    _updateProfileBadgeAfterSuccess() {
        // Cari container badge di halaman profil dan ubah menjadi hijau "Aktif"
        const badges = document.querySelectorAll('.tokobii-card .tokobii-badge');
        badges.forEach((b) => {
            if (b.textContent.includes('Belum Aktif')) {
                b.className = 'tokobii-badge bg-emerald-50 text-emerald-700 border-emerald-200';
                b.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Aktif';
            }
        });
    }

    _computeCentroid(samples) {
        if (!samples || samples.length === 0) return [];
        const dim = samples[0].length;
        const count = samples.length;
        const centroid = new Array(dim).fill(0);

        for (let i = 0; i < count; i++) {
            for (let j = 0; j < dim; j++) {
                centroid[j] += samples[i][j];
            }
        }

        let norm = 0;
        for (let j = 0; j < dim; j++) {
            centroid[j] /= count;
            norm += centroid[j] * centroid[j];
        }

        norm = Math.sqrt(norm);
        if (norm > 0) {
            for (let j = 0; j < dim; j++) {
                centroid[j] /= norm;
            }
        }

        return centroid;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  VERIFICATION HANDLER (Mode Login)
    // ═══════════════════════════════════════════════════════════════════

    async _handleVerifyFrame(face) {
        // Evaluasi Interactive Challenge jika belum lolos
        if (!this.challenge.passed) {
            this.currentStage = TokobiiFaceVerification.STAGE.CHALLENGE;
            this._setOvalGuideState('liveness');
            this._setProgress(45);
            this._setStatus('Uji Gerakan Aktif...', 'purple');
            this._setInstruction(this.challenge.prompt || 'Ikuti gerakan yang diminta...');

            const passed = this._evaluateChallengeAction(face);
            if (passed) {
                this.challenge.passed = true;
                this._log(`✓ Interactive challenge [${this.challenge.action}] Terkonfirmasi!`);
                this._setStatus('Gerakan Terkonfirmasi! ✓', 'success');
                this._setOvalGuideState('success');
                this._setProgress(70);
            }
            return;
        }

        // Begitu challenge lolos, ambil embedding presisi via Heavy Path
        if (!this.isCapturing && this.verifyBuffers.embeddings.length < 3) {
            this.isCapturing = true;
            try {
                const capture = await humanEngine.detectCapture(this.video);
                if (capture && capture.embedding) {
                    this.verifyBuffers.embeddings.push(capture.embedding);
                    this.verifyBuffers.antispoofScores.push(capture.realScore);
                    this.verifyBuffers.livenessScores.push(capture.liveScore);
                }
            } catch (e) {
            } finally {
                this.isCapturing = false;
            }
        }

        // Jika minimal 3 frame embedding terkumpul -> SUBMIT VERIFIKASI!
        if (this.verifyBuffers.embeddings.length >= 3) {
            this.currentStage = TokobiiFaceVerification.STAGE.SUBMITTING;
            this._clearCanvas();
            this._setProgress(95);
            this._setStatus('Memverifikasi Identitas...', 'info');
            this._setInstruction('Memeriksa kecocokan biometrik dengan server...');

            const avgEmbedding = this._computeCentroid(this.verifyBuffers.embeddings);
            const avgAntispoof = this.verifyBuffers.antispoofScores.reduce((a, b) => a + b, 0) / this.verifyBuffers.antispoofScores.length;
            const avgLiveness  = this.verifyBuffers.livenessScores.reduce((a, b) => a + b, 0) / this.verifyBuffers.livenessScores.length;

            await this._submitVerification(avgEmbedding, avgAntispoof, avgLiveness);
        }
    }

    _evaluateChallengeAction(face) {
        const action = this.challenge.action || 'blink';

        if (action === 'blink') {
            if (face.ear < 0.20) {
                this.challenge.blinkFrames++;
            } else if (this.challenge.blinkFrames >= 1 && face.ear >= 0.25) {
                return true;
            }
        } else if (action === 'turn_left') {
            if (face.rotation.yaw < -12) {
                return true;
            }
        } else if (action === 'turn_right') {
            if (face.rotation.yaw > 12) {
                return true;
            }
        } else if (action === 'smile') {
            if (face.mar >= 0.30) {
                return true;
            }
        }

        return false;
    }

    async _submitVerification(embedding, antispoofScore, livenessScore) {
        try {
            const res = await fetch(this.config.verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                body: JSON.stringify({
                    nonce: this.challenge.nonce,
                    descriptor: embedding,
                    challenge_result: true,
                    antispoof_score: Number(antispoofScore.toFixed(4)),
                    liveness_score: Number(livenessScore.toFixed(4)),
                }),
            });

            const data = await res.json();
            this.perf.decisionMs = Math.round(performance.now() - this.startTime);

            if (res.ok && data.success) {
                this._setStatus('Verifikasi Berhasil! ✓', 'success');
                this._setInstruction('Autentikasi terkonfirmasi. Mengarahkan ke dashboard...');
                this._setOvalGuideState('success');
                this._setProgress(100);
                this.stop();

                this._log(`Verifikasi Sukses dalam ${this.perf.decisionMs}ms`, data);

                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(data);
                } else if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            } else {
                this._setStatus(data.message || 'Verifikasi Wajah Gagal', 'danger');
                this._setInstruction(data.message || 'Wajah tidak cocok dengan akun ini.');
                this._setOvalGuideState('danger');
                this.stop();
                if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

                if (typeof this.config.onError === 'function') {
                    this.config.onError(data);
                }
            }
        } catch (err) {
            this._setStatus('Kesalahan Jaringan', 'danger');
            this._setInstruction('Gagal menghubungi server verifikasi. Silakan coba lagi.');
            this._setOvalGuideState('danger');
            this.stop();
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CANVAS & UI RENDERING
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Pemetaan koordinat dari ruang video mentah (videoWidth x videoHeight)
     * ke ruang tampilan display container (clientWidth x clientHeight)
     * dengan memperhitungkan CSS object-fit: cover dan rasio aspek.
     *
     * @param {number} x - Koordinat X mentah pada video
     * @param {number} y - Koordinat Y mentah pada video
     * @param {number} w - Lebar mentah pada video
     * @param {number} h - Tinggi mentah pada video
     * @returns {{ x: number, y: number, width: number, height: number, scale: number, offsetX: number, offsetY: number, cw: number, ch: number, vw: number, vh: number }}
     */
    _mapVideoToDisplay(x, y, w = 0, h = 0) {
        const vw = (this.video && this.video.videoWidth > 0) ? this.video.videoWidth : 640;
        const vh = (this.video && this.video.videoHeight > 0) ? this.video.videoHeight : 480;
        const cw = (this.video && this.video.clientWidth > 0) ? this.video.clientWidth : (this.canvas?.clientWidth || 320);
        const ch = (this.video && this.video.clientHeight > 0) ? this.video.clientHeight : (this.canvas?.clientHeight || 240);

        // object-fit: cover menggunakan scale = max(cw/vw, ch/vh)
        const scale = Math.max(cw / vw, ch / vh);
        const offsetX = (cw - vw * scale) / 2;
        const offsetY = (ch - vh * scale) / 2;

        return {
            x: x * scale + offsetX,
            y: y * scale + offsetY,
            width: w * scale,
            height: h * scale,
            scale,
            offsetX,
            offsetY,
            cw,
            ch,
            vw,
            vh,
        };
    }

    _syncCanvas() {
        if (!this.canvas || !this.video) return;
        const cw = this.video.clientWidth || this.canvas.clientWidth || 320;
        const ch = this.video.clientHeight || this.canvas.clientHeight || 240;
        if (cw <= 0 || ch <= 0) return;

        const dpr = window.devicePixelRatio || 1;
        const targetW = Math.round(cw * dpr);
        const targetH = Math.round(ch * dpr);

        if (this.canvas.width !== targetW || this.canvas.height !== targetH) {
            this.canvas.width = targetW;
            this.canvas.height = targetH;
            this.canvas.style.width = `${cw}px`;
            this.canvas.style.height = `${ch}px`;
            const ctx = this.canvas.getContext('2d');
            if (ctx) {
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            }
        }
    }

    _clearCanvas() {
        if (!this.canvas) return;
        const ctx = this.canvas.getContext('2d');
        if (ctx) {
            const cw = this.video?.clientWidth || this.canvas.clientWidth || 320;
            const ch = this.video?.clientHeight || this.canvas.clientHeight || 240;
            ctx.clearRect(0, 0, cw, ch);
        }
    }

    _drawCanvas(face) {
        if (!this.canvas || !face || !this.video) return;
        this._syncCanvas();

        const ctx = this.canvas.getContext('2d');
        if (!ctx) return;

        const cw = this.video.clientWidth || this.canvas.clientWidth || 320;
        const ch = this.video.clientHeight || this.canvas.clientHeight || 240;
        ctx.clearRect(0, 0, cw, ch);

        const mappedBox = this._mapVideoToDisplay(face.box.x, face.box.y, face.box.width, face.box.height);

        // Bounding Box (Menempel Akurat pada Kontur Wajah di Mobile & Desktop)
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 2;
        ctx.strokeRect(mappedBox.x, mappedBox.y, mappedBox.width, mappedBox.height);

        // Landmark Titik Mesh (Menempel Presisi pada Fitur Wajah)
        if (face.mesh && face.mesh.length > 0) {
            ctx.fillStyle = 'rgba(56, 189, 248, 0.7)';
            for (let i = 0; i < face.mesh.length; i += 4) {
                const pt = face.mesh[i];
                const px = pt[0] * mappedBox.scale + mappedBox.offsetX;
                const py = pt[1] * mappedBox.scale + mappedBox.offsetY;
                ctx.fillRect(px - 1.5, py - 1.5, 3, 3);
            }
        }
    }

    _setStatus(text, type = 'info') {
        if (!this.statusEl) return;
        const colors = {
            info:    'bg-blue-50 text-blue-700 border-blue-200',
            success: 'bg-emerald-50 text-emerald-700 border-emerald-200',
            warning: 'bg-amber-50 text-amber-700 border-amber-200',
            danger:  'bg-rose-50 text-rose-700 border-rose-200',
            purple:  'bg-purple-50 text-purple-700 border-purple-200',
        };
        this.statusEl.className = `tokobii-badge ${colors[type] || colors.info} px-3 py-1.5 small fw-semibold border shadow-sm`;
        this.statusEl.innerHTML = text;
    }

    _setInstruction(text) {
        if (this.instructionEl) this.instructionEl.textContent = text;
    }

    _setProgress(pct) {
        if (this.progressBar) this.progressBar.style.width = `${Math.min(100, Math.max(0, pct))}%`;
    }

    _setOvalGuideState(state) {
        if (!this.ovalGuide) return;
        const styles = {
            searching: { color: 'rgba(59, 130, 246, 0.8)',  style: 'dashed', glow: '' },
            success:   { color: 'rgba(16, 185, 129, 0.95)', style: 'solid',  glow: '0 0 22px rgba(16, 185, 129, 0.7),' },
            warning:   { color: 'rgba(245, 158, 11, 0.95)', style: 'dashed', glow: '0 0 12px rgba(245, 158, 11, 0.3),' },
            danger:    { color: 'rgba(244, 63, 94, 0.95)',  style: 'solid',  glow: '0 0 15px rgba(244, 63, 94, 0.4),' },
            liveness:  { color: 'rgba(147, 51, 234, 0.95)', style: 'solid',  glow: '0 0 18px rgba(147, 51, 234, 0.5),' },
        };
        const s = styles[state] || styles.searching;
        this.ovalGuide.style.borderColor = s.color;
        this.ovalGuide.style.borderStyle = s.style;
        this.ovalGuide.style.boxShadow = `${s.glow} 0 0 0 9999px rgba(15, 23, 42, 0.55)`;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  PANEL DEBUG TELEMETRI LENGKAP (FACE_DEBUG_MODE)
    // ═══════════════════════════════════════════════════════════════════

    _renderDebugPanel(face = null) {
        if (!this.config.debug) return;

        let panel = document.getElementById('faceDebugPanel');
        if (!panel) {
            const container = this.video?.closest('.face-camera-wrapper') || this.video?.parentElement;
            if (container) {
                panel = document.createElement('div');
                panel.id = 'faceDebugPanel';
                panel.style.cssText = `
                    margin-top: 10px;
                    padding: 8px 12px;
                    background: rgba(15, 23, 42, 0.95);
                    border: 1px solid rgba(148, 163, 184, 0.25);
                    border-radius: 8px;
                    font-family: ui-monospace, monospace;
                    font-size: 11px;
                    color: #94a3b8;
                    text-align: left;
                    line-height: 1.5;
                `;
                container.insertAdjacentElement('afterend', panel);
            }
        }

        if (!panel) return;

        const yawStr = face ? `${face.rotation.yaw > 0 ? '+' : ''}${face.rotation.yaw}°` : '-';
        const pitchStr = face ? `${face.rotation.pitch > 0 ? '+' : ''}${face.rotation.pitch}°` : '-';
        const rollStr = face ? `${face.rotation.roll > 0 ? '+' : ''}${face.rotation.roll}°` : '-';
        const bright = face ? `${face.faceBrightness}` : '-';
        const inferMs = humanEngine.perf.lastInferenceMs || 0;
        const mode = humanEngine.perf.lastMode || 'fast';

        const vw = this.video?.videoWidth || 0;
        const vh = this.video?.videoHeight || 0;
        const cw = this.video?.clientWidth || 0;
        const ch = this.video?.clientHeight || 0;
        const mapped = face ? this._mapVideoToDisplay(face.box.x, face.box.y, face.box.width, face.box.height) : null;
        const boxStr = mapped ? `${Math.round(mapped.x)},${Math.round(mapped.y)} (${Math.round(mapped.width)}x${Math.round(mapped.height)})` : '-';

        panel.innerHTML = `
            <div style="font-weight:600; color:#38bdf8; margin-bottom:4px; display:flex; justify-content:space-between;">
                <span>[TokobiiFace Telemetry]</span>
                <span id="faceDebugFps" style="color:#22c55e;">FPS: ${this.currentFps || 0} (${inferMs}ms [${mode}])</span>
            </div>
            <div><b style="color:#e2e8f0;">Resolution:</b> Video: ${vw}x${vh} | Screen: ${cw}x${ch}</div>
            <div><b style="color:#e2e8f0;">Mapped Box:</b> ${boxStr}</div>
            <div><b style="color:#e2e8f0;">Pose Angle:</b> Yaw: <b style="color:#f59e0b;">${yawStr}</b> | Pitch: ${pitchStr} | Roll: ${rollStr}</div>
            <div><b style="color:#e2e8f0;">Face Brightness:</b> ${bright}/255 | <b style="color:#e2e8f0;">Hold Frames:</b> ${this.poseHoldFrames}/3</div>
            <div><b style="color:#e2e8f0;">Quality Gate:</b> <span style="color:#a5f3fc;">${this.lastQGError}</span></div>
            <div><b style="color:#e2e8f0;">Sampel Terkumpul:</b> ${this.collectedSamples.length}/5</div>
        `;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  LIFECYCLE CONTROLS
    // ═══════════════════════════════════════════════════════════════════

    stop() {
        this.isStopped = true;
        this.currentStage = TokobiiFaceVerification.STAGE.FINISHED;
        this._clearCanvas();
        this._stopCamera();
    }

    restart() {
        if (this.retryBtn) {
            this.retryBtn.disabled = true;
            this.retryBtn.setAttribute('data-loading', 'true');
            const originalContent = this.retryBtn.innerHTML;
            this.retryBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                <span>Memulai Ulang...</span>
            `;
            setTimeout(() => {
                if (this.retryBtn) {
                    this.retryBtn.disabled = false;
                    this.retryBtn.removeAttribute('data-loading');
                    this.retryBtn.innerHTML = originalContent;
                }
            }, 1200);
        }

        this.stop();
        this.isDetecting = false;
        this.isCapturing = false;
        this.currentEnrollStepIndex = 0;
        this.collectedSamples = [];
        this.poseHoldFrames = 0;
        this.stepStartTime = Date.now();
        this.verifyBuffers = { embeddings: [], antispoofScores: [], livenessScores: [] };
        this.challenge.passed = false;
        this.challenge.blinkFrames = 0;
        this.startTime = performance.now();

        this._setProgress(0);
        if (this.retryBtn) this.retryBtn.style.display = 'none';
        if (this.skipBtn) this.skipBtn.style.display = 'none';
        if (this.saveEnrollBtn) this.saveEnrollBtn.style.display = 'none';

        this.isStopped = false;
        this.currentStage = TokobiiFaceVerification.STAGE.SEARCHING;
        this._setStatus('Mencari Wajah...', 'info');
        this._setInstruction('Posisikan wajah Anda tepat di dalam bingkai oval.');
        this._setOvalGuideState('searching');
        this._setProgress(20);

        this._init();
    }

    _log(message, data = null) {
        if (!this.config.debug) return;
        if (data !== null) {
            console.log(`[TokobiiFace] ${message}`, data);
        } else {
            console.log(`[TokobiiFace] ${message}`);
        }
    }
}

window.TokobiiFaceVerification = TokobiiFaceVerification;
export default TokobiiFaceVerification;
