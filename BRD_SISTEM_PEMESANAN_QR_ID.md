# Dokumen Kebutuhan Bisnis (Business Requirements Document)
## Sistem Pemesanan Restoran Berbasis QR - Dapoer Katendjo

---

### Kontrol Dokumen

| Item | Detail |
|------|---------|
| **Nama Proyek** | Sistem Pemesanan Restoran Berbasis QR (Dapoer Katendjo) |
| **Versi Dokumen** | 2.0 |
| **Tanggal Dibuat** | November 2025 |
| **Terakhir Diperbarui** | 30 November 2025 |
| **Dibuat Oleh** | Tim Pengembang |
| **Status** | Implementasi Final + Enhancements |

---

## 1. Ringkasan Eksekutif

### 1.1 Tujuan
Dokumen ini menguraikan kebutuhan bisnis untuk Sistem Pemesanan Restoran Berbasis QR yang dikembangkan untuk Dapoer Katendjo, sebuah solusi digital komprehensif yang memungkinkan pemesanan tanpa kontak, pemrosesan pembayaran, dan manajemen dapur melalui teknologi QR code.

### 1.2 Ruang Lingkup Proyek
Sistem ini menyediakan solusi manajemen restoran end-to-end termasuk pemesanan pelanggan via QR code, fungsi kasir (POS), manajemen pesanan dapur, pemrosesan pembayaran real-time dengan QRIS, dan kemampuan pelaporan yang komprehensif.

### 1.3 Nilai Bisnis
- **Layanan Tanpa Kontak**: Mengurangi kontak fisik melalui pemesanan berbasis QR
- **Efisiensi Operasional**: Menyederhanakan alur pesanan dari pelanggan ke dapur
- **Integrasi Pembayaran**: Otomasi pemrosesan pembayaran dengan dukungan QRIS
- **Update Real-time**: Sinkronisasi status pesanan instan di semua role
- **Keputusan Berbasis Data**: Pelaporan dan analitik komprehensif

---

## 2. Tujuan Bisnis

### 2.1 Tujuan Utama
1. **Digitalisasi Proses Pemesanan**: Memungkinkan pelanggan memesan secara mandiri via QR code
2. **Meningkatkan Akurasi Pesanan**: Mengurangi kesalahan melalui manajemen pesanan digital
3. **Meningkatkan Pengalaman Pelanggan**: Menyediakan antarmuka pemesanan yang intuitif dan cepat
4. **Menyederhanakan Operasi Dapur**: Sentralisasi manajemen pesanan untuk staf dapur
5. **Mendukung Berbagai Metode Pembayaran**: Mendukung pembayaran tunai dan QRIS

### 2.2 Kriteria Kesuksesan
- 90% pesanan dilakukan via sistem QR code
- Waktu pemrosesan pesanan berkurang 40%
- Tingkat keberhasilan pemrosesan pembayaran > 95%
- Nol ketidaksesuaian pesanan antara pelanggan dan dapur
- Uptime sistem > 99%

---

## 3. Analisis Pemangku Kepentingan

### 3.1 Pemangku Kepentingan Utama & Role Sistem

Sistem memiliki **3 role pengguna yang berbeda**, dengan Admin yang menjalankan fungsi ganda:

| Pemangku Kepentingan | Role Sistem | Level Akses | Kebutuhan Utama |
|----------------------|-------------|-------------|-----------------|
| **Admin/Kasir** | `admin` | Akses Penuh | Operasi POS, manajemen menu, manajemen pesanan, pelaporan |
| **Staf Dapur** | `kitchen` | Dashboard Dapur Saja | Antrian pesanan, alur persiapan, update status |
| **Pelanggan** | `customer` atau `guest` | Menu & Pemesanan Saja | Pemesanan mudah, pembayaran, lacak pesanan |

**Catatan**: Role Admin menggabungkan fungsi administratif dan operasi kasir/POS. Tidak ada role "Kasir" terpisah dalam sistem.

