import Human from '@vladmandic/human';

/**
 * Tokobii Human Engine Wrapper v4.2
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Engine biometrik performa tinggi berbasis @vladmandic/human v3.3.6.
 *
 * Optimasi Kunci:
 * 1. Dual-Pipeline Architecture (Fast Path vs Heavy Path):
 *    - Jalur Cepat (Tracking): Hanya BlazeFace + FaceMesh (~15-25ms, >20 FPS)
 *    - Jalur Berat (Capture): FaceRes (1024-D) + AntiSpoof + Liveness hanya saat snapshot
 * 2. Normalisasi Sudut Kepala (Pose Angles) DERAJAT mutlak dengan kalibrasi mirror.
 * 3. Face ROI Brightness Calculation (tidak terpengaruh cahaya latar belakang/jendela).
 * 4. Guard ketat & Preflight check terisolasi.
 */

// Konstanta Kalibrasi Arah Tolehan (Mirror Space):
// Menoleh ke KIRI user (kiri cermin) = userYaw bernilai negatif (< 0)
// Menoleh ke KANAN user (kanan cermin) = userYaw bernilai positif (> 0)
export const POSE_YAW_SIGN = -1;

class HumanEngine {
    constructor() {
        this.human = null;
        this.isLoaded = false;
        this.isWarmingUp = false;
        this.loadPromise = null;
        this.backendUsed = 'webgl';
        this.modelBasePath = '';
        this.wasmPath = '';
        this.debug = false;
        this.modelStatus = {}; // Hasil preflight per file model

        // Telemetri Performa Inferensi
        this.perf = {
            fastPathMs: 0,
            heavyPathMs: 0,
            lastInferenceMs: 0,
            lastMode: 'fast',
        };

        this._brightnessCanvas = null;
        this._brightnessCtx = null;
    }

    /**
     * Inisialisasi Human instance dengan konfigurasi model & backend.
     */
    init(config = {}) {
        if (this.human) return this.human;

        const faceConfig = (typeof window !== 'undefined' && window.FACE_CONFIG)
            ? window.FACE_CONFIG
            : null;

        const modelBase = config.modelsUri || config.modelBasePath || faceConfig?.modelBase;

        if (!modelBase) {
            const err = new Error('[TokobiiFace] FACE_CONFIG tidak ditemukan. Pastikan partial face-config telah dimuat.');
            console.error(err.message);
            throw err;
        }

        const wasmBase = config.wasmPath || faceConfig?.wasmBase || modelBase;

        this.modelBasePath = modelBase;
        this.wasmPath = wasmBase;
        this.debug = Boolean(config.debug ?? faceConfig?.debug ?? false);

        const humanConfig = {
            backend: config.backend || 'webgl',
            modelBasePath: this.modelBasePath,
            wasmPath: this.wasmPath,
            debug: this.debug,
            async: true,
            warmup: 'none',
            filter: {
                enabled: true,
                flip: false, // Mirroring dilakukan lewat CSS transform: scaleX(-1)
                width: 0,   // Resolusi natural kamera (0 = no resize, tidak mendistorsi portrait mobile)
                height: 0,  // Resolusi natural kamera (0 = no resize, tidak mendistorsi portrait mobile)
            },
            cacheSensitivity: 0.75,
            skipAllowed: true, // Izinkan skipping frame jika video stabil pada tracking
            face: {
                enabled: true,
                detector: {
                    modelPath: 'blazeface.json',
                    maxDetected: 1,
                    minConfidence: 0.50,
                    rotation: false,
                    return: true,
                },
                mesh: {
                    enabled: true,
                    modelPath: 'facemesh.json',
                },
                description: {
                    enabled: true,
                    modelPath: 'faceres.json',
                },
                antispoof: {
                    enabled: true,
                    modelPath: 'antispoof.json',
                    skipFrames: 0,
                },
                liveness: {
                    enabled: true,
                    modelPath: 'liveness.json',
                    skipFrames: 0,
                },
                iris: { enabled: false },
                emotion: { enabled: false },
            },
            body: { enabled: false },
            hand: { enabled: false },
            object: { enabled: false },
            gesture: { enabled: false },
            segmentation: { enabled: false },
        };

        try {
            this.human = new Human(humanConfig);
            if (this.debug) {
                console.log('[TokobiiFace] Human Engine diinisialisasi.', {
                    modelBasePath: this.modelBasePath,
                    wasmPath: this.wasmPath,
                    backend: humanConfig.backend,
                });
            }
        } catch (initErr) {
            console.error('[TokobiiFace] Gagal inisialisasi Human instance:', initErr);
            throw initErr;
        }

        return this.human;
    }

