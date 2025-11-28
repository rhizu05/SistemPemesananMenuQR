# 🔧 CARA SETUP WEBHOOK MIDTRANS & FIX BUG QRIS

## 🐛 MASALAH YANG TERJADI:
- Pembayaran QRIS sudah settlement di Midtrans
- Status di database masih "pending"
- Modal masih menunggu pembayaran
- Cek status manual tetap pending

## 🎯 PENYEBAB:
**Webhook Midtrans tidak sampai ke aplikasi** karena:
1. Aplikasi berjalan di localhost (Midtrans tidak bisa akses)
2. Webhook URL belum dikonfigurasi di Midtrans Dashboard

---

## ✅ SOLUSI: SETUP NGROK + WEBHOOK

### STEP 1: Jalankan Ngrok (Sudah Berjalan)

Ngrok sudah running. Cek URL ngrok:
1. Buka browser
2. Akses: http://localhost:4040
3. Catat URL HTTPS (contoh: https://abc123.ngrok.io)

### STEP 2: Setup Webhook di Midtrans Dashboard

1. **Login ke Midtrans Dashboard**
   - Buka: https://dashboard.sandbox.midtrans.com
   - Login dengan akun Anda

2. **Buka Settings → Configuration**
   - Klik menu "Settings" di sidebar
   - Pilih "Configuration"

3. **Set Payment Notification URL**
   - Cari bagian "Payment Notification URL"
   - Isi dengan: `https://[NGROK-URL]/midtrans/callback`
   - Contoh: `https://abc123.ngrok.io/midtrans/callback`
   - **PENTING:** Gunakan URL HTTPS dari ngrok!

4. **Set Finish Redirect URL (Opsional)**
   - Isi dengan: `https://[NGROK-URL]/admin/orders`
   - Ini untuk redirect setelah pembayaran

5. **Set Error Redirect URL (Opsional)**
   - Isi dengan: `https://[NGROK-URL]/admin/pos`

6. **Klik "Update"** untuk menyimpan

---

## 🧪 TESTING WEBHOOK

### Cara 1: Test dengan Mock Notification (Recommended)

1. **Buka Midtrans Dashboard**
2. **Klik menu "Transactions"**
3. **Cari transaksi QRIS yang sudah settlement**
4. **Klik "..." (titik tiga) di kanan**
5. **Pilih "Send Notification"**
6. **Pilih status: "settlement"**
7. **Klik "Send"**

Midtrans akan kirim webhook ke aplikasi Anda!

### Cara 2: Buat Transaksi Baru

1. Buat order QRIS baru di POS
2. Scan QR code dengan simulator
3. Bayar di simulator Midtrans
4. Webhook otomatis terkirim

---

## 📋 CEK APAKAH WEBHOOK BERHASIL

### 1. Cek Log Laravel

Buka file: `storage/logs/laravel.log`

Cari log seperti ini:
```
[2025-11-28 21:30:00] local.INFO: Midtrans Webhook Received
[2025-11-28 21:30:00] local.INFO: Midtrans Notification Parsed
[2025-11-28 21:30:00] local.INFO: Order found
[2025-11-28 21:30:00] local.INFO: Processing settlement
[2025-11-28 21:30:00] local.INFO: Order updated to paid
```

Jika ada log ini = **WEBHOOK BERHASIL!** ✅

### 2. Cek Database

```sql
SELECT order_number, payment_status, paid_at 
FROM orders 
WHERE payment_method = 'qris' 
ORDER BY created_at DESC 
LIMIT 5;
```

Jika `payment_status` = 'paid' = **BERHASIL!** ✅

### 3. Cek di Aplikasi

- Refresh halaman POS
- Modal QRIS seharusnya otomatis tutup
- Modal sukses muncul
- Status order jadi "paid"

---

## 🚨 TROUBLESHOOTING

### Webhook Tidak Sampai

**Cek 1: Ngrok Masih Running?**
```bash
# Cek di terminal, seharusnya ada output ngrok
# Atau buka http://localhost:4040
```

**Cek 2: URL Webhook Benar?**
- Harus HTTPS (bukan HTTP)
- Harus ada `/midtrans/callback` di akhir
- Contoh benar: `https://abc123.ngrok.io/midtrans/callback`
- Contoh salah: `http://abc123.ngrok.io/midtrans/callback` (HTTP)

**Cek 3: CSRF Protection**
- Route webhook sudah di-exclude dari CSRF
- Cek file `app/Http/Middleware/VerifyCsrfToken.php`
- Pastikan ada: `'/midtrans/callback'` di array `$except`

### Status Tetap Pending

**Solusi 1: Kirim Ulang Notifikasi**
- Buka Midtrans Dashboard
- Transactions → Pilih transaksi
- Send Notification → settlement

**Solusi 2: Update Manual (Temporary)**
```sql
UPDATE orders 
SET payment_status = 'paid', 
    paid_at = NOW(),
    amount_paid = total_amount,
    change_amount = 0
WHERE order_number = 'ORD-XXXXX';
```

---

## 📝 CHECKLIST

- [ ] Ngrok sudah running
- [ ] Catat URL HTTPS ngrok
- [ ] Login Midtrans Dashboard
- [ ] Set Payment Notification URL
- [ ] Klik Update/Save
- [ ] Test dengan Send Notification
- [ ] Cek log Laravel
- [ ] Cek database
- [ ] Cek aplikasi

---

## 🎯 LANGKAH SELANJUTNYA

Setelah webhook berhasil:

1. **Untuk Development:**
   - Tetap gunakan ngrok
   - Setiap restart ngrok, update URL di Midtrans

2. **Untuk Production (InfinityFree):**
   - Deploy ke InfinityFree
   - Update webhook URL ke URL production
   - Contoh: `https://akpl-pos.infinityfreeapp.com/midtrans/callback`

---

## 💡 TIPS

1. **Ngrok URL Berubah Setiap Restart**
   - Jika restart ngrok, URL berubah
   - Harus update di Midtrans Dashboard lagi

2. **Gunakan Ngrok Auth Token (Opsional)**
   - Daftar akun ngrok gratis
   - URL tidak berubah-ubah
   - Lebih stabil

3. **Cek Log Secara Berkala**
   - `storage/logs/laravel.log`
   - Untuk debugging masalah

---

Selamat mencoba! 🚀
