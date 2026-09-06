import * as faceapi from '@vladmandic/face-api';

/**
 * Tokobii Face Verification Engine v3.0
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Arsitektur 2-Layer: face-api.js (Browser) + Laravel (Euclidean Distance PHP)
 *
 * Pipeline Bertahap:
 *   Stage 1 — SEARCHING  : TinyFaceDetector, deteksi bounding box & posisi lurus (~15ms/frame @ 80ms)
 *   Stage 2 — LIVENESS   : faceLandmark68Net, Head Turn / Pose Estimation Yaw (~20ms/frame @ 50ms)
 *   Stage 3 — SAMPLING   : faceRecognitionNet, ambil 128-D descriptor (~35ms/frame @ 60ms)
 *   Stage 4 — SUBMITTING : Kirim rata-rata descriptor JSON ke Laravel
 *
 * Liveness Method: Head Pose / Yaw Estimation (Anti-Photo Spoofing):
 *   1. Zero-Wait Instant Flow: Wajah lurus terdeteksi di awal, lalu prompt menengok.
 *   2. Head Turn Detection: Mengukur rasio simetri horizontal ujung hidung (landmark 30)
 *      terhadap kontur pipi kiri (landmark 0) dan kanan (landmark 16).
 *   3. Ultra-Responsive: Begitu gerakan kepala terdeteksi, LANGSUNG masuk sampling 128-D descriptor.
 *   4. Bebas Bug Kedipan: Tidak menggunakan EAR / deteksi mata yang rentan variasi kelopak mata.
 */
class TokobiiFaceVerification {
    // ─── Singleton Model Cache ───
    static modelsLoadedPromise = null;
    static areModelsReady = false;

    // ─── Stage Constants ───
    static STAGE = Object.freeze({
        INITIALIZING: 'INITIALIZING',
        SEARCHING:    'SEARCHING',
        LIVENESS:     'LIVENESS',
        SAMPLING:     'SAMPLING',
        SUBMITTING:   'SUBMITTING',
        FINISHED:     'FINISHED',
    });

