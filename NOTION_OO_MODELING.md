# 🧩 Pemodelan Berorientasi Objek (UML)

Halaman ini mendokumentasikan perancangan sistem menggunakan diagram UML (*Unified Modeling Language*) untuk memvisualisasikan struktur, perilaku, dan interaksi dalam sistem.

---

## 1. Use Case Diagram
Diagram ini menggambarkan interaksi antara aktor (pengguna) dengan fungsionalitas sistem.

### 🎭 Aktor
*   **Pelanggan**: Pengguna yang memesan makanan via QR Code.
*   **Admin / Kasir**: Pengelola sistem, menu, dan pembayaran tunai.
*   **Staf Dapur**: Penanggung jawab produksi pesanan.

### 🖼️ Diagram
```mermaid
graph LR
    subgraph Actors
        C[👤 Pelanggan]
        A[👤 Admin / Kasir]
        K[👤 Staf Dapur]
    end

    subgraph System["Sistem Pemesanan QR"]
        UC1(Scan QR Code)
        UC2(Lihat Menu Digital)
        UC3(Kelola Keranjang)
        UC4(Checkout & Voucher)
        UC5(Bayar QRIS/Tunai)
        UC6(Lacak Status Pesanan)
        
        UC7(Login Admin)
        UC8(Manajemen Menu & Stok)
        UC9(Manajemen Voucher)
        UC10(POS - Kasir Manual)
        UC11(Konfirmasi Pembayaran)
        UC12(Laporan Penjualan)
        
        UC13(Lihat Antrian Pesanan)
        UC14(Update Status Pesanan)
    end

    C --> UC1
    C --> UC2
    C --> UC3
    C --> UC4
    C --> UC5
    C --> UC6

    A --> UC7
    A --> UC8
    A --> UC9
    A --> UC10
    A --> UC11
    A --> UC12

    K --> UC13
    K --> UC14
    
    %% Styling
    classDef actor fill:#f9f,stroke:#333,stroke-width:2px;
    classDef usecase fill:#fff,stroke:#333,stroke-width:1px,rx:20,ry:20;
    
    class C,A,K actor;
    class UC1,UC2,UC3,UC4,UC5,UC6,UC7,UC8,UC9,UC10,UC11,UC12,UC13,UC14 usecase;
```

### 📝 Deskripsi Use Case Utama
| Use Case | Aktor | Deskripsi |
| :--- | :--- | :--- |
| **Scan QR Code** | Pelanggan | Aktor memindai kode QR di meja untuk membuka aplikasi web. |
| **Checkout & Voucher** | Pelanggan | Aktor memfinalisasi pesanan dan memasukkan kode voucher diskon. |
| **Bayar (QRIS/Tunai)** | Pelanggan | Aktor memilih metode bayar. Jika QRIS, sistem generate kode bayar otomatis. |
| **Manajemen Menu** | Admin | Aktor menambah, mengedit, atau menghapus data menu dan stok. |
| **Update Status** | Staf Dapur | Aktor mengubah status pesanan dari "Pending" -> "Diproses" -> "Siap". |

---

## 2. Activity Diagram
Diagram ini menggambarkan alur kerja (workflow) dari proses bisnis utama: **Pemesanan oleh Pelanggan**.

```mermaid
flowchart TD
    Start([Mulai]) --> Scan[Scan QR Code Meja]
    Scan --> ViewMenu[Lihat Menu & Kategori]
    ViewMenu --> AddCart[Tambah Item ke Keranjang]
    AddCart --> CheckVoucher{Punya Voucher?}
    CheckVoucher -- Ya --> ApplyVoucher[Input & Validasi Voucher]
    CheckVoucher -- Tidak --> Checkout[Lanjut ke Checkout]
    ApplyVoucher --> Checkout
    
    Checkout --> InputData[Input Nama Pelanggan]
    InputData --> ChoosePay{Pilih Metode Bayar}
    
    ChoosePay -- QRIS --> GenQR[Generate QR Code Midtrans]
    GenQR --> PayQR[Pelanggan Scan & Bayar]
    PayQR --> CheckStatus[Sistem Cek Status Pembayaran]
    CheckStatus -- Sukses --> Paid[Status: Lunas]
    
    ChoosePay -- Tunai --> WaitCash[Tunggu Konfirmasi Kasir]
    WaitCash --> AdminConfirm[Admin Terima Uang & Konfirmasi]
    AdminConfirm --> Paid
    
    Paid --> CreateOrder[Pesanan Masuk Dapur]
    CreateOrder --> Track[Pelanggan Lacak Status]
    Track --> End([Selesai])
```

---

## 3. Sequence Diagram
Diagram ini menggambarkan interaksi detail antar objek untuk skenario: **Pembayaran via QRIS**.

```mermaid
sequenceDiagram
    participant C as Pelanggan
    participant FE as Frontend (Web)
    participant BE as Backend (Laravel)
    participant DB as Database
    participant MT as Midtrans (Payment Gateway)
    participant K as Dashboard Dapur

    C->>FE: Klik "Proses Pesanan"
    FE->>BE: POST /order (Items, Voucher, QRIS)
    BE->>DB: Save Order (Status: Pending)
    BE->>MT: Request Snap Token/QR
    MT-->>BE: Return QR Code URL
    BE-->>FE: Return Order ID & QR Image
    
    FE->>C: Tampilkan QR Code
    C->>MT: Scan & Bayar via E-Wallet
    
    par Async Process
        MT->>BE: Webhook (Transaction Status: settlement)
        BE->>DB: Update Order (Payment: Paid, Status: Confirmed)
        BE->>K: Real-time Update (New Order)
    and Polling
        FE->>BE: Check Payment Status
        BE-->>FE: Status: Paid
    end
    
    FE->>C: Tampilkan Halaman "Pesanan Berhasil"
```

---

## 4. Class Diagram
Diagram ini menggambarkan struktur database dan relasi antar entitas (Model) dalam sistem.

```mermaid
classDiagram
    class User {
        +id: int
        +name: string
        +email: string
        +role: enum
    }

    class Category {
        +id: int
        +name: string
        +image: string
    }

    class Menu {
        +id: int
        +category_id: int
        +name: string
        +price: decimal
        +stock: int
        +is_available: boolean
    }

    class Voucher {
        +id: int
        +code: string
        +type: enum
        +amount: decimal
        +quota: int
        +start_date: datetime
        +end_date: datetime
    }

    class Order {
        +id: int
        +order_number: string
        +customer_name: string
        +table_number: string
        +total_amount: decimal
        +status: enum
        +payment_method: enum
        +payment_status: enum
    }

    class OrderItem {
        +id: int
        +order_id: int
        +menu_id: int
        +quantity: int
        +price: decimal
        +notes: text
    }

    class VoucherUsage {
        +id: int
        +voucher_id: int
        +order_id: int
        +discount_amount: decimal
    }

    Category "1" -- "*" Menu : has
    Order "1" -- "*" OrderItem : contains
    Menu "1" -- "*" OrderItem : in
    Voucher "1" -- "*" VoucherUsage : used_in
    Order "1" -- "0..1" VoucherUsage : applies
```
