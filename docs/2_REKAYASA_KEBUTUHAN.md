# Rekayasa Kebutuhan - Sistem Pemesanan Restoran Berbasis QR (Dapoer Katendjo)

---

## 1. Identifikasi Aktor

Sistem ini memiliki 4 aktor utama yang berinteraksi langsung dengan aplikasi:

| ID Aktor | Aktor | Deskripsi Peran |
|----------|-------|-----------------|
| **ACT-01** | **Admin** | Pengguna yang memiliki hak akses penuh untuk mengelola master data (menu, kategori, meja), pengguna, voucher, dan melihat laporan penjualan. |
| **ACT-02** | **Kasir** | Pengguna yang bertanggung jawab untuk memverifikasi pembayaran (terutama tunai), memproses pesanan di tempat (POS), dan mencetak struk. |
| **ACT-03** | **Dapur (Koki)** | Pengguna yang bertugas menerima pesanan masuk, menyiapkan makanan, dan memperbarui status pesanan agar diketahui pelanggan/pelayan. |
| **ACT-04** | **Pelanggan** | Pengguna akhir yang melakukan pemindaian QR Code, melihat menu, memesan makanan, dan melakukan pembayaran secara mandiri. |

---

## 2. Kebutuhan Fungsional (Functional Requirements)

Kebutuhan fungsional mendefinisikan fitur dan layanan spesifik yang harus disediakan oleh sistem untuk setiap aktor.

### 2.1 Modul Autentikasi & Akun
| ID Req | Fitur | Deskripsi | Aktor |
|--------|-------|-----------|-------|
| **FR-AUTH-001** | Login Admin/Staf | Sistem memvalidasi email dan password untuk Admin, Kasir, dan Dapur. | Admin, Kasir, Dapur |
| **FR-AUTH-002** | Login Pelanggan (OTP) | Sistem memvalidasi nomor WhatsApp pelanggan dengan mengirimkan kode OTP via Fonnte API. | Pelanggan |
| **FR-AUTH-003** | Auto-Register Pelanggan | Sistem otomatis mendaftarkan pelanggan baru saat pertama kali login valid via OTP. | Sistem |
| **FR-AUTH-004** | Logout | Sistem mengakhiri sesi pengguna. | Semua Aktor |
| **FR-AUTH-005** | Profil Pelanggan | Pelanggan dapat melihat dan mengubah data profil (nama, email). | Pelanggan |

### 2.2 Modul Manajemen (Admin)
| ID Req | Fitur | Deskripsi | Aktor |
|--------|-------|-----------|-------|
| **FR-ADM-001** | Dashboard Admin | Menampilkan ringkasan statistik (total pesanan, pendapatan hari ini, menu terlaris). | Admin |
| **FR-ADM-002** | Kelola Kategori | Admin dapat menambah, mengubah, dan menghapus kategori menu. | Admin |
| **FR-ADM-003** | Kelola Menu | Admin dapat menambah, mengubah, menghapus menu (incl. foto, harga, stok, deskripsi). | Admin |
| **FR-ADM-004** | Generate QR Code | Admin dapat membuat QR Code unik untuk setiap nomor meja. | Admin |
| **FR-ADM-005** | Kelola Voucher | Admin dapat membuat voucher diskon dengan syarat tertentu (min. belanja, kuota). | Admin |
| **FR-ADM-006** | Kelola Pelanggan | Admin dapat melihat daftar pelanggan dan menonaktifkan akun jika perlu. | Admin |
| **FR-ADM-007** | Laporan Penjualan | Admin dapat melihat laporan transaksi berdasarkan periode. | Admin |

### 2.3 Modul Pemesanan (Pelanggan)
| ID Req | Fitur | Deskripsi | Aktor |
|--------|-------|-----------|-------|
| **FR-CUST-001** | Scan QR Akses | Sistem mendeteksi nomor meja dari URL hasil scan QR Code. | Pelanggan |
| **FR-CUST-002** | Lihat Menu Digital | Sistem menampilkan daftar menu berdasarkan kategori, lengkap dengan gambar dan harga. | Pelanggan |
| **FR-CUST-003** | Kelola Keranjang | Pelanggan dapat menambah menu ke keranjang, ubah kuantitas, dan tambah catatan (notes). | Pelanggan |
| **FR-CUST-004** | Input Voucher | Pelanggan dapat memasukkan kode voucher untuk mendapatkan potongan harga. | Pelanggan |
| **FR-CUST-005** | Checkout Pesanan | Sistem memproses pesanan dan menghitung total harga akhir. | Pelanggan |
| **FR-CUST-006** | Pembayaran Online | Sistem terintegrasi dengan Midtrans (QRIS, E-Wallet, VA) untuk pembayaran. | Pelanggan |
| **FR-CUST-007** | Tracking Pesanan | Pelanggan dapat melihat status pesanan secara real-time (Pending -> Preparing -> Ready). | Pelanggan |
| **FR-CUST-008** | Riwayat Pesanan | Pelanggan dapat melihat histori transaksi sebelumnya. | Pelanggan |

