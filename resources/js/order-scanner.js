import { BrowserCodeReader, BrowserQRCodeReader } from '@zxing/browser';

const configuration = window.tokobiiOrderScanner;

if (configuration) {
    const video = document.getElementById('qrScannerVideo');
    const cameraSelect = document.getElementById('cameraSelect');
    const restartButton = document.getElementById('restartScanner');
    const alertBox = document.getElementById('scannerAlert');
    const reader = new BrowserQRCodeReader();
    let controls = null;
    let session = 0;
    let isProcessing = false;

    const setStatus = (message, type) => {
        alertBox.className = `alert alert-${type} mt-3 mb-0`;
        alertBox.textContent = message;
    };

    const stopScanner = () => {
        session += 1;

        if (controls) {
            controls.stop();
            controls = null;
        }

        BrowserCodeReader.releaseAllStreams();
        video.srcObject = null;
    };

    const populateCameras = async () => {
        const cameras = await BrowserCodeReader.listVideoInputDevices();
        cameraSelect.innerHTML = '';

        if (!cameras.length) {
            const option = new Option('Kamera tidak ditemukan', '');
            cameraSelect.add(option);
            cameraSelect.disabled = true;
            return [];
        }

        cameras.forEach((camera, index) => {
            cameraSelect.add(new Option(camera.label || `Kamera ${index + 1}`, camera.deviceId));
        });
        cameraSelect.disabled = false;

        return cameras;
    };

    const lookupOrder = async (payload) => {
        isProcessing = true;
        stopScanner();
        setStatus('QR berhasil dibaca. Mencari pesanan...', 'success');
        restartButton.disabled = true;

        try {
            const response = await fetch(configuration.lookupUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': configuration.csrfToken,
                },
                body: JSON.stringify({ payload }),
            });
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'QR Code tidak valid.');
            }

            window.location.assign(data.redirect_url);
        } catch (error) {
            console.error('Order QR lookup failed:', error);
            setStatus(error.message || 'QR Code tidak valid.', 'danger');
            isProcessing = false;
            restartButton.disabled = false;
        }
    };

    const startScanner = async (deviceId) => {
        stopScanner();
        isProcessing = false;
        const currentSession = session;
        restartButton.disabled = true;
        setStatus('Menyalakan kamera...', 'secondary');

        try {
            controls = await reader.decodeFromVideoDevice(deviceId || undefined, video, (result, error) => {
                if (currentSession !== session || isProcessing) {
                    return;
                }

                if (result) {
                    lookupOrder(result.getText());
                    return;
                }

                if (error && error.name !== 'NotFoundException') {
                    console.error('QR scanner error:', error);
                }
            });

            if (currentSession !== session) {
                controls.stop();
                return;
            }

            restartButton.disabled = false;
            setStatus('Scanner aktif. Arahkan kamera ke QR Code.', 'success');
        } catch (error) {
            console.error('Camera initialization failed:', error);
            const messages = {
                NotAllowedError: 'Akses kamera ditolak. Izinkan kamera dari pengaturan browser.',
                NotFoundError: 'Kamera tidak ditemukan.',
                NotReadableError: 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.',
                SecurityError: 'Perangkat/browser ini tidak mendukung akses kamera.',
            };
            setStatus(messages[error.name] || 'Gagal mengaktifkan kamera. Silakan coba lagi.', 'danger');
            restartButton.disabled = false;
        }
    };

    const initialise = async () => {
        if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
            setStatus('Perangkat/browser ini tidak mendukung akses kamera.', 'danger');
            cameraSelect.innerHTML = '<option>Kamera tidak tersedia</option>';
            restartButton.disabled = false;
            return;
        }

        try {
            setStatus('Meminta akses kamera...', 'secondary');
            const permissionStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            permissionStream.getTracks().forEach((track) => track.stop());
            const cameras = await populateCameras();

            if (!cameras.length) {
                setStatus('Kamera tidak ditemukan.', 'danger');
                restartButton.disabled = false;
                return;
            }

            await startScanner(cameraSelect.value);
        } catch (error) {
            console.error('Camera permission or enumeration failed:', error);
            const message = error.name === 'NotAllowedError'
                ? 'Akses kamera ditolak. Izinkan kamera dari pengaturan browser.'
                : 'Gagal mengaktifkan kamera. Silakan coba lagi.';
            setStatus(message, 'danger');
            cameraSelect.innerHTML = '<option>Kamera tidak tersedia</option>';
            restartButton.disabled = false;
        }
    };

    cameraSelect.addEventListener('change', () => startScanner(cameraSelect.value));
    restartButton.addEventListener('click', () => initialise());
    window.addEventListener('beforeunload', stopScanner);
    initialise();
}
