import * as faceapi from '@vladmandic/face-api';

/**
 * Tokobii High-Performance Face Verification Engine
 * Menggunakan Deep Neural Network (@vladmandic/face-api) dengan eksekusi WebGL GPU terakselerasi.
 * 
 * Pipeline Bertahap (Staged Inference Architecture):
 * 1. Searching Phase : Ultra-lightweight TinyFaceDetector (~15-25ms per frame)
 * 2. Liveness Phase  : Face Landmark 68 Net untuk Adaptive EAR Blink Detection (~25-35ms)
 * 3. Sampling Phase  : Face Recognition Net (128-D Descriptor) dieksekusi HANYA setelah liveness terkonfirmasi
 */
class TokobiiFaceVerification {
    // Shared Singleton Model Promise (agar model HANYA di-load 1 kali selama page lifecycle)
    static modelsLoadedPromise = null;
    static areModelsReady = false;

    constructor(config) {
        this.config = Object.assign({
            mode: 'verify', // 'verify' or 'enroll'
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

        this.video = document.getElementById(this.config.videoElementId);
        this.statusEl = document.getElementById(this.config.statusElementId);
        this.instructionEl = document.getElementById(this.config.instructionElementId);
        this.progressBar = document.getElementById(this.config.progressBarId);
        this.ovalGuide = document.getElementById(this.config.ovalGuideId);
        this.retryBtn = document.getElementById(this.config.retryButtonId);
        this.cameraSelect = document.getElementById(this.config.cameraSelectId);

        // State Machine
        this.STAGE = {
            INITIALIZING: 'INITIALIZING',
            SEARCHING: 'SEARCHING',
            LIVENESS: 'LIVENESS',
            SAMPLING: 'SAMPLING',
            SUBMITTING: 'SUBMITTING',
            FINISHED: 'FINISHED',
        };
        this.currentStage = this.STAGE.INITIALIZING;

        // Flags & Guards
        this.stream = null;
        this.isDetecting = false; // Mutex lock untuk mencegah overlapping inference
        this.isStopped = false;
        this.loopTimer = null;

        // Detector Configuration
        this.detectionScoreThreshold = 0.38;
        this.inputSize = 224; // 224x224 input size: latency ultra-rendah (<25ms) dengan akurasi optimal
        this.detectorOptions = new faceapi.TinyFaceDetectorOptions({
            inputSize: this.inputSize,
            scoreThreshold: this.detectionScoreThreshold,
        });

        // Multi-frame stability & tracking
        this.consecutiveDetections = 0;
        this.consecutiveNoFace = 0;

        // Liveness tracking (Adaptive EAR)
        this.livenessPassed = false;
        this.blinkState = 'open';
        this.blinkCount = 0;
        this.baselineEAR = 0.28;
        this.earSamples = [];

        // Sample collection
        this.collectedDescriptors = [];
        this.targetSamples = 3;

        // Performance Metrics (Instrumentation)
        this.perf = {
            startTime: performance.now(),
            modelLoadMs: 0,
            cameraStartupMs: 0,
            firstDetectionMs: 0,
            livenessCompleteMs: 0,
            totalVerificationMs: 0,
            inferenceCount: 0,
            totalInferenceMs: 0,
        };

        this.init();
    }

    log(message, data = null) {
        if (this.config.debug) {
            if (data !== null) {
                console.log(`[TokobiiFaceAI] ${message}`, data);
            } else {
                console.log(`[TokobiiFaceAI] ${message}`);
            }
        }
    }

    setStatus(text, type = 'info') {
        if (!this.statusEl) return;
        const colorMap = {
            info: 'bg-blue-50 text-blue-700 border-blue-200',
            success: 'bg-emerald-50 text-emerald-700 border-emerald-200',
            warning: 'bg-amber-50 text-amber-700 border-amber-200',
            danger: 'bg-rose-50 text-rose-700 border-rose-200',
            purple: 'bg-purple-50 text-purple-700 border-purple-200',
        };
        this.statusEl.className = `tokobii-badge ${colorMap[type] || colorMap.info} px-3 py-1.5 small fw-semibold border shadow-sm`;
        this.statusEl.innerHTML = text;
    }

    setInstruction(text) {
        if (this.instructionEl) {
            this.instructionEl.textContent = text;
        }
    }

    setProgress(percentage) {
        if (this.progressBar) {
            this.progressBar.style.width = `${Math.min(100, Math.max(0, percentage))}%`;
        }
    }

    setOvalGuideState(state) {
        if (!this.ovalGuide) return;
        switch (state) {
            case 'success':
                this.ovalGuide.style.borderColor = 'rgba(16, 185, 129, 0.95)';
                this.ovalGuide.style.borderStyle = 'solid';
                this.ovalGuide.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.5), 0 0 0 9999px rgba(15, 23, 42, 0.55)';
                break;
            case 'warning':
                this.ovalGuide.style.borderColor = 'rgba(245, 158, 11, 0.95)';
                this.ovalGuide.style.borderStyle = 'dashed';
                this.ovalGuide.style.boxShadow = '0 0 10px rgba(245, 158, 11, 0.3), 0 0 0 9999px rgba(15, 23, 42, 0.55)';
                break;
            case 'danger':
                this.ovalGuide.style.borderColor = 'rgba(244, 63, 94, 0.95)';
                this.ovalGuide.style.borderStyle = 'dashed';
                this.ovalGuide.style.boxShadow = '0 0 10px rgba(244, 63, 94, 0.3), 0 0 0 9999px rgba(15, 23, 42, 0.55)';
                break;
            case 'liveness':
                this.ovalGuide.style.borderColor = 'rgba(147, 51, 234, 0.95)';
                this.ovalGuide.style.borderStyle = 'solid';
                this.ovalGuide.style.boxShadow = '0 0 15px rgba(147, 51, 234, 0.4), 0 0 0 9999px rgba(15, 23, 42, 0.55)';
                break;
            default: // searching
                this.ovalGuide.style.borderColor = 'rgba(59, 130, 246, 0.8)';
                this.ovalGuide.style.borderStyle = 'dashed';
                this.ovalGuide.style.boxShadow = '0 0 0 9999px rgba(15, 23, 42, 0.55)';
        }
    }

    async init() {
        if (this.retryBtn) {
            this.retryBtn.addEventListener('click', () => this.restart());
        }

        if (this.cameraSelect) {
            this.cameraSelect.addEventListener('change', () => this.startCamera(this.cameraSelect.value));
        }

        window.addEventListener('beforeunload', () => this.stop());

        // Optimasi: Inisialisasi Model AI dan Kamera secara PARALEL untuk memangkas latency startup
        this.setStatus('Menyiapkan sistem verifikasi...', 'info');
        this.setInstruction('Memuat modul AI dan mengaktifkan kamera...');
        this.setProgress(15);

        try {
            await Promise.all([
                this.loadModels(),
                this.setupCamera(),
            ]);

            this.currentStage = this.STAGE.SEARCHING;
            this.setStatus('Mencari wajah...', 'info');
            this.setInstruction('Posisikan wajah Anda di dalam bingkai oval.');
            this.setOvalGuideState('searching');
            this.setProgress(35);

            this.startDetectionLoop();
        } catch (err) {
            console.error('Initialization error:', err);
        }
    }

    async loadModels() {
        if (TokobiiFaceVerification.areModelsReady) {
            this.log('Model AI sudah tersedia di memory (Cached Singleton).');
            return;
        }

        if (!TokobiiFaceVerification.modelsLoadedPromise) {
            const startT = performance.now();
            TokobiiFaceVerification.modelsLoadedPromise = (async () => {
                // Konfigurasi WebGL GPU backend acceleration jika tersedia
                if (faceapi.tf) {
                    try {
                        if (faceapi.tf.getBackend() !== 'webgl') {
                            await faceapi.tf.setBackend('webgl');
                        }
                        faceapi.tf.enableProdMode();
                        faceapi.tf.env().set('WEBGL_VERSION', 2);
                        faceapi.tf.env().set('WEBGL_PACK', true);
                    } catch (e) {
                        console.warn('[TokobiiFaceAI] WebGL backend optimization note:', e);
                    }
                }

                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(this.config.modelsUri),
                    faceapi.nets.faceLandmark68Net.loadFromUri(this.config.modelsUri),
                    faceapi.nets.faceRecognitionNet.loadFromUri(this.config.modelsUri),
                ]);

                TokobiiFaceVerification.areModelsReady = true;
                const loadDuration = Math.round(performance.now() - startT);
                this.perf.modelLoadMs = loadDuration;
                this.log(`Model AI berhasil dimuat & dikompilasi dalam ${loadDuration}ms`);
            })();
        }