### 3.2 Ringkasan Kebutuhan Pemangku Kepentingan
- **Admin/Kasir**: 
  - POS untuk pelanggan walk-in
  - Manajemen menu dan kategori lengkap
  - Monitoring dan konfirmasi pesanan
  - Pemrosesan pembayaran (Tunai/QRIS)
  - Manajemen akun pelanggan
  - Pembuatan QR code untuk meja
  - Laporan dan analitik bisnis
  
- **Staf Dapur**: 
  - Tampilan pesanan yang jelas berdasarkan status
  - Alur update status yang mudah
  - Antrian persiapan pesanan
  - Visibilitas instruksi khusus
  - Akses terbatas (hanya dapur)
  
- **Pelanggan**: 
  - Antarmuka pemesanan yang mobile-friendly
  - Berbagai opsi pembayaran
  - Pelacakan status pesanan
  - Riwayat pesanan (berbasis sesi untuk guest, permanen untuk pengguna terdaftar)

---

## 4. Kebutuhan Fungsional

### 4.1 Fitur Pelanggan

#### 4.1.1 Scan QR Code & Akses Menu
- **FR-C001**: Pelanggan dapat memindai QR code di meja untuk mengakses menu
- **FR-C002**: Sistem otomatis menangkap nomor meja dari QR code
- **FR-C003**: Menu menampilkan semua item yang tersedia dengan gambar, harga, dan deskripsi
- **FR-C004**: Item diorganisir berdasarkan kategori dengan navigasi scroll horizontal
- **FR-C005**: Item yang habis stok ditandai dengan jelas dan dinonaktifkan

#### 4.1.2 Keranjang Belanja & Pemesanan
- **FR-C006**: Tambahkan item ke keranjang dengan pemilihan jumlah
- **FR-C007**: Lihat ringkasan keranjang dengan perhitungan total real-time
- **FR-C008**: Modifikasi item keranjang (update jumlah atau hapus)
- **FR-C009**: Tambahkan instruksi khusus per item
- **FR-C010**: Fungsi kosongkan seluruh keranjang

#### 4.1.3 Checkout & Pembayaran
- **FR-C011**: Pilih metode pembayaran (Tunai atau QRIS)
- **FR-C012**: Untuk QRIS: Tampilkan QR code untuk pembayaran
- **FR-C013**: Auto-check status pembayaran setiap 5 detik
- **FR-C014**: Redirect ke halaman sukses pesanan setelah pembayaran
- **FR-C015**: Tampilkan nomor pesanan untuk referensi

#### 4.1.4 Pelacakan Pesanan
- **FR-C016**: Lihat status pesanan (Pending, Sedang Diproses, Siap)
- **FR-C017**: Akses riwayat pesanan untuk sesi (guest) atau akun (login)
- **FR-C018**: Lihat informasi pesanan detail termasuk item dan status pembayaran
- **FR-C019**: Riwayat pesanan guest hanya bertahan selama sesi
- **FR-C020**: Riwayat pesanan pengguna terdaftar disimpan permanen

#### 4.1.5 Manajemen Akun Pengguna
- **FR-C021**: Daftar akun dengan nomor telepon dan verifikasi OTP
- **FR-C022**: Login dengan email atau nomor telepon
- **FR-C023**: Lihat dan edit profil (hanya nama dan alamat)
- **FR-C024**: Nomor telepon terkunci setelah pendaftaran (tidak dapat diubah)

#### 4.1.6 Penggunaan Voucher
- **FR-C025**: Input kode voucher saat checkout
- **FR-C026**: Validasi kode voucher otomatis
- **FR-C027**: Lihat diskon yang diterapkan dari voucher
- **FR-C028**: Lihat syarat dan ketentuan voucher
- **FR-C029**: Hapus voucher yang sudah diaplikasikan
- **FR-C030**: Lihat riwayat penggunaan voucher (untuk pengguna terdaftar)

### 4.2 Fitur Admin

#### 4.2.1 Dashboard & Ringkasan
- **FR-A001**: Lihat pendapatan hari ini dan statistik pesanan
- **FR-A002**: Tampilkan jumlah pesanan pending, sedang diproses, dan siap
- **FR-A003**: Link akses cepat ke semua modul manajemen
- **FR-A004**: Update data real-time tanpa refresh halaman