### 2.4 Modul Kasir & POS
| ID Req | Fitur | Deskripsi | Aktor |
|--------|-------|-----------|-------|
| **FR-CSH-001** | Dashboard Kasir | Menampilkan antrian pembayaran (khusus metode tunai) dan ringkasan harian. | Kasir |
| **FR-CSH-002** | Verifikasi Pembayaran | Kasir memverifikasi dan mengonfirmasi pembayaran tunai dari pelanggan. | Kasir |
| **FR-CSH-003** | Point of Sale (POS) | Kasir dapat menginput pesanan manual untuk pelanggan walk-in (tanpa QR). | Kasir |
| **FR-CSH-004** | Cetak Struk | Sistem dapat mencetak struk/nota pesanan untuk pelanggan. | Kasir |

### 2.5 Modul Dapur (Kitchen)
| ID Req | Fitur | Deskripsi | Aktor |
|--------|-------|-----------|-------|
| **FR-KIT-001** | Dashboard Dapur | Menampilkan daftar pesanan masuk yang sudah terbayar (Paid) secara real-time. | Dapur |
| **FR-KIT-002** | Detail Pesanan | Koki dapat melihat detail item, kuantitas, dan catatan khusus per menu. | Dapur |
| **FR-KIT-003** | Update Status | Koki memperbarui status pesanan: *Preparing* (Sedang dibuat) -> *Ready* (Siap saji) -> *Delivered* (Dihidangkan). | Dapur |

---

## 3. Kebutuhan Non-Fungsional (Non-Functional Requirements)

Kebutuhan non-fungsional mendefinisikan atribut kualitas, batasan, dan standar sistem.

### 3.1 Performance (Kinerja)
| ID Req | Parameter | Kebutuhan |
|--------|-----------|-----------|
| **NFR-PERF-01** | Response Time | Halaman menu harus dimuat dalam waktu kurang dari 3 detik pada koneksi 4G stabil. |
| **NFR-PERF-02** | Real-time Latency | Pembaruan status pesanan (Dapur -> Pelanggan) memiliki latensi maksimal 5 detik. |
| **NFR-PERF-03** | Concurrency | Sistem mampu menangani minimal 50 pesanan bersamaan tanpa degradasi performa signifikan. |

### 3.2 Security (Keamanan)
| ID Req | Parameter | Kebutuhan |
|--------|-----------|-----------|
| **NFR-SEC-01** | Enkripsi Data | Seluruh komunikasi data harus menggunakan protokol HTTPS. |
| **NFR-SEC-02** | Password Hashing | Password pengguna disimpan dalam bentuk hash (Bcrypt). |
| **NFR-SEC-03** | Role-Based Access | Implementasi Middleware untuk membatasi akses URL berdasarkan peran pengguna (Admin/Kasir/Pelanggan). |
| **NFR-SEC-04** | Payment Security | Transaksi pembayaran diverifikasi menggunakan Signature Key dari Midtrans. |

### 3.3 Usability (Ketergunaan)
| ID Req | Parameter | Kebutuhan |
|--------|-----------|-----------|
| **NFR-USE-01** | Responsive Design | Antarmuka Pelanggan harus optimal untuk layar Smartphone (Mobile First). |
| **NFR-USE-02** | Ease of Use | QR Code Scanner dapat langsung mengarahkan ke browser tanpa aplikasi tambahan (menggunakan kamera bawaan HP). |
| **NFR-USE-03** | Feedback | Sistem memberikan notifikasi visual (Toast/Alert) untuk setiap aksi (Berhasil/Gagal). |

### 3.4 Availability & Reliability (Ketersediaan & Keandalan)
| ID Req | Parameter | Kebutuhan |
|--------|-----------|-----------|
| **NFR-AVA-01** | Uptime | Target ketersediaan server 99% selama jam operasional restoran. |
| **NFR-REL-01** | Data Integrity | Stok menu berkurang otomatis secara akurat setelah pembayaran sukses. |

---

## 4. Aturan Bisnis (Business Rules)

1. **BR-01**: Pesanan baru hanya akan masuk ke Dashboard Dapur setelah status pembayaran **LUNAS (Paid)** atau diverifikasi Kasir.
2. **BR-02**: Pelanggan tidak dapat membatalkan pesanan yang statusnya sudah **Preparing** (Sedang dibuat).
3. **BR-03**: Voucher hanya dapat digunakan satu kali per transaksi dan harus memenuhi syarat minimum pembelian.
4. **BR-04**: QR Code meja bersifat unik; pesanan akan otomatis terhubung dengan nomor meja tersebut.
5. **BR-05**: Order yang belum dibayar dalam batas waktu tertentu (midtrans expiry) akan otomatis dibatalkan/kadaluarsa.

