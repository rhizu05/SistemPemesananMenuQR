# Konfigurasi WhatsApp OTP dengan Fonnte

## Setup Fonnte API

1. Daftar akun di https://fonnte.com
2. Verifikasi nomor WhatsApp Anda
3. Dapatkan API Token dari Dashboard Fonnte

## Tambahkan ke file `.env`:

```env
# WhatsApp OTP Configuration (Fonnte)
FONNTE_TOKEN=your_fonnte_api_token_here
FONNTE_URL=https://api.fonnte.com/send
```

## Cara Mendapatkan Token:

1. Login ke https://app.fonnte.com
2. Klik menu "Account" atau "API"
3. Copy API Token Anda
4. Paste ke `.env` di bagian `FONNTE_TOKEN`

## Testing:

Anda bisa test API dengan curl:

```bash
curl -X POST https://api.fonnte.com/send \
  -H "Authorization: YOUR_TOKEN" \
  -d "target=628123456789" \
  -d "message=Test OTP: 123456"
```

## Harga Fonnte (per November 2025):

- **Trial Gratis**: 100 pesan
- **Paket Starter**: Rp 50.000 untuk 500 pesan
- **Paket Premium**: Sesuai kebutuhan

## Alternatif Jika Tidak Menggunakan Fonnte:

Jika belum setup Fonnte, sistem akan **fallback ke log file**:
- OTP akan di-log ke `storage/logs/laravel.log`
- Cek log untuk mendapatkan kode OTP saat development
- **PENTING**: Ini hanya untuk testing, HARUS pakai Fonnte di production

## Format Nomor Telepon:

- Input customer: `08123456789` (format lokal)
- Akan dikonversi ke: `628123456789` (format internasional)
- Prefix `62` = kode negara Indonesia
