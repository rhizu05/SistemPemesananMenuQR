<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login dengan phone atau email
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Cek apakah login menggunakan email atau phone
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'kitchen':
                    return redirect()->intended(route('kitchen.dashboard'));
                case 'customer':
                    // Cek apakah ada table_number di session
                    $tableNumber = session('table_number');
                    if ($tableNumber) {
                        return redirect()->intended(route('customer.menu', ['table' => $tableNumber]));
                    } else {
                        return redirect()->intended(route('customer.scan-qr'));
                    }
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'login' => 'Email/No. HP atau password salah.',
        ])->onlyInput('login');
    }

    /**
     * Kirim OTP Login
     */
    public function sendLoginOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $phone = $request->phone;
        
        // Cek apakah user ada
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Nomor WhatsApp tidak terdaftar. Silakan daftar terlebih dahulu.']);
        }

        // Generate OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan OTP ke user
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Simpan phone di session untuk verifikasi
        session(['login_otp_phone' => $phone]);

        // Kirim OTP via WhatsApp
        $this->sendOTPWhatsApp($phone, $otpCode);

        return redirect()->route('login.otp.verify')->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda');
    }

    /**
     * Tampilkan form verifikasi OTP Login
     */
    public function showLoginVerifyOTPForm()
    {
        if (!session()->has('login_otp_phone')) {
            return redirect()->route('login');
        }

        return view('auth.login-verify-otp');
    }

    /**
     * Verifikasi OTP Login
     */
    public function verifyLoginOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $phone = session('login_otp_phone');
        
        if (!$phone) {
            return redirect()->route('login')->with('error', 'Session expired.');
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        // Cek expired
        if ($user->otp_expires_at && Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.');
        }

        // Cek kode
        if ($user->otp_code !== $request->otp) {
            return back()->with('error', 'Kode OTP salah.');
        }

        // OTP Valid
        // Clear OTP
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Clear session
        session()->forget('login_otp_phone');

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'kitchen':
                return redirect()->intended(route('kitchen.dashboard'));
            case 'customer':
                // Cek apakah ada table_number di session
                $tableNumber = session('table_number');
                if ($tableNumber) {
                    return redirect()->intended(route('customer.menu', ['table' => $tableNumber]));
                } else {
                    return redirect()->intended(route('customer.scan-qr'));
                }
            default:
                return redirect('/');
        }
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Tampilkan halaman register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses register - Step 1: Input nama dan phone
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,phone',
        ]);

        // Generate OTP 6 digit
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan data sementara di session
        session([
            'registration_data' => [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'otp_code' => $otpCode,
                'otp_expires_at' => Carbon::now()->addMinutes(5)
            ]
        ]);

        // Kirim OTP via WhatsApp
        $this->sendOTPWhatsApp($validated['phone'], $otpCode);

        return redirect()->route('verify.otp')->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda');
    }

    /**
     * Tampilkan halaman verifikasi OTP
     */
    public function showVerifyOTPForm()
    {
        if (!session()->has('registration_data')) {
            return redirect()->route('register')->with('error', 'Silakan daftar terlebih dahulu');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verifikasi OTP
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $registrationData = session('registration_data');

        if (!$registrationData) {
            return back()->with('error', 'Session expired. Silakan daftar ulang.');
        }

        // Cek apakah OTP sudah expired
        if (Carbon::now()->greaterThan($registrationData['otp_expires_at'])) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan daftar ulang.');
        }

        // Cek apakah OTP cocok
        if ($request->otp !== $registrationData['otp_code']) {
            return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
        }

        // OTP valid, buat user baru
        $user = User::create([
            'name' => $registrationData['name'],
            'phone' => $registrationData['phone'],
            'password' => Hash::make($registrationData['phone']), // Default password = phone number
            'role' => 'customer',
            'phone_verified_at' => Carbon::now()
        ]);

        // Hapus session registration
        session()->forget('registration_data');

        // Auto login
        Auth::login($user);

        return redirect()->route('customer.menu')->with('success', 'Registrasi berhasil! Selamat datang di Dapoer Katendjo');
    }

    /**
     * Resend OTP
     */
    public function resendOTP()
    {
        $registrationData = session('registration_data');

        if (!$registrationData) {
            return back()->with('error', 'Session expired. Silakan daftar ulang.');
        }

        // Generate OTP baru
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update session
        $registrationData['otp_code'] = $otpCode;
        $registrationData['otp_expires_at'] = Carbon::now()->addMinutes(5);
        session(['registration_data' => $registrationData]);

        // Kirim OTP via WhatsApp
        $this->sendOTPWhatsApp($registrationData['phone'], $otpCode);

        return back()->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda');
    }

    /**
     * Kirim OTP via WhatsApp menggunakan Fonnte API
     */
    private function sendOTPWhatsApp($phone, $otpCode)
    {
        // Format nomor telepon (tambahkan 62 jika dimulai dengan 0)
        $formattedPhone = $phone;
        if (substr($phone, 0, 1) === '0') {
            $formattedPhone = '62' . substr($phone, 1);
        }

        $message = "Kode OTP Anda untuk login/registrasi di *Dapoer Katendjo* adalah:\n\n*{$otpCode}*\n\nKode ini berlaku selama 5 menit.\n\nJangan bagikan kode ini kepada siapapun.";

        // Pengiriman WhatsApp menggunakan Fonnte API
        try {
            $apiKey = env('FONNTE_API_KEY');
            
            // Jika API key tidak ada, log saja (untuk development)
            if (!$apiKey) {
                \Log::warning("FONNTE_API_KEY not set. OTP for {$formattedPhone}: {$otpCode}");
                return true;
            }
            
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post('https://api.fonnte.com/send', [
                'target' => $formattedPhone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                \Log::info("OTP sent successfully to {$formattedPhone}");
                return true;
            } else {
                \Log::error("Failed to send OTP to {$formattedPhone}: " . $response->body());
                // Tetap return true agar registrasi bisa lanjut (OTP ada di log)
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP: ' . $e->getMessage());
            // Tetap return true agar registrasi bisa lanjut (OTP ada di log)
            return true;
        }
    }
}
