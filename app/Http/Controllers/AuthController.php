<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\FonnteService;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    // ==================== ADMIN LOGIN ====================
    
    /**
     * Show admin login form
     */
    public function showAdminLogin()
    {
        // If already logged in, redirect based on role
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.admin-login');
    }

    /**
     * Process admin login
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Check if user is customer role
            if ($user->role === 'customer') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun pelanggan tidak dapat login di halaman admin. Silakan login di halaman pelanggan.'
                ]);
            }

            // Redirect based on role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ==================== CUSTOMER LOGIN (OTP) ====================
    
    /**
     * Show customer login form
     */
    public function showCustomerLogin()
    {
        // If already logged in, redirect
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.customer-login');
    }

    /**
     * Send OTP to customer phone
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^8[0-9]{8,11}$/', // Format: 8xxxxxxxxx
            ],
        ], [
            'phone.required' => 'Nomor WhatsApp harus diisi.',
            'phone.regex' => 'Format nomor tidak valid. Contoh: 8123456789',
        ]);

        // Format phone number
        $phone = FonnteService::formatPhoneNumber($request->phone);

        // Check rate limiting (max 1 OTP per 5 minutes per phone)
        $lastOTPTime = session('last_otp_' . $phone);
        if ($lastOTPTime && Carbon::parse($lastOTPTime)->diffInMinutes(now()) < 5) {
            $retryAfter = 5 - Carbon::parse($lastOTPTime)->diffInMinutes(now());
            return back()->withErrors([
                'phone' => "Terlalu banyak permintaan. Coba lagi dalam $retryAfter menit."
            ]);
        }

        // Generate OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP and name to session
        session([
            'otp_code' => $otpCode,
            'otp_phone' => $phone,
            'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString(),
            'otp_sent' => true,
            'last_otp_' . $phone => now()->toDateTimeString(),
        ]);

        // Send OTP via WhatsApp
        $sent = $this->fonnteService->sendWhatsAppOTP($phone, $otpCode);

        if ($sent) {
            return back()->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda!');
        } else {
            return back()->withErrors([
                'phone' => 'Gagal mengirim OTP. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Clear OTP session (when user clicks "Ubah Nomor")
     */
    public function clearOTPSession(Request $request)
    {
        session()->forget(['otp_code', 'otp_phone', 'otp_name', 'otp_expires_at', 'otp_sent']);
        
        return response()->json(['success' => true]);
    }

    /**
     * Verify OTP and login/register customer
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => [
                'required',
                'string',
                'size:6',
                'regex:/^[0-9]{6}$/',
            ],
        ], [
            'otp.required' => 'Kode OTP harus diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
            'otp.regex' => 'Kode OTP harus berupa angka.',
        ]);

        // Get OTP from session
        $sessionOTP = session('otp_code');
        $sessionPhone = session('otp_phone');
        $sessionName = session('otp_name', 'Pelanggan');
        $expiresAt = session('otp_expires_at');

        // Check if OTP session exists
        if (!$sessionOTP || !$sessionPhone || !$expiresAt) {
            return back()->withErrors([
                'otp' => 'Sesi OTP tidak ditemukan. Silakan kirim ulang OTP.'
            ]);
        }

        // Check if OTP expired
        if (Carbon::now()->greaterThan(Carbon::parse($expiresAt))) {
            session()->forget(['otp_code', 'otp_phone', 'otp_name', 'otp_expires_at', 'otp_sent']);
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang OTP.'
            ]);
        }

        // Verify OTP
        if ($request->otp !== $sessionOTP) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah. Silakan coba lagi.'
            ]);
        }

        // OTP Valid! Find or create user
        $user = User::where('phone', $sessionPhone)->first();

        if (!$user) {
            // Auto-register new customer
            $user = User::create([
                'name' => $sessionName,
                'phone' => $sessionPhone,
                'role' => 'customer',
                'is_active' => true,
                'phone_verified_at' => now(),
            ]);

            \Log::info('New customer registered via OTP', ['phone' => $sessionPhone, 'name' => $sessionName]);
        }

        // Login user
        Auth::login($user, true); // Remember = true
        $request->session()->regenerate();

        // Clear OTP session
        session()->forget(['otp_code', 'otp_phone', 'otp_name', 'otp_expires_at', 'otp_sent']);

        // Redirect to menu
        return redirect()->route('customer.menu')->with('success', 'Login berhasil! Selamat datang di Dapoer Katendjo.');
    }

    // ==================== LOGOUT ====================
    
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }

    // ==================== HELPER METHODS ====================
    
    /**
     * Redirect based on user role
     */
    protected function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'cashier':
                return redirect()->route('cashier.dashboard');
            case 'kitchen':
                return redirect()->route('kitchen.dashboard');
            case 'customer':
                return redirect()->route('customer.menu');
            default:
                return redirect('/');
        }
    }
}
