# 🏠 Dapoer Katendjo - QR Ordering System

> **Status**: 🟢 Live / Maintenance  
> **Version**: 2.0  
> **Last Updated**: November 30, 2025  
> **Developer**: Tim Pengembang  

---

## 📌 Project Overview

**Sistem Pemesanan Restoran Berbasis QR** untuk Dapoer Katendjo adalah solusi digital *end-to-end* yang memungkinkan pelanggan memesan makanan tanpa kontak fisik, mendukung operasional kasir (POS), dan manajemen dapur yang efisien.

### 🎯 Key Objectives
1.  **Contactless Ordering**: Pelanggan scan QR, pesan, dan bayar sendiri.
2.  **Real-time Operations**: Sinkronisasi instan antara Pesanan -> Kasir -> Dapur.
3.  **Digital Payment**: Integrasi QRIS (Midtrans) untuk pembayaran otomatis.
4.  **Data-Driven**: Laporan penjualan dan analitik lengkap.

---

## 🛠 Tech Stack

| Category | Technology | Description |
| :--- | :--- | :--- |
| **Backend** | ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white) **Laravel 10.x** | PHP Framework utama |
| **Frontend** | ![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=flat&logo=bootstrap&logoColor=white) **Bootstrap 5** | UI Framework + Blade Templates |
| **Database** | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) **MySQL 8.0** | Relational Database |
| **Payment** | ![Midtrans](https://img.shields.io/badge/Midtrans-0052CC?style=flat&logo=contactless-payment&logoColor=white) **Midtrans** | Payment Gateway (QRIS) |
| **Server** | **Apache/Nginx** | Web Server (Laragon Env) |

---

## 📱 Features & Modules

### 👤 Customer App (Mobile Web)
*Akses via Scan QR Code*
- [x] **Digital Menu**: Foto, harga, deskripsi, kategori.
- [x] **Smart Cart**: Tambah item, catatan khusus, edit jumlah.
- [x] **Voucher System**: Input kode promo diskon.
- [x] **Checkout**: Pembayaran Tunai atau QRIS.
- [x] **Order Tracking**: Status real-time (Pending -> Siap).

### 🖥 Admin Dashboard & POS
*Akses via `/admin`*
- [x] **Point of Sale (POS)**: Kasir manual untuk walk-in customer.
- [x] **Menu Management**: CRUD Menu, Kategori, Stok.
- [x] **Voucher Management**: Buat promo diskon (Persen/Tetap).
- [x] **Order Management**: Monitor pesanan masuk, konfirmasi bayar.
- [x] **Reporting**: Laporan harian, bulanan, export PDF.
- [x] **Customer Management**: Database pelanggan & riwayat.

### 🍳 Kitchen Display System (KDS)
*Akses via `/kitchen`*
- [x] **Order Queue**: Daftar pesanan yang harus dimasak.
- [x] **Status Update**: Tandai "Sedang Diproses" atau "Siap".
- [x] **Auto Refresh**: Tampilan selalu update otomatis.

---

## 🎨 Design System

Kami menggunakan skema warna yang konsisten untuk membangun identitas visual yang kuat.

### Color Palette

| Color Name | Hex Code | Usage | Preview |
| :--- | :--- | :--- | :--- |
| **Primary Green** | `#2A5C3F` | Header, Primary Buttons, Active States | 🟩 |
| **Secondary Green** | `#4A7F5A` | Secondary Buttons, Subtitles, Borders | 🌿 |
| **Accent Green** | `#8FC69A` | Badges, Highlights, Charts | 🍈 |
| **Info Blue** | `#1976D2` | Statistics, Info Alerts | 🟦 |
| **Warning Orange** | `#FBC02D` | Pending Status, Low Stock | 🟧 |
| **Error Red** | `#D32F2F` | Delete Actions, Errors, Cancel | 🟥 |

### Typography
- **Font Family**: System UI (San Francisco, Segoe UI, Roboto)
- **Headings**: Bold, Primary Color
- **Body**: Legible, High Contrast

---

## 🚀 Getting Started (Local Dev)

Panduan untuk menjalankan project ini di komputer lokal menggunakan **Laragon**.

### 1. Prerequisites
- PHP 8.1+
- Composer
- MySQL
- Node.js (Optional)

### 2. Installation Steps
```bash
# 1. Clone Repository
git clone https://github.com/username/dapoer-katendjo.git

# 2. Install Dependencies
composer install

# 3. Environment Setup
cp .env.example .env
# Edit .env (DB_DATABASE, MIDTRANS_KEYS, etc.)

# 4. Generate Key
php artisan key:generate

# 5. Migrate & Seed
php artisan migrate --seed

# 6. Run Server
php artisan serve
```

### 3. Access Points
- **Customer**: `http://localhost:8000/`
- **Admin**: `http://localhost:8000/admin/login`
- **Kitchen**: `http://localhost:8000/kitchen/login`

---

## 📚 Database Schema

> *Lihat file `UML_DIAGRAMS.md` untuk diagram visual.*

### Core Tables
- **`users`**: Admin & Customer accounts.
- **`menus`**: Daftar makanan/minuman.
- **`categories`**: Kategori menu (Makanan, Minuman, Snack).
- **`orders`**: Header transaksi pesanan.
- **`order_items`**: Detail item dalam pesanan.
- **`vouchers`**: Data promo diskon.
- **`voucher_usage`**: Log penggunaan voucher.

---

## 🔮 Future Roadmap

### Phase 2 (Next)
- [ ] Multi-language Support (EN/ID).
- [ ] Push Notifications (WebPush).
- [ ] Inventory Management Lanjutan (Bahan Baku).

### Phase 3
- [ ] Multi-outlet Support.
- [ ] Mobile App (Native).
- [ ] AI Recommendations.
