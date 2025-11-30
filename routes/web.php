<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\CustomerController;

// ==================== AUTHENTICATION ROUTES ====================

// Admin Login (Email + Password)
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

    // Customer Login (Phone + OTP WhatsApp)
    Route::get('/login', [AuthController::class, 'showCustomerLogin'])->name('login');
    Route::post('/login/send-otp', [AuthController::class, 'sendOTP'])->name('customer.sendotp');
    Route::post('/login/verify-otp', [AuthController::class, 'verifyOTP'])->name('customer.verifyotp');
});

// Temporary Migration Route (Delete after use)
Route::get('/migrate-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'Migration and Seeding Completed Successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/', [CustomerController::class, 'index'])->name('home');

// Logout (Universal)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== OTHER ROUTES ====================

// Include admin routes
require_once __DIR__.'/admin.php';

// Midtrans Webhook
Route::post('/midtrans/callback', [\App\Http\Controllers\PaymentCallbackController::class, 'handle'])->name('midtrans.callback');
