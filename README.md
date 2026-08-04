# Dapoer Katendjo - QR Ordering System

Sistem Pemesanan Menu Restoran Berbasis **QR Code** untuk restoran **Dapoer Katendjo**. Pelanggan memindai QR di meja, memesan langsung dari smartphone, membayar secara digital, dan pesanan masuk otomatis ke dashboard dapur secara real-time.

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-blueviolet?logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap)
![CI](https://github.com/rhizu05/SistemPemesananMenuQR/actions/workflows/ci.yml/badge.svg)
![Tests](https://img.shields.io/badge/tests-107%20passed-brightgreen)

---

## Fitur Utama

- **Pemesanan via QR** - Scan QR per meja → lihat menu digital → pesan → bayar
- **Multi-Role Dashboard** - Admin, Kasir, Dapur, dan Pelanggan
- **Pembayaran Digital** - Integrasi Midtrans Snap (QRIS, GoPay, OVO, DANA, ShopeePay, Transfer Bank, Kartu)
- **Lacak Status Pesanan** - Pantau status pesanan dari dapur hingga siap diantar
- **Login OTP WhatsApp** - Autentikasi pelanggan via nomor WhatsApp + OTP (Fonnte API)
- **Manajemen Menu & Kategori** - CRUD lengkap dengan gambar, harga, dan stok
- **Sistem Voucher** - Kode promo diskon persentase/nominal dengan kuota & minimum belanja
- **QR Code Generator** - Generate QR unik untuk setiap nomor meja
- **POS (Point of Sale)** - Input pesanan manual untuk pelanggan walk-in
- **Laporan Penjualan** - Rekap pendapatan dan pesanan harian/mingguan/bulanan
- **Cetak Struk** - Print receipt dari dashboard admin/kasir

## Peran Pengguna

| Role | Akses |
|------|-------|
| **Admin** | Dashboard, manajemen menu/kategori, QR meja, voucher, pelanggan, laporan |
| **Kasir** | POS, verifikasi pembayaran tunai, cetak struk |
| **Dapur (Kitchen)** | Dashboard pesanan real-time, update status (Proses → Siap → Selesai) |
| **Pelanggan** | Menu digital, keranjang, checkout, tracking pesanan, voucher |

Login: Admin/Kasir/Dapur memakai **email + password**, Pelanggan memakai **nomor WhatsApp + OTP**.

## Tampilan (Screenshot)

### Alur Pelanggan

| Scan QR | Menu Digital | Keranjang |
|---------|--------------|-----------|
| <img src="docs/screenshots/customer/01-customer-scan-qr.jpeg" alt="Scan QR" width="200"> | <img src="docs/screenshots/customer/02-customer-menu.jpeg" alt="Menu Digital" width="200"> | <img src="docs/screenshots/customer/03-customer-cart.jpeg" alt="Keranjang" width="200"> |
| <img src="docs/screenshots/customer/04-customer-payment.jpeg" alt="Pembayaran" width="200"> | <img src="docs/screenshots/customer/05-customer-order-success.jpeg" alt="Pesanan Berhasil" width="200"> | <img src="docs/screenshots/customer/06-customer-history.jpeg" alt="Riwayat Pesanan" width="200"> |

### Alur Admin

| Dashboard | Manajemen Menu | Tambah Menu |
|-----------|----------------|-------------|
| <img src="docs/screenshots/admin/01-admin-dashboard.png" alt="Dashboard Admin" width="340"> | <img src="docs/screenshots/admin/02-admin-manage-menu.png" alt="Manajemen Menu" width="340"> | <img src="docs/screenshots/admin/03-admin-add-menu.png" alt="Tambah Menu" width="340"> |
| <img src="docs/screenshots/admin/04-admin-manage-qr-table.png" alt="QR Code Meja" width="340"> | <img src="docs/screenshots/admin/05-admin-manage-voucher.png" alt="Manajemen Voucher" width="340"> | <img src="docs/screenshots/admin/06-admin-report.png" alt="Laporan Penjualan" width="340"> |

### Alur Kasir

| Dashboard Kasir | POS | Verifikasi Pembayaran |
|-----------------|-----|-----------------------|
| <img src="docs/screenshots/cashier/01-cashier-dashboard.png" alt="Dashboard Kasir" width="340"> | <img src="docs/screenshots/cashier/02-cashier-pos.png" alt="POS" width="340"> | <img src="docs/screenshots/cashier/03-cashier-payment-qr.png" alt="Verifikasi Pembayaran" width="340"> |
| <img src="docs/screenshots/cashier/04-cashier-order-success.png" alt="Pesanan Berhasil" width="340"> | <img src="docs/screenshots/cashier/05-cashier-receipt.png" alt="Cetak Struk" width="340"> | |

### Alur Dapur

| Dashboard Dapur |
|-----------------|
| <img src="docs/screenshots/kitchen/01-kitchen-dashboard.png" alt="Dashboard Dapur" width="340"> |

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12 (PHP 8.3+) |
| Frontend | Blade + Bootstrap 5.3 + Tailwind CSS (via Vite) |
| Database | SQLite (dev) / MySQL / PostgreSQL (prod) |
| Payment | Midtrans Snap API |
| OTP/WhatsApp | Fonnte API |
| QR Code | SimpleSoftwareIO/simple-qrcode |
| Broadcast Event | Laravel Echo + Pusher (dependencies) |
| Build Tool | Vite |

## Struktur Proyek

```
akpl/
├── app/
│   ├── Events/                 # Event domain (mis. OrderStatusUpdated)
│   ├── Http/
│   │   ├── Controllers/        # Admin, Cashier, Customer, Kitchen, Auth, QR, Voucher, PaymentCallback
│   │   └── Middleware/         # RoleMiddleware, PreventKitchenAccess, RedirectIfAuthenticated
│   ├── Models/                 # User, Category, Menu, Order, OrderItem, Voucher, VoucherUsage
│   ├── Providers/
│   └── Services/               # FonnteService (OTP WhatsApp)
├── bootstrap/                  # Bootstrap aplikasi & middleware
├── config/                     # Konfigurasi Laravel
├── database/
│   ├── factories/
│   ├── migrations/             # Skema tabel (users, menus, orders, vouchers, dll.)
│   └── seeders/                # User, Category, Menu, Voucher seeder
├── docs/                       # Dokumentasi lengkap (lihat indeks di bawah)
├── public/                     # Entry point & asset publik
├── resources/
│   ├── css/                    # app.css (Tailwind v4)
│   ├── js/                     # app.js, bootstrap.js (Laravel Echo)
│   └── views/                  # Blade template per role (admin, cashier, kitchen, customer, auth, qr)
├── routes/
│   ├── admin.php               # Semua route role-based
│   ├── console.php
│   └── web.php                 # Auth, webhook Midtrans, include admin.php
├── tests/                      # Pest test (Feature/Unit)
├── composer.json
├── package.json
├── phpunit.xml
└── vite.config.js
```

## Persyaratan

- PHP **8.3+** (toolchain test — Pest 4 / PHPUnit 12 — butuh PHP ≥ 8.3)
- Composer
- Node.js & NPM
- SQLite (default, sudah tersedia di `database/database.sqlite`) atau MySQL/PostgreSQL

## Instalasi Lokal

```bash
# 1. Install dependency
composer install
npm install

# 2. Siapkan environment
copy .env.example .env   # (Windows) / cp .env.example .env (Linux/macOS)
php artisan key:generate

# 3. Set konfigurasi di .env
#    DB_CONNECTION=sqlite
#    MIDTRANS_SERVER_KEY=...     (opsional untuk payment)
#    MIDTRANS_CLIENT_KEY=...
#    FONNTE_TOKEN=...            (opsional untuk OTP WhatsApp)

# 4. Migrasi & seed data awal
php artisan migrate --seed

# 5. Jalankan server (3 terminal / pakai composer dev)
composer run dev
# atau secara manual:
#   php artisan serve
#   php artisan queue:listen --tries=1
#   npm run dev
```

Akses aplikasi di `http://localhost:8000`.

### Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@dapoerkatendjo.com` | `password` |
| Kasir | `cashier@dapoerkatendjo.com` | `password` |
| Dapur | `kitchen@dapoerkatendjo.com` | `password` |
| Pelanggan | `customer@akpl.com` | `password` |

> ⚠️ **Akun di atas hanya untuk demo/lokal.** Ganti password & email default sebelum dipakai di produksi.
>
> Jalankan seeder: `php artisan db:seed`

## Menjalankan Test

Proyek menggunakan **Pest**:

```bash
composer test                 # php artisan test (semua test)
php artisan test --filter=AdminFeatures
php artisan test --parallel   # lebih cepat
```

Alternatif interaktif: `.\run-tests.ps1`

> **CI:** GitHub Actions (`.github/workflows/ci.yml`) otomatis menjalankan test (PHP 8.2–8.4), build asset (Vite), dan cek code style (Pint) pada setiap push/PR.

## Deployment

Panduan lengkap deploy ke **Render.com** (gratis) ada di [`docs/6_DEPLOYMENT_RENDER.md`](./docs/6_DEPLOYMENT_RENDER.md). Konfigurasi disertakan pada [`render.yaml`](./render.yaml).

## Dokumentasi Lainnya

Indeks lengkap dokumentasi ada di [`docs/README.md`](./docs/README.md):

1. [Overview Project](./docs/1_OVERVIEW_PROJECT.md)
2. [Rekayasa Kebutuhan](./docs/2_REKAYASA_KEBUTUHAN.md)
3. [Daftar Kebutuhan](./docs/3_DAFTAR_KEBUTUHAN.md)
4. [Dokumentasi Notion](./docs/4_DOKUMENTASI_NOTION.md)
5. [UML Diagrams (Mermaid)](./docs/5_UML_DIAGRAMS_MERMAID.md)
6. [Panduan Deploy Render](./docs/6_DEPLOYMENT_RENDER.md)

## Lisensi

Proyek ini dikembangkan untuk kebutuhan akademik **Kelompok 4** dengan klien Dapoer Katendjo.