#### 4.2.2 POS (Point of Sale / Kasir)
- **FR-A005**: Buat pesanan atas nama pelanggan
- **FR-A006**: Telusuri menu dengan filter kategori dan pencarian
- **FR-A007**: Tambahkan item ke pesanan dengan jumlah
- **FR-A008**: Pilih tipe pesanan (Dine-in, Takeaway, Delivery)
- **FR-A009**: Pilih metode pembayaran (Tunai, QRIS, Kartu)
- **FR-A010**: Untuk Tunai: Hitung kembalian otomatis
- **FR-A011**: Untuk QRIS: Generate dan tampilkan QR code
- **FR-A012**: Auto-poll status pembayaran QRIS
- **FR-A013**: Cetak struk setelah pembayaran berhasil

#### 4.2.3 Manajemen Menu
- **FR-A014**: Buat, baca, update, hapus item menu
- **FR-A015**: Upload gambar item menu
- **FR-A016**: Atur status ketersediaan menu
- **FR-A017**: Kelola level stok
- **FR-A018**: Assign item menu ke kategori
- **FR-A019**: Lihat semua menu dalam tabel yang dapat diurutkan

#### 4.2.4 Manajemen Kategori
- **FR-A020**: Buat, baca, update, hapus kategori
- **FR-A021**: Atur status aktif/tidak aktif kategori
- **FR-A022**: Lihat jumlah menu per kategori

#### 4.2.5 Manajemen Pesanan
- **FR-A023**: Lihat semua pesanan dengan filter (status, tanggal, metode pembayaran)
- **FR-A024**: Lihat informasi pesanan detail
- **FR-A025**: Update status pesanan secara manual
- **FR-A026**: Konfirmasi pembayaran tunai
- **FR-A027**: Lihat metode pembayaran dan jumlah yang dibayar
- **FR-A028**: Akses struk pesanan untuk pencetakan
- **FR-A029**: Cek status pembayaran QRIS

#### 4.2.6 Manajemen Pelanggan
- **FR-A030**: Lihat semua pelanggan terdaftar
- **FR-A031**: Lihat riwayat pesanan pelanggan
- **FR-A032**: Aktifkan/nonaktifkan akun pelanggan
- **FR-A033**: Hapus akun pelanggan
- **FR-A034**: Lihat informasi kontak pelanggan

#### 4.2.7 Manajemen QR Code
- **FR-A035**: Generate QR code untuk nomor meja
- **FR-A036**: Preview QR code sebelum print
- **FR-A037**: Cetak QR code untuk meja
- **FR-A038**: QR code otomatis menyimpan nomor meja

#### 4.2.8 Pelaporan & Analitik
- **FR-A039**: Lihat laporan penjualan harian
- **FR-A040**: Lihat laporan penjualan mingguan
- **FR-A041**: Lihat laporan penjualan bulanan
- **FR-A042**: Export laporan ke PDF
- **FR-A043**: Lihat menu terlaris
- **FR-A044**: Lihat pendapatan berdasarkan metode pembayaran
- **FR-A045**: Lihat trend jumlah pesanan

#### 4.2.9 Manajemen Voucher
- **FR-A046**: Buat voucher diskon baru (persentase atau nilai tetap)
- **FR-A047**: Edit voucher yang sudah ada
- **FR-A048**: Hapus voucher
- **FR-A049**: Aktifkan/nonaktifkan voucher
- **FR-A050**: Atur periode validitas voucher
- **FR-A051**: Atur kuota penggunaan total voucher
- **FR-A052**: Atur limit penggunaan per pengguna
- **FR-A053**: Atur minimum transaksi untuk voucher
- **FR-A054**: Atur maksimum diskon untuk voucher persentase
- **FR-A055**: Lihat statistik penggunaan voucher
- **FR-A056**: Lihat laporan voucher yang digunakan

### 4.3 Fitur Dapur

