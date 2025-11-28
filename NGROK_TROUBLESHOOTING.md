# Troubleshooting: Error Tambah ke Keranjang di Ngrok

## Masalah
Ketika hosting di ngrok (https://norine-polybasic-artie.ngrok-free.dev/), muncul error saat tambah menu ke keranjang.

## Penyebab
1. **APP_URL tidak sesuai** - Masih menggunakan localhost
2. **CSRF Token mismatch** - Laravel generate token untuk localhost, tapi request dari ngrok
3. **Mixed Content** - HTTP vs HTTPS
4. **Session Domain** - Cookie tidak bisa di-share

## Solusi

### 1. Update File .env

Buka file `.env` dan update baris berikut:

```env
APP_URL=https://norine-polybasic-artie.ngrok-free.dev
SESSION_DOMAIN=.ngrok-free.dev
SESSION_SECURE_COOKIE=true
```

**PENTING:** Ganti URL ngrok sesuai dengan URL Anda yang aktif!

### 2. Clear Cache Laravel

Jalankan command berikut satu per satu:

```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3. Restart Laravel Server

Stop server (Ctrl+C) lalu jalankan lagi:

```powershell
php artisan serve
```

### 4. Test Lagi

1. Buka URL ngrok di browser
2. Refresh halaman (Ctrl+F5)
3. Coba tambah menu ke keranjang
4. Seharusnya sudah berfungsi!

---

## Jika Masih Error

### Cek 1: Pastikan Ngrok Masih Running

```powershell
# Cek apakah ngrok masih jalan
# Buka http://localhost:4040
```

Jika ngrok mati, jalankan lagi:
```powershell
ngrok http 8000
```

**INGAT:** Setiap kali restart ngrok, URL akan berubah!

### Cek 2: Update APP_URL Setiap Ngrok Restart

Jika URL ngrok berubah menjadi (contoh):
```
https://abc-xyz-123.ngrok-free.dev
```

Update `.env`:
```env
APP_URL=https://abc-xyz-123.ngrok-free.dev
```

Lalu clear cache:
```powershell
php artisan config:clear
```

### Cek 3: Lihat Browser Console

1. Buka browser
2. Tekan F12 (Developer Tools)
3. Tab "Console"
4. Coba tambah ke keranjang
5. Lihat error message

**Error umum:**
- `CSRF token mismatch` → Clear cache & refresh
- `Mixed Content` → Pastikan APP_URL pakai HTTPS
- `404 Not Found` → Cek route & URL

### Cek 4: Lihat Laravel Log

```powershell
# Buka file log
Get-Content storage/logs/laravel.log -Tail 50
```

Cari error terbaru dan lihat detailnya.

---

## Tips Ngrok

### 1. Gunakan Domain Tetap (Berbayar)

Dengan ngrok berbayar, Anda bisa punya domain tetap:
```powershell
ngrok http 8000 --domain=your-domain.ngrok.app
```

Keuntungan:
- URL tidak berubah setiap restart
- Tidak perlu update `.env` terus-menerus

### 2. Gunakan Ngrok Config

Buat file `ngrok.yml`:
```yaml
version: "2"
authtoken: YOUR_AUTH_TOKEN
tunnels:
  akpl:
    proto: http
    addr: 8000
    domain: your-domain.ngrok.app
```

Jalankan:
```powershell
ngrok start akpl
```

### 3. Monitor Traffic

Buka http://localhost:4040 untuk melihat:
- Semua request yang masuk
- Response dari Laravel
- Headers & body
- Debugging tools

---

## Checklist Lengkap

Sebelum test, pastikan:

- [ ] Ngrok running (`ngrok http 8000`)
- [ ] Copy URL ngrok yang aktif
- [ ] Update `APP_URL` di `.env`
- [ ] Update `SESSION_DOMAIN` di `.env`
- [ ] Run `php artisan config:clear`
- [ ] Run `php artisan cache:clear`
- [ ] Laravel server running (`php artisan serve`)
- [ ] Refresh browser (Ctrl+F5)
- [ ] Test tambah ke keranjang

---

## File yang Sudah Diperbaiki

1. **✅ TrustProxies.php** - Trust all proxies (ngrok)
2. **✅ AppServiceProvider.php** - Force HTTPS untuk ngrok
3. **✅ .env** - Perlu update manual

---

## Jika Semua Gagal

### Alternatif 1: Gunakan Localhost Tunnel

```powershell
# Install localtunnel
npm install -g localtunnel

# Run tunnel
lt --port 8000
```

### Alternatif 2: Serveo (Gratis, No Install)

```powershell
ssh -R 80:localhost:8000 serveo.net
```

### Alternatif 3: Deploy ke Hosting

Deploy ke:
- Heroku (gratis)
- Railway (gratis)
- Vercel (gratis untuk static)
- DigitalOcean (berbayar)

---

**Setelah update `.env`, WAJIB clear cache!**

```powershell
php artisan config:clear
```

Lalu refresh browser dengan **Ctrl+F5** (hard refresh).
