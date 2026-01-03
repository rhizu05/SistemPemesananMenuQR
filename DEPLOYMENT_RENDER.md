# 🚀 Panduan Deploy Laravel ke Render (Gratis)

## 📋 Prasyarat
- Akun GitHub dengan project ini sudah di-push
- Akun Render (gratis) di [render.com](https://render.com)

---

## 🔧 Langkah 1: Persiapan Project

### 1.1 Push ke GitHub
```bash
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

---

## 🌐 Langkah 2: Setup di Render

### 2.1 Buat Akun Render
1. Kunjungi [render.com](https://render.com)
2. Klik **"Get Started for Free"**
3. Sign up dengan **GitHub**

### 2.2 Create New Web Service
1. Di Dashboard, klik **"New +"** → **"Web Service"**
2. Connect ke repository GitHub Anda
3. Pilih repository project ini

### 2.3 Konfigurasi Service
Isi form dengan:

| Field | Value |
|-------|-------|
| **Name** | `akpl-laravel` (atau nama lain) |
| **Region** | Singapore (terdekat) |
| **Branch** | `main` |
| **Runtime** | `PHP` |
| **Build Command** | Lihat di bawah |
| **Start Command** | Lihat di bawah |
| **Instance Type** | **Free** |

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php artisan db:seed --class=CategorySeeder && php artisan db:seed --class=MenuSeeder
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## ⚙️ Langkah 3: Environment Variables

Di tab **"Environment"**, tambahkan variables berikut:

### Wajib
```
APP_NAME=AKPL
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (generate dengan: php artisan key:generate --show)
APP_URL=https://nama-app-anda.onrender.com

DB_CONNECTION=sqlite
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Midtrans (sesuaikan dengan key Anda)
```
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### Generate APP_KEY
Jalankan di local:
```bash
php artisan key:generate --show
```
Copy hasilnya ke environment variable `APP_KEY`

---

## 🗄️ Langkah 4: Database Setup

Project ini menggunakan **SQLite** yang sudah configured.
Render akan otomatis membuat file database.

> ⚠️ **Catatan**: SQLite di Render free tier tidak persistent. 
> Untuk production, gunakan PostgreSQL/MySQL external.

---

## 🚀 Langkah 5: Deploy

1. Klik **"Create Web Service"**
2. Tunggu proses build (5-10 menit pertama kali)
3. Setelah selesai, akses URL yang diberikan Render

---

## 🔔 Langkah 6: Setup Cron Ping (Opsional)

Untuk mencegah **sleep time** (server tidur setelah 15 menit):

1. Kunjungi [cron-job.org](https://cron-job.org)
2. Buat akun gratis
3. Buat cron job baru:
   - **URL**: `https://nama-app-anda.onrender.com`
   - **Schedule**: Every 10 minutes
   - **HTTP Method**: GET

---

## 🔗 Langkah 7: Update Midtrans Webhook

Setelah deploy, update webhook URL di Midtrans Dashboard:

1. Login ke [dashboard.midtrans.com](https://dashboard.midtrans.com)
2. Settings → Configuration
3. Update **Notification URL** ke:
   ```
   https://nama-app-anda.onrender.com/api/midtrans/callback
   ```

---

## ✅ Checklist Setelah Deploy

- [ ] Website bisa diakses
- [ ] Login admin berfungsi
- [ ] Menu bisa dilihat customer
- [ ] QR Code bisa di-generate
- [ ] Midtrans payment berfungsi (test di sandbox)
- [ ] Setup cron ping agar tidak sleep
- [ ] Menu otomatis muncul (karena seeder sudah dijalankan)

---

## ❓ Troubleshooting

### Build Failed
- Cek logs di Render dashboard
- Pastikan `composer.json` dan `package.json` valid

### 500 Error
- Cek `APP_KEY` sudah di-set
- Cek database migration berhasil

### Webhook Midtrans Gagal
- Pastikan URL webhook benar
- Cek server tidak sedang sleep

---

## 📞 Support

Jika ada masalah, cek:
- [Render Documentation](https://render.com/docs)
- [Laravel Deployment Docs](https://laravel.com/docs/deployment)
