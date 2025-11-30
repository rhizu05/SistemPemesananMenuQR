# 📑 Daftar Kebutuhan (Elisitasi)

Dokumen ini merinci proses elisitasi kebutuhan sistem melalui 5 tahapan, mulai dari pengumpulan awal hingga finalisasi kebutuhan yang akan diimplementasikan.

---

## 1. Elisitasi Tahap 1: Pengumpulan Kebutuhan
*Daftar seluruh kebutuhan mentah yang dikumpulkan dari hasil wawancara, observasi, dan studi literatur.*

| No | Deskripsi Kebutuhan | Sumber |
| :--- | :--- | :--- |
| 1 | Sistem bisa scan QR code untuk lihat menu | Pelanggan |
| 2 | Bisa pesan makanan lewat HP tanpa panggil pelayan | Pelanggan |
| 3 | Bisa bayar pakai OVO/Gopay/QRIS langsung | Pelanggan |
| 4 | Ada fitur voucher diskon biar menarik | Pemilik |
| 5 | Admin bisa tambah/edit/hapus menu makanan | Admin |
| 6 | Admin bisa lihat laporan penjualan harian | Admin |
| 7 | Dapur punya layar khusus buat lihat pesanan masuk | Koki |
| 8 | Pesanan di dapur bisa diubah statusnya jadi "Siap" | Koki |
| 9 | Sistem harus cepat loadingnya, jangan lemot | Pelanggan |
| 10 | Bisa cetak struk pesanan di kasir | Kasir |
| 11 | Ada notifikasi WA kalau pesanan jadi (fitur tambahan) | Pelanggan |
| 12 | Bisa login pakai Google (fitur tambahan) | Pelanggan |
| 13 | Bisa reservasi meja dari rumah (fitur tambahan) | Pelanggan |
| 14 | Admin bisa atur stok menu biar gak dipesan kalau habis | Admin |
| 15 | Tampilan harus bagus dan mudah dipakai | Pemilik |

---

## 2. Elisitasi Tahap 2: Analisis Kebutuhan
*Mengklasifikasikan kebutuhan mentah menjadi Kebutuhan Fungsional (F) dan Non-Fungsional (NF), serta membuang yang duplikat atau ambigu.*

| No | Deskripsi Kebutuhan | Tipe | Keterangan |
| :--- | :--- | :--- | :--- |
| 1 | Sistem dapat memindai QR Code untuk menampilkan menu digital | F | Diterima |
| 2 | Sistem menyediakan fitur keranjang belanja dan checkout mandiri | F | Diterima |
| 3 | Sistem terintegrasi dengan Payment Gateway (QRIS) | F | Diterima |
| 4 | Sistem memiliki manajemen Voucher/Promo | F | Diterima |
| 5 | Admin dapat melakukan CRUD pada data Menu dan Kategori | F | Diterima |
| 6 | Sistem menyediakan Laporan Penjualan dan Analitik | F | Diterima |
| 7 | Tersedia Dashboard khusus untuk operasional Dapur (KDS) | F | Diterima |
| 8 | Staf dapur dapat memperbarui status pesanan (Update Status) | F | Diterima |
| 9 | Waktu muat halaman (load time) maksimal 3 detik | NF | Diterima (Performa) |
| 10 | Fitur cetak struk pada modul POS/Kasir | F | Diterima |
| 11 | Integrasi Notifikasi WhatsApp | F | **Ditunda** (Fase 2) |
| 12 | Login Social Media (Google) | F | **Ditunda** (Fase 2) |
| 13 | Sistem Reservasi Meja Online | F | **Ditolak** (Di luar scope) |
| 14 | Manajemen Stok Menu otomatis | F | Diterima |
| 15 | Antarmuka pengguna (UI) responsif dan *user-friendly* | NF | Diterima (Usability) |

---

## 3. Elisitasi Tahap 3: Klasifikasi & Prioritas (MDI)
*Menentukan prioritas kebutuhan berdasarkan metode MDI (Mandatory, Desirable, Inessential).*
*   **M (Mandatory)**: Kebutuhan mutlak, sistem tidak jalan tanpanya.
*   **D (Desirable)**: Kebutuhan yang diinginkan, tapi sistem masih bisa jalan tanpanya.
*   **I (Inessential)**: Kebutuhan yang tidak terlalu penting/bisa ditunda.

