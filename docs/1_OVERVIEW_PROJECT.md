# Deskripsi Project - Sistem Pemesanan Restoran Berbasis QR (Dapoer Katendjo)

---

## 📋 Informasi Umum Project

| Aspek | Detail |
|-------|--------|
| **Nama Project** | Sistem Pemesanan Menu Restoran Berbasis QR Code |
| **Nama Klien** | Dapoer Katendjo |
| **Jenis Aplikasi** | Web Application (Responsive) |
| **Kelompok** | Kelompok 4 |

---

## 🎯 Latar Belakang

### Permasalahan yang Dihadapi

Restoran Dapoer Katendjo merupakan usaha kuliner yang sedang berkembang. Namun, dalam operasionalnya terdapat beberapa kendala yang menghambat efisiensi dan pelayanan:

1. **Waktu Tunggu yang Lama**
   - Pelanggan harus menunggu pelayan datang untuk mencatat pesanan
   - Pada jam sibuk, pelayan kewalahan melayani banyak meja sekaligus

2. **Kesalahan Pencatatan Pesanan**
   - Pesanan ditulis manual sehingga rawan salah tulis atau terbaca
   - Catatan kertas bisa hilang atau rusak

3. **Komunikasi Tidak Efisien**
   - Pesanan dari meja ke dapur membutuhkan waktu karena harus diantar manual
   - Terjadi miskomunikasi antara pelayan dan koki

4. **Ketergantungan Pembayaran Tunai**
   - Belum ada sistem pembayaran digital yang terintegrasi
   - Proses pembayaran manual dan rentan kesalahan hitung

5. **Tidak Ada Data Penjualan Real-time**
   - Laporan penjualan harus dihitung manual di akhir hari
   - Tidak ada insight tentang menu favorit atau jam ramai

---

## 💡 Solusi yang Ditawarkan

Sistem Pemesanan Menu Berbasis QR Code hadir untuk mengatasi seluruh permasalahan di atas dengan pendekatan digital dan otomatis.

### Konsep Utama

```
┌──────────────┐     Scan QR      ┌──────────────┐     Kirim      ┌──────────────┐
│   Pelanggan  │ ──────────────►  │  Menu Digital │ ──────────────► │    Dapur     │
│   di Meja    │                  │   + Checkout  │                 │  (Real-time) │
└──────────────┘                  └──────────────┘                 └──────────────┘
       │                                 │                                │
       │                                 ▼                                │
       │                          ┌──────────────┐                       │
       │                          │   Midtrans   │                       │
       │                          │   Payment    │                       │
       │                          └──────────────┘                       │
       │                                 │                                │
       ▼                                 ▼                                ▼
┌────────────────────────────────────────────────────────────────────────────┐
│                           DASHBOARD ADMIN & KASIR                          │
│               (Monitoring, Verifikasi Pembayaran, Laporan)                 │
└────────────────────────────────────────────────────────────────────────────┘
```

### Alur Kerja Sistem

1. **Pelanggan** duduk di meja dan scan QR Code menggunakan smartphone
2. **Menu digital** muncul dengan kategori, gambar, harga, dan deskripsi
3. Pelanggan memilih menu, menambahkan catatan khusus (level pedas, dll)
4. Pelanggan melakukan **checkout** dan membayar via Midtrans
5. **Pesanan otomatis masuk** ke dashboard dapur
6. Koki memproses dan update status pesanan
7. **Kasir memverifikasi** pembayaran jika diperlukan
8. Pelanggan bisa tracking status pesanan secara real-time

---

## 🛠️ Fitur Sistem

### Untuk Pelanggan (Customer)

| Fitur | Deskripsi |
|-------|-----------|
| **Scan QR Menu** | Akses menu digital dengan scan QR di meja |
| **Browse Menu** | Lihat menu berdasarkan kategori dengan gambar dan harga |
| **Keranjang** | Tambah, edit, hapus item dengan catatan khusus per item |
| **Checkout & Payment** | Bayar via Midtrans (QRIS, GoPay, OVO, DANA, Transfer Bank) |
| **Order Tracking** | Pantau status pesanan secara real-time |
| **Riwayat Pesanan** | Lihat semua pesanan sebelumnya |
| **Voucher** | Gunakan kode voucher untuk diskon |
| **Profile** | Kelola profil dan informasi akun |

### Untuk Admin

| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Overview statistik penjualan dan pesanan |
| **Manajemen Menu** | CRUD menu (nama, harga, deskripsi, gambar, stok) |
| **Manajemen Kategori** | CRUD kategori menu |
| **Manajemen QR Code** | Generate QR code untuk setiap nomor meja |
| **Manajemen Voucher** | Buat dan kelola voucher promo |
| **Manajemen Pelanggan** | Lihat data pelanggan dan riwayat pesanan |
| **Laporan Penjualan** | Analytics penjualan harian/mingguan/bulanan |

