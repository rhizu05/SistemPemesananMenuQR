# Setup WhatsApp OTP dengan Fonnte

## Langkah-langkah Setup:

### 1. Daftar Fonnte (GRATIS)

1. Buka: https://fonnte.com
2. Klik "Daftar" atau "Sign Up"
3. Isi data:
   - Email
   - Password
   - Nomor WhatsApp yang akan digunakan

### 2. Verifikasi WhatsApp

1. Login ke dashboard Fonnte
2. Hubungkan nomor WhatsApp Anda:
   - Scan QR Code dengan WhatsApp
   - Atau gunakan WhatsApp Web
3. Tunggu sampai status "Connected"

### 3. Dapatkan API Token

1. Di dashboard Fonnte, klik menu "API"
2. Copy **API Token** Anda
3. Contoh token: `abc123def456ghi789`

### 4. Update File .env

Buka file `.env` di root project dan tambahkan:

```env
FONNTE_API_KEY=paste_token_anda_disini
```

Contoh:
```env
FONNTE_API_KEY=abc123def456ghi789
```

### 5. Clear Cache Laravel

Jalankan command berikut:

```powershell
php artisan config:clear
php artisan cache:clear
```

### 6. Test Pengiriman OTP

1. Buka aplikasi di browser
2. Klik "Register" / "Daftar"
3. Isi nama dan nomor HP
4. Klik "Daftar"
5. **OTP akan dikirim ke WhatsApp Anda!**

---

## Troubleshooting

### OTP Tidak Terkirim

**Cek 1: API Key Sudah Benar?**
```powershell
# Cek di .env
FONNTE_API_KEY=your_token_here
```

**Cek 2: WhatsApp Masih Connected?**
- Login ke dashboard Fonnte
- Pastikan status "Connected"
- Jika disconnect, scan QR lagi

**Cek 3: Format Nomor HP**
- Harus angka saja: `081234567890`
- Tidak pakai +62 atau spasi
- Sistem otomatis convert ke format internasional

**Cek 4: Lihat Log**
```powershell
# Buka file log
storage/logs/laravel.log
```

Cari baris:
- `OTP sent successfully` → Berhasil
- `Failed to send OTP` → Gagal (lihat error message)
- `FONNTE_API_KEY not set` → API key belum di-set

### Error: "Invalid Token"

API token salah atau expired. Solusi:
1. Login ke dashboard Fonnte
2. Generate token baru
3. Update di `.env`
4. Clear cache

### Error: "Device Not Connected"

WhatsApp disconnect. Solusi:
1. Login ke dashboard Fonnte
2. Reconnect WhatsApp (scan QR)
3. Test lagi

---

## Limit Fonnte Free

### Paket Gratis:
- **100 pesan/hari**
- 1 device WhatsApp
- Cukup untuk testing dan development

### Paket Berbayar:
- Mulai dari Rp 50.000/bulan
- Unlimited pesan
- Multiple devices
- Priority support

---

## Alternatif Jika Fonnte Tidak Bisa

### Opsi 1: Twilio (Internasional)
- Daftar: https://www.twilio.com
- Lebih mahal tapi lebih reliable
- Support global

### Opsi 2: WA Business API (Resmi)
- Paling profesional
- Perlu verifikasi bisnis Facebook
- Lebih mahal

### Opsi 3: Disable OTP (Development Only)
Untuk testing, Anda bisa:
1. Lihat OTP di `storage/logs/laravel.log`
2. Copy OTP dari log
3. Paste di form verifikasi

**JANGAN GUNAKAN DI PRODUCTION!**

---

## Monitoring Pengiriman

### Dashboard Fonnte
- Login ke https://fonnte.com
- Lihat "Message History"
- Cek status pengiriman

### Laravel Log
```powershell
# Tail log real-time
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

---

## Tips

1. **Gunakan Nomor HP Asli**
   - Jangan gunakan nomor virtual
   - Pastikan WhatsApp aktif

2. **Jangan Spam**
   - Limit 1 OTP per menit per nomor
   - Gunakan fitur "Resend OTP" dengan bijak

3. **Format Pesan**
   - Jangan terlalu panjang
   - Gunakan format yang jelas
   - Sertakan nama aplikasi

4. **Backup Plan**
   - Selalu log OTP ke file
   - Bisa digunakan jika WhatsApp gagal

---

## Sudah Siap!

File `AuthController.php` sudah diupdate dengan:
- ✅ Integrasi Fonnte API
- ✅ Auto format nomor HP (0812 → 62812)
- ✅ Error handling
- ✅ Fallback ke log jika API key tidak ada
- ✅ Logging untuk monitoring

**Tinggal tambahkan `FONNTE_API_KEY` di `.env` dan OTP akan terkirim ke WhatsApp!** 🚀
