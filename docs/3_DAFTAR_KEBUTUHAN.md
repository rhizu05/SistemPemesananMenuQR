# Daftar Kebutuhan - Sistem Pemesanan Restoran Berbasis QR (Dapoer Katendjo)

---

## 1. Elisitasi Tahap 1: Pengumpulan Kebutuhan

Berikut adalah daftar seluruh kebutuhan mentah yang dikumpulkan dari hasil wawancara dengan pemilik restoran, observasi operasional, dan studi literatur sistem sejenis.

| No | Deskripsi Kebutuhan Mentah | Sumber |
|----|----------------------------|--------|
| 1 | Sistem harus bisa menampilkan menu makanan dan minuman lewat HP pelanggan. | Wawancara |
| 2 | Pelanggan harus bisa memesan langsung dari HP tanpa panggil pelayan. | Wawancara |
| 3 | Harus ada gambar makanannya biar menarik. | Wawancara |
| 4 | Pelanggan harus scan QR Code dulu sebelum pesan biar ketahuan nomor mejanya. | Observasi |
| 5 | Sistem harus bisa hitung total harga otomatis. | Wawancara |
| 6 | Pembayaran maunya bisa pakai GoPay, OVO, atau transfer bank biar gak ribet kembalian. | Wawancara |
| 7 | Kalau pelanggan bayar tunai, kasir harus bisa konfirmasi manuall. | Observasi |
| 8 | Orang dapur harus langsung tahu kalau ada pesanan masuk. | Wawancara |
| 9 | Login pelanggan pakai nomor WA saja biar gampang, jangan pakai email ribet. | Wawancara |
| 10 | Ada kode OTP ke WA biar nomornya valid. | Studi Literatur |
| 11 | Admin bisa ganti harga dan stok menu sewaktu-waktu. | Wawancara |
| 12 | Admin bisa tambah menu baru atau hapus yang sudah tidak dijual. | Wawancara |
| 13 | Harus ada laporan penghasilan harian buat pemilik. | Wawancara |
| 14 | Kasir butuh fitur buat cetak struk pesanan. | Observasi |
| 15 | Kalau ada promo, bisa pakai kode voucher diskon. | Wawancara |
| 16 | Tampilan di HP harus bagus dan tidak berat (lemot). | Studi Literatur |
| 17 | Sistem harus aman, data pembeli tidak bocor. | Studi Literatur |
| 18 | Kasir bisa input pesanan manual buat orang yang tidak bawa HP (POS). | Observasi |
| 19 | Admin bisa melihat daftar pelanggan yang pernah pesan. | Wawancara |
| 20 | QR Code setiap meja harus beda-beda. | Observasi |

---

## 2. Elisitasi Tahap 2: Analisis Kebutuhan

Pada tahap ini, kebutuhan mentah diklasifikasikan menjadi **Fungsional (F)** dan **Non-Fungsional (NF)**. Kebutuhan yang duplikat atau ambigu disatukan.

| ID | Deskripsi Kebutuhan | Jenis | Keterangan |
|----|---------------------|-------|------------|
| 1 | Sistem menampilkan daftar menu dengan gambar, harga, dan deskripsi. | F | Modul Menu |
| 2 | Pelanggan dapat melakukan scanning QR Code untuk akses menu per meja. | F | Modul Akses |
| 3 | Sistem melakukan perhitungan total harga pesanan secara otomatis. | F | Modul Keranjang |
| 4 | Integrasi pembayaran digital (E-Wallet, VA, QRIS) via Midtrans. | F | Modul Pembayaran |
| 5 | Kasir dapat memverifikasi pembayaran tunai dan mencetak struk. | F | Modul Kasir |
| 6 | Dashboard real-time untuk Dapur menerima pesanan masuk. | F | Modul Dapur |
| 7 | Login pelanggan menggunakan Nomor WhatsApp dan OTP (Fonnte). | F | Modul Autentikasi |
| 8 | Admin dapat mengelola (CRUD) data kategori, menu, dan stok. | F | Modul Admin |
| 9 | Sistem menyediakan laporan penjualan periodik. | F | Modul Laporan |
| 10 | Fitur Voucher diskon untuk promosi. | F | Modul Voucher |
| 11 | Fitur POS (Point of Sale) untuk input pesanan manual oleh kasir. | F | Modul Kasir |
| 12 | Admin dapat meng-generate QR Code unik untuk setiap meja. | F | Modul Admin |
| 13 | Antarmuka sistem responsif (Mobile-First) dan ringan. | NF | Usability/Performance |
| 14 | Keamanan transaksi dan enkripsi data saldo/pembayaran. | NF | Security |

---

## 3. Elisitasi Tahap 3: Klasifikasi dan Prioritas (MDI)

Menggunakan metode **MDI** (Mandatory, Desirable, Inessential) untuk menentukan prioritas implementasi.
- **M (Mandatory)**: Wajib ada.
- **D (Desirable)**: Diinginkan, nilai tambah.
- **I (Inessential)**: Bisa ditunda/pelengkap.