    constructor(config) {
        // ─── Configuration ───
        this.config = Object.assign({
            mode: 'verify',                 // 'verify' | 'enroll'
            videoElementId: 'faceVideo',
            statusElementId: 'faceStatus',
            instructionElementId: 'faceInstruction',
            progressBarId: 'faceProgress',
            ovalGuideId: 'faceOvalGuide',
            retryButtonId: 'btnRetryFace',
            cameraSelectId: 'faceCameraSelect',
            modelsUri: '/models/face-api',
            verifyUrl: '/verify-face',
            enrollUrl: null,
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            onSuccess: null,
            onError: null,
            debug: true,
        }, config);

        // ─── DOM Elements ───
        this.video         = document.getElementById(this.config.videoElementId);
        this.statusEl      = document.getElementById(this.config.statusElementId);
        this.instructionEl = document.getElementById(this.config.instructionElementId);
        this.progressBar   = document.getElementById(this.config.progressBarId);
        this.ovalGuide     = document.getElementById(this.config.ovalGuideId);
        this.retryBtn      = document.getElementById(this.config.retryButtonId);
        this.cameraSelect  = document.getElementById(this.config.cameraSelectId);

        // ─── State Machine ───
        this.currentStage = TokobiiFaceVerification.STAGE.INITIALIZING;

        // ─── Guards & Stream ───
        this.stream       = null;
        this.isDetecting  = false;   // Mutex: prevent overlapping inference
        this.isStopped    = false;
        this.timerHandle  = null;    // Dynamic setTimeout handle

        // ─── Detector Config (Lightweight & Fast) ───
        this.detectorOptions = new faceapi.TinyFaceDetectorOptions({
            inputSize: 224,
            scoreThreshold: 0.35,
        });

        // ─── Tracking Counters ───
        this.consecutiveDetections = 0;
        this.consecutiveNoFace     = 0;

        // ─── Head Turn (Yaw) Parameters ───
        this.centerBaselineYaw     = 0.50;  // Baseline rasio saat wajah menghadap lurus (~0.50)
        this.YAW_TURN_DELTA        = 0.11;  // Minimal pergeseran rasio yaw untuk konfirmasi menengok
        this.YAW_LEFT_THRESHOLD    = 0.39;  // Rasio <= 0.39 = Menengok ke satu arah
        this.YAW_RIGHT_THRESHOLD   = 0.61;  // Rasio >= 0.61 = Menengok ke arah lain

        // ─── Liveness State ───
        this.livenessPassed        = false;
        this.livenessStartTime     = 0;

        // ─── Sampling State ───
        this.collectedDescriptors  = [];
        this.targetSamples         = 2;     // 2 sampel presisi untuk respon secepat kilat

        // ─── Performance Metrics ───
        this.perf = this._createPerfObject();

        // ─── Init ───
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
        window.addEventListener('beforeunload', () => this.stop());

        this._setStatus('Menyiapkan AI...', 'info');
        this._setInstruction('Mengaktifkan kamera & model biometrik...');
        this._setProgress(10);

        try {
            // Parallel: model loading + camera setup
            await Promise.all([
                this._loadModels(),
                this._setupCamera(),
            ]);

            this.currentStage = TokobiiFaceVerification.STAGE.SEARCHING;
            this._setStatus('Mencari wajah...', 'info');
            this._setInstruction('Posisikan wajah Anda lurus di dalam bingkai oval.');
            this._setOvalGuideState('searching');
            this._setProgress(30);

            this._scheduleNextLoop();
        } catch (err) {
            this._log('Initialization error:', err);
            this._setStatus('Gagal memuat sistem AI', 'danger');
            this._setInstruction('Terjadi kesalahan saat memuat. Silakan muat ulang halaman.');
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  MODEL LOADING (Singleton + WebGL Acceleration)
    // ═══════════════════════════════════════════════════════════════════

    async _loadModels() {
        if (TokobiiFaceVerification.areModelsReady) {
            this._log('Model AI tersedia dari cache (Singleton).');
            return;
        }

        if (!TokobiiFaceVerification.modelsLoadedPromise) {
            const t0 = performance.now();
            TokobiiFaceVerification.modelsLoadedPromise = (async () => {
                // WebGL GPU Acceleration
                if (faceapi.tf) {
                    try {
                        if (faceapi.tf.getBackend() !== 'webgl') {
                            await faceapi.tf.setBackend('webgl');
                        }
                        faceapi.tf.enableProdMode();
                        faceapi.tf.env().set('WEBGL_VERSION', 2);
                        faceapi.tf.env().set('WEBGL_PACK', true);
                    } catch (e) {
                        this._log('WebGL fallback note:', e);
                    }
                }

                // Load 3 model secara paralel
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(this.config.modelsUri),
                    faceapi.nets.faceLandmark68Net.loadFromUri(this.config.modelsUri),
                    faceapi.nets.faceRecognitionNet.loadFromUri(this.config.modelsUri),
                ]);

                TokobiiFaceVerification.areModelsReady = true;
                this.perf.modelLoadMs = Math.round(performance.now() - t0);
                this._log(`Model AI dimuat dalam ${this.perf.modelLoadMs}ms`);
            })();
        }

        return TokobiiFaceVerification.modelsLoadedPromise;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CAMERA MANAGEMENT
    // ═══════════════════════════════════════════════════════════════════

    async _setupCamera() {
        if (!navigator.mediaDevices?.getUserMedia) {
            this._setStatus('Kamera tidak didukung', 'danger');
            this._setInstruction('Browser Anda tidak mendukung akses kamera WebRTC.');
            throw new Error('getUserMedia not supported');
        }

        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');

            if (this.cameraSelect) {
                this.cameraSelect.innerHTML = '';
                videoDevices.forEach((device, i) => {
                    const opt = document.createElement('option');
                    opt.value = device.deviceId;
                    opt.text = device.label || `Kamera ${i + 1}`;
                    this.cameraSelect.appendChild(opt);
                });
                this.cameraSelect.style.display = videoDevices.length > 1 ? 'block' : 'none';
            }

            const initialId = videoDevices.length > 0 ? videoDevices[0].deviceId : undefined;
            await this._startCamera(initialId);
        } catch (err) {
            this._handleCameraError(err);
            throw err;
        }
    }

