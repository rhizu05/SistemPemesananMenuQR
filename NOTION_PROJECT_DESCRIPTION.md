# 🚀 Deskripsi Proyek: Sistem Pemesanan QR Dapoer Katendjo

## 💡 Latar Belakang
Di era digital yang serba cepat, efisiensi dan pengalaman pelanggan adalah kunci keberhasilan bisnis kuliner. **Dapoer Katendjo**, sebagai restoran yang berkembang, menghadapi tantangan operasional klasik: antrian kasir yang panjang saat jam sibuk, keterbatasan staf pelayan untuk mencatat pesanan, dan potensi kesalahan komunikasi antara pelanggan dan dapur.

Sistem manual yang ada membatasi potensi pertumbuhan restoran dan mengurangi kepuasan pelanggan. Oleh karena itu, diperlukan solusi teknologi yang dapat mengotomatisasi alur pemesanan, mempercepat transaksi, dan memberikan pengalaman makan yang modern.

---

## 🌟 Solusi: QR Ordering System
Kami mengembangkan **Sistem Pemesanan Restoran Berbasis QR** yang komprehensif. Sistem ini mengubah meja restoran menjadi titik layanan mandiri (*self-service point*).

### Bagaimana Cara Kerjanya?
1.  **Scan**: Pelanggan memindai QR Code unik yang ada di meja menggunakan smartphone.
2.  **Order**: Menu digital terbuka. Pelanggan memilih makanan, menambah catatan (opsional), dan memasukkan kode voucher promo.
3.  **Pay**: Pelanggan membayar langsung via **QRIS** (OVO, GoPay, Dana, dll) atau memilih bayar Tunai di kasir.
4.  **Serve**: Pesanan otomatis muncul di layar dapur. Pelanggan bisa memantau status pesanan (Diproses -> Siap) dari HP mereka.

---

## 🎯 Tujuan Utama Proyek

### 1. Efisiensi Tanpa Kompromi ⚡
Mengurangi waktu tunggu pelanggan secara drastis. Tidak perlu lagi menunggu pelayan datang membawa menu atau mencatat pesanan. Pesanan langsung masuk ke sistem dapur detik itu juga.

### 2. Zero Error Accuracy ✅
Menghilangkan kesalahan *human error* seperti salah catat pesanan atau salah hitung kembalian. Apa yang diklik pelanggan, itulah yang diterima dapur dan sistem kasir.

### 3. Contactless & Modern 📱
Memberikan rasa aman dan nyaman dengan meminimalkan kontak fisik (terutama pasca-pandemi) dan mendukung gaya hidup *cashless society* dengan integrasi pembayaran digital.

### 4. Strategi Marketing Cerdas 🏷️
Meningkatkan loyalitas pelanggan melalui **Sistem Voucher Digital**. Restoran dapat dengan mudah membuat promo (misal: "Diskon 20% Hari Ini") untuk menarik lebih banyak transaksi.

---

## 🏗️ Arsitektur & Teknologi

Sistem ini dibangun dengan standar industri terkini untuk menjamin performa, keamanan, dan skalabilitas.

*   **Core Framework**: Laravel 10 (PHP) - *Robust & Secure Backend*
*   **Database**: MySQL 8.0 - *Reliable Data Storage*
*   **Frontend**: Bootstrap 5 + Blade - *Responsive Mobile-First UI*
*   **Payment Gateway**: Midtrans API - *Secure QRIS Transactions*
*   **Server Environment**: Laragon (Local Dev) / Linux (Production)

---

## 📊 Modul Utama

| Modul | Fungsi Kunci |
| :--- | :--- |
| **📱 Client App** | Menu Digital, Keranjang Belanja, Input Voucher, Tracking Pesanan |
| **🖥️ Admin Dashboard** | Manajemen Menu & Stok, Laporan Penjualan, Manajemen Voucher, User Management |
| **💰 POS (Kasir)** | Kasir Manual (Walk-in), Konfirmasi Pembayaran Tunai, Cetak Struk |
| **👨‍🍳 Kitchen Display** | Antrian Pesanan Real-time, Update Status Masakan |

---

## 📈 Dampak Bisnis (Expected Impact)
*   **40%** Lebih cepat dalam pemrosesan pesanan.
*   **95%** Akurasi data pesanan dan stok.
*   **Peningkatan Omzet** melalui rotasi meja yang lebih cepat dan penggunaan voucher promosi.