#### 4.3.1 Dashboard Pesanan
- **FR-K001**: Lihat pesanan berdasarkan tab status (Confirmed, Sedang Diproses, Siap)
- **FR-K002**: Tampilkan detail pesanan (item, jumlah, nomor meja)
- **FR-K003**: Tampilkan instruksi khusus dengan jelas
- **FR-K004**: Tampilkan waktu pembuatan pesanan

#### 4.3.2 Pemrosesan Pesanan
- **FR-K005**: Tandai pesanan sebagai "Sedang Diproses" saat mulai mengerjakan
- **FR-K006**: Tandai pesanan sebagai "Siap" saat selesai
- **FR-K007**: Auto-refresh daftar pesanan setiap 30 detik
- **FR-K008**: Notifikasi visual/audio untuk pesanan baru (enhancement masa depan)

#### 4.3.3 Kontrol Akses
- **FR-K009**: Staf dapur hanya dapat mengakses dashboard dapur
- **FR-K010**: Redirect otomatis jika mengakses halaman admin/pelanggan
- **FR-K011**: Tidak dapat melihat informasi pembayaran
- **FR-K012**: Tidak dapat memodifikasi menu atau pengaturan

---

## 5. Kebutuhan Non-Fungsional

### 5.1 Performa
- **NFR-001**: Waktu loading halaman < 3 detik pada koneksi 4G
- **NFR-002**: Mendukung 50 pengguna bersamaan
- **NFR-003**: Response query database < 500ms
- **NFR-004**: Response pengecekan pembayaran QRIS < 2 detik

### 5.2 Kegunaan (Usability)
- **NFR-005**: Desain responsive mobile (Bootstrap 5)
- **NFR-006**: UI intuitif dengan hierarki visual yang jelas
- **NFR-007**: Maksimal 3 klik untuk menyelesaikan aksi apapun
- **NFR-008**: Mendukung Bahasa Indonesia
- **NFR-009**: Rasio kontras warna yang accessible

### 5.3 Konsistensi Desain
- **NFR-010**: Skema warna konsisten di seluruh aplikasi
- **NFR-011**: Color Palette:
  - Primary Green: `#2A5C3F` (Status sukses, tombol utama, header)
  - Secondary Green: `#4A7F5A` (Aksi sekunder, status aman)
  - Accent Green: `#8FC69A` (Data kuantitatif, informasi pendukung)
  - Informational Blue: `#1976D2` (Statistik, informasi umum)
  - Warning Orange: `#FBC02D` (Peringatan, perlu perhatian)
  - Error Red: `#D32F2F` (Aksi destruktif, error)
- **NFR-012**: Tipografi konsisten (Inter, Roboto)
- **NFR-013**: Spacing and layout grid yang konsisten
- **NFR-014**: Efek hover dan transisi yang smooth (0.3s)
- **NFR-015**: Border radius konsisten (6px untuk button)

### 5.4 Keamanan
- **NFR-016**: Password hashing dengan bcrypt
- **NFR-017**: Autentikasi berbasis session
- **NFR-018**: Kontrol akses berbasis role (Admin, Kitchen, Customer)
- **NFR-019**: Proteksi CSRF pada semua form
- **NFR-020**: Pencegahan SQL injection via ORM
- **NFR-021**: Proteksi XSS via framework
- **NFR-022**: Integrasi pembayaran QRIS yang aman

### 5.5 Keandalan (Reliability)
- **NFR-023**: Target uptime 99%
- **NFR-024**: Penanganan error yang graceful
- **NFR-025**: Rollback transaksi database pada kegagalan
- **NFR-026**: Mekanisme retry webhook pembayaran

### 5.6 Maintainability
- **NFR-027**: Arsitektur kode modular (MVC)
- **NFR-028**: Komentar kode yang komprehensif
- **NFR-029**: Migrasi database untuk perubahan skema
- **NFR-030**: Logging untuk debugging dan monitoring

---

## 6. Fitur Sistem Berdasarkan Modul

### 6.1 Autentikasi & Otorisasi
- Login multi-metode (email/telepon + password)
- Verifikasi OTP untuk registrasi
- Fungsi reset password
- Manajemen session
- Proteksi middleware berbasis role