    async _startCamera(deviceId = undefined) {
        this._stopCameraStream();
        this.isStopped = false;
        const t0 = performance.now();

        const constraints = {
            video: deviceId
                ? { deviceId: { exact: deviceId }, width: { ideal: 640, min: 320 }, height: { ideal: 480, min: 240 } }
                : { facingMode: 'user', width: { ideal: 640, min: 320 }, height: { ideal: 480, min: 240 } },
            audio: false,
        };

        try {
            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
            if (!this.video) return;

            this.video.srcObject = this.stream;

            // Tunggu hingga frame pertama siap
            await new Promise((resolve) => {
                const check = () => {
                    if (this.video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA && this.video.videoWidth > 0) {
                        resolve();
                    } else {
                        setTimeout(check, 25);
                    }
                };
                this.video.onloadedmetadata = () => {
                    this.video.play().catch(() => {});
                    check();
                };
                this.video.onloadeddata = check;
                setTimeout(check, 250);
            });

            this.perf.cameraStartupMs = Math.round(performance.now() - t0);
            this._log(`Kamera aktif (${this.video.videoWidth}x${this.video.videoHeight}) dalam ${this.perf.cameraStartupMs}ms`);
        } catch (err) {
            this._handleCameraError(err);
            throw err;
        }
    }

    _handleCameraError(err) {
        const messages = {
            NotAllowedError:      'Akses kamera ditolak. Izinkan akses kamera pada browser.',
            PermissionDeniedError:'Akses kamera ditolak. Izinkan akses kamera pada browser.',
            NotFoundError:        'Perangkat kamera tidak ditemukan.',
            DevicesNotFoundError: 'Perangkat kamera tidak ditemukan.',
            NotReadableError:     'Kamera sedang digunakan oleh aplikasi lain.',
            TrackStartError:      'Kamera sedang digunakan oleh aplikasi lain.',
            OverconstrainedError: 'Resolusi kamera tidak didukung.',
            SecurityError:        'Akses kamera dibatasi (HTTPS/Localhost diperlukan).',
        };
        const msg = messages[err.name] || 'Gagal mengakses kamera.';

        this._setStatus(msg, 'danger');
        this._setInstruction(msg);
        this._setOvalGuideState('danger');
        if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
    }

