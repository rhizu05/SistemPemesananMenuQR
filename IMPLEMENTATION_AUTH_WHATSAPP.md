# Implementation Plan: Pemisahan Login & WhatsApp OTP

## Status: 🚧 IN PROGRESS

### ✅ Completed Steps:
1. **Database Migration** - Password column made nullable ✓
2. **Documentation** - WhatsApp OTP setup guide created ✓

### 🔄 Next Steps:

---

## 3. Create Admin Login View

**File**: `resources/views/auth/admin-login.blade.php`

```blade
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Login Admin/Kasir</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                           <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 4. Create Customer Login View (OTP)

**File**: `resources/views/auth/customer-login.blade.php`

Flow: 
- Step 1: Input nomor HP → Kirim OTP
- Step 2: Input OTP → Verify & Login

---

## 5. Update Routes

**File**: `routes/web.php`

```php
// Admin Login (Email + Password)
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// Customer Login (Phone + OTP)
Route::get('/login', [AuthController::class, 'showCustomerLogin'])->name('customer.login');
Route::post('/login/send-otp', [AuthController::class, 'sendOTP'])->name('customer.sendotp');
Route::post('/login/verify-otp', [AuthController::class, 'verifyOTP'])->name('customer.verifyotp');

// Logout (sama untuk semua)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// HAPUS: /register route (tidak diperlukan lagi)
```

---

## 6. Update AuthController

**Methods needed**:

### `showAdminLogin()`
- Return view `auth.admin-login`

### `adminLogin(Request $request)`
- Validate email + password
- Cek apakah user role = admin atau kitchen
- Jika customer role → error "Akun pelanggan tidak dapat login di halaman ini"
- Auth::attempt()
- Redirect based on role

### `showCustomerLogin()`
- Return view `auth.customer-login`
- Show form input nomor HP

### `sendOTP(Request $request)`
- Validate phone number
- Format phone: 08xxx → 628xxx
- Generate 6-digit OTP code
- Save to session: `otp_code`, `otp_phone`, `otp_expires_at`
- **Send via Fonnte API** (atau log jika tidak ada token)
- Return success message

### `verifyOTP(Request $request)`
- Validate OTP code
- Cek session OTP
- Jika valid:
  - Cari user by phone
  - Jika tidak ada → Create user baru (auto-register)
  - Login user
  - Clear OTP session
  - Redirect to menu
- Jika invalid → error

---

## 7. Fonnte Integration Service

Create: `app/Services/FonnteService.php`

```php
class FonnteService
{
    public function sendWhatsAppOTP($phone, $code)
    {
        $token = config('services.fonnte.token');
        
        if (!$token) {
            // Fallback: Log OTP
            \Log::info("OTP for $phone: $code");
            return true;
        }
        
        $message = "Kode OTP Dapoer Katendjo: *$code*\n\nBerlaku 5 menit.\nJangan bagikan kode ini.";
        
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
        ]);
        
        return $response->successful();
    }
}
```

---

## 8. Config File for Fonnte

**File**: `config/services.php`

Add:
```php
'fonnte' => [
    'token' => env('FONNTE_TOKEN'),
    'url' => env('FONNTE_URL', 'https://api.fonnte.com/send'),
],
```

---

## 9. Middleware Updates

**PreventKitchenAccess** - Already exists, no change needed

**New Middleware**: `RedirectIfAuthenticated`
- If user logged in as admin → redirect to admin.dashboard
- If user logged in as customer → redirect to customer.menu
- If user logged in as kitchen → redirect to kitchen.dashboard

---

## 10. Update Navbar Links

**File**: `resources/views/layouts/app.blade.php`

For guests:
- Change "Login" link from `/login` to `/login` (customer)
- Remove "Register" link
- Add small text: "Admin? Login di sini" → link to `/admin/login`

---

## 11. Security Considerations

1. **Rate Limiting**: Max 3 OTP requests per  phone per hour
2. **OTP Expiry**: 5 minutes
3. **OTP Format**: 6-digit random number
4. **Session Security**: Clear OTP after verification
5. **Phone Validation**: Indonesian format only (08xxx)

---

## 12. Testing Checklist

### Admin Login:
- [ ] Admin dapat login dengan email + password
- [ ] Kitchen dapat login dengan email + password
- [ ] Customer tidak dapat login di halaman admin
- [ ] Redirect ke dashboard yang sesuai setelah login

### Customer Login/Register:
- [ ] Customer input nomor HP → OTP terkirim (cek log jika no Fonnte)
- [ ] Customer input OTP benar → Login sukses
- [ ] Customer input OTP salah → Error message
- [ ] Nomor HP baru → Auto-register + login
- [ ] Nomor HP existing → Login langsung
- [ ] OTP expired setelah 5 menit
- [ ] Rate limiting bekerja (max 3 request/hour)

### General:
- [ ] Logout berfungsi untuk semua role
- [ ] Navbar menampilkan link yang benar
- [ ] Guest di-redirect dengan benar
- [ ] Session management aman

---

## 13. User Instructions

Setelah implementasi:

1. **Admin** harus login di: `http://localhost:8000/admin/login`
2. **Customer** login/daftar di: `http://localhost:8000/login`
3. **Setup Fonnte**: Ikuti `SETUP_WHATSAPP_OTP.md`

---

## Estimated Time: 2-3 hours

## Risks:
- WhatsApp API bisa down
- Rate limiting Fonnte
- Customer confusion jika nomor salah

## Mitigation:
- Fallback ke log file untuk development
- Clear error messages
- Help text di form

---

**Lanjutkan implementasi? (ya/tidak)**
