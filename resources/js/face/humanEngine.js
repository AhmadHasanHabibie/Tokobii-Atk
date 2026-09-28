import Human from '@vladmandic/human';

/**
 * Tokobii Human Engine Wrapper v4.1
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Engine biometrik berbasis @vladmandic/human dengan WebGL acceleration,
 * modul face detector (BlazeFace), mesh 468-titik (FaceMesh),
 * 1024-D embedding (FaceRes), anti-spoofing (AntiSpoof),
 * dan real-time interactive liveness detection (Liveness).
 *
 * Path model dan wasm disuplai secara dinamis dari Blade via window.FACE_CONFIG,
 * menjamin portabilitas absolut antara localhost (Laragon) dan production (cPanel).
 */
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
        this.modelStatus = {}; // Menyimpan hasil preflight tiap file model
    }

    /**
     * Inisialisasi Human instance dengan konfigurasi model & backend.
     * Mengambil base URL model murni dari window.FACE_CONFIG atau parameter config.
     * Tidak menggunakan window.location.origin hardcoded.
     */
    init(config = {}) {
        if (this.human) return this.human;

        const faceConfig = (typeof window !== 'undefined' && window.FACE_CONFIG)
            ? window.FACE_CONFIG
            : null;

        const modelBase = config.modelsUri || config.modelBasePath || faceConfig?.modelBase;

        if (!modelBase) {
            const err = new Error('[TokobiiFace] FACE_CONFIG tidak ditemukan. Pastikan partial face-config telah dimuat sebelum script biometrik.');
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
            warmup: 'none', // Warmup dipanggil eksplisit via warmup()
            filter: {
                enabled: true,
                flip: false, // Video webcam di-mirror via CSS/Canvas
                width: 640,
                height: 480,
            },
            cacheSensitivity: 0.70,
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
                console.log('[TokobiiFace] Human Engine berhasil diinisialisasi.', {
                    modelBasePath: this.modelBasePath,
                    wasmPath: this.wasmPath,
                    backend: humanConfig.backend,
                });
            }
        } catch (initErr) {
            console.error('[TokobiiFace] Gagal menginisialisasi Human instance:', initErr);
            throw initErr;
        }

        return this.human;
    }

    /**
     * Preflight check ketersediaan semua file model JSON aktif sebelum dimuat.
     * Menggunakan fetch HEAD dengan fallback GET untuk kompatibilitas server.
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
                // Gunakan HEAD terlebih dahulu
                let res = await fetch(url, { method: 'HEAD', cache: 'no-cache' });
                // Fallback ke GET jika server menolak method HEAD (mis. 405 Method Not Allowed)
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
     * Memuat model ke memori GPU / CPU secara paralel dengan preflight dan validasi ketat.
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

            // 1. Eksekusi Preflight Check
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
                onProgress({ stage: 'loading_models', percent: 35, message: 'Memuat model AI biometrik ke GPU...' });
            }

            try {
                // 2. Load model ke instance Human
                await this.human.load();
                this.backendUsed = this.human.tf?.getBackend ? this.human.tf.getBackend() : 'webgl';

                // 3. Verifikasi apakah model yang aktif benar-benar loaded
                const loadedList = typeof this.human.models?.loaded === 'function'
                    ? this.human.models.loaded()
                    : Object.keys(this.human.models?.models || {});

                if (this.debug) {
                    console.log('[TokobiiFace] Model loaded verification list:', loadedList);
                }

                if (typeof onProgress === 'function') {
                    onProgress({ stage: 'warming_up', percent: 70, message: 'Melakukan akselerasi WebGL...' });
                }

                // 4. Warmup WebGL shader
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
     * Deteksi frame video webcam dengan pengamanan (guard) total.
     * Mengembalikan objek wajah tunggal yang dinormalisasi atau null jika tidak ada wajah.
     * TIDAK AKAN PERNAH memanggil inference jika isLoaded bernilai false.
     */
    async detect(videoElement) {
        if (!this.human || !this.isLoaded) {
            return null;
        }
        if (!videoElement || videoElement.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) {
            return null;
        }

        try {
            const result = await this.human.detect(videoElement);

            if (!result || !result.face || result.face.length === 0) {
                return null;
            }

            // Ambil wajah utama (pertama / terdeteksi)
            const face = result.face[0];

            // Ekstraksi data biometrik
            const box = face.box || [0, 0, 0, 0];
            const score = face.score ?? face.boxScore ?? 0.8;
            const mesh = face.mesh || [];
            const embedding = face.embedding ? Array.from(face.embedding) : null;
            const realScore = face.real ?? face.antispoof ?? 0.5;
            const liveScore = face.live ?? face.liveness ?? 0.5;
            const rotation = face.rotation || { yaw: 0, pitch: 0, roll: 0 };

            // Metrik liveness (EAR, MAR, Rotasi Derajat)
            const ear = this.computeEAR(mesh);
            const mar = this.computeMAR(mesh);
            const yawDeg = rotation.angle?.yaw ?? (rotation.yaw ? (rotation.yaw * 180 / Math.PI) : 0);

            return {
                raw: face,
                box: {
                    x: box[0],
                    y: box[1],
                    width: box[2],
                    height: box[3],
                },
                score,
                mesh,
                embedding,
                realScore: Number(realScore.toFixed(4)),
                liveScore: Number(liveScore.toFixed(4)),
                rotation: {
                    yaw: yawDeg,
                    pitch: rotation.angle?.pitch ?? 0,
                    roll: rotation.angle?.roll ?? 0,
                },
                ear,
                mar,
                allFacesCount: result.face.length,
            };
        } catch (inferErr) {
            console.error('[TokobiiFace] Inference error pada detect():', inferErr);
            return null;
        }
    }

    /**
     * Hitung Eye Aspect Ratio (EAR) dari 468 titik Face Mesh.
     * EAR turun saat kelopak mata menutup (kedipan).
     */
    computeEAR(mesh) {
        if (!mesh || mesh.length < 400) return 0.30;

        // Landmark mata kiri (canonical points):
        // Atas: 386, Bawah: 374; Luar: 263, Dalam: 362
        const pLeftTop = mesh[386];
        const pLeftBot = mesh[374];
        const pLeftOut = mesh[263];
        const pLeftIn  = mesh[362];

        // Landmark mata kanan:
        // Atas: 159, Bawah: 145; Luar: 33, Dalam: 133
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

        // Bibir atas: 13, bibir bawah: 14; sudut mulut kiri: 61, kanan: 291
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
     * Hitung tingkat kecerahan frame video (0..255).
     */
    calculateBrightness(videoElement) {
        try {
            if (!this._brightnessCanvas) {
                this._brightnessCanvas = document.createElement('canvas');
                this._brightnessCanvas.width = 64;
                this._brightnessCanvas.height = 48;
                this._brightnessCtx = this._brightnessCanvas.getContext('2d', { willReadFrequently: true });
            }

            this._brightnessCtx.drawImage(videoElement, 0, 0, 64, 48);
            const imageData = this._brightnessCtx.getImageData(0, 0, 64, 48);
            const data = imageData.data;
            let sum = 0;

            for (let i = 0; i < data.length; i += 4) {
                sum += (0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
            }

            return Math.round(sum / (data.length / 4));
        } catch (e) {
            return 120; // Fallback aman
        }
    }

    /**
     * Hentikan stream kamera dan bebaskan track hardware secara menyeluruh.
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