| No | Deskripsi Kebutuhan | M | D | I | Alasan |
| :--- | :--- | :---: | :---: | :---: | :--- |
| 1 | Scan QR & Menu Digital | √ | | | Fitur inti sistem |
| 2 | Keranjang & Checkout | √ | | | Proses bisnis utama |
| 3 | Integrasi Pembayaran QRIS | √ | | | Kebutuhan utama bisnis |
| 4 | Manajemen Voucher | | √ | | Fitur marketing (penting tapi bukan core) |
| 5 | CRUD Menu & Kategori | √ | | | Admin harus bisa kelola data |
| 6 | Laporan Penjualan | √ | | | Kebutuhan pemilik bisnis |
| 7 | Dashboard Dapur | √ | | | Operasional dapur |
| 8 | Update Status Pesanan | √ | | | Sinkronisasi data |
| 9 | Load time < 3 detik | | √ | | Kualitas layanan (NFR) |
| 10 | Cetak Struk Kasir | √ | | | Bukti transaksi fisik |
| 11 | Notifikasi WhatsApp | | | √ | Fitur tambahan (Nice to have) |
| 12 | Login Google | | | √ | Login biasa sudah cukup |
| 13 | Manajemen Stok | √ | | | Mencegah pesanan kosong |
| 14 | UI Responsif | √ | | | Akses via HP wajib responsif |

---

## 4. Elisitasi Tahap 4: Analisis Kelayakan (TOE)
*Menilai kelayakan kebutuhan berdasarkan aspek Teknis (T), Operasional (O), dan Ekonomi (E).*
*   **High (H)**: Mudah diimplementasikan / Biaya rendah / Dampak tinggi.
*   **Middle (M)**: Sedang.
*   **Low (L)**: Sulit / Biaya tinggi / Dampak rendah.

| No | Deskripsi Kebutuhan | T | O | E | Kesimpulan |
| :--- | :--- | :---: | :---: | :---: | :--- |
| 1 | Scan QR & Menu Digital | H | H | H | **Layak** |
| 2 | Keranjang & Checkout | H | H | H | **Layak** |
| 3 | Integrasi Pembayaran QRIS | M | H | M | **Layak** (Butuh API Midtrans) |
| 4 | Manajemen Voucher | H | M | H | **Layak** |
| 5 | CRUD Menu & Kategori | H | H | H | **Layak** |
| 6 | Laporan Penjualan | M | H | H | **Layak** |
| 7 | Dashboard Dapur | H | H | H | **Layak** |
| 8 | Update Status Pesanan | H | H | H | **Layak** |
| 9 | Load time < 3 detik | M | M | H | **Layak** (Optimasi server) |
| 10 | Cetak Struk Kasir | M | M | M | **Layak** (Butuh hardware printer) |
| 11 | Notifikasi WhatsApp | L | M | L | **Tidak Layak** (Biaya API mahal) |
| 12 | Login Google | M | M | M | **Tidak Layak** (Kompleksitas auth) |

---

## 5. Elisitasi Tahap 5: Finalisasi Kebutuhan
*Daftar kebutuhan final yang **PASTI** akan diimplementasikan dalam sistem (Final Requirements).*

| ID | Kebutuhan Fungsional Final | Aktor |
| :--- | :--- | :--- |
| **FR-01** | Sistem menampilkan menu digital via scan QR | Pelanggan |
| **FR-02** | Sistem mengelola keranjang belanja dan checkout | Pelanggan |
| **FR-03** | Sistem memproses pembayaran QRIS (Midtrans) & Tunai | Pelanggan/Kasir |
| **FR-04** | Sistem mengelola data Menu, Kategori, dan Stok | Admin |
| **FR-05** | Sistem mengelola Voucher Diskon dan validasinya | Admin/Pelanggan |
| **FR-06** | Sistem menampilkan antrian pesanan di dapur | Staf Dapur |
| **FR-07** | Sistem memperbarui status pesanan secara real-time | Staf Dapur |
| **FR-08** | Sistem menghasilkan laporan penjualan periodik | Admin |
| **FR-09** | Sistem mencetak struk bukti pembayaran | Admin |
| **FR-10** | Sistem memiliki antarmuka responsif (Mobile-First) | Semua |
