import Human from '@vladmandic/human';

/**
 * Tokobii Human Engine Wrapper
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * Engine biometrik berbasis @vladmandic/human dengan WebGL acceleration,
 * modul face detector, mesh 468-titik, 1024-D embedding, anti-spoofing,
 * dan real-time interactive liveness detection.
 */
class HumanEngine {
    constructor() {
        this.human = null;
        this.isLoaded = false;
        this.isWarmingUp = false;
        this.loadPromise = null;
        this.backendUsed = 'webgl';
    }

    /**
     * Inisialisasi Human instance dengan konfigurasi minimal & teroptimasi.
     */
    init(config = {}) {
        if (this.human) return this.human;

        const origin = (typeof window !== 'undefined' && window.location?.origin)
            ? window.location.origin
            : '';

        const modelsPath = config.modelsUri || `${origin}/models/human/`;

        const humanConfig = {
            backend: config.backend || 'webgl',
            modelBasePath: modelsPath,
            wasmPath: modelsPath,
            debug: Boolean(config.debug),
            async: true,
            warmup: 'none', // Warmup dipanggil manual via warmup()
            filter: {
                enabled: true,
                flip: false, // Video webcam diatur cermin di CSS/HTML
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

        this.human = new Human(humanConfig);
        return this.human;
    }

    /**
     * Memuat model ke memori GPU / CPU secara paralel.
     */
    async load(onProgress = null) {
        if (this.isLoaded) return true;
        if (this.loadPromise) return this.loadPromise;

        this.loadPromise = (async () => {
            if (!this.human) {
                this.init();
            }

            if (typeof onProgress === 'function') {
                onProgress({ stage: 'loading_models', percent: 20, message: 'Memuat model AI biometrik...' });
            }

            try {
                await this.human.load();
                this.backendUsed = this.human.tf?.getBackend ? this.human.tf.getBackend() : 'webgl';

                if (typeof onProgress === 'function') {
                    onProgress({ stage: 'warming_up', percent: 60, message: 'Melakukan akselerasi WebGL...' });
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
                console.error('[HumanEngine] Gagal memuat model:', err);
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
            console.warn('[HumanEngine] Warmup fallback note:', e);
        }
    }

    /**
     * Deteksi frame video webcam.
     * Mengembalikan objek wajah tunggal yang dinormalisasi atau null jika tidak ada wajah.
     */
    async detect(videoElement) {
        if (!this.human || !this.isLoaded) return null;
        if (!videoElement || videoElement.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) return null;

        const result = await this.human.detect(videoElement);

        if (!result || !result.face || result.face.length === 0) {
            return null;
        }

        // Ambil wajah utama (terbesar/pertama)
        const face = result.face[0];

        // Ekstraksi data penting
        const box = face.box || [0, 0, 0, 0];
        const score = face.score ?? face.boxScore ?? 0.8;
        const mesh = face.mesh || [];
        const embedding = face.embedding ? Array.from(face.embedding) : null;
        const realScore = face.real ?? face.antispoof ?? 0.5;
        const liveScore = face.live ?? face.liveness ?? 0.5;
        const rotation = face.rotation || { yaw: 0, pitch: 0, roll: 0 };

        // Hitung metrik pendukung liveness
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
    }

    /**
     * Hitung Eye Aspect Ratio (EAR) dari 468 titik Face Mesh.
     * EAR turun saat kelopak mata menutup (kedipan).
     */
    computeEAR(mesh) {
        if (!mesh || mesh.length < 400) return 0.30;

        // Landmark mata kiri (FaceMesh canonical points):
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

        // Bibir atas tengah: 13, bibir bawah tengah: 14; sudut mulut kiri: 61, kanan: 291
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
                // Perceived luminance
                sum += (0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
            }

            return Math.round(sum / (data.length / 4));
        } catch (e) {
            return 120; // Default fallback jika ada restriksi canvas
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
