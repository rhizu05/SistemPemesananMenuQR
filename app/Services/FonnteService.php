<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->url = config('services.fonnte.url', 'https://api.fonnte.com/send');
    }

    /**
     * Send WhatsApp OTP message
     * 
     * @param string $phone Phone number in format 628xxx
     * @param string $code OTP code
     * @return bool Success status
     */
    public function sendWhatsAppOTP($phone, $code)
    {
        // If no token, fallback to log (for development)
        if (!$this->token) {
            Log::warning("Fonnte token not configured. OTP will be logged instead.");
            Log::info("=== OTP CODE ===");
            Log::info("Phone: $phone");
            Log::info("Code: $code");
            Log::info("================");
            return true;
        }

        try {
            // Format message
            $message = $this->formatOTPMessage($code);

            // Send via Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->url, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ]);

            // Log response for debugging
            Log::info('Fonnte API Response', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            // Check if successful
            if ($response->successful()) {
                $result = $response->json();
                
                // Fonnte returns status: true on success
                if (isset($result['status']) && $result['status'] === true) {
                    Log::info("OTP sent successfully to $phone");
                    return true;
                }
            }

            // If we reach here, sending failed
            Log::error('Fonnte API failed', [
                'phone' => $phone,
                'response' => $response->body()
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage(), [
                'phone' => $phone,
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Format OTP message
     * 
     * @param string $code
     * @return string
     */
    protected function formatOTPMessage($code)
    {
        return "*Dapoer Katendjo - Kode Verifikasi*\n\n"
             . "Kode OTP Anda: *$code*\n\n"
             . "Berlaku selama 5 menit.\n"
             . "Jangan bagikan kode ini kepada siapapun.\n\n"
             . "_Abaikan pesan ini jika Anda tidak melakukan permintaan login._";
    }

    /**
     * Format phone number to international format
     * 
     * @param string $phone Input format: 08xxx or 8xxx
     * @return string Output format: 628xxx
     */
    public static function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading 0 if exists
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }

        // Add Indonesia country code if not present
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Validate Indonesian phone number
     * 
     * @param string $phone
     * @return bool
     */
    public static function validatePhoneNumber($phone)
    {
        // Remove non-numeric
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Must start with 08 or 8
        // Length: 10-13 digits (08xxx or 628xxx)
        if (preg_match('/^(0?8[0-9]{8,11})$/', $phone)) {
            return true;
        }

        if (preg_match('/^(628[0-9]{8,11})$/', $phone)) {
            return true;
        }

        return false;
    }
}
