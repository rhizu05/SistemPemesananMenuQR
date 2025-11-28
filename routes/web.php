<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QRController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/otp', [AuthController::class, 'sendLoginOTP'])->name('login.otp.request');
Route::get('/login/verify', [AuthController::class, 'showLoginVerifyOTPForm'])->name('login.otp.verify');
Route::post('/login/verify', [AuthController::class, 'verifyLoginOTP']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify-otp', [AuthController::class, 'showVerifyOTPForm'])->name('verify.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('/resend-otp', [AuthController::class, 'resendOTP'])->name('resend.otp');

// Include admin routes
// Include admin routes
require_once __DIR__.'/admin.php';

// Midtrans Webhook
Route::post('/midtrans/callback', [\App\Http\Controllers\PaymentCallbackController::class, 'handle'])->name('midtrans.callback');