    /**
     * Preflight check ketersediaan semua file model JSON aktif sebelum dimuat.
     */
    async preflightModels(basePath = this.modelBasePath) {
        const activeModels = [
            'blazeface.json',
            'facemesh.json',
            'faceres.json',
            'antispoof.json',
            'liveness.json',
        ];

        const results = [];
        const failedModels = [];

        for (const model of activeModels) {
            const url = `${basePath}${model}`;
            let status = 0;
            let ok = false;

            try {
                let res = await fetch(url, { method: 'HEAD', cache: 'no-cache' });
                if (res.status === 405) {
                    res = await fetch(url, { method: 'GET', cache: 'no-cache' });
                }
                status = res.status;
                ok = res.ok;
            } catch (networkErr) {
                status = 0;
                ok = false;
            }

            const record = { model, url, status, ok };
            results.push(record);
            this.modelStatus[model] = record;

            if (!ok) {
                failedModels.push(record);
                console.error(`[TokobiiFace] Preflight FAILED: Model '${model}' (${status || 'Network Error'}) di ${url}`);
            } else if (this.debug) {
                console.log(`[TokobiiFace] Preflight OK: Model '${model}' (${status}) di ${url}`);
            }
        }

        return {
            ok: failedModels.length === 0,
            failedModels,
            results,
        };
    }

    /**
     * Memuat model ke memori GPU / CPU secara paralel dengan preflight dan validasi.
     */
    async load(onProgress = null) {
        if (this.isLoaded) return true;
        if (this.loadPromise) return this.loadPromise;

        this.loadPromise = (async () => {
            if (!this.human) {
                this.init();
            }

            if (typeof onProgress === 'function') {
                onProgress({ stage: 'preflight', percent: 15, message: 'Memverifikasi ketersediaan model biometrik...' });
            }

            const preflight = await this.preflightModels();
            if (!preflight.ok) {
                this.loadPromise = null;
                this.isLoaded = false;
                const firstFail = preflight.failedModels[0];
                const statusLabel = firstFail.status ? `${firstFail.status}` : 'Network Error';
                const err = new Error(`Model '${firstFail.model}' tidak ditemukan (${statusLabel}) di ${firstFail.url}`);
                err.failedModels = preflight.failedModels;
                throw err;
            }

            if (typeof onProgress === 'function') {
                onProgress({ stage: 'loading_models', percent: 40, message: 'Memuat model AI biometrik ke GPU...' });
            }

            try {
                await this.human.load();
                this.backendUsed = this.human.tf?.getBackend ? this.human.tf.getBackend() : 'webgl';

                if (typeof onProgress === 'function') {
                    onProgress({ stage: 'warming_up', percent: 75, message: 'Melakukan akselerasi WebGL...' });
                }

                await this.warmup();

                this.isLoaded = true;
                if (typeof onProgress === 'function') {
                    onProgress({ stage: 'ready', percent: 100, message: 'Sistem biometrik siap.' });
                }

                return true;
            } catch (err) {
                this.loadPromise = null;
                this.isLoaded = false;
                console.error('[TokobiiFace] Gagal memuat atau memverifikasi model Human:', err);
                throw err;
            }
        })();

        return this.loadPromise;
    }

    /**
     * Warmup pipeline deteksi untuk mengompilasi shader WebGL terlebih dahulu.
     */
    async warmup() {
        if (!this.human) return;
        try {
            await this.human.warmup({ warmup: 'face' });
        } catch (e) {
            console.warn('[TokobiiFace] Warmup note (fallback safe):', e);
        }
    }

    /**
     * JALUR CEPAT (Fast Path):
     * Dijalankan pada setiap frame video untuk tracking posisi wajah, mesh, dan pose angle.
     * Hanya menjalankan BlazeFace + FaceMesh (~15-25ms).
     */
    async detectTracking(videoElement) {
        return this.detect(videoElement, { mode: 'fast' });
    }