| ID | Deskripsi Kebutuhan | Kategori | Alasan |
|----|---------------------|:--------:|--------|
| 1 | Login Pelanggan via OTP WhatsApp | **M** | Validasi identitas pelanggan wajib untuk keamanan order. |
| 2 | Scan QR & Tampilan Menu Digital | **M** | Core business process dari sistem "Pemesanan QR". |
| 3 | Keranjang & Checkout Pesanan | **M** | Proses utama transaksi. |
| 4 | Integrasi Payment Gateway (Midtrans) | **M** | Mendukung cashless society sesuai tujuan bisnis. |
| 5 | Dashboard Dapur Real-time | **M** | Menggantikan kertas order manual (efisiensi). |
| 6 | CRUD Menu & Kategori (Admin) | **M** | Manajemen data dasar restoran. |
| 7 | Verifikasi Pembayaran Tunai (Kasir) | **M** | Mengakomodir pelanggan yang belum bisa cashless. |
| 8 | Generate QR Code Meja | **M** | Kebutuhan operasional penanda meja. |
| 9 | Laporan Penjualan Otomatis | **D** | Sangat membantu owner, tapi operasional bisa jalan tanpanya. |
| 10 | Fitur Voucher/Diskon | **D** | Fitur marketing, bukan operasional kritis. |
| 11 | Sistem POS (Input Manual Kasir) | **D** | Backup jika pelanggan tidak bawa HP, tapi bukan flow utama QR. |
| 12 | Cetak Struk Fisik | **D** | Bukti fisik transaksi jika diminta pelanggan. |
| 13 | Manajemen Data Pelanggan | **I** | Fitur tambahan untuk CRM, tidak krusial di awal. |

---

## 4. Elisitasi Tahap 4: Analisis Kelayakan (TOE)

Menilai kelayakan berdasarkan aspek **Teknis (T)**, **Operasional (O)**, dan **Ekonomi (E)**.
- **High (H)**: Mudah/Murah/Dampak Tinggi (Layak).
- **Middle (M)**: Sedang.
- **Low (L)**: Sulit/Mahal/Dampak Rendah (Kurang Layak).

| ID | Deskripsi Kebutuhan | T | O | E | Kesimpulan |
|----|---------------------|:-:|:-:|:-:|:----------:|
| 1 | Login OTP WhatsApp | H | H | M | **Layak** (Biaya API WA terjangkau) |
| 2 | Scan QR & Menu Digital | H | H | H | **Sangat Layak** |
| 3 | Integrasi Midtrans (Payment) | M | H | H | **Layak** (Teknis butuh integrasi API) |
| 4 | Dashboard Dapur | H | H | H | **Sangat Layak** |
| 5 | CRUD Menu Admin | H | H | H | **Sangat Layak** |
| 6 | Laporan Pejualan | H | H | H | **Sangat Layak** |
| 7 | Fitur Voucher | H | M | H | **Layak** |
| 8 | Sistem POS Kasir | H | M | H | **Layak** |

> *Catatan: Hampir seluruh kebutuhan M dan D dinilai layak (High/Middle) untuk diimplementasikan mengingat teknologi yang digunakan (Laravel) sangat mendukung fitur tersebut.*

---

## 5. Elisitasi Tahap 5: Finalisasi Kebutuhan

Daftar kebutuhan final (**Final Requirements**) yang **PASTI** diimplementasikan dalam sistem Dapoer Katendjo (Versi 1.0).

| ID Req | Kode | Nama Fitur | Deskripsi Singkat |
|--------|------|------------|-------------------|
| **FR-01** | **AUTH** | **Login OTP & Multi-user** | Login pelangan via WA, Login Staff via Email. |
| **FR-02** | **MENU** | **Manajemen Menu Digital** | Tampilan menu responsif, CRUD Admin, Stok. |
| **FR-03** | **ORDER** | **QR Ordering System** | Scan QR, Cart, Checkout, Notes per item. |
| **FR-04** | **PAY** | **Payment Gateway** | Integrasi Midtrans untuk pembayaran digital otomatis. |
| **FR-05** | **KITCHEN** | **Kitchen Display System** | Dashboard status pesanan real-time untuk koki. |
| **FR-06** | **CASHIER** | **POS & Verification** | Verifikasi tunai, cetak struk, input manual. |
| **FR-07** | **ADMIN** | **QR Generator** | Pembuatan QR Code unik per meja. |
| **FR-08** | **REPORT** | **Laporan Transaksi** | Rekap pendapatan dan order harian/bulanan. |
| **FR-09** | **PROMO** | **Sistem Voucher** | Manajemen kode promo dan potongan harga. |

Semua kebutuhan final di atas telah disetujui untuk dikembangkan dan di-deploy pada tahap produksi.
