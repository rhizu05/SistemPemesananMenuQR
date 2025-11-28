# 🍽️ AKPL Restaurant - Panduan Login

## 📌 Cara Mengakses Login Admin

### 1️⃣ **Akses Halaman Login**
Buka browser dan kunjungi:
```
http://localhost:8000/login
```

### 2️⃣ **Kredensial Login Demo**

Gunakan salah satu akun berikut untuk login:

#### 👨‍💼 **Admin**
- **Email:** `admin@akpl.com`
- **Password:** `password`
- **Akses:** Dashboard admin, kelola menu, kelola pesanan, laporan

#### 👨‍🍳 **Kitchen (Dapur)**
- **Email:** `kitchen@akpl.com`
- **Password:** `password`
- **Akses:** Dashboard dapur, kelola status pesanan

#### 👤 **Customer**
- **Email:** `customer@akpl.com`
- **Password:** `password`
- **Akses:** Menu, keranjang, buat pesanan

---

## 🔗 Route yang Tersedia

### **Authentication Routes**
- `/login` - Halaman login
- `/register` - Halaman registrasi customer baru
- `/logout` - Logout (POST)

### **Admin Routes** (Perlu login sebagai admin)
- `/admin` - Dashboard admin
- `/admin/menu` - Kelola menu
- `/admin/menu/create` - Tambah menu baru
- `/admin/orders` - Kelola pesanan
- `/admin/reports` - Laporan penjualan

### **Kitchen Routes** (Perlu login sebagai kitchen)
- `/kitchen` - Dashboard dapur
- `/kitchen/orders` - Daftar pesanan masuk

### **Customer Routes** (Perlu login sebagai customer)
- `/menu` - Lihat menu
- `/cart` - Keranjang belanja
- `/orders` - Riwayat pesanan saya

### **QR Ordering Routes** (Tanpa login)
- `/qr/table/{tableNumber}` - Menu via QR code untuk meja tertentu
- `/qr/generate/{tableNumber}` - Generate QR code untuk meja

---

## 🚀 Cara Menjalankan Aplikasi

### **Opsi 1: Menggunakan PHP Artisan Serve**
```bash
php artisan serve
```
Akses di: `http://localhost:8000`

### **Opsi 2: Menggunakan Laragon**
Jika sudah setup virtual host di Laragon:
- `http://akpl.test`
- `http://localhost/akpl/public`

---

## 🔐 Fitur Authentication

### **Login**
1. Masukkan email dan password
2. Centang "Ingat saya" jika ingin tetap login
3. Klik tombol "Login"
4. Sistem akan redirect otomatis berdasarkan role:
   - Admin → `/admin`
   - Kitchen → `/kitchen`
   - Customer → `/menu`

### **Register** (Untuk Customer Baru)
1. Klik "Daftar sekarang" di halaman login
2. Isi form registrasi:
   - Nama lengkap
   - Email
   - Password (minimal 8 karakter)
   - Konfirmasi password
3. Akun baru otomatis memiliki role "customer"

### **Logout**
Klik tombol logout di dashboard masing-masing role

---

## 🛠️ Troubleshooting

### **Tidak bisa login?**
1. Pastikan database sudah di-migrate dan di-seed:
   ```bash
   php artisan migrate:fresh --seed
   ```

2. Cek apakah user sudah ada di database:
   ```bash
   php artisan tinker
   User::all();
   ```

### **Error "Route not found"?**
Pastikan server Laravel sudah berjalan:
```bash
php artisan serve
```

### **Redirect ke halaman welcome?**
Sudah diperbaiki! Route `/` sekarang mengarah ke aplikasi, bukan welcome.blade.php

---

## 📝 Catatan Penting

- **Password default:** `password` (untuk semua user demo)
- **Role-based access:** Setiap role memiliki akses yang berbeda
- **Middleware protection:** Route admin/kitchen/customer dilindungi middleware auth dan role
- **Session-based auth:** Menggunakan session Laravel default

---

## 🎯 Next Steps

Setelah login sebagai admin, Anda bisa:
1. ✅ Mengelola menu makanan/minuman
2. ✅ Melihat dan memproses pesanan
3. ✅ Melihat laporan penjualan
4. ✅ Generate QR code untuk meja

Selamat mencoba! 🚀