### 6.2 Sistem Menu & Kategori
- Organisasi kategori hierarkis
- Metadata item menu yang lengkap (nama, deskripsi, harga, gambar)
- Pelacakan stok dan status ketersediaan
- Filter dan pencarian dinamis

### 6.3 Sistem Keranjang & Checkout
- Keranjang berbasis session untuk guest
- Keranjang persistent untuk pengguna login
- Perhitungan harga real-time
- Perhitungan pajak (10%)
- Dukungan berbagai metode pembayaran

### 6.4 Integrasi Pembayaran
- **Pembayaran Tunai**: Konfirmasi manual dengan perhitungan kembalian
- **Pembayaran QRIS**: Integrasi Midtrans dengan auto-status checking
- Pelacakan status pembayaran (Pending, Lunas)
- Pembuatan struk

### 6.5 Sistem Manajemen Pesanan
- Siklus pesanan: Pending → Confirmed → Sedang Diproses → Siap → Selesai
- Auto-update status berdasarkan pembayaran
- Pembuatan nomor pesanan (ORD-YYYYMMDD-XXXXXX)
- Instruksi khusus per item

### 6.6 Manajemen Dapur
- Dashboard terisolasi untuk staf dapur
- Visualisasi antrian pesanan
- Alur update status
- Kemampuan auto-refresh

### 6.7 Sistem Pelaporan
- Laporan penjualan (harian, mingguan, bulanan)
- Analisis pendapatan berdasarkan metode pembayaran
- Laporan produk terlaris
- Chart trend pesanan
- Fungsi export PDF

### 6.8 Sistem Voucher & Promosi
- Manajemen voucher diskon (CRUD)
- Dua tipe voucher: persentase dan nilai tetap
- Validasi kode voucher otomatis
- Pelacakan penggunaan voucher per pesanan
- Limit penggunaan per pengguna
- Kuota total voucher
- Periode validitas voucher
- Minimum transaksi dan maksimum diskon
- Statistik dan laporan penggunaan voucher
- Integrasi dengan sistem checkout

---

## 7. Kebutuhan Teknis

### 7.1 Stack Teknologi
- **Backend Framework**: Laravel 12.x
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL 8.0+
- **Server**: Apache/Nginx + PHP 8.4+
- **Payment Gateway**: Midtrans (QRIS)
- **Session Storage**: File-based (dapat diupgrade ke Redis)

### 7.2 Integrasi Pihak Ketiga
- **Midtrans Core API**: Pemrosesan pembayaran QRIS
- **Bootstrap Icons**: Ikonografi UI
- **Animate.css**: Animasi UI
- **Chart.js**: Visualisasi pelaporan (masa depan)

### 7.3 Kebutuhan Deployment
- Hosting yang kompatibel dengan Laravel (InfinityFree, cPanel, VPS)
- PHP 8.4+ dengan ekstensi yang diperlukan
- Database MySQL dengan hak CREATE/ALTER
- HTTPS untuk transaksi aman
- Aksesibilitas webhook untuk callback pembayaran

### 7.4 Struktur Database
- **Users**: Data autentikasi dan profil
- **Categories**: Kategorisasi menu
- **Menus**: Katalog produk
- **Orders**: Informasi header pesanan
- **Order Items**: Line item pesanan
- **Vouchers**: Master data voucher
- **Voucher Usage**: Tracking penggunaan voucher per pesanan
- **Sessions**: Manajemen sesi pengguna

---

## 8. Alur Kerja Pengguna

### 8.1 Alur Pesanan Pelanggan (Guest)
1. Scan QR code di meja
2. Telusuri menu berdasarkan kategori
3. Tambahkan item ke keranjang
4. Lanjut ke checkout
5. Masukkan nama dan pilih metode pembayaran
6. Untuk QRIS: Scan QR code pembayaran
7. Tunggu konfirmasi pembayaran
8. Lihat halaman sukses pesanan
9. Lacak status pesanan
10. Terima pesanan saat siap

