<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dapoer Katendjo</title>
    
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

        .login-container {
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

        .login-header {
            background: linear-gradient(135deg, #1a4d2e 0%, #2d7a4f 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .login-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .login-body {
            padding: 30px;
        }

        .login-tabs {
            display: flex;
            margin-bottom: 25px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 4px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px;
            background: none;
            border: none;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .tab-btn.active {
            background: white;
            color: #1a4d2e;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: #1a4d2e;
            cursor: pointer;
        }

        .remember-me label {
            font-size: 14px;
            color: #666;
            cursor: pointer;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 77, 46, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer p {
            color: #666;
            font-size: 14px;
        }

        .login-footer a {
            color: #1a4d2e;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="Dapoer Katendjo">
            <h1>Dapoer Katendjo</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        <div class="login-body">
            <!-- Tabs -->
            <div class="login-tabs">
                <button class="tab-btn active" onclick="switchTab('email')">Email</button>
                <button class="tab-btn" onclick="switchTab('phone')">No. HP</button>
            </div>

            <!-- Email Login Tab -->
            <div id="email-tab" class="tab-content active">
                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="login">Email</label>
                        <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>

            <!-- Phone OTP Login Tab -->
            <div id="phone-tab" class="tab-content">
                <form method="POST" action="{{ route('login.otp.request') }}">
                    @csrf
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <button type="submit" class="btn-login">Kirim OTP</button>
                </form>
            </div>

            <div class="login-footer">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            if (tab === 'email') {
                document.getElementById('email-tab').classList.add('active');
                document.querySelectorAll('.tab-btn')[0].classList.add('active');
            } else {
                document.getElementById('phone-tab').classList.add('active');
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
            }
        }
    </script>
</body>
</html>
