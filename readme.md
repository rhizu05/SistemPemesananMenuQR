# 📝 Sistem Pemesanan Restoran (Ordering System)

Sistem pemesanan restoran berbasis web yang dikembangkan menggunakan Laravel 12 dengan arsitektur role-based untuk mengelola proses pemesanan dari pelanggan hingga ke dapur.

## 🏗️ Arsitektur Sistem

Sistem ini dibangun dengan arsitektur berbasis role yang terdiri dari:
- **Admin/Kasir**: Mengelola menu, memantau pesanan, verifikasi pembayaran, dan mencetak struk
- **Pelanggan**: Melihat menu, memesan makanan, dan melihat status pesanan
- **Koki/Dapur**: Memantau pesanan dan memperbarui status pesanan

## ✨ Fitur Utama

### 1. Manajemen Menu (Admin)
- CRUD menu dan kategori
- Pengaturan harga, stok, dan status ketersediaan
- Upload gambar menu
- Manajemen kategori menu

### 2. Pemesanan Pelanggan
- Sistem pemesanan melalui QR code (QR Ordering)
- Tampilan menu yang responsif dan menarik
- Keranjang belanja dinamis
- Proses pemesanan lengkap dengan informasi pelanggan

### 3. Dashboard Pemantauan
- Dashboard admin untuk memantau seluruh pesanan
- Dashboard kitchen untuk memantau pesanan yang siap diproses
- Update status pesanan secara real-time

### 4. Sistem Real-time
- Notifikasi update status pesanan secara real-time
- Menggunakan Laravel Echo dan Pusher untuk komunikasi real-time
- Update otomatis di semua perangkat saat status berubah

### 5. Sistem Pembayaran & Struk
- Verifikasi pembayaran oleh admin
- Manajemen status pembayaran (Lunas, Pending, Gagal)
- Cetak struk otomatis

### 6. QR Code Ordering
- Sistem pemesanan langsung dari meja via QR code
- Tidak perlu login untuk memesan
- Pengalaman pelanggan yang lebih interaktif

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12 (PHP)
- **Frontend**: Blade Template, Bootstrap 5, JavaScript
- **Database**: SQLite (dapat diubah ke MySQL/PostgreSQL)
- **Real-time**: Laravel Echo + Pusher
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage System

## 📊 Struktur Database

### Tabel Utama:
- **users**: Manajemen pengguna dengan role (admin, kitchen, customer)
- **categories**: Kategori menu
- **menus**: Data menu lengkap dengan harga, stok, dan gambar
- **orders**: Informasi pesanan pelanggan
- **order_items**: Detail item dalam setiap pesanan

## 🚀 Cara Menjalankan Aplikasi

1. Pastikan Anda memiliki:
   - PHP 8.2+
   - Composer
   - Node.js
   - Database (SQLite bawaan Laravel sudah dikonfigurasi)

2. Instal dependensi:
   ```bash
   composer install
   npm install
   ```

3. Konfigurasi .env:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```

5. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```

## 🔐 Role-based Access Control

- **Admin**: Akses penuh ke semua fitur
- **Kitchen**: Akses ke dashboard kitchen dan update status pesanan
- **Customer**: Akses ke menu dan status pesanan

## 📱 Fitur Pelanggan

- Scan QR code untuk mengakses menu langsung dari meja
- Pilih dan pesan makanan secara mandiri
- Lihat status pesanan secara real-time
- Tidak perlu registrasi untuk memesan via QR

## 🧹 Konfigurasi Real-time (Opsional)

Untuk fitur real-time, Anda bisa mengintegrasikan Pusher:
1. Buat akun Pusher
2. Update konfigurasi di .env
3. Jalankan server Pusher

## 📈 Kinerja & Skalabilitas

- Sistem dirancang untuk menangani banyak pesanan secara bersamaan
- Penggunaan AJAX untuk pengalaman pengguna yang lebih baik
- Sistem cache untuk kinerja lebih cepat
- Arsitektur yang dapat diskalakan sesuai kebutuhan

## 🛡️ Keamanan

- Validasi input yang ketat
- CSRF protection bawaan Laravel
- Otentikasi berbasis role
- Proteksi terhadap SQL injection dan XSS

## 🧩 Modul Sistem

1. **Modul Autentikasi**: Login/logout dengan role-based access
2. **Modul Menu**: Manajemen menu dan kategori
3. **Modul Pemesanan**: Proses pembuatan dan pemantauan pesanan
4. **Modul Pembayaran**: Manajemen status pembayaran
5. **Modul Real-time**: Update status pesanan secara langsung
6. **Modul Laporan**: Riwayat dan statistik pesanan
7. **Modul QR Ordering**: Akses menu tanpa login

## 📋 Kebutuhan Sistem Terpenuhi

Semua kebutuhan fungsional dan non-fungsional dari dokumen kebutuhan telah diimplementasikan:

✅ **Admin**: Otentikasi, CRUD menu, pemantauan pesanan, verifikasi pembayaran, cetak struk  
✅ **Pelanggan**: Akses sistem via QR, pemesanan, tampilan menu, status pesanan  
✅ **Koki**: Pemantauan pesanan, update status, notifikasi  
✅ **Real-time**: Update status pesanan secara instan  
✅ **Kinerja**: Responsif dan cepat  
✅ **Keamanan**: Proteksi data dan transaksi  