### Untuk Kasir

| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard Kasir** | Overview pesanan pending pembayaran |
| **Point of Sale (POS)** | Input pesanan manual untuk pelanggan walk-in |
| **Verifikasi Pembayaran** | Konfirmasi pembayaran tunai/transfer manual |
| **Cetak Struk** | Print receipt untuk pelanggan |

### Untuk Dapur

| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard Dapur** | Tampilan pesanan masuk real-time |
| **Update Status** | Ubah status: Pending → Diproses → Siap → Selesai |
| **Detail Pesanan** | Lihat item pesanan dengan catatan khusus |

---

## 🔐 Sistem Autentikasi

### Multi-Method Authentication

| Role | Metode Login |
|------|--------------|
| **Admin** | Email + Password |
| **Kasir** | Email + Password |
| **Dapur** | Email + Password |
| **Pelanggan** | Nomor WhatsApp + OTP |

### Alur Login Pelanggan (OTP WhatsApp)

```
1. Input nomor WhatsApp (format: 8xxxxxxxxx)
           │
           ▼
2. Sistem kirim OTP via Fonnte WhatsApp API
           │
           ▼
3. Pelanggan input kode OTP 6 digit
           │
           ▼
4. Verifikasi OTP → Login berhasil
           │
           ▼
5. Jika nomor baru → Auto-register sebagai pelanggan
```

---

## 💳 Integrasi Pembayaran

### Midtrans Payment Gateway

Sistem terintegrasi dengan **Midtrans Snap** untuk pembayaran digital:

| Metode Pembayaran | Status |
|-------------------|--------|
| QRIS | ✅ Tersedia |
| GoPay | ✅ Tersedia |
| OVO | ✅ Tersedia |
| DANA | ✅ Tersedia |
| ShopeePay | ✅ Tersedia |
| Transfer Bank (BCA, BNI, BRI, Mandiri) | ✅ Tersedia |
| Kartu Kredit/Debit | ✅ Tersedia |
| Tunai (via Kasir) | ✅ Tersedia |

### Alur Pembayaran

```
1. Pelanggan checkout
        │
        ▼
2. Pilih metode pembayaran
        │
        ▼
3. Redirect ke Midtrans Snap
        │
        ▼
4. Pembayaran diproses
        │
        ▼
5. Callback ke sistem → Update status order
        │
        ▼
6. Notifikasi ke dapur → Pesanan diproses
```

---

## 📱 Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 12
- **Bahasa**: PHP 8.3+
- **Database**: SQLite (dev) / MySQL / PostgreSQL (prod)
- **API Pattern**: RESTful API

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5.3
- **JavaScript**: Vanilla ES6+
- **Icons**: Bootstrap Icons

### Third-Party Services
- **Payment**: Midtrans Snap API
- **OTP/WhatsApp**: Fonnte API
- **QR Code**: SimpleSoftwareIO/simple-qrcode

### Development Tools
- **Build Tool**: Vite
- **Version Control**: Git / GitHub
- **Deployment**: Render.com

---

## 📊 Model Data Utama

| Entity | Atribut Utama |
|--------|---------------|
| **User** | id, name, email, phone, role, password |
| **Category** | id, name, description, is_active |
| **Menu** | id, category_id, name, price, description, image, stock, is_available |
| **Order** | id, order_number, user_id, table_number, status, total_amount, payment_status |
| **OrderItem** | id, order_id, menu_id, quantity, price, notes |
| **Voucher** | id, code, discount_type, discount_value, min_purchase, max_uses |

---

## ✅ Keunggulan Sistem

1. **Efisiensi Operasional** - Pesanan langsung ke dapur tanpa perantara
2. **Mengurangi Human Error** - Tidak ada pencatatan manual
3. **Multi-Payment** - Berbagai metode pembayaran digital
4. **Real-time Tracking** - Status pesanan update secara live
5. **Data-Driven** - Laporan penjualan otomatis untuk analisis bisnis
6. **Responsive Design** - Bisa diakses dari smartphone, tablet, dan desktop
7. **Scalable** - Mudah ditambah fitur dan dikembangkan

---

## 📌 Batasan Sistem

1. Memerlukan koneksi internet untuk semua fitur
2. Pelanggan harus memiliki smartphone dengan camera untuk scan QR
3. Pembayaran digital memerlukan akun e-wallet atau rekening bank
4. Sistem tidak menangani inventory management secara detail
5. Tidak ada fitur reservasi meja online