### 8.2 Alur POS Admin
1. Login ke panel admin
2. Navigasi ke POS
3. Pilih item menu
4. Masukkan detail pelanggan
5. Pilih metode pembayaran
6. Proses pembayaran (tunai/QRIS)
7. Cetak struk
8. Monitor pesanan di dashboard

### 8.3 Alur Pesanan Dapur
1. Login ke dashboard dapur
2. Lihat pesanan baru di tab "Confirmed"
3. Mulai menyiapkan pesanan (pindah ke "Sedang Diproses")
4. Selesai persiapan
5. Tandai sebagai "Siap"
6. Notifikasi pelanggan/waiter

---

## 9. Kebutuhan Data

### 9.1 Retensi Data
- **Data Pesanan**: Retensi permanen untuk pelaporan
- **Akun Pengguna**: Disimpan hingga dihapus manual
- **Session Keranjang**: Expiry 24 jam untuk guest
- **Logs**: Retensi 30 hari

### 9.2 Privasi Data
- Nomor telepon pelanggan terkunci setelah registrasi
- Informasi pembayaran tidak disimpan (hanya referensi)
- Admin tidak dapat melihat password pelanggan
- Penanganan data sesuai GDPR

---

## 10. Batasan & Asumsi

### 10.1 Batasan
- Lokasi restoran tunggal (bukan multi-tenant)
- Bahasa Indonesia saja
- Tidak ada integrasi pelacakan delivery eksternal
- Tidak ada manajemen inventori lanjutan selain stok
- **~~Tidak ada program loyalitas pelanggan~~** ⚠️ **TERATASI** - Sistem voucher telah diimplementasikan

### 10.2 Asumsi
- Konektivitas internet stabil di restoran
- Pelanggan memiliki smartphone dengan kamera
- Akun Midtrans sudah disetup dan diverifikasi
- Meja memiliki nomor unik
- Dapur memiliki perangkat display untuk dashboard

---

## 11. Pengembangan Masa Depan

### 11.1 Fitur Fase 2
- Dukungan multi-bahasa (Inggris, dll)
- Push notification untuk update pesanan
- **~~Sistem poin loyalitas pelanggan~~** ✅ **SELESAI - Sistem Voucher v1.0**
- Integrasi mitra delivery eksternal
- Modul manajemen inventori lanjutan
- Pelacakan waktu karyawan

### 11.2 Fitur Fase 3
- Dukungan multi-cabang
- Analitik lanjutan dan forecasting
- Sistem reservasi meja
- Feedback dan rating pelanggan
- Aplikasi mobile (native iOS/Android)
- Upgrade sistem voucher (auto-apply, tier customer, cashback)

---

## 12. Glosarium

| Istilah | Definisi |
|---------|----------|
| **BRD** | Business Requirements Document (Dokumen Kebutuhan Bisnis) |
| **POS** | Point of Sale (Sistem Kasir) |
| **QRIS** | Quick Response Code Indonesian Standard (pembayaran) |
| **OTP** | One-Time Password untuk verifikasi |
| **Guest** | Pelanggan yang tidak terdaftar |
| **Session** | Penyimpanan browser sementara untuk pengguna guest |
| **Voucher** | Kode diskon yang dapat digunakan pelanggan untuk mengurangi total pembayaran |

---

## 13. Persetujuan

| Role | Nama | Tanda Tangan | Tanggal |
|------|------|--------------|---------|
| **Pemilik Proyek** | __________ | __________ | ___/___/___ |
| **Technical Lead** | __________ | __________ | ___/___/___ |
| **Business Analyst** | __________ | __________ | ___/___/___ |

---

### Riwayat Revisi Dokumen

| Versi | Tanggal | Penulis | Perubahan |
|-------|---------|---------|-----------|
| 1.0 | Nov 2025 | Tim Pengembang | BRD lengkap awal (Bahasa Indonesia) |
| 2.0 | 30 Nov 2025 | Tim Pengembang | - Penambahan fitur sistem voucher & promosi<br>- Penambahan color palette & design system<br>- Update UI/UX improvements (POS, Customer Management)<br>- Perbaikan form validation<br>- Penambahan error handling & logging |

---

**AKHIR DOKUMEN**
