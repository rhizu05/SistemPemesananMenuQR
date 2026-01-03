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

                    @php
                        // Check if we have valid OTP session (all required data exists and not expired)
                        $hasValidOtpSession = session('otp_sent') && 
                                              session('otp_phone') && 
                                              session('otp_expires_at') &&
                                              \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse(session('otp_expires_at')));
                    @endphp
                    
                    <!-- Step 1: Input Nama & Nomor HP -->
                    <div id="phoneStep" style="display: {{ $hasValidOtpSession ? 'none' : 'block' }}">
                        <!-- Timer Error Alert (hidden by default) -->
                        <div id="timerErrorAlert" class="alert alert-warning d-none" role="alert">
                            <i class="bi bi-clock-history"></i> 
                            <span id="timerErrorMessage">Harap tunggu sebelum mengirim OTP lagi.</span>
                        </div>
                        
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
                    <div id="otpStep" style="display: {{ $hasValidOtpSession ? 'block' : 'none' }}">
                        <form method="POST" action="{{ route('customer.verifyotp') }}" id="otpForm">
                            @csrf

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
                                <!-- Timer Countdown -->
                                <div id="timerContainer" class="mt-2">
                                    <small class="text-muted d-block" style="color: #4A7F5A !important;">
                                        <i class="bi bi-clock"></i> Kode berlaku: <span id="countdown" class="fw-bold" style="color: #D32F2F;">05:00</span>
                                    </small>
                                    <div class="progress mt-1" style="height: 4px;">
                                        <div class="progress-bar" id="timerProgress" role="progressbar" style="width: 100%; background-color: #2A5C3F;"></div>
                                    </div>
                                </div>
                                <!-- Timer Expired Message -->
                                <div id="timerExpired" class="mt-2" style="display: none;">
                                    <small class="text-danger d-block">
                                        <i class="bi bi-exclamation-circle"></i> Kode OTP sudah kadaluarsa. Silakan kirim ulang.
                                    </small>
                                </div>
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
                            <!-- Timer Running: Hide resend button -->
                            <p id="resendHidden" class="text-muted small mb-0">
                                <i class="bi bi-hourglass-split"></i> Tunggu timer selesai untuk kirim ulang
                            </p>
                            <!-- Timer Finished: Show resend button -->
                            <div id="resendVisible" style="display: none;">
                                <p class="text-muted small mb-0">Tidak menerima kode?</p>
                                <form method="POST" action="{{ route('customer.sendotp') }}" id="resendForm" class="mt-2">
                                    @csrf
                                    @php
                                        $sessionPhone = session('otp_phone', '');
                                        // Remove 62 prefix to get format 8xxxxxxxxx
                                        $phoneForResend = preg_replace('/^(\+?62)/', '', $sessionPhone);
                                    @endphp
                                    <input type="hidden" name="phone" value="{{ $phoneForResend }}">
                                    <button type="submit" class="btn btn-warning btn-sm fw-bold" id="resendOtpBtn">
                                        <i class="bi bi-arrow-repeat"></i> Kirim Ulang OTP
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-shield-check"></i> Admin/Kasir/Koki? 
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
    const phoneInput = document.getElementById('phone');
    const otpInput = document.getElementById('otp');
    
    // Timer Elements
    const countdown = document.getElementById('countdown');
    const timerProgress = document.getElementById('timerProgress');
    const timerContainer = document.getElementById('timerContainer');
    const timerExpired = document.getElementById('timerExpired');
    const resendHidden = document.getElementById('resendHidden');
    const resendVisible = document.getElementById('resendVisible');
    
    // Timer Constants (5 minutes = 300 seconds)
    const TIMER_DURATION = 300;
    const TIMER_STORAGE_KEY = 'otp_timers'; // Store multiple timers per phone
    
    // Timer Variables
    let timerInterval = null;
    
    // Get current phone from session (rendered by PHP)
    const currentSessionPhone = '{{ session("otp_phone", "") }}';
    
    // Get all stored timers
    function getAllTimers() {
        const stored = localStorage.getItem(TIMER_STORAGE_KEY);
        if (stored) {
            try {
                return JSON.parse(stored);
            } catch (e) {
                return {};
            }
        }
        return {};
    }
    
    // Get timer for specific phone
    function getTimerForPhone(phone) {
        const timers = getAllTimers();
        return timers[phone] || null;
    }
    
    // Save timer for specific phone
    function saveTimerForPhone(phone, endTime) {
        const timers = getAllTimers();
        timers[phone] = endTime;
        // Clean up expired timers
        const now = new Date().getTime();
        for (const p in timers) {
            if (timers[p] < now) {
                delete timers[p];
            }
        }
        localStorage.setItem(TIMER_STORAGE_KEY, JSON.stringify(timers));
    }
    
    // Remove timer for specific phone
    function removeTimerForPhone(phone) {
        const timers = getAllTimers();
        delete timers[phone];
        localStorage.setItem(TIMER_STORAGE_KEY, JSON.stringify(timers));
    }
    
    // Initialize timer when OTP step is visible
    function initTimer() {
        // If OTP step is not visible, don't initialize
        if (!otpStep || otpStep.style.display !== 'block') return;
        if (!currentSessionPhone) return;
        
        const storedEndTime = getTimerForPhone(currentSessionPhone);
        const now = new Date().getTime();
        
        // Check if we have existing timer for this phone number that's still valid
        if (storedEndTime && storedEndTime > now) {
            // Continue existing timer for THIS phone
            startCountdown(storedEndTime);
        } else {
            // New timer for this phone (no timer or expired)
            const endTime = now + (TIMER_DURATION * 1000);
            saveTimerForPhone(currentSessionPhone, endTime);
            startCountdown(endTime);
        }
    }
    
    // Start countdown function
    function startCountdown(endTime) {
        // Clear any existing interval
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        
        function updateTimer() {
            const now = new Date().getTime();
            const remaining = endTime - now;
            
            if (remaining <= 0) {
                // Timer expired
                clearInterval(timerInterval);
                localStorage.removeItem(TIMER_STORAGE_KEY);
                
                // Update UI
                if (countdown) countdown.textContent = '00:00';
                if (timerProgress) timerProgress.style.width = '0%';
                if (timerContainer) timerContainer.style.display = 'none';
                if (timerExpired) timerExpired.style.display = 'block';
                if (resendHidden) resendHidden.style.display = 'none';
                if (resendVisible) resendVisible.style.display = 'block';
                
                return;
            }
            
            // Calculate minutes and seconds
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
            
            // Update countdown display
            const displayMinutes = String(minutes).padStart(2, '0');
            const displaySeconds = String(seconds).padStart(2, '0');
            if (countdown) countdown.textContent = `${displayMinutes}:${displaySeconds}`;
            
            // Update progress bar
            const totalMs = TIMER_DURATION * 1000;
            const progressPercent = (remaining / totalMs) * 100;
            if (timerProgress) timerProgress.style.width = `${progressPercent}%`;
            
            // Change color based on remaining time
            if (remaining <= 60000) { // Last 1 minute - red
                if (countdown) countdown.style.color = '#D32F2F';
                if (timerProgress) timerProgress.style.backgroundColor = '#D32F2F';
            } else if (remaining <= 120000) { // Last 2 minutes - orange
                if (countdown) countdown.style.color = '#F57C00';
                if (timerProgress) timerProgress.style.backgroundColor = '#F57C00';
            }
        }
        
        // Initial update
        updateTimer();
        
        // Update every second
        timerInterval = setInterval(updateTimer, 1000);
    }
    
    // Reset timer for current phone (for resend only)
    function resetTimer() {
        // Remove timer only for current phone number
        if (currentSessionPhone) {
            removeTimerForPhone(currentSessionPhone);
        }
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        resetTimerUI();
    }
    
    // Reset timer UI only (without clearing localStorage)
    function resetTimerUI() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
        // Reset UI elements
        if (timerContainer) timerContainer.style.display = 'block';
        if (timerExpired) timerExpired.style.display = 'none';
        if (resendHidden) resendHidden.style.display = 'block';
        if (resendVisible) resendVisible.style.display = 'none';
        if (countdown) countdown.style.color = '#D32F2F';
        if (timerProgress) timerProgress.style.backgroundColor = '#2A5C3F';
    }
    
    // Initialize timer immediately if OTP step is visible
    initTimer();

    // Auto-focus OTP input if on OTP step
    if (otpStep.style.display === 'block' && otpInput) {
        otpInput.focus();
    }

    // Back to phone input - Also clear session on backend
    if (backToPhone) {
        backToPhone.addEventListener('click', function() {
            // Clear OTP session on backend via AJAX
            fetch('{{ route("customer.clearotp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                // Session cleared, now update UI
                otpStep.style.display = 'none';
                phoneStep.style.display = 'block';
                resetTimerUI();
                if (phoneInput) phoneInput.focus();
            }).catch(error => {
                console.error('Error clearing OTP session:', error);
                // Still update UI even if request fails
                otpStep.style.display = 'none';
                phoneStep.style.display = 'block';
                resetTimerUI();
                if (phoneInput) phoneInput.focus();
            });
        });
    }

    // Resend OTP - using the new resend form
    // This DOES reset timer since a new OTP will be sent
    const resendForm = document.getElementById('resendForm');
    if (resendForm) {
        resendForm.addEventListener('submit', function(e) {
            // Reset timer before submitting (clears localStorage)
            resetTimer();
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
    
    // Validate phone form submission - check if timer is still active
    const phoneForm = document.getElementById('phoneForm');
    const timerErrorAlert = document.getElementById('timerErrorAlert');
    const timerErrorMessage = document.getElementById('timerErrorMessage');
    
    // Variable to track error countdown interval
    let errorCountdownInterval = null;
    
    if (phoneForm) {
        phoneForm.addEventListener('submit', function(e) {
            // Get the phone number being submitted
            const phone = phoneInput ? phoneInput.value : '';
            if (!phone) return; // Let HTML validation handle empty
            
            // Format phone to match storage format (62 + phone)
            const formattedPhone = '62' + phone.replace(/^0/, '');
            
            // Check if there's an active timer for this phone
            const storedEndTime = getTimerForPhone(formattedPhone);
            const now = new Date().getTime();
            
            if (storedEndTime && storedEndTime > now) {
                // Timer is still active - prevent submission
                e.preventDefault();
                
                // Clear any existing countdown interval
                if (errorCountdownInterval) {
                    clearInterval(errorCountdownInterval);
                }
                
                // Show error message with realtime countdown
                if (timerErrorAlert) {
                    timerErrorAlert.classList.remove('d-none');
                    
                    // Function to update countdown
                    function updateErrorCountdown() {
                        const currentTime = new Date().getTime();
                        const remaining = storedEndTime - currentTime;
                        
                        if (remaining <= 0) {
                            // Timer expired - hide error and clear interval
                            timerErrorAlert.classList.add('d-none');
                            clearInterval(errorCountdownInterval);
                            errorCountdownInterval = null;
                            return;
                        }
                        
                        const minutes = Math.floor(remaining / 60000);
                        const seconds = Math.floor((remaining % 60000) / 1000);
                        
                        if (timerErrorMessage) {
                            timerErrorMessage.textContent = `Kode OTP sudah dikirim ke nomor ini. Harap tunggu ${minutes} menit ${seconds} detik sebelum mengirim ulang.`;
                        }
                    }
                    
                    // Initial update
                    updateErrorCountdown();
                    
                    // Update every second
                    errorCountdownInterval = setInterval(updateErrorCountdown, 1000);
                }
                
                return false;
            }
            
            // No active timer - allow submission
            // Hide any previous error and clear interval
            if (timerErrorAlert) {
                timerErrorAlert.classList.add('d-none');
            }
            if (errorCountdownInterval) {
                clearInterval(errorCountdownInterval);
                errorCountdownInterval = null;
            }
        });
    }
});
</script>
@endsection
