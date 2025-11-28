<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Dapoer Katendjo</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .otp-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .otp-header {
            background: linear-gradient(135deg, #1a4d2e 0%, #2d7a4f 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .otp-header img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .otp-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .otp-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .otp-body {
            padding: 30px;
        }

        .info-box {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            text-align: center;
            letter-spacing: 5px;
            font-weight: 600;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1a4d2e;
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 77, 46, 0.1);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c62828;
        }

        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #4caf50;
        }

        .btn-verify {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a4d2e 0%, #2d7a4f 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 77, 46, 0.3);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        .resend-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .resend-link p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .resend-link button {
            background: none;
            border: none;
            color: #1a4d2e;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
        }

        .resend-link button:hover {
            color: #113820;
        }
    </style>
</head>
<body>
    <div class="otp-card">
        <div class="otp-header">
            <img src="{{ asset('images/logo.png') }}" alt="Dapoer Katendjo">
            <h1>Verifikasi OTP</h1>
            <p>Masukkan kode OTP yang dikirim</p>
        </div>

        <div class="otp-body">
            <div class="info-box">
                Kode OTP telah dikirim ke nomor <strong>{{ session('phone') }}</strong> via WhatsApp
            </div>

            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verify.otp') }}">
                @csrf

                <div class="form-group">
                    <label for="otp">Kode OTP</label>
                    <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" required autofocus placeholder="000000">
                </div>

                <button type="submit" class="btn-verify">Verifikasi</button>
            </form>

            <div class="resend-link">
                <p>Tidak menerima kode?</p>
                <form method="POST" action="{{ route('resend.otp') }}" style="display: inline;">
                    @csrf
                    <button type="submit">Kirim Ulang OTP</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
