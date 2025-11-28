@extends('layouts.app')

@section('title', 'QR Code Generator')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <h2><i class="bi bi-qr-code"></i> QR Code Generator</h2>
        <p>Generate QR Code untuk setiap meja restoran</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-gear"></i> Generate QR Code
                </div>
                <div class="card-body">
                    <form id="qrForm">
                        <div class="mb-3">
                            <label for="tableNumber" class="form-label">Nomor Meja</label>
                            <input type="text" class="form-control" id="tableNumber" placeholder="Contoh: 1, 2, 3..." required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-qr-code"></i> Generate QR Code
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-lightning"></i> Generate Batch
                </div>
                <div class="card-body">
                    <p>Generate QR Code untuk beberapa meja sekaligus:</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" class="form-control" id="startTable" placeholder="Dari meja" min="1">
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control" id="endTable" placeholder="Sampai meja" min="1">
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mt-3 w-100" onclick="generateBatch()">
                        <i class="bi bi-grid-3x3"></i> Generate Batch
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-eye"></i> Preview QR Code
                </div>
                <div class="card-body text-center" id="qrPreview">
                    <p class="text-muted" id="previewPlaceholder">QR Code akan muncul di sini</p>
                    <div id="qrCodeContainer" style="display: none;">
                        <h5 id="tableLabel" class="mb-3"></h5>
                        <div id="qrcode" style="display: inline-block;"></div>
                        <div class="mt-3">
                            <a href="#" id="printButton" class="btn btn-primary" target="_blank">
                                <i class="bi bi-printer"></i> Print QR Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generated QR Codes -->
    <div id="batchResults" class="mt-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
let currentQRCode = null;

document.getElementById('qrForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const tableNumber = document.getElementById('tableNumber').value;
    
    if (tableNumber) {
        // Clear previous QR code
        const qrCodeDiv = document.getElementById('qrcode');
        qrCodeDiv.innerHTML = '';
        
        // Hide placeholder, show QR container
        document.getElementById('previewPlaceholder').style.display = 'none';
        document.getElementById('qrCodeContainer').style.display = 'block';
        
        // Update table label
        document.getElementById('tableLabel').textContent = `Meja ${tableNumber}`;
        
        // Generate QR Code
        const qrUrl = `${window.location.origin}/menu?table=${tableNumber}`;
        currentQRCode = new QRCode(qrCodeDiv, {
            text: qrUrl,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        
        // Update print button
        document.getElementById('printButton').href = `/admin/qr-codes/table/${tableNumber}`;
    }
});

function generateBatch() {
    const start = parseInt(document.getElementById('startTable').value);
    const end = parseInt(document.getElementById('endTable').value);
    
    if (!start || !end || start > end) {
        alert('Mohon masukkan range meja yang valid');
        return;
    }
    
    const resultsDiv = document.getElementById('batchResults');
    resultsDiv.innerHTML = '<div class="card"><div class="card-header"><i class="bi bi-grid-3x3"></i> QR Codes Generated</div><div class="card-body"><div class="row g-3" id="qrGrid"></div></div></div>';
    
    const grid = document.getElementById('qrGrid');
    
    for (let i = start; i <= end; i++) {
        const col = document.createElement('div');
        col.className = 'col-md-3 col-sm-4 col-6 text-center';
        col.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h5>Meja ${i}</h5>
                    <div id="qr-${i}" class="mb-2" style="display: flex; justify-content: center;"></div>
                    <a href="/admin/qr-codes/table/${i}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="bi bi-printer"></i> Print
                    </a>
                </div>
            </div>
        `;
        grid.appendChild(col);
        
        // Generate QR Code
        new QRCode(document.getElementById(`qr-${i}`), {
            text: `${window.location.origin}/menu?table=${i}`,
            width: 128,
            height: 128
        });
    }
}
</script>
@endsection
