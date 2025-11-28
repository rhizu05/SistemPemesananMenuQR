# ✅ CHECKLIST DEPLOY KE INFINITYFREE

## 📋 PERSIAPAN (Sudah Selesai ✓)
- [x] File .htaccess sudah dibuat
- [x] File index_infinityfree.php sudah dibuat
- [x] Panduan lengkap sudah dibuat (DEPLOY_INFINITYFREE.md)
- [x] Cache Laravel sudah dibersihkan

## 🌐 LANGKAH SELANJUTNYA (Yang Perlu Anda Lakukan)

### 1. DAFTAR INFINITYFREE
- [ ] Buka https://www.infinityfree.com
- [ ] Klik "Sign Up"
- [ ] Isi email dan password
- [ ] Verifikasi email

### 2. BUAT HOSTING ACCOUNT
- [ ] Login ke dashboard InfinityFree
- [ ] Klik "Create Account"
- [ ] Pilih subdomain (contoh: akpl-pos.infinityfreeapp.com)
- [ ] Tunggu akun aktif (5-10 menit)

### 3. CATAT INFORMASI PENTING
Dari cPanel, catat:
- [ ] FTP Hostname: _________________
- [ ] FTP Username: _________________
- [ ] FTP Password: _________________
- [ ] MySQL Hostname: _________________
- [ ] MySQL Database: _________________
- [ ] MySQL Username: _________________
- [ ] MySQL Password: _________________

### 4. BUAT DATABASE
- [ ] Buka cPanel → MySQL Databases
- [ ] Buat database baru
- [ ] Buat user MySQL
- [ ] Tambahkan user ke database (ALL PRIVILEGES)

### 5. DOWNLOAD FILEZILLA
- [ ] Download dari https://filezilla-project.org/
- [ ] Install FileZilla

### 6. UPLOAD FILE
Upload struktur berikut:

**Folder htdocs/** (root website):
- [ ] Upload semua file dari folder `public/`
- [ ] Rename `index_infinityfree.php` menjadi `index.php`
- [ ] Upload `.htaccess`

**Folder laravel/** (buat folder baru di luar htdocs):
- [ ] Upload folder `app/`
- [ ] Upload folder `bootstrap/`
- [ ] Upload folder `config/`
- [ ] Upload folder `database/`
- [ ] Upload folder `resources/`
- [ ] Upload folder `routes/`
- [ ] Upload folder `storage/`
- [ ] Upload folder `vendor/`
- [ ] Upload file `.env`
- [ ] Upload file `artisan`
- [ ] Upload file `composer.json`

### 7. KONFIGURASI .ENV
Edit file `.env` di folder `laravel/`:
- [ ] Update APP_URL dengan URL subdomain Anda
- [ ] Update DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- [ ] Update MIDTRANS credentials
- [ ] Set APP_DEBUG=false
- [ ] Set APP_ENV=production

### 8. SET PERMISSIONS
- [ ] Folder `storage/` → 755
- [ ] Folder `storage/framework/` → 755
- [ ] Folder `storage/logs/` → 755
- [ ] Folder `bootstrap/cache/` → 755

### 9. IMPORT DATABASE
- [ ] Export database dari phpMyAdmin local
- [ ] Import ke phpMyAdmin InfinityFree

### 10. TESTING
- [ ] Akses website: https://[subdomain-anda].infinityfreeapp.com
- [ ] Test login admin
- [ ] Test POS
- [ ] Test QRIS payment

---

## 📁 FILE YANG SUDAH DISIAPKAN

1. **DEPLOY_INFINITYFREE.md** - Panduan lengkap deploy
2. **public/.htaccess** - File .htaccess yang sudah disesuaikan
3. **public/index_infinityfree.php** - File index.php untuk InfinityFree

---

## 🆘 BUTUH BANTUAN?

Jika ada kendala saat deploy, tanyakan saja! Saya siap membantu.

---

## 🎯 TIPS PENTING

1. **Jangan lupa rename** `index_infinityfree.php` menjadi `index.php` setelah upload
2. **Backup database** sebelum import ke production
3. **Test semua fitur** setelah deploy
4. **Update Midtrans callback URL** ke URL production Anda

---

Selamat deploy! 🚀
