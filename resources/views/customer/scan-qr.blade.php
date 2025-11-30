@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-qr-code-scan" style="font-size: 2.5rem; color: #4A7F5A;"></i>
                        </div>
                    </div>
                    
                    <h2 class="mb-3 fw-bold">Scan QR Code Meja</h2>
                    <p class="text-muted mb-4">
                        Silakan scan QR Code yang terdapat di meja Anda untuk melihat menu dan memesan makanan.
                    </p>

                    <!-- Scanner Area -->
                    <div id="reader" class="mb-4 rounded-3 overflow-hidden border" style="width: 100%;"></div>

                    <div id="scan-result" class="d-none">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i> QR Code berhasil dipindai!
                            <br>Mengalihkan ke menu...
                        </div>
                    </div>

                    <button id="start-scan" class="btn text-white btn-lg w-100 mb-3" 
                            style="background-color: #2A5C3F; transition: all 0.3s;"
                            onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                            onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                        <i class="bi bi-camera me-2"></i> Buka Kamera
                    </button>
                    
                    <button id="stop-scan" class="btn btn-danger w-100 mb-3 d-none">
                        <i class="bi bi-stop-circle me-2"></i> Stop Kamera
                    </button>

                    <div class="mt-4">
                        <small style="color: #4A7F5A;">
                            Atau minta bantuan pelayan jika mengalami kesulitan.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML5-QRCode Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const html5QrCode = new Html5Qrcode("reader");
    const startBtn = document.getElementById('start-scan');
    const stopBtn = document.getElementById('stop-scan');
    const resultDiv = document.getElementById('scan-result');
    let isScanning = false;

    // Config scanner
    const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };

    startBtn.addEventListener('click', () => {
        startBtn.classList.add('d-none');
        stopBtn.classList.remove('d-none');
        
        html5QrCode.start(
            { facingMode: "environment" }, // Use rear camera
            config,
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Error starting scanner", err);
            alert("Gagal membuka kamera. Pastikan Anda memberikan izin akses kamera.");
            stopScanning();
        });
    });

    stopBtn.addEventListener('click', stopScanning);

    function stopScanning() {
        html5QrCode.stop().then(() => {
            startBtn.classList.remove('d-none');
            stopBtn.classList.add('d-none');
            document.getElementById('reader').innerHTML = ''; // Clear scanner area
        }).catch(err => {
            console.error("Failed to stop scanner", err);
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning
        html5QrCode.stop();
        
        // Show success message
        resultDiv.classList.remove('d-none');
        
        // Check if URL contains table parameter
        if (decodedText.includes('table=')) {
            window.location.href = decodedText;
        } else {
            // If just table number is scanned (optional handling)
            // Assuming the QR might just be the URL
            window.location.href = decodedText;
        }
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // console.warn(`Code scan error = ${error}`);
    }
});
</script>
@endsection
