# Kelompok 4 - Sistem Pemesanan Restoran Berbasis QR (Dapoer Katendjo)

---

## 🔍 Deskripsi Sistem

**Sistem Pemesanan Menu Restoran Berbasis QR Code** adalah aplikasi web yang memungkinkan pelanggan restoran **Dapoer Katendjo** melakukan pemesanan makanan dan minuman secara mandiri dengan memindai kode QR yang tersedia di setiap meja. Sistem ini mengintegrasikan proses pemesanan, pembayaran digital, dan manajemen dapur dalam satu platform terpadu.

### Fitur Utama

| Fitur | Keterangan |
|-------|------------|
| **Pemesanan via QR** | Pelanggan scan QR di meja → melihat menu → pesan → bayar |
| **Multi-Role** | Admin, Kasir, Dapur, dan Pelanggan dengan dashboard masing-masing |
| **Pembayaran Digital** | Integrasi Midtrans (GoPay, OVO, DANA, QRIS, Transfer Bank, Kartu Kredit) |
| **Lacak Status Pesanan** | Pantau status pesanan dari dapur hingga siap diantar |
| **Manajemen Menu & Kategori** | CRUD lengkap untuk menu dan kategori makanan/minuman |
| **Sistem Voucher/Diskon** | Pembuatan dan manajemen voucher promo |
| **QR Code Generator** | Generate QR code untuk setiap meja secara otomatis |
| **Point of Sale (POS)** | Sistem kasir untuk pesanan langsung di tempat |
| **Laporan Penjualan** | Dashboard analytics dan reporting |
| **Manajemen Pelanggan** | Data pelanggan dan riwayat pesanan |

### Teknologi

| Komponen | Teknologi |
|----------|-----------|
| **Backend Framework** | Laravel 12 (PHP 8.3+) |
| **Frontend** | Blade Template Engine + Bootstrap 5.3 |
| **JavaScript** | Vanilla JavaScript (ES6+) |
| **Database** | SQLite (Development) / MySQL / PostgreSQL (Production) |
| **Payment Gateway** | Midtrans Snap API |
| **OTP Service** | Fonnte WhatsApp API |
| **QR Code** | SimpleSoftwareIO/simple-qrcode |
| **Build Tool** | Vite |
| **Icon** | Bootstrap Icons |

### Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                         PELANGGAN                                │
│  (Scan QR → Lihat Menu → Pesan → Bayar via Midtrans)           │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      LARAVEL APPLICATION                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐        │
│  │  Admin   │  │  Kasir   │  │  Dapur   │  │ Customer │        │
│  │Dashboard │  │  (POS)   │  │Dashboard │  │  (Menu)  │        │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘        │
└─────────────────────────────────────────────────────────────────┘
                              │
          ┌───────────────────┼───────────────────┐
          ▼                   ▼                   ▼
    ┌──────────┐        ┌──────────┐        ┌──────────┐
    │ Midtrans │        │  Fonnte  │        │ Database │
    │ Payment  │        │WhatsApp  │        │  SQLite  │
    └──────────┘        └──────────┘        └──────────┘
```

---

## 💼 Ringkasan Bisnis

### Profil Klien

| Aspek | Keterangan |
|-------|------------|
| **Nama Usaha** | Dapoer Katendjo |
| **Jenis Usaha** | Restoran / Rumah Makan |
| **Lokasi** | [Sesuaikan] |

### Latar Belakang Masalah

Restoran Dapoer Katendjo mengalami beberapa kendala operasional:

- ⏳ **Waktu tunggu lama** - Pelanggan harus menunggu pelayan untuk memesan
- ❌ **Kesalahan pencatatan** - Pesanan manual rawan salah catat
- 💵 **Ketergantungan tunai** - Belum ada sistem pembayaran digital terintegrasi
- 📊 **Sulit tracking data** - Tidak ada laporan penjualan otomatis
- 🔄 **Komunikasi terhambat** - Pesanan dari meja ke dapur tidak efisien

### Solusi yang Ditawarkan

Sistem pemesanan berbasis QR Code yang memungkinkan:

1. **Self-service ordering** - Pelanggan pesan sendiri melalui smartphone
2. **Paperless & cashless** - Menu digital dan pembayaran online
3. **Direct to kitchen** - Pesanan langsung masuk ke display dapur
4. **Automated reporting** - Laporan penjualan otomatis
5. **Customer data** - Riwayat pelanggan untuk marketing

### Manfaat Bisnis

| Untuk Restoran | Untuk Pelanggan |
|----------------|-----------------|
| ✅ Operasional lebih cepat | ✅ Tidak perlu menunggu pelayan |
| ✅ Mengurangi kesalahan pesanan | ✅ Menu lengkap dengan gambar & harga |
| ✅ Laporan penjualan real-time | ✅ Pembayaran praktis (e-wallet, QRIS) |
| ✅ Efisiensi tenaga kerja | ✅ Tracking status pesanan live |
| ✅ Data pelanggan tersimpan | ✅ Promo & voucher digital |

### Stakeholder

| Role | Kepentingan | Akses |
|------|-------------|-------|
| **Owner/Admin** | Monitoring & manajemen keseluruhan | Dashboard admin lengkap |
| **Kasir** | Verifikasi pembayaran & cetak struk | POS & verifikasi pembayaran |
| **Koki/Dapur** | Menerima & memproses pesanan | Dashboard dapur real-time |
| **Pelanggan** | Memesan & membayar | Menu digital via QR |

---

## 📄 Halaman Dokumentasi

1. **Sesi Wawancara**
2. **Deskripsi Project**
3. **Rekayasa Kebutuhan**
4. **Daftar Kebutuhan**
5. **Pemodelan Berorientasi Objek**