        return TokobiiFaceVerification.modelsLoadedPromise;
    }

    async setupCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            this.setStatus('Kamera tidak didukung', 'danger');
            this.setInstruction('Browser atau perangkat Anda tidak mendukung akses kamera WebRTC.');
            return;
        }

        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');

            if (this.cameraSelect) {
                this.cameraSelect.innerHTML = '';
                videoDevices.forEach((device, index) => {
                    const opt = document.createElement('option');
                    opt.value = device.deviceId;
                    opt.text = device.label || `Kamera ${index + 1}`;
                    this.cameraSelect.appendChild(opt);
                });
                this.cameraSelect.style.display = videoDevices.length > 1 ? 'block' : 'none';
            }

            const initialDeviceId = videoDevices.length > 0 ? videoDevices[0].deviceId : undefined;
            await this.startCamera(initialDeviceId);
        } catch (err) {
            console.error('Setup camera error:', err);
            this.handleCameraError(err);
        }
    }

    async startCamera(deviceId = undefined) {
        this.stopCameraStream();
        this.isStopped = false;
        const camStartT = performance.now();

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

            // Tunggu hingga frame pertama siap didekode
            await new Promise((resolve) => {
                const checkReady = () => {
                    if (this.video.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA && this.video.videoWidth > 0) {
                        resolve();
                    } else {
                        setTimeout(checkReady, 30);
                    }
                };
                this.video.onloadeddata = checkReady;
                this.video.onloadedmetadata = () => {
                    this.video.play().catch(e => console.warn('Autoplay caught:', e));
                    checkReady();
                };
                setTimeout(checkReady, 300);
            });

            this.perf.cameraStartupMs = Math.round(performance.now() - camStartT);
            this.log(`Kamera aktif (${this.video.videoWidth}x${this.video.videoHeight}) dalam ${this.perf.cameraStartupMs}ms`);
        } catch (err) {
            this.handleCameraError(err);
        }
    }

    handleCameraError(err) {
        let msg = 'Gagal mengakses kamera.';
        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
            msg = 'Akses kamera ditolak. Silakan izinkan izin kamera pada pengaturan browser Anda lalu klik Coba Lagi.';
        } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
            msg = 'Perangkat kamera tidak ditemukan.';
        } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
            msg = 'Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.';
        } else if (err.name === 'OverconstrainedError') {
            msg = 'Konfigurasi resolusi kamera tidak didukung perangkat ini.';
        } else if (err.name === 'SecurityError') {
            msg = 'Akses kamera dibatasi karena konteks tidak aman (HTTPS/Localhost diperlukan).';
        }

        this.setStatus(msg, 'danger');
        this.setInstruction(msg);
        this.setOvalGuideState('danger');
        if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
    }

    stopCameraStream() {
        if (this.loopTimer) {
            clearTimeout(this.loopTimer);
            this.loopTimer = null;
        }
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        if (this.video) {
            this.video.srcObject = null;
        }
    }

    stop() {
        this.isStopped = true;
        this.currentStage = this.STAGE.FINISHED;
        this.stopCameraStream();
    }

    restart() {
        this.stop();
        this.isDetecting = false;
        this.livenessPassed = false;
        this.blinkCount = 0;
        this.blinkState = 'open';
        this.consecutiveDetections = 0;
        this.consecutiveNoFace = 0;
        this.earSamples = [];
        this.collectedDescriptors = [];
        this.perf = {
            startTime: performance.now(),
            modelLoadMs: 0,
            cameraStartupMs: 0,
            firstDetectionMs: 0,
            livenessCompleteMs: 0,
            totalVerificationMs: 0,
            inferenceCount: 0,
            totalInferenceMs: 0,
        };
        this.setProgress(0);
        if (this.retryBtn) this.retryBtn.style.display = 'none';
        this.setupCamera();
    }

    calculateEAR(eyePoints) {
        const dist = (p1, p2) => Math.hypot(p1.x - p2.x, p1.y - p2.y);
        const vertical1 = dist(eyePoints[1], eyePoints[5]);
        const vertical2 = dist(eyePoints[2], eyePoints[4]);
        const horizontal = dist(eyePoints[0], eyePoints[3]);
        if (horizontal === 0) return 0.28;
        return (vertical1 + vertical2) / (2.0 * horizontal);
    }

    evaluateFaceGeometry(box, videoW, videoH) {
        const faceW = box.width;
        const faceH = box.height;
        const faceCenterX = box.x + (faceW / 2);
        const faceCenterY = box.y + (faceH / 2);

        const relW = faceW / videoW;
        const relCenterX = faceCenterX / videoW;
        const relCenterY = faceCenterY / videoH;

        if (relW < 0.15) {
            return { valid: false, message: 'Dekatkan wajah sedikit ke kamera.', state: 'warning' };
        }
        if (relW > 0.78) {
            return { valid: false, message: 'Jauhkan wajah sedikit dari kamera.', state: 'warning' };
        }
        if (relCenterX < 0.22) {
            return { valid: false, message: 'Geser posisi wajah sedikit ke kanan.', state: 'warning' };
        }
        if (relCenterX > 0.78) {
            return { valid: false, message: 'Geser posisi wajah sedikit ke kiri.', state: 'warning' };
        }
        if (relCenterY < 0.15) {
            return { valid: false, message: 'Turunkan posisi wajah sedikit.', state: 'warning' };
        }
        if (relCenterY > 0.85) {
            return { valid: false, message: 'Naikkan posisi wajah sedikit.', state: 'warning' };
        }

        return { valid: true, message: 'Posisi wajah tepat. Pertahankan posisi...', state: 'success' };
    }

    startDetectionLoop() {
        const DETECTION_INTERVAL_MS = 60; // Throttling optimal ~16 FPS

        const scheduleNext = () => {
            if (this.isStopped || !this.video) return;
            this.loopTimer = setTimeout(async () => {
                await this.processFrame();
                scheduleNext();
            }, DETECTION_INTERVAL_MS);
        };

        scheduleNext();
    }

    /**
     * Staged Frame Processing:
     * Menjalankan neural network secara bertahap sesuai kebutuhan stage aktif.
     */
    async processFrame() {
        // Mutex Guard: Cegah overlapping inference jika frame sebelumnya belum selesai
        if (this.isDetecting || this.isStopped || !TokobiiFaceVerification.areModelsReady) return;
        if (!this.video || this.video.paused || this.video.ended || this.video.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) return;

        this.isDetecting = true;
        const frameStartT = performance.now();

        try {
            const videoW = this.video.videoWidth || 640;
            const videoH = this.video.videoHeight || 480;

            // =========================================================================
            // TAHAP 1: SEARCHING PHASE (Ultra-fast TinyFaceDetector ~15ms)
            // =========================================================================
            if (this.currentStage === this.STAGE.SEARCHING) {
                // Hanya jalankan deteksi bounding box ringan tanpa landmark atau recognition
                const detections = await faceapi.detectAllFaces(this.video, this.detectorOptions);

                if (detections.length === 0) {
                    this.consecutiveNoFace++;
                    if (this.consecutiveNoFace >= 3) {
                        this.consecutiveDetections = 0;
                        this.setStatus('Wajah belum terdeteksi', 'warning');
                        this.setInstruction('Posisikan wajah Anda tepat di dalam bingkai oval.');
                        this.setOvalGuideState('searching');
                        this.setProgress(20);
                    }
                    return;
                }

                if (detections.length > 1) {
                    this.consecutiveDetections = 0;
                    this.setStatus('Terdeteksi lebih dari 1 wajah', 'danger');
                    this.setInstruction('Pastikan hanya ada satu orang di depan kamera.');
                    this.setOvalGuideState('danger');
                    this.setProgress(15);
                    return;
                }

                // Tepat SATU wajah ditemukan
                this.consecutiveNoFace = 0;
                this.consecutiveDetections++;

                const faceBox = detections[0].box;
                const geometry = this.evaluateFaceGeometry(faceBox, videoW, videoH);

                if (!geometry.valid) {
                    this.setStatus('Sesuaikan Posisi', geometry.state);
                    this.setInstruction(geometry.message);
                    this.setOvalGuideState(geometry.state);
                    this.setProgress(35);
                    return;
                }

                // INSTANT FEEDBACK: Wajah terdeteksi langsung diberikan indikator visual split second
                if (this.perf.firstDetectionMs === 0) {
                    this.perf.firstDetectionMs = Math.round(performance.now() - this.perf.startTime);
                    this.log(`First Face Detected in ${this.perf.firstDetectionMs}ms`);
                }

                this.setStatus('Wajah terdeteksi ✓', 'success');
                this.setInstruction('Posisi wajah tepat. Bersiap untuk uji liveness...');
                this.setOvalGuideState('success');
                this.setProgress(50);

                // Setelah 2 frame stabil, langsung masuk ke Tahap Liveness
                if (this.consecutiveDetections >= 2) {
                    this.currentStage = this.STAGE.LIVENESS;
                }
                return;
            }

            // =========================================================================
            // TAHAP 2: LIVENESS PHASE (Face Detection + 68 Landmark Net ~25ms)
            // =========================================================================
            if (this.currentStage === this.STAGE.LIVENESS) {
                const singleDetection = await faceapi.detectSingleFace(this.video, this.detectorOptions)
                    .withFaceLandmarks();

                if (!singleDetection) {
                    this.consecutiveNoFace++;
                    if (this.consecutiveNoFace >= 4) {
                        this.currentStage = this.STAGE.SEARCHING;
                    }
                    return;
                }

                this.consecutiveNoFace = 0;
                this.setOvalGuideState('liveness');
                this.setStatus('Uji Liveness: Silakan Berkedip', 'purple');
                this.setInstruction('Kedipkan mata Anda sekali secara alami untuk verifikasi keamanan...');
                this.setProgress(60);

                const landmarks = singleDetection.landmarks;
                const leftEAR = this.calculateEAR(landmarks.getLeftEye());
                const rightEAR = this.calculateEAR(landmarks.getRightEye());
                const currentEAR = (leftEAR + rightEAR) / 2.0;

                // Kalibrasi dinamis baseline EAR mata terbuka
                if (this.earSamples.length < 4) {
                    this.earSamples.push(currentEAR);
                    this.baselineEAR = this.earSamples.reduce((a, b) => a + b, 0) / this.earSamples.length;
                }

                const blinkClosedThreshold = this.baselineEAR * 0.74;
                const blinkOpenThreshold = this.baselineEAR * 0.88;

                if (currentEAR < blinkClosedThreshold && this.blinkState === 'open') {
                    this.blinkState = 'closed';
                    this.log(`Blink down: EAR ${currentEAR.toFixed(3)} < ${blinkClosedThreshold.toFixed(3)}`);
                } else if (currentEAR > blinkOpenThreshold && this.blinkState === 'closed') {
                    this.blinkState = 'open';
                    this.blinkCount += 1;
                    this.log(`Blink confirmed! Total blinks: ${this.blinkCount}`);
                }

                if (this.blinkCount >= 1) {
                    this.livenessPassed = true;
                    this.perf.livenessCompleteMs = Math.round(performance.now() - this.perf.startTime);
                    this.log(`Liveness Verified in ${this.perf.livenessCompleteMs}ms`);

                    this.setStatus('Liveness Terverifikasi!', 'success');
                    this.setInstruction('Pertahankan posisi wajah, sedang merekam biometrik...');
                    this.setOvalGuideState('success');
                    this.setProgress(75);

                    this.currentStage = this.STAGE.SAMPLING;
                }
                return;
            }

            // =========================================================================
            // TAHAP 3: SAMPLING & RECOGNITION (Dijalankan HANYA setelah Liveness lolos)
            // =========================================================================
            if (this.currentStage === this.STAGE.SAMPLING) {
                const sampleResult = await faceapi.detectSingleFace(this.video, this.detectorOptions)
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (sampleResult && sampleResult.descriptor) {
                    this.collectedDescriptors.push(Array.from(sampleResult.descriptor));
                    const progressVal = 75 + (this.collectedDescriptors.length * 8);
                    this.setProgress(progressVal);
                    this.setStatus('Merekam Fitur Biometrik...', 'info');
                    this.setInstruction(`Merekam sampel ${this.collectedDescriptors.length} dari ${this.targetSamples}...`);
                }

                if (this.collectedDescriptors.length >= this.targetSamples) {
                    this.currentStage = this.STAGE.SUBMITTING;
                    this.setProgress(100);
                    this.setStatus('Memproses ke Server...', 'info');
                    this.setInstruction('Sedang melakukan verifikasi biometrik dengan server Tokobii...');

                    // Hitung rata-rata vektor 128-D
                    const finalVector = new Array(128).fill(0);
                    const count = this.collectedDescriptors.length;

                    for (let i = 0; i < 128; i++) {
                        let sum = 0;
                        for (let s = 0; s < count; s++) {
                            sum += this.collectedDescriptors[s][i];
                        }
                        finalVector[i] = sum / count;
                    }

                    await this.submitBiometric(finalVector);
                }
            }

        } catch (err) {
            console.error('Frame processing error:', err);
        } finally {
            const frameDuration = Math.round(performance.now() - frameStartT);
            this.perf.inferenceCount++;
            this.perf.totalInferenceMs += frameDuration;
            this.perf.avgInferenceMs = Math.round(this.perf.totalInferenceMs / this.perf.inferenceCount);
            this.isDetecting = false;
        }
    }

    async submitBiometric(descriptorVector) {
        if (this.config.mode === 'enroll') {
            await this.submitEnrollment(descriptorVector);
        } else {
            await this.submitVerification(descriptorVector);
        }
    }

    async submitVerification(descriptorVector) {
        try {
            const response = await fetch(this.config.verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                body: JSON.stringify({
                    descriptor: descriptorVector,
                }),
            });

            const data = await response.json();
            this.perf.totalVerificationMs = Math.round(performance.now() - this.perf.startTime);

            if (response.ok && data.success) {
                this.setStatus('Verifikasi Berhasil!', 'success');
                this.setInstruction('Autentikasi biometrik berhasil. Mengarahkan ke dashboard...');
                this.setOvalGuideState('success');
                this.stop();

                this.log('Performance Summary:', {
                    modelLoad: `${this.perf.modelLoadMs}ms`,
                    cameraStartup: `${this.perf.cameraStartupMs}ms`,
                    firstDetection: `${this.perf.firstDetectionMs}ms`,
                    livenessComplete: `${this.perf.livenessCompleteMs}ms`,
                    totalVerification: `${this.perf.totalVerificationMs}ms`,
                    avgInference: `${this.perf.avgInferenceMs}ms`,
                });

                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(data);
                } else if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            } else {
                this.setStatus(data.message || 'Verifikasi wajah gagal.', 'danger');
                this.setInstruction(data.message || 'Wajah tidak cocok dengan data biometrik akun ini.');
                this.setOvalGuideState('danger');
                this.stop();
                if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

                if (typeof this.config.onError === 'function') {
                    this.config.onError(data);
                }
            }
        } catch (err) {
            console.error('Submit verification error:', err);
            this.setStatus('Kesalahan Jaringan', 'danger');
            this.setInstruction('Gagal menghubungi server Tokobii. Silakan coba lagi.');
            this.setOvalGuideState('danger');
            this.stop();
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
        }
    }

    async submitEnrollment(descriptorVector) {
        const passwordInput = document.getElementById('enrollPassword');
        const password = passwordInput ? passwordInput.value : '';

        if (!password) {
            this.setStatus('Kata Sandi Diperlukan', 'danger');
            this.setInstruction('Masukkan kata sandi akun Anda pada formulir untuk menyelesaikan pendaftaran.');
            this.setOvalGuideState('danger');
            this.currentStage = this.STAGE.FINISHED;
            return;
        }

        try {
            const response = await fetch(this.config.enrollUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                body: JSON.stringify({
                    password: password,
                    descriptor: descriptorVector,
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                this.setStatus('Pendaftaran Berhasil!', 'success');
                this.setInstruction(data.message || 'Data biometrik wajah Anda berhasil didaftarkan.');
                this.setOvalGuideState('success');
                this.stop();

                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(data);
                } else {
                    setTimeout(() => window.location.reload(), 1200);
                }
            } else {
                this.setStatus(data.message || 'Pendaftaran biometrik gagal.', 'danger');
                this.setInstruction(data.message || 'Gagal mendaftarkan biometrik wajah.');
                this.setOvalGuideState('danger');
                this.stop();
                if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';

                if (typeof this.config.onError === 'function') {
                    this.config.onError(data);
                }
            }
        } catch (err) {
            console.error('Submit enrollment error:', err);
            this.setStatus('Kesalahan Jaringan', 'danger');
            this.setInstruction('Gagal menghubungi server Tokobii. Silakan coba lagi.');
            this.setOvalGuideState('danger');
            this.stop();
            if (this.retryBtn) this.retryBtn.style.display = 'inline-flex';
        }
    }
}

window.TokobiiFaceVerification = TokobiiFaceVerification;
export default TokobiiFaceVerification;
