import humanEngine from './face/humanEngine.js';

/**
 * Tokobii Face Verification Engine v4.0 (@vladmandic/human)
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Fitur Utama:
 * 1. Engine @vladmandic/human dengan akselerasi WebGL GPU.
 * 2. Anti-Spoofing AI (deteksi foto kertas, layar smartphone/monitor).
 * 3. Interactive Challenge-Response Liveness (kedip mata, tengok kiri/kanan, senyum).
 * 4. 1024-D embedding centroid averaging untuk akurasi tinggi & cepat (< 2 detik).
 * 5. Single-use challenge nonce (60s TTL) untuk mencegah replay attack.
 * 6. Server-side validation (Laravel Cosine Similarity + Anti-Spoof Threshold).
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

    constructor(config) {
        const origin = (typeof window !== 'undefined' && window.location?.origin)
            ? window.location.origin
            : '';

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
            modelsUri: `${origin}/models/human/`,
            verifyUrl: '/verify-face',
            challengeUrl: '/verify-face/challenge-data',
            enrollUrl: null,
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            onSuccess: null,
            onError: null,
            debug: true,
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

        // ─── State Machine ───
        this.currentStage  = TokobiiFaceVerification.STAGE.INITIALIZING;
        this.stream        = null;
        this.isDetecting   = false;
        this.isStopped     = false;
        this.animFrameId   = null;
        this.lastFrameTime = 0;
        this.frameThrottle = 100; // ms per inference (~10 FPS optimal untuk mobile & laptop)

        // ─── Challenge & Liveness State ───
        this.challenge = {
            nonce: null,
            action: null,   // 'blink' | 'turn_left' | 'turn_right' | 'smile'
            prompt: null,
            passed: false,
            baselineEAR: 0.30,
            blinkFrames: 0,
        };

        // ─── Enrollment Samples (5 Samples) ───
        this.enrollSteps = [
            { id: 'center', prompt: 'Posisikan wajah menghadap lurus ke depan', condition: (f) => Math.abs(f.rotation.yaw) < 8 },
            { id: 'left',   prompt: 'Tengok kepala ke kiri sedikit',          condition: (f) => f.rotation.yaw < -10 },
            { id: 'right',  prompt: 'Tengok kepala ke kanan sedikit',         condition: (f) => f.rotation.yaw > 10 },
            { id: 'smile',  prompt: 'Tersenyumlah sedikit ke arah kamera',    condition: (f) => f.mar >= 0.28 },
            { id: 'neutral',prompt: 'Hadap lurus tenang untuk sampel akhir',   condition: (f) => Math.abs(f.rotation.yaw) < 8 },
        ];
        this.currentEnrollStepIndex = 0;
        this.collectedSamples       = [];

        // ─── Verification Samples Buffer ───
        this.verifyBuffers = {
            embeddings: [],
            antispoofScores: [],
            livenessScores: [],
        };

        // ─── Telemetry & Performance ───
        this.startTime = performance.now();
        this.perf = {
            modelLoadMs: 0,
            cameraStartupMs: 0,
            firstDetectionMs: 0,
            decisionMs: 0,
        };

        if (typeof window !== 'undefined') {
            window.addEventListener('resize', () => this._syncCanvas());
        }

        this._init();
    }

    // ═══════════════════════════════════════════════════════════════════
    //  INITIALIZATION
    // ═══════════════════════════════════════════════════════════════════

    async _init() {
        if (this.retryBtn) {
            this.retryBtn.addEventListener('click', () => this.restart());
        }
        if (this.cameraSelect) {
            this.cameraSelect.addEventListener('change', () => this._startCamera(this.cameraSelect.value));
        }

        this._setStatus('Menyiapkan AI Biometrik...', 'info');
        this._setInstruction('Menginisialisasi GPU & model @vladmandic/human...');
        this._setProgress(15);
        this._setOvalGuideState('searching');

        try {
            // Paralel: setup kamera dan inisialisasi Human Engine
            const [_, cameraReady] = await Promise.all([
                this._loadEngine(),
                this._setupCamera(),
            ]);

            // Jika dalam mode verifikasi, ambil challenge nonce dari server
            if (this.config.mode === 'verify') {
                await this._fetchChallengeData();
            }

            this.currentStage = (this.config.mode === 'enroll')
                ? TokobiiFaceVerification.STAGE.SAMPLING
                : TokobiiFaceVerification.STAGE.SEARCHING;

            this._setStatus('Mencari Wajah...', 'info');
            this._updateStepInstruction();
            this._setProgress(35);

            this._startLoop();
        } catch (err) {
            this._log('Init error:', err);
            this._setStatus('Gagal Memuat Kamera/Model', 'danger');
            this._setInstruction('Pastikan izin kamera diberikan dan koneksi internet stabil.');
            this._setOvalGuideState('danger');
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

            if (typeof this.config.onError === 'function') {
                this.config.onError(err);
            }
        }
    }

    async _loadEngine() {
        const t0 = performance.now();
        humanEngine.init({
            modelsUri: this.config.modelsUri,
            debug: this.config.debug,
        });

        await humanEngine.load((prog) => {
            if (this.progressBar) {
                this._setProgress(prog.percent || 25);
            }
        });

        this.perf.modelLoadMs = Math.round(performance.now() - t0);
        this._log(`Model Human Engine siap dalam ${this.perf.modelLoadMs}ms`);
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
                this._log(`Challenge diterima: [${data.action}] - ${data.prompt} (TTL: ${data.expires_in}s)`);
            }
        } catch (e) {
            this._log('Warning: Menggunakan challenge offline default', e);
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

            if (this.cameraSelect) {
                this.cameraSelect.innerHTML = '';
                videoDevices.forEach((device, idx) => {
                    const opt = document.createElement('option');
                    opt.value = device.deviceId;
                    opt.text = device.label || `Kamera ${idx + 1}`;
                    this.cameraSelect.appendChild(opt);
                });
                this.cameraSelect.style.display = videoDevices.length > 1 ? 'block' : 'none';
            }

            const initialId = videoDevices.length > 0 ? videoDevices[0].deviceId : undefined;
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
                ? { deviceId: { exact: deviceId }, width: { ideal: 640 }, height: { ideal: 480 } }
                : { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
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
    //  INFERENCE DETECTION LOOP (Throttle 100ms via requestAnimationFrame)
    // ═══════════════════════════════════════════════════════════════════

    _startLoop() {
        const loop = async (timestamp) => {
            if (this.isStopped || this.currentStage === TokobiiFaceVerification.STAGE.FINISHED) {
                return;
            }

            if (!this.isDetecting && (timestamp - this.lastFrameTime >= this.frameThrottle)) {
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
        if (!this.video || this.video.paused || this.video.ended || !humanEngine.isLoaded) return;

        // 1. Eksekusi deteksi Human Engine
        const face = await humanEngine.detect(this.video);

        // 2. Jika tidak ada wajah terdeteksi
        if (!face) {
            this._clearCanvas();
            this._setStatus('Mencari Wajah...', 'info');
            this._setOvalGuideState('searching');
            return;
        }

        if (this.perf.firstDetectionMs === 0) {
            this.perf.firstDetectionMs = Math.round(performance.now() - this.startTime);
            this._log(`Wajah pertama terdeteksi dalam ${this.perf.firstDetectionMs}ms`);
        }

        // 3. Render Canvas Landmark & Bounding Box
        this._drawCanvas(face);

        // 4. Quality Gate Validations
        const qg = this._evaluateQualityGate(face);
        if (!qg.valid) {
            this._setStatus(qg.status, qg.state);
            this._setInstruction(qg.message);
            this._setOvalGuideState(qg.state);
            return;
        }

        // 5. Eksekusi berdasarkan Mode (Enroll atau Verify)
        if (this.config.mode === 'enroll') {
            await this._handleEnrollFrame(face);
        } else {
            await this._handleVerifyFrame(face);
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  QUALITY GATE (Evaluasi Posisi, Pencahayaan, & Anti-Spoof)
    // ═══════════════════════════════════════════════════════════════════

    _evaluateQualityGate(face) {
        const vw = this.video.videoWidth || 640;
        const vh = this.video.videoHeight || 480;

        // Cek jumlah wajah (Harus tepat 1 orang)
        if (face.allFacesCount > 1) {
            return { valid: false, status: 'Lebih dari 1 Wajah', message: 'Hanya 1 wajah yang diizinkan di depan kamera.', state: 'danger' };
        }

        // Cek confidence score
        if (face.score < 0.60) {
            return { valid: false, status: 'Wajah Kurang Jelas', message: 'Tingkatkan pencahayaan atau bersihkan lensa kamera.', state: 'warning' };
        }

        // Cek Geometri Bounding Box di dalam Oval
        const box = face.box;
        const relW = box.width / vw;
        const centerX = (box.x + box.width / 2) / vw;
        const centerY = (box.y + box.height / 2) / vh;

        if (relW < 0.18) return { valid: false, status: 'Terlalu Jauh', message: 'Dekatkan wajah sedikit ke kamera.', state: 'warning' };
        if (relW > 0.78) return { valid: false, status: 'Terlalu Dekat', message: 'Jauhkan wajah sedikit dari kamera.', state: 'warning' };
        if (centerX < 0.22) return { valid: false, status: 'Geser ke Kanan', message: 'Posisikan wajah di tengah oval.', state: 'warning' };
        if (centerX > 0.78) return { valid: false, status: 'Geser ke Kiri', message: 'Posisikan wajah di tengah oval.', state: 'warning' };
        if (centerY < 0.15) return { valid: false, status: 'Turunkan Sedikit', message: 'Posisikan wajah di dalam bingkai oval.', state: 'warning' };
        if (centerY > 0.85) return { valid: false, status: 'Naikkan Sedikit', message: 'Posisikan wajah di dalam bingkai oval.', state: 'warning' };

        // Cek Pencahayaan Frame
        const brightness = humanEngine.calculateBrightness(this.video);
        if (brightness < 35) {
            return { valid: false, status: 'Terlalu Gelap', message: 'Cahaya kurang terang. Silakan nyalakan lampu atau hadap cahaya.', state: 'warning' };
        }

        // Cek Anti-Spoof Real Score
        if (face.realScore < 0.35) {
            return { valid: false, status: 'Terdeteksi Objek Tiruan', message: 'Hadapkan wajah asli Anda langsung ke kamera.', state: 'danger' };
        }

        return { valid: true };
    }

    // ═══════════════════════════════════════════════════════════════════
    //  ENROLLMENT HANDLER (5 Variasi Sampel)
    // ═══════════════════════════════════════════════════════════════════

    async _handleEnrollFrame(face) {
        if (!face.embedding) return;

        const currentStep = this.enrollSteps[this.currentEnrollStepIndex];
        if (!currentStep) return;

        this._setOvalGuideState('liveness');

        // Evaluasi kondisi langkah saat ini (mis. tengok kiri, senyum, dll)
        const isStepSatisfied = currentStep.condition(face);

        if (isStepSatisfied) {
            this.collectedSamples.push(face.embedding);
            this.currentEnrollStepIndex++;

            const pct = Math.round((this.currentEnrollStepIndex / this.enrollSteps.length) * 90);
            this._setProgress(pct);
            this._setStatus(`Sampel ${this.currentEnrollStepIndex}/5 Terambil ✓`, 'success');
            this._setOvalGuideState('success');

            this._log(`Enroll Step ${this.currentEnrollStepIndex} (${currentStep.id}) sukses!`);

            if (this.currentEnrollStepIndex >= this.enrollSteps.length) {
                // Semua 5 sampel berhasil terkumpul!
                this.currentStage = TokobiiFaceVerification.STAGE.SUBMITTING;
                this._setProgress(95);
                this._setStatus('Menyimpan Biometrik...', 'info');
                this._setInstruction('Mengenkripsi dan mendaftarkan biometrik ke server...');

                const centroid = this._computeCentroid(this.collectedSamples);
                await this._submitEnrollment(centroid, this.collectedSamples);
            } else {
                this._updateStepInstruction();
            }
        } else {
            this._setStatus('Posisikan Wajah', 'info');
            this._setInstruction(currentStep.prompt);
        }
    }

    _updateStepInstruction() {
        if (this.config.mode === 'enroll') {
            const step = this.enrollSteps[this.currentEnrollStepIndex];
            if (step) {
                this._setInstruction(`[Langkah ${this.currentEnrollStepIndex + 1}/5] ${step.prompt}`);
            }
        } else if (this.challenge.prompt) {
            this._setInstruction(this.challenge.prompt);
        }
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

        // Average and normalize
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

    async _submitEnrollment(centroid, samples) {
        // Ambil password dari input
        const passwordInput = document.getElementById('enrollPassword')
            || document.getElementById('enroll_admin_face_password')
            || document.getElementById('enroll_owner_face_password');

        const password = passwordInput?.value?.trim() || '';

        if (!password) {
            this._setStatus('Kata Sandi Wajib Diisi', 'danger');
            this._setInstruction('Masukkan kata sandi akun Anda untuk konfirmasi keamanan pendaftaran.');
            this._setOvalGuideState('danger');
            this.currentStage = TokobiiFaceVerification.STAGE.FINISHED;
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
            return;
        }

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
                    samples,
                }),
            });

            const data = await res.json();

            if (res.ok && data.success) {
                this._setStatus('Pendaftaran Berhasil! ✓', 'success');
                this._setInstruction(data.message || 'Biometrik wajah berhasil didaftarkan.');
                this._setOvalGuideState('success');
                this._setProgress(100);
                this.stop();

                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(data);
                } else {
                    setTimeout(() => window.location.reload(), 1200);
                }
            } else {
                this._setStatus(data.message || 'Pendaftaran Gagal', 'danger');
                this._setInstruction(data.message || 'Kata sandi salah atau format biometrik tidak valid.');
                this._setOvalGuideState('danger');
                this.stop();
                if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

                if (typeof this.config.onError === 'function') {
                    this.config.onError(data);
                }
            }
        } catch (err) {
            this._setStatus('Kesalahan Jaringan', 'danger');
            this._setInstruction('Gagal menghubungi server. Periksa koneksi internet Anda.');
            this._setOvalGuideState('danger');
            this.stop();
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  VERIFICATION HANDLER (Interactive Challenge + Multi-Frame Buffer)
    // ═══════════════════════════════════════════════════════════════════

    async _handleVerifyFrame(face) {
        if (!face.embedding) return;

        // Kumpulkan data buffer kualitas tinggi
        this.verifyBuffers.embeddings.push(face.embedding);
        this.verifyBuffers.antispoofScores.push(face.realScore);
        this.verifyBuffers.livenessScores.push(face.liveScore);

        // Batasi buffer maksimal 8 frame terbaik
        if (this.verifyBuffers.embeddings.length > 8) {
            this.verifyBuffers.embeddings.shift();
            this.verifyBuffers.antispoofScores.shift();
            this.verifyBuffers.livenessScores.shift();
        }

        // 1. Evaluasi Interactive Challenge jika belum lolos
        if (!this.challenge.passed) {
            this.currentStage = TokobiiFaceVerification.STAGE.CHALLENGE;
            this._setOvalGuideState('liveness');
            this._setProgress(50);
            this._setStatus('Uji Gerakan Aktif...', 'purple');
            this._setInstruction(this.challenge.prompt || 'Ikuti gerakan yang diminta...');

            const passed = this._evaluateChallengeAction(face);
            if (passed) {
                this.challenge.passed = true;
                this._log(`✓ Interactive challenge [${this.challenge.action}] CONFIRMED!`);
                this._setStatus('Gerakan Terkonfirmasi! ✓', 'success');
                this._setOvalGuideState('success');
                this._setProgress(75);
            }
            return;
        }

        // 2. Begitu challenge lolos dan minimal 3 frame terkumpul -> SUBMIT!
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
            // Deteksi kedipan mata via EAR
            if (face.ear < 0.20) {
                this.challenge.blinkFrames++;
            } else if (this.challenge.blinkFrames >= 1 && face.ear >= 0.25) {
                // Mata tertutup lalu terbuka kembali = Kedip valid!
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
            if (face.mar >= 0.32) {
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
    //  UI & CANVAS RENDERING
    // ═══════════════════════════════════════════════════════════════════

    _syncCanvas() {
        if (!this.canvas || !this.video) return;
        const w = this.video.clientWidth || this.video.videoWidth || 640;
        const h = this.video.clientHeight || this.video.videoHeight || 480;

        if (w > 0 && h > 0 && (this.canvas.width !== w || this.canvas.height !== h)) {
            this.canvas.width = w;
            this.canvas.height = h;
        }
    }

    _clearCanvas() {
        if (!this.canvas) return;
        const ctx = this.canvas.getContext('2d');
        if (ctx) {
            ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        }
    }

    _drawCanvas(face) {
        if (!this.canvas || !face || !this.video) return;
        this._syncCanvas();

        const ctx = this.canvas.getContext('2d');
        if (!ctx) return;

        ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Skala koordinat box ke ukuran canvas tampilan
        const scaleX = this.canvas.width / (this.video.videoWidth || 640);
        const scaleY = this.canvas.height / (this.video.videoHeight || 480);

        const bx = face.box.x * scaleX;
        const by = face.box.y * scaleY;
        const bw = face.box.width * scaleX;
        const bh = face.box.height * scaleY;

        // Gambar Bounding Box Halus
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 2;
        ctx.strokeRect(bx, by, bw, bh);

        // Gambar Landmark Mesh 468 titik (titik-titik cyan tipis)
        if (face.mesh && face.mesh.length > 0) {
            ctx.fillStyle = 'rgba(56, 189, 248, 0.6)';
            // Render setiap titik ke-4 agar tidak memberatkan rendering
            for (let i = 0; i < face.mesh.length; i += 4) {
                const pt = face.mesh[i];
                const px = pt[0] * scaleX;
                const py = pt[1] * scaleY;
                ctx.fillRect(px - 1, py - 1, 2, 2);
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
            success:   { color: 'rgba(16, 185, 129, 0.95)', style: 'solid',  glow: '0 0 20px rgba(16, 185, 129, 0.5),' },
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
    //  LIFECYCLE CONTROLS
    // ═══════════════════════════════════════════════════════════════════

    stop() {
        this.isStopped = true;
        this.currentStage = TokobiiFaceVerification.STAGE.FINISHED;
        this._clearCanvas();
        this._stopCamera();
    }

    restart() {
        this.stop();
        this.isDetecting = false;
        this.currentEnrollStepIndex = 0;
        this.collectedSamples = [];
        this.verifyBuffers = { embeddings: [], antispoofScores: [], livenessScores: [] };
        this.challenge.passed = false;
        this.challenge.blinkFrames = 0;
        this.startTime = performance.now();
        this.perf = { modelLoadMs: 0, cameraStartupMs: 0, firstDetectionMs: 0, decisionMs: 0 };

        this._setProgress(0);
        if (this.retryBtn) this.retryBtn.style.display = 'none';

        this.isStopped = false;
        this.currentStage = TokobiiFaceVerification.STAGE.SEARCHING;
        this._setStatus('Mencari wajah...', 'info');
        this._setInstruction('Posisikan wajah Anda tepat di dalam bingkai oval.');
        this._setOvalGuideState('searching');
        this._setProgress(25);

        this._setupCamera().then(() => {
            if (this.config.mode === 'verify') {
                this._fetchChallengeData();
            }
            this._startLoop();
        }).catch(() => {});
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
