# Dapoer Katendjo - QR Ordering System

Sistem Pemesanan Menu Restoran Berbasis **QR Code** untuk restoran **Dapoer Katendjo**. Pelanggan memindai QR di meja, memesan langsung dari smartphone, membayar secara digital, dan pesanan masuk otomatis ke dashboard dapur secara real-time.

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-blueviolet?logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap)
![CI](https://github.com/YOUR_USERNAME/YOUR_REPO/actions/workflows/ci.yml/badge.svg)
![Tests](https://img.shields.io/badge/tests-102%20passed-brightgreen)

> Ganti `YOUR_USERNAME/YOUR_REPO` pada badge CI dengan username & nama repositori GitHub kamu setelah di-push.

---

## Fitur Utama

- **Pemesanan via QR** - Scan QR per meja → lihat menu digital → pesan → bayar
- **Multi-Role Dashboard** - Admin, Kasir, Dapur, dan Pelanggan
- **Pembayaran Digital** - Integrasi Midtrans Snap (QRIS, GoPay, OVO, DANA, ShopeePay, Transfer Bank, Kartu)
- **Real-time Order Tracking** - Status pesanan live dari dapur ke pelanggan (Pusher/Broadcast)
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

> Screenshot alur utama diletakkan di `docs/screenshots/`. Ganti nama file di bawah setelah gambar diambil.

| Menu Digital (Pelanggan) | Dashboard Admin | Dashboard Dapur |
|--------------------------|-----------------|-----------------|
| `docs/screenshots/customer/02-menu.png` | `docs/screenshots/admin/01-dashboard.png` | `docs/screenshots/kitchen/01-dashboard.png` |

### Template Alur Screenshot

**Persiapan:** jalankan `php artisan migrate:fresh --seed` (membuat menu, kategori, voucher, dan 4 akun demo).

**Viewport:** alur pelanggan memakai mode mobile (DevTools → iPhone 12/13, 390×844); admin/kasir/dapur memakai desktop 1440×900. Simpan PNG; untuk alur aksi gunakan GIF (ScreenToGif / OBS).

```
docs/screenshots/
├── customer/            # alur pelanggan
├── admin/               # dashboard admin
├── cashier/
├── kitchen/
└── demo.gif             # gif alur utama (opsional)
```

**1. Alur Pelanggan (9 shot — paling penting)**

| # | Nama File | URL | Aksi |
|---|-----------|-----|------|
| 1 | `customer/01-scan-qr.png` | `/scan-qr` | Halaman scan QR |
| 2 | `customer/02-menu.png` | `/?table=1` | Daftar menu + kategori + harga |
| 3 | `customer/03-menu-detail.png` | `/menu?table=1` | Detail 1 item (qty, catatan) |
| 4 | `customer/04-cart.png` | `/cart` | Keranjang berisi 2–3 item |
| 5 | `customer/05-voucher.png` | `/cart` (bagian voucher) | Input kode voucher + diskon |
| 6 | `customer/06-checkout.png` | `/cart` (checkout) | Ringkasan order + total akhir |
| 7 | `customer/07-payment.png` | Modal Midtrans | Snap payment (sandbox) |
| 8 | `customer/08-order-success.png` | `/order/{n}/success` | Pesanan berhasil + nomor order |
| 9 | `customer/09-tracking.png` | `/order/{n}/status` | Status live: Preparing → Ready |

**2. Alur Admin (6 shot)**

| # | Nama File | URL | Aksi |
|---|-----------|-----|------|
| 1 | `admin/01-dashboard.png` | `/admin` | Statistik penjualan |
| 2 | `admin/02-menu.png` | `/admin/menu` | Tabel manajemen menu |
| 3 | `admin/03-menu-create.png` | `/admin/menu/create` | Form tambah menu |
| 4 | `admin/04-qr-codes.png` | `/admin/qr-codes` | Generate QR per meja |
| 5 | `admin/05-vouchers.png` | `/admin/vouchers` | Daftar voucher promo |
| 6 | `admin/06-reports.png` | `/admin/reports` | Laporan penjualan |

**3. Alur Kasir (3 shot)**

| # | Nama File | URL | Aksi |
|---|-----------|-----|------|
| 1 | `cashier/01-pos.png` | `/cashier/pos` | Input pesanan walk-in manual |
| 2 | `cashier/02-payments.png` | `/cashier/payments` | Antrian pembayaran tunai |
| 3 | `cashier/03-receipt.png` | `/admin/order/{id}/receipt` | Cetak struk |

**4. Alur Dapur (2 shot)** — pastikan ada 1 order berbayar agar antrian tampil.

| # | Nama File | URL | Aksi |
|---|-----------|-----|------|
| 1 | `kitchen/01-dashboard.png` | `/kitchen` | Antrian pesanan real-time |
| 2 | `kitchen/02-status.png` | `/kitchen` (klik update) | Ubah status: Proses → Siap |

**GIF alur utama (opsional):** rekam 1 take ~15 detik (mobile): scan QR → menu → tambah item → keranjang → checkout → Midtrans sandbox → sukses. Simpan sebagai `docs/screenshots/demo.gif`.

## Demo Live

> **Belum tersedia.** Isi link di bawah setelah deploy (panduan: [`docs/6_DEPLOYMENT_RENDER.md`](./docs/6_DEPLOYMENT_RENDER.md)).

🔗 [https://your-app.onrender.com](https://your-app.onrender.com)

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12 (PHP 8.3+) |
| Frontend | Blade + Bootstrap 5.3 + Tailwind CSS (via Vite) |
| Database | SQLite (dev) / MySQL / PostgreSQL (prod) |
| Payment | Midtrans Snap API |
| OTP/WhatsApp | Fonnte API |
| QR Code | SimpleSoftwareIO/simple-qrcode |
| Realtime | Pusher + Laravel Echo |
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