    _stopCameraStream() {
        if (this.timerHandle) {
            clearTimeout(this.timerHandle);
            this.timerHandle = null;
        }
        if (this.stream) {
            this.stream.getTracks().forEach(t => t.stop());
            this.stream = null;
        }
        if (this.video) {
            this.video.srcObject = null;
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  FAST ADAPTIVE DETECTION LOOP
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Jadwal loop responsif:
     * - Stage 1 (SEARCHING): 80ms
     * - Stage 2 (LIVENESS) : 50ms (Ultra-fast head pose estimation ~20 FPS)
     * - Stage 3 (SAMPLING) : 60ms
     */
    _scheduleNextLoop() {
        if (this.isStopped || this.currentStage === TokobiiFaceVerification.STAGE.FINISHED) return;

        let delay = 80;
        if (this.currentStage === TokobiiFaceVerification.STAGE.LIVENESS) {
            delay = 50; // Ultra responsif untuk deteksi gerakan menengok
        } else if (this.currentStage === TokobiiFaceVerification.STAGE.SAMPLING) {
            delay = 60;
        }

        this.timerHandle = setTimeout(async () => {
            await this._processFrame();
            this._scheduleNextLoop();
        }, delay);
    }

    /**
     * Stage-based Frame Processing
     */
    async _processFrame() {
        if (this.isDetecting || this.isStopped || !TokobiiFaceVerification.areModelsReady) return;
        if (!this.video || this.video.paused || this.video.ended || this.video.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) return;

        this.isDetecting = true;
        const t0 = performance.now();

        try {
            const vw = this.video.videoWidth || 640;
            const vh = this.video.videoHeight || 480;

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // STAGE 1: SEARCHING — Posisi Wajah Lurus di Tengah
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            if (this.currentStage === TokobiiFaceVerification.STAGE.SEARCHING) {
                const detection = await faceapi
                    .detectSingleFace(this.video, this.detectorOptions)
                    .withFaceLandmarks();

                if (!detection) {
                    this.consecutiveNoFace++;
                    if (this.consecutiveNoFace >= 3) {
                        this.consecutiveDetections = 0;
                        this._setStatus('Mencari wajah...', 'info');
                        this._setInstruction('Posisikan wajah Anda tepat di dalam bingkai oval.');
                        this._setOvalGuideState('searching');
                        this._setProgress(20);
                    }
                    return;
                }

                this.consecutiveNoFace = 0;

                // Evaluasi posisi geometri oval
                const geo = this._evaluateGeometry(detection.detection.box, vw, vh);
                if (!geo.valid) {
                    this.consecutiveDetections = 0;
                    this._setStatus('Sesuaikan Posisi', geo.state);
                    this._setInstruction(geo.message);
                    this._setOvalGuideState(geo.state);
                    this._setProgress(30);
                    return;
                }

                // Cek apakah wajah menghadap lurus (Centered Yaw)
                const yaw = this._calculateYaw(detection.landmarks);
                if (!yaw.isCentered) {
                    this._setStatus('Hadapkan Wajah Lurus', 'warning');
                    this._setInstruction('Posisikan kepala menghadap lurus ke arah kamera.');
                    this._setOvalGuideState('warning');
                    this._setProgress(35);
                    return;
                }

                this.consecutiveDetections++;

                if (this.perf.firstDetectionMs === 0) {
                    this.perf.firstDetectionMs = Math.round(performance.now() - this.perf.startTime);
                    this._log(`First face detected in ${this.perf.firstDetectionMs}ms`);
                }

                this._setStatus('Wajah Lurus Terdeteksi ✓', 'success');
                this._setInstruction('Posisi tepat. Bersiap uji gerakan...');
                this._setOvalGuideState('success');
                this._setProgress(45);

                // Setelah 2 frame posisi lurus terkonfirmasi, langsung masuk Stage 2 LIVENESS
                if (this.consecutiveDetections >= 2) {
                    this.currentStage = TokobiiFaceVerification.STAGE.LIVENESS;
                    this.centerBaselineYaw = yaw.ratio; // Simpan baseline posisi lurus user
                    this.livenessStartTime = performance.now();

                    this._setStatus('Uji Gerakan: Silakan Menengok', 'purple');
                    this._setInstruction('Tengok ke kiri atau kanan sedikit...');
                    this._setOvalGuideState('liveness');
                    this._setProgress(55);
                    this._log(`Transitioned to STAGE 2: LIVENESS (Baseline Yaw: ${this.centerBaselineYaw.toFixed(3)})`);
                }
                return;
            }

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // STAGE 2: LIVENESS — Head Turn / Yaw Pose Estimation (~20ms)
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            if (this.currentStage === TokobiiFaceVerification.STAGE.LIVENESS) {
                const result = await faceapi
                    .detectSingleFace(this.video, this.detectorOptions)
                    .withFaceLandmarks();

                if (!result) {
                    this.consecutiveNoFace++;
                    if (this.consecutiveNoFace >= 6) {
                        // Wajah hilang beberapa frame, kembali ke SEARCHING
                        this.currentStage = TokobiiFaceVerification.STAGE.SEARCHING;
                        this.consecutiveDetections = 0;
                        this._setStatus('Wajah hilang', 'warning');
                        this._setInstruction('Posisikan wajah kembali di dalam bingkai oval.');
                        this._setOvalGuideState('searching');
                        this._setProgress(30);
                    }
                    return;
                }
                this.consecutiveNoFace = 0;

                const yaw = this._calculateYaw(result.landmarks);
                const isTurnDetected = (yaw.direction === 'left' || yaw.direction === 'right' || yaw.turnDelta >= this.YAW_TURN_DELTA);

                if (isTurnDetected) {
                    // Gerakan kepala valid terkonfirmasi!
                    this.livenessPassed = true;
                    this.perf.livenessCompleteMs = Math.round(performance.now() - this.perf.startTime);
                    this._log(`✓ Head Turn CONFIRMED (${yaw.direction.toUpperCase()}, Yaw=${yaw.ratio.toFixed(3)}, Delta=${yaw.turnDelta.toFixed(3)}) in ${this.perf.livenessCompleteMs}ms`);

                    this._setStatus('Gerakan Terdeteksi! ✓', 'success');
                    this._setInstruction('Gerakan terverifikasi. Merekam data biometrik...');
                    this._setOvalGuideState('success');
                    this._setProgress(75);

                    // LANGSUNG masuk Stage 3: SAMPLING tanpa jeda
                    this.currentStage = TokobiiFaceVerification.STAGE.SAMPLING;
                    this.collectedDescriptors = [];
                } else {
                    const elapsed = performance.now() - this.livenessStartTime;
                    if (elapsed > 5000 && elapsed <= 10000) {
                        this._setInstruction('Putar kepala Anda ke kiri atau kanan sedikit...');
                    }
                }
                return;
            }

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // STAGE 3: SAMPLING — Instant 128-D Descriptors (~35ms)
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            if (this.currentStage === TokobiiFaceVerification.STAGE.SAMPLING) {
                const sampleResult = await faceapi
                    .detectSingleFace(this.video, this.detectorOptions)
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (sampleResult?.descriptor) {
                    this.collectedDescriptors.push(Array.from(sampleResult.descriptor));
                    const currentCount = this.collectedDescriptors.length;
                    const pct = 75 + Math.round((currentCount / this.targetSamples) * 20);
                    this._setProgress(pct);
                    this._setStatus('Merekam Biometrik...', 'info');
                    this._setInstruction(`Mengambil sampel ${currentCount}/${this.targetSamples}...`);
                    this._log(`Sample ${currentCount}/${this.targetSamples} captured`);
                } else {
                    this._log('Sampling frame skipped (realigning)');
                }

                if (this.collectedDescriptors.length >= this.targetSamples) {
                    const avgDescriptor = this._averageDescriptors(this.collectedDescriptors);

                    this.currentStage = TokobiiFaceVerification.STAGE.SUBMITTING;
                    this._setProgress(98);
                    this._setStatus('Mengirim ke Server...', 'info');
                    this._setInstruction('Memproses verifikasi biometrik...');

                    await this._submitBiometric(avgDescriptor);
                }
            }

        } catch (err) {
            this._log('Frame processing error:', err);
        } finally {
            const frameMs = Math.round(performance.now() - t0);
            this.perf.inferenceCount++;
            this.perf.totalInferenceMs += frameMs;
            this.isDetecting = false;
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  HEAD POSE / YAW ESTIMATION
    // ═══════════════════════════════════════════════════════════════════

    /**
     * Hitung rasio Yaw (arah hadap horizontal wajah) menggunakan landmark 68-titik.
     *
     * Titik referensi:
     * - Landmark 0  : Kontur pipi/rahang paling kiri
     * - Landmark 16 : Kontur pipi/rahang paling kanan
     * - Landmark 30 : Ujung hidung (nose tip)
     *
     * Rasio Yaw = d_left / (d_left + d_right)
     * - Wajah lurus (Center) : ~0.46 - 0.54
     * - Nengok Kiri / Kanan  : <= 0.39 atau >= 0.61 (perubahan delta >= 0.11)
     */
    _calculateYaw(landmarks) {
        if (!landmarks || !landmarks.positions || landmarks.positions.length < 31) {
            return { ratio: 0.5, isCentered: true, direction: 'center', turnDelta: 0 };
        }

        const pos = landmarks.positions;
        const leftCheek  = pos[0];   // Titik paling kiri kontur wajah
        const rightCheek = pos[16];  // Titik paling kanan kontur wajah
        const noseTip    = pos[30];  // Ujung hidung

        const distLeft  = Math.abs(noseTip.x - leftCheek.x);
        const distRight = Math.abs(noseTip.x - rightCheek.x);
        const totalSpan = distLeft + distRight;

        if (totalSpan < 1) {
            return { ratio: 0.5, isCentered: true, direction: 'center', turnDelta: 0 };
        }

        const ratio = distLeft / totalSpan;
        const turnDelta = Math.abs(ratio - this.centerBaselineYaw);

        // Klasifikasi arah hadap
        let direction = 'center';
        if (ratio <= this.YAW_LEFT_THRESHOLD || (this.centerBaselineYaw - ratio) >= this.YAW_TURN_DELTA) {
            direction = 'left';
        } else if (ratio >= this.YAW_RIGHT_THRESHOLD || (ratio - this.centerBaselineYaw) >= this.YAW_TURN_DELTA) {
            direction = 'right';
        }

        // Posisi lurus jika rasio seimbang di rentang 0.44 - 0.56
        const isCentered = ratio >= 0.44 && ratio <= 0.56;

        return { ratio, isCentered, direction, turnDelta };
    }

    // ═══════════════════════════════════════════════════════════════════
    //  GEOMETRY VALIDATION (face position inside oval)
    // ═══════════════════════════════════════════════════════════════════

    _evaluateGeometry(box, videoW, videoH) {
        const relW       = box.width / videoW;
        const relCenterX = (box.x + box.width / 2) / videoW;
        const relCenterY = (box.y + box.height / 2) / videoH;

        if (relW < 0.15) return { valid: false, message: 'Dekatkan wajah sedikit ke kamera.', state: 'warning' };
        if (relW > 0.78) return { valid: false, message: 'Jauhkan wajah sedikit dari kamera.', state: 'warning' };
        if (relCenterX < 0.22) return { valid: false, message: 'Geser posisi wajah sedikit ke kanan.', state: 'warning' };
        if (relCenterX > 0.78) return { valid: false, message: 'Geser posisi wajah sedikit ke kiri.', state: 'warning' };
        if (relCenterY < 0.15) return { valid: false, message: 'Turunkan posisi wajah sedikit.', state: 'warning' };
        if (relCenterY > 0.85) return { valid: false, message: 'Naikkan posisi wajah sedikit.', state: 'warning' };

        return { valid: true, message: 'Posisi wajah tepat.', state: 'success' };
    }

    // ═══════════════════════════════════════════════════════════════════
    //  DESCRIPTOR AVERAGING
    // ═══════════════════════════════════════════════════════════════════

    _averageDescriptors(descriptors) {
        const dim = 128;
        const count = descriptors.length;
        const avg = new Array(dim);

        for (let i = 0; i < dim; i++) {
            let sum = 0;
            for (let s = 0; s < count; s++) {
                sum += descriptors[s][i];
            }
            avg[i] = sum / count;
        }

        return avg;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  BIOMETRIC SUBMISSION
    // ═══════════════════════════════════════════════════════════════════

    async _submitBiometric(descriptor) {
        if (this.config.mode === 'enroll') {
            await this._submitEnrollment(descriptor);
        } else {
            await this._submitVerification(descriptor);
        }
    }

    async _submitVerification(descriptor) {
        try {
            const res = await fetch(this.config.verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                body: JSON.stringify({ descriptor }),
            });

            const data = await res.json();
            this.perf.totalVerificationMs = Math.round(performance.now() - this.perf.startTime);

            if (res.ok && data.success) {
                this._onVerifySuccess(data);
            } else {
                this._onVerifyFail(data);
            }
        } catch (err) {
            this._log('Network error:', err);
            this._onNetworkError();
        }
    }

    async _submitEnrollment(descriptor) {
        const passwordInput = document.getElementById('enrollPassword');
        const password = passwordInput?.value || '';

        if (!password) {
            this._setStatus('Kata Sandi Diperlukan', 'danger');
            this._setInstruction('Masukkan kata sandi akun Anda untuk menyelesaikan pendaftaran.');
            this._setOvalGuideState('danger');
            this.currentStage = TokobiiFaceVerification.STAGE.FINISHED;
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
                body: JSON.stringify({ password, descriptor }),
            });

            const data = await res.json();

            if (res.ok && data.success) {
                this._setStatus('Pendaftaran Berhasil! ✓', 'success');
                this._setInstruction(data.message || 'Data biometrik wajah berhasil didaftarkan.');
                this._setOvalGuideState('success');
                this._setProgress(100);
                this.stop();

                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(data);
                } else {
                    setTimeout(() => window.location.reload(), 1200);
                }
            } else {
                this._setStatus(data.message || 'Pendaftaran gagal.', 'danger');
                this._setInstruction(data.message || 'Gagal mendaftarkan biometrik.');
                this._setOvalGuideState('danger');
                this.stop();
                if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

                if (typeof this.config.onError === 'function') {
                    this.config.onError(data);
                }
            }
        } catch (err) {
            this._log('Enrollment network error:', err);
            this._onNetworkError();
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    //  RESULT HANDLERS
    // ═══════════════════════════════════════════════════════════════════

    _onVerifySuccess(data) {
        this._setStatus('Verifikasi Berhasil! ✓', 'success');
        this._setInstruction('Autentikasi biometrik berhasil. Mengarahkan ke dashboard...');
        this._setOvalGuideState('success');
        this._setProgress(100);
        this.stop();

        this._log('Performance Summary:', {
            modelLoad:      `${this.perf.modelLoadMs}ms`,
            cameraStartup:  `${this.perf.cameraStartupMs}ms`,
            firstDetection: `${this.perf.firstDetectionMs}ms`,
            livenessTotal:  `${this.perf.livenessCompleteMs}ms`,
            totalFlow:      `${this.perf.totalVerificationMs}ms`,
            avgInference:   `${Math.round(this.perf.totalInferenceMs / Math.max(1, this.perf.inferenceCount))}ms`,
        });

        if (typeof this.config.onSuccess === 'function') {
            this.config.onSuccess(data);
        } else if (data.redirect_url) {
            window.location.href = data.redirect_url;
        }
    }

    _onVerifyFail(data) {
        this._setStatus(data.message || 'Verifikasi wajah gagal.', 'danger');
        this._setInstruction(data.message || 'Wajah tidak cocok dengan data biometrik akun ini.');
        this._setOvalGuideState('danger');
        this.stop();
        if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

        if (typeof this.config.onError === 'function') {
            this.config.onError(data);
        }
    }

    _onNetworkError() {
        this._setStatus('Kesalahan Jaringan', 'danger');
        this._setInstruction('Gagal menghubungi server. Silakan coba lagi.');
        this._setOvalGuideState('danger');
        this.stop();
        if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
    }

    // ═══════════════════════════════════════════════════════════════════
    //  LIFECYCLE CONTROLS
    // ═══════════════════════════════════════════════════════════════════

    stop() {
        this.isStopped = true;
        this.currentStage = TokobiiFaceVerification.STAGE.FINISHED;
        this._stopCameraStream();
    }

    restart() {
        this.stop();

        this.isDetecting           = false;
        this.livenessPassed        = false;
        this.consecutiveDetections = 0;
        this.consecutiveNoFace     = 0;
        this.centerBaselineYaw     = 0.50;
        this.collectedDescriptors  = [];
        this.perf                  = this._createPerfObject();

        this._setProgress(0);
        if (this.retryBtn) this.retryBtn.style.display = 'none';

        this.isStopped = false;
        this.currentStage = TokobiiFaceVerification.STAGE.SEARCHING;
        this._setStatus('Mencari wajah...', 'info');
        this._setInstruction('Posisikan wajah Anda lurus di dalam bingkai oval.');
        this._setOvalGuideState('searching');
        this._setProgress(30);

        this._setupCamera().then(() => {
            this._scheduleNextLoop();
        }).catch(() => {});
    }

    // ═══════════════════════════════════════════════════════════════════
    //  UI HELPERS
    // ═══════════════════════════════════════════════════════════════════

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
            searching: { color: 'rgba(59,130,246,0.8)',   style: 'dashed', glow: '' },
            success:   { color: 'rgba(16,185,129,0.95)',   style: 'solid',  glow: '0 0 20px rgba(16,185,129,0.5),' },
            warning:   { color: 'rgba(245,158,11,0.95)',   style: 'dashed', glow: '0 0 10px rgba(245,158,11,0.3),' },
            danger:    { color: 'rgba(244,63,94,0.95)',     style: 'dashed', glow: '0 0 10px rgba(244,63,94,0.3),' },
            liveness:  { color: 'rgba(147,51,234,0.95)',   style: 'solid',  glow: '0 0 15px rgba(147,51,234,0.4),' },
        };
        const s = styles[state] || styles.searching;
        this.ovalGuide.style.borderColor = s.color;
        this.ovalGuide.style.borderStyle = s.style;
        this.ovalGuide.style.boxShadow = `${s.glow} 0 0 0 9999px rgba(15,23,42,0.55)`;
    }

    _createPerfObject() {
        return {
            startTime:          performance.now(),
            modelLoadMs:        0,
            cameraStartupMs:    0,
            firstDetectionMs:   0,
            livenessCompleteMs: 0,
            totalVerificationMs:0,
            inferenceCount:     0,
            totalInferenceMs:   0,
        };
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
