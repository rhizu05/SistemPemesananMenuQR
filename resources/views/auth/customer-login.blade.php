@extends('layouts.app')

@section('title', 'Login Pelanggan')

@section('content')
<div class="container">
    <style>
        .form-control:focus {
            border-color: #4A7F5A;
            box-shadow: 0 0 0 0.2rem rgba(74, 127, 90, 0.25);
        }
    </style>
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header text-white text-center py-4" style="background-color: #2A5C3F;">
                    <h3 class="mb-0"><i class="bi bi-whatsapp"></i> Login Pelanggan</h3>
                    <p class="mb-0 small mt-2">Verifikasi via WhatsApp</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-dismissible fade show" style="background-color: #FFEBEE; color: #D32F2F; border-color: #FFCDD2;">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="color: #D32F2F;"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-dismissible fade show" style="background-color: #E8F5E9; color: #2A5C3F; border-color: #C8E6C9;">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="color: #D32F2F;"></button>
                        </div>
                    @endif

                    <!-- Step 1: Input Nama & Nomor HP -->
                    <div id="phoneStep" style="display: {{ session('otp_sent') ? 'none' : 'block' }}">
                        <form method="POST" action="{{ route('customer.sendotp') }}" id="phoneForm">
                            @csrf
                            


                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', session('otp_phone')) }}"
                                           placeholder="8123456789"
                                           required 
                                           maxlength="13"
                                           pattern="8[0-9]{8,11}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> Contoh: 8123456789 (tanpa 0 di depan)
                                </small>
                            </div>

                            <button type="submit" class="btn text-white w-100 btn-lg" id="sendOtpBtn"
                                    style="background-color: #2A5C3F; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                                <i class="bi bi-send"></i> Kirim Kode OTP
                            </button>
                        </form>
                    </div>

                    <!-- Step 2: Input OTP -->
                    <div id="otpStep" style="display: {{ session('otp_sent') ? 'block' : 'none' }}">
                        <form method="POST" action="{{ route('customer.verifyotp') }}" id="otpForm">
                            @csrf
                            
                            <div class="alert" style="background-color: #E3F2FD; color: #1976D2; border-color: #BBDEFB;">
                                <i class="bi bi-whatsapp"></i> Kode OTP telah dikirim ke WhatsApp Anda!
                            </div>

                            <div class="mb-3">
                                <label for="otp" class="form-label">Masukkan Kode OTP <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                                       id="otp" 
                                       name="otp" 
                                       placeholder="••••••"
                                       required
                                       maxlength="6"
                                       pattern="[0-9]{6}"
                                       style="font-size: 24px; letter-spacing: 8px;">
                                @error('otp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" style="color: #4A7F5A !important;">
                                    <i class="bi bi-clock"></i> Kode berlaku 5 menit
                                </small>
                            </div>

                            <button type="submit" class="btn text-white w-100 btn-lg mb-2"
                                    style="background-color: #2A5C3F; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                                <i class="bi bi-check-circle"></i> Verifikasi & Login
                            </button>

                            <button type="button" class="btn w-100" id="backToPhone"
                                    style="background-color: #ffffff; color: #4A7F5A; border: 1px solid #4A7F5A; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)';">
                                <i class="bi bi-arrow-left"></i> Ubah Nomor
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <p class="text-muted small mb-0">
                                Tidak menerima kode? 
                                <a href="#" id="resendOtp" class="text-decoration-none" style="color: #FBC02D;">Kirim ulang</a>
                            </p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-shield-check"></i> Admin/Staf? 
                            <a href="{{ route('admin.login') }}" class="text-decoration-none" style="color: #4A7F5A;">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-3">
                <div class="card-body text-center">
                    <h6 class="fw-bold"><i class="bi bi-question-circle" style="color: #1976D2;"></i> Cara Login</h6>
                    <ol class="text-start small">
                        <li>Masukkan nomor WhatsApp Anda</li>
                        <li>Klik "Kirim Kode OTP"</li>
                        <li>Cek WhatsApp untuk kode 6 digit</li>
                        <li>Masukkan kode dan klik "Verifikasi"</li>
                    </ol>
                    <p class="small mb-0" style="color: #2A5C3F;">
                        <i class="bi bi-lock-fill"></i> Aman & mudah, tanpa password!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneStep = document.getElementById('phoneStep');
    const otpStep = document.getElementById('otpStep');
    const backToPhone = document.getElementById('backToPhone');
    const resendOtp = document.getElementById('resendOtp');
    const phoneInput = document.getElementById('phone');
    const otpInput = document.getElementById('otp');

    // Auto-focus OTP input if on OTP step
    if (otpStep.style.display === 'block' && otpInput) {
        otpInput.focus();
    }

    // Back to phone input
    if (backToPhone) {
        backToPhone.addEventListener('click', function() {
            otpStep.style.display = 'none';
            phoneStep.style.display = 'block';
            if (phoneInput) phoneInput.focus();
        });
    }

    // Resend OTP
    if (resendOtp) {
        resendOtp.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Kirim ulang kode OTP ke WhatsApp Anda?')) {
                document.getElementById('phoneForm').submit();
            }
        });
    }

    // Format phone input
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-add 8 if user types 0
            if (this.value.startsWith('0')) {
                this.value = '8' + this.value.substring(1);
            }
        });
    }

    // Format OTP input
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            // Only numbers
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Auto-submit when 6 digits entered
        otpInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Optional: auto-submit
                // document.getElementById('otpForm').submit();
            }
        });
    }
});
</script>
@endsection
