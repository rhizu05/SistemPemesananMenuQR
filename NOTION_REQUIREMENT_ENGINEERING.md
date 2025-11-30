# 📋 Rekayasa Kebutuhan (Requirement Engineering)

Halaman ini mendokumentasikan analisis kebutuhan sistem secara mendalam, mencakup kebutuhan fungsional (FR) dan non-fungsional (NFR) untuk memastikan sistem yang dibangun sesuai dengan tujuan bisnis Dapoer Katendjo.

---

## 👤 Analisis Pemangku Kepentingan (Stakeholders)

| Aktor | Peran & Tanggung Jawab | Kebutuhan Utama |
| :--- | :--- | :--- |
| **Pelanggan** | Pengguna akhir yang memesan makanan | Kemudahan akses menu, pemesanan cepat, pembayaran aman. |
| **Admin / Kasir** | Pengelola operasional restoran | Manajemen menu, monitoring pesanan, laporan penjualan akurat. |
| **Staf Dapur** | Penanggung jawab produksi makanan | Informasi pesanan jelas, notifikasi real-time, manajemen antrian. |
| **Pemilik Bisnis** | Pengambil keputusan strategis | Data analitik penjualan, efisiensi biaya, kepuasan pelanggan. |

---

## ⚙️ Kebutuhan Fungsional (Functional Requirements)

Fitur-fitur spesifik yang harus dapat dilakukan oleh sistem.

### 📱 Modul Pelanggan (Front-end)
*   **FR-C01**: Sistem harus dapat memindai QR Code dan mengidentifikasi nomor meja secara otomatis.
*   **FR-C02**: Sistem harus menampilkan menu digital dengan gambar, harga, dan kategori yang *scrollable*.
*   **FR-C03**: Pelanggan dapat menambahkan item ke keranjang, mengubah jumlah, dan menambah catatan khusus.
*   **FR-C04**: **Sistem Voucher**: Pelanggan dapat memasukkan kode voucher untuk mendapatkan potongan harga (validasi otomatis).
*   **FR-C05**: Sistem harus mendukung pembayaran via **QRIS (Midtrans)** dan **Tunai**.
*   **FR-C06**: Pelanggan dapat memantau status pesanan secara *real-time* (Pending -> Diproses -> Siap).

### 🖥️ Modul Admin & POS (Back-end)
*   **FR-A01**: Admin dapat mengelola (CRUD) data Menu, Kategori, dan Stok.
*   **FR-A02**: **Manajemen Voucher**: Admin dapat membuat voucher diskon (Persen/Nominal), mengatur kuota, dan masa berlaku.
*   **FR-A03**: **POS Manual**: Admin dapat membuat pesanan untuk pelanggan *walk-in* atau *takeaway*.
*   **FR-A04**: Admin dapat memverifikasi pembayaran tunai dari pelanggan.
*   **FR-A05**: Sistem harus menyediakan Laporan Penjualan (Harian/Bulanan) dan Laporan Menu Terlaris.
*   **FR-A06**: Admin dapat mencetak QR Code unik untuk setiap meja.

### 🍳 Modul Dapur (Kitchen Display)
*   **FR-K01**: Sistem harus menampilkan daftar pesanan masuk secara *real-time* (tanpa refresh manual).
*   **FR-K02**: Staf dapur dapat mengubah status pesanan menjadi "Sedang Diproses" dan "Siap Disajikan".
*   **FR-K03**: Tampilan dapur harus memisahkan pesanan berdasarkan status untuk manajemen antrian yang efektif.

---

## 🛡️ Kebutuhan Non-Fungsional (Non-Functional Requirements)

Kualitas, batasan, dan standar performa sistem.

### 1. Performa (Performance)
*   **NFR-P01**: Waktu muat halaman (Load Time) menu digital harus < 3 detik pada jaringan 4G.
*   **NFR-P02**: Sistem harus mampu menangani minimal 50 pesanan bersamaan (concurrent users).
*   **NFR-P03**: Sinkronisasi status pesanan antar modul (Pelanggan-Dapur-Admin) harus terjadi dalam < 5 detik.

### 2. Keamanan (Security)
*   **NFR-S01**: Semua password pengguna harus dienkripsi menggunakan **Bcrypt**.
*   **NFR-S02**: Transaksi pembayaran harus menggunakan protokol aman (HTTPS) dan standar keamanan Midtrans.
*   **NFR-S03**: Akses ke halaman Admin dan Dapur harus dibatasi dengan autentikasi (Login).
*   **NFR-S04**: Perlindungan terhadap serangan umum web (CSRF, XSS, SQL Injection).

### 3. Antarmuka Pengguna (Usability)
*   **NFR-U01**: Desain antarmuka pelanggan harus **Mobile-First** dan responsif di berbagai ukuran layar HP.
*   **NFR-U02**: Alur pemesanan harus dapat diselesaikan maksimal dalam 5 langkah (Scan -> Pilih -> Keranjang -> Voucher -> Bayar).
*   **NFR-U03**: Menggunakan skema warna yang konsisten dan kontras yang cukup untuk keterbacaan.

### 4. Ketersediaan (Availability)
*   **NFR-A01**: Sistem harus tersedia 99% selama jam operasional restoran.
*   **NFR-A02**: Sistem harus memiliki penanganan error yang baik (menampilkan pesan ramah pengguna saat terjadi kesalahan).

---

## 📝 Batasan Sistem (Constraints)
1.  Sistem ini berbasis web (Web App), bukan aplikasi native Android/iOS, untuk mempermudah akses tanpa instalasi.
2.  Sistem membutuhkan koneksi internet aktif untuk memproses pembayaran QRIS dan sinkronisasi data.
3.  Bahasa antarmuka utama adalah **Bahasa Indonesia**.