    /**
     * JALUR BERAT (Heavy Path):
     * Dijalankan SEKALI saat pose stabil tercapai untuk mengambil embedding 1024-D,
     * skor anti-spoofing, dan liveness (~80-120ms).
     */
    async detectCapture(videoElement) {
        return this.detect(videoElement, { mode: 'heavy' });
    }

    /**
     * Inferensi Utama dengan Dukungan Dual-Pipeline.
     *
     * @param {HTMLVideoElement} videoElement
     * @param {Object} options { mode: 'fast' | 'heavy' | 'full' }
     */
    async detect(videoElement, options = {}) {
        if (!this.human || !this.isLoaded) {
            return null;
        }
        if (!videoElement || videoElement.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) {
            return null;
        }

        const mode = options.mode || 'full';
        const isFast = (mode === 'fast');
        const t0 = performance.now();

        // Dynamic config override per-frame tanpa mengalokasikan instance baru
        const configOverride = {
            face: {
                enabled: true,
                detector: { return: true, rotation: false },
                mesh: { enabled: true },
                // Nonaktifkan model berat di jalur cepat
                description: { enabled: !isFast },
                antispoof: { enabled: !isFast },
                liveness: { enabled: !isFast },
                iris: { enabled: false },
                emotion: { enabled: false },
            },
            skipAllowed: isFast,
        };

        try {
            const result = await this.human.detect(videoElement, configOverride);
            const durationMs = Math.round(performance.now() - t0);

            this.perf.lastInferenceMs = durationMs;
            this.perf.lastMode = mode;
            if (isFast) {
                this.perf.fastPathMs = durationMs;
            } else {
                this.perf.heavyPathMs = durationMs;
            }

            if (!result || !result.face || result.face.length === 0) {
                return null;
            }

            const face = result.face[0];
            const box = face.box || [0, 0, 0, 0];
            const score = face.score ?? face.boxScore ?? 0.8;
            const mesh = face.mesh || [];
            const embedding = face.embedding ? Array.from(face.embedding) : null;
            const realScore = face.real ?? face.antispoof ?? 0.85;
            const liveScore = face.live ?? face.liveness ?? 0.85;
            const rotation = face.rotation || { yaw: 0, pitch: 0, roll: 0 };

            // ─── KONVERSI SUDUT KEPALA (EULER ANGLES) DARI RADIAN KE DERAJAT ───
            // @vladmandic/human mengembalikan angle dalam satuan RADIAN.
            const rawYawRad   = rotation.angle?.yaw ?? (typeof rotation.yaw === 'number' ? rotation.yaw : 0);
            const rawPitchRad = rotation.angle?.pitch ?? (typeof rotation.pitch === 'number' ? rotation.pitch : 0);
            const rawRollRad  = rotation.angle?.roll ?? (typeof rotation.roll === 'number' ? rotation.roll : 0);

            const yawDegrees   = (rawYawRad * 180) / Math.PI;
            const pitchDegrees = (rawPitchRad * 180) / Math.PI;
            const rollDegrees  = (rawRollRad * 180) / Math.PI;

            // userYaw dinormalisasi dengan mirror sign agar:
            // Tolehan ke KIRI (arah kiri layar cermin) bernilai NEGATIF (< 0)
            // Tolehan ke KANAN (arah kanan layar cermin) bernilai POSITIF (> 0)
            const userYaw = Number((yawDegrees * POSE_YAW_SIGN).toFixed(2));
            const userPitch = Number(pitchDegrees.toFixed(2));
            const userRoll = Number(rollDegrees.toFixed(2));

            // Metrik Liveness Tambahan (EAR & MAR)
            const ear = this.computeEAR(mesh);
            const mar = this.computeMAR(mesh);

            // Perhitungan kecerahan spesifik area wajah (Face ROI Brightness)
            const faceBoxObj = {
                x: box[0],
                y: box[1],
                width: box[2],
                height: box[3],
            };
            const faceBrightness = this.calculateFaceBrightness(videoElement, faceBoxObj);

            return {
                raw: face,
                box: faceBoxObj,
                score,
                mesh,
                embedding,
                realScore: Number(realScore.toFixed(4)),
                liveScore: Number(liveScore.toFixed(4)),
                rotation: {
                    yaw: userYaw,            // Derajat terkalibrasi (- = kiri, + = kanan)
                    rawYaw: Number(yawDegrees.toFixed(2)),
                    pitch: userPitch,        // Derajat (- = nunduk, + = dongak)
                    roll: userRoll,          // Derajat miring
                },
                ear,
                mar,
                faceBrightness,
                mode,
                durationMs,
                allFacesCount: result.face.length,
            };
        } catch (inferErr) {
            console.error('[TokobiiFace] Inference error pada detect():', inferErr);
            return null;
        }
    }

