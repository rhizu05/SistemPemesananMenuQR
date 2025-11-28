<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - Meja {{ $tableNumber }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 40px;
        }
        
        .qr-container {
            border: 3px solid #1a4d2e;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            margin: 0 auto;
            background: #f5efe6;
        }
        
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #1a4d2e;
            margin: 20px 0;
            font-size: 2.5rem;
        }
        
        .table-number {
            background: #1a4d2e;
            color: #f5efe6;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
        }
        
        #qrcode {
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #qrcode img,
        #qrcode canvas {
            display: block;
            margin: 0 auto;
        }
        
        .instructions {
            color: #4f772d;
            margin-top: 20px;
            font-size: 1.1rem;
        }
        
        .btn-print {
            background: #1a4d2e;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .btn-print:hover {
            background: #113820;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <img src="{{ asset('images/logo.png') }}" alt="Dapoer Katendjo" class="logo">
        
        <h1>Dapoer Katendjo</h1>
        
        <div class="table-number">
            MEJA {{ $tableNumber }}
        </div>
        
        <div id="qrcode"></div>
        
        <div class="instructions">
            <p><strong>Scan untuk melihat menu</strong></p>
            <p>dan pesan langsung dari meja Anda</p>
        </div>
    </div>
    
    <button class="btn-print no-print" onclick="window.print()">
        <i class="bi bi-printer"></i> Print QR Code
    </button>
    
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $url }}",
            width: 256,
            height: 256,
            colorDark: "#1a4d2e",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
