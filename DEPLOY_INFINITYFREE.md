# 🚀 PANDUAN DEPLOY LARAVEL KE INFINITYFREE

## 📝 INFORMASI PENTING
- Hosting: InfinityFree
- Framework: Laravel 12
- Database: MySQL
- PHP Version: 8.x

---

## ✅ STEP 1: SETUP AKUN INFINITYFREE

1. Daftar di https://www.infinityfree.com
2. Buat hosting account baru
3. Pilih subdomain gratis (contoh: akpl-pos.infinityfreeapp.com)
4. Tunggu akun aktif (5-10 menit)

---

## ✅ STEP 2: AKSES CPANEL

1. Login ke InfinityFree dashboard
2. Klik "Control Panel" atau "cPanel"
3. Catat informasi berikut:
   - FTP Hostname
   - FTP Username
   - FTP Password
   - MySQL Hostname
   - MySQL Database Name
   - MySQL Username
   - MySQL Password

---

## ✅ STEP 3: BUAT DATABASE MYSQL

1. Di cPanel, cari "MySQL Databases"
2. Buat database baru:
   - Database Name: (akan otomatis ada prefix)
   - Catat nama database lengkap
3. Buat user MySQL:
   - Username: (akan otomatis ada prefix)
   - Password: (buat password kuat)
   - Catat username dan password
4. Tambahkan user ke database:
   - Pilih database yang dibuat
   - Pilih user yang dibuat
   - Berikan ALL PRIVILEGES
   - Klik "Add"

---

## ✅ STEP 4: UPLOAD FILE VIA FTP

### Opsi A: Menggunakan FileZilla (Recommended)

1. Download FileZilla: https://filezilla-project.org/
2. Install FileZilla
3. Buka FileZilla dan connect:
   - Host: ftpupload.net (atau sesuai info cPanel)
   - Username: (dari cPanel)
   - Password: (dari cPanel)
   - Port: 21
4. Upload struktur folder:
   ```
   htdocs/
   ├── (isi folder public Laravel) <- Upload semua file dari folder public
   ├── .htaccess
   └── index.php
   
   (folder di luar htdocs, buat folder "laravel")
   laravel/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .env
   ├── artisan
   └── composer.json
   ```

### Opsi B: Menggunakan File Manager cPanel

1. Di cPanel, buka "File Manager"
2. Upload file ke folder yang sesuai
3. Extract jika upload dalam bentuk ZIP

---

## ✅ STEP 5: KONFIGURASI .ENV

1. Buka File Manager di cPanel
2. Edit file `.env` di folder `laravel/`
3. Update konfigurasi:

```env
APP_NAME="AKPL POS"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://akpl-pos.infinityfreeapp.com

DB_CONNECTION=mysql
DB_HOST=sql123.infinityfree.com (sesuai info cPanel)
DB_PORT=3306
DB_DATABASE=epiz_12345678_akpl (sesuai database yang dibuat)
DB_USERNAME=epiz_12345678 (sesuai user yang dibuat)
DB_PASSWORD=your_database_password

# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

---

## ✅ STEP 6: UPDATE INDEX.PHP

Edit file `htdocs/index.php`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../laravel/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../laravel/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../laravel/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

---

## ✅ STEP 7: SET PERMISSIONS

Di File Manager atau FTP, set permissions:

1. Folder `storage/` → 755
2. Folder `storage/framework/` → 755
3. Folder `storage/logs/` → 755
4. Folder `bootstrap/cache/` → 755

---

## ✅ STEP 8: IMPORT DATABASE

1. Export database dari local:
   ```bash
   php artisan migrate:fresh --seed
   # Lalu export dari phpMyAdmin local
   ```

2. Di cPanel InfinityFree:
   - Buka phpMyAdmin
   - Pilih database yang dibuat
   - Klik "Import"
   - Upload file SQL
   - Klik "Go"

---

## ✅ STEP 9: GENERATE APP KEY (Jika Belum Ada)

1. Buka terminal/command prompt di local
2. Jalankan:
   ```bash
   php artisan key:generate --show
   ```
3. Copy key yang muncul
4. Paste ke `.env` di server (APP_KEY)

---

## ✅ STEP 10: TESTING

1. Buka browser
2. Akses: https://akpl-pos.infinityfreeapp.com
3. Test fitur:
   - Login admin
   - POS
   - QRIS Payment
   - Menu management

---

## 🔧 TROUBLESHOOTING

### Error 500
- Cek file `.env` sudah benar
- Cek APP_KEY sudah di-set
- Cek permissions folder storage dan bootstrap/cache

### Database Connection Error
- Cek DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- Pastikan user sudah ditambahkan ke database dengan ALL PRIVILEGES

### CSS/JS Tidak Load
- Cek APP_URL di .env
- Pastikan file di folder public sudah terupload semua

### QRIS Tidak Muncul
- Update MIDTRANS_SERVER_KEY di .env production
- Pastikan ngrok/tunnel tidak digunakan lagi
- Gunakan URL production untuk Midtrans callback

---

## 📞 SUPPORT

Jika ada masalah, hubungi:
- InfinityFree Forum: https://forum.infinityfree.com
- InfinityFree Support: Via ticket di dashboard

---

## 🎉 SELESAI!

Aplikasi POS Anda sekarang sudah online dan bisa diakses dari mana saja!

URL: https://akpl-pos.infinityfreeapp.com (sesuai subdomain yang dipilih)