    /**
     * Hitung Eye Aspect Ratio (EAR) dari 468 titik Face Mesh.
     */
    computeEAR(mesh) {
        if (!mesh || mesh.length < 400) return 0.30;

        const pLeftTop = mesh[386];
        const pLeftBot = mesh[374];
        const pLeftOut = mesh[263];
        const pLeftIn  = mesh[362];

        const pRightTop = mesh[159];
        const pRightBot = mesh[145];
        const pRightOut = mesh[33];
        const pRightIn  = mesh[133];

        const dist = (a, b) => Math.hypot((a[0] - b[0]), (a[1] - b[1]));

        const earLeft = dist(pLeftTop, pLeftBot) / Math.max(1, dist(pLeftOut, pLeftIn));
        const earRight = dist(pRightTop, pRightBot) / Math.max(1, dist(pRightOut, pRightIn));

        return Number(((earLeft + earRight) / 2).toFixed(4));
    }

    /**
     * Hitung Mouth Aspect Ratio (MAR) untuk deteksi senyum / membuka mulut.
     */
    computeMAR(mesh) {
        if (!mesh || mesh.length < 350) return 0.20;

        const pTop = mesh[13];
        const pBot = mesh[14];
        const pLeft = mesh[61];
        const pRight = mesh[291];

        const dist = (a, b) => Math.hypot((a[0] - b[0]), (a[1] - b[1]));
        const vertical = dist(pTop, pBot);
        const horizontal = dist(pLeft, pRight);

        return Number((vertical / Math.max(1, horizontal)).toFixed(4));
    }

    /**
     * Hitung tingkat kecerahan khusus pada area wajah (Face ROI),
     * sehingga cahaya latar belakang terang / jendela tidak merusak kalkulasi.
     */
    calculateFaceBrightness(videoElement, box) {
        try {
            if (!this._brightnessCanvas) {
                this._brightnessCanvas = document.createElement('canvas');
                this._brightnessCanvas.width = 48;
                this._brightnessCanvas.height = 48;
                this._brightnessCtx = this._brightnessCanvas.getContext('2d', { willReadFrequently: true });
            }

            const vw = videoElement.videoWidth || 640;
            const vh = videoElement.videoHeight || 480;

            let bx = 0, by = 0, bw = vw, bh = vh;
            if (box && box.width > 20 && box.height > 20) {
                bx = Math.max(0, Math.min(vw - 10, box.x));
                by = Math.max(0, Math.min(vh - 10, box.y));
                bw = Math.max(10, Math.min(vw - bx, box.width));
                bh = Math.max(10, Math.min(vh - by, box.height));
            }

            this._brightnessCtx.drawImage(videoElement, bx, by, bw, bh, 0, 0, 48, 48);
            const imageData = this._brightnessCtx.getImageData(0, 0, 48, 48);
            const data = imageData.data;
            let sum = 0;

            for (let i = 0; i < data.length; i += 4) {
                // Perceived luminance Formula (ITU-R BT.601)
                sum += (0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
            }

            return Math.round(sum / (data.length / 4));
        } catch (e) {
            return 110; // Fallback wajar
        }
    }

    /**
     * Hentikan stream kamera dan bebaskan track hardware.
     */
    stopStream(stream) {
        if (stream && typeof stream.getTracks === 'function') {
            stream.getTracks().forEach((track) => {
                try {
                    track.stop();
                } catch (e) {}
            });
        }
    }
}

const humanEngineSingleton = new HumanEngine();
export default humanEngineSingleton;
export { HumanEngine };
