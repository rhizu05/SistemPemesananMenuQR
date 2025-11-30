# UML Diagrams - Sistem Pemesanan Restoran Berbasis QR (Dapoer Katendjo)

Dokumen ini berisi diagram UML utama untuk sistem: **Use Case Diagram**, **Activity Diagram**, dan **Sequence Diagram**.

---

## 1. Use Case Diagram

Diagram ini menggambarkan interaksi antara 3 aktor utama (Pelanggan, Admin, Staf Dapur) dengan fitur-fitur sistem.

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
        UC4(Checkout Pesanan)
        UC5(Gunakan Voucher)
        UC6(Bayar QRIS/Tunai)
        UC7(Lacak Status Pesanan)
        
        UC8(Login Admin)
        UC9(Manajemen Menu & Kategori)
        UC10(Manajemen Voucher)
        UC11(POS - Kasir Manual)
        UC12(Konfirmasi Pembayaran Tunai)
        UC13(Lihat Laporan)
        
        UC14(Lihat Antrian Pesanan)
        UC15(Update Status Pesanan)
    end

    C --> UC1
    C --> UC2
    C --> UC3
    C --> UC4
    C --> UC5
    C --> UC6
    C --> UC7

    A --> UC8
    A --> UC9
    A --> UC10
    A --> UC11
    A --> UC12
    A --> UC13

    K --> UC14
    K --> UC15
    
    %% Styling
    classDef actor fill:#f9f,stroke:#333,stroke-width:2px;
    classDef usecase fill:#fff,stroke:#333,stroke-width:1px,rx:20,ry:20;
    
    class C,A,K actor;
    class UC1,UC2,UC3,UC4,UC5,UC6,UC7,UC8,UC9,UC10,UC11,UC12,UC13,UC14,UC15 usecase;
```

---

## 2. Activity Diagrams

### A. Alur Pemesanan Pelanggan (Customer Ordering Flow)

Menggambarkan langkah-langkah pelanggan dari scan QR hingga pesanan diterima.

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
    
    Paid --> CreateOrder[Pesanan Dibuat & Masuk Dapur]
    CreateOrder --> Track[Pelanggan Lacak Status]
    Track --> End([Selesai])
```

### B. Alur Operasional Dapur (Kitchen Workflow)

Menggambarkan bagaimana dapur memproses pesanan yang masuk.

```mermaid
flowchart TD
    Start([Mulai Shift]) --> Login[Login Dashboard Dapur]
    Login --> Monitor[Monitor Tab 'Confirmed']
    
    Monitor --> NewOrder{Ada Pesanan Baru?}
    NewOrder -- Tidak --> Monitor
    NewOrder -- Ya --> ViewDetail[Lihat Detail Pesanan]
    
    ViewDetail --> Process[Klik 'Proses Pesanan']
    Process --> Cooking[Menyiapkan Makanan]
    Cooking --> Finish[Makanan Selesai]
    
    Finish --> Ready[Klik 'Tandai Siap']
    Ready --> Notify[Status Terupdate di Pelanggan]
    Notify --> Monitor
```

---

## 3. Sequence Diagrams

### A. Skenario: Pemesanan dengan Pembayaran QRIS

Detail interaksi antar objek saat pelanggan memesan dan membayar via QRIS.

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

### B. Skenario: Penggunaan Voucher

Detail validasi saat pelanggan memasukkan kode voucher.

```mermaid
sequenceDiagram
    participant C as Pelanggan
    participant FE as Frontend
    participant BE as Backend
    participant DB as Database

    C->>FE: Input Kode Voucher "DISKON50"
    FE->>BE: POST /vouchers/validate
    
    BE->>DB: Query Voucher by Code
    DB-->>BE: Return Voucher Data
    
    alt Voucher Tidak Ditemukan / Expired
        BE-->>FE: Error "Voucher tidak valid"
        FE->>C: Tampilkan Pesan Error
    else Voucher Valid
        BE->>BE: Hitung Diskon (Cek Min. Transaksi, Kuota)
        BE-->>FE: Return Nominal Diskon
        FE->>C: Update Total Harga & Tampilkan Diskon
    end
```

---

## 4. Class Diagram

Diagram ini menggambarkan struktur database dan relasi antar model dalam sistem.

```mermaid
classDiagram
    class User {
        +id: int
        +name: string
        +email: string
        +phone: string
        +password: string
        +role: enum
        +phone_verified_at: datetime
    }

    class Customer {
        +id: int
        +user_id: int
        +name: string
        +phone: string
        +is_active: boolean
    }

    class Category {
        +id: int
        +name: string
        +image: string
        +is_active: boolean
    }

    class Menu {
        +id: int
        +category_id: int
        +name: string
        +description: text
        +price: decimal
        +stock: int
        +image: string
        +is_available: boolean
    }

    class Voucher {
        +id: int
        +code: string
        +type: enum
        +amount: decimal
        +min_purchase: decimal
        +max_discount: decimal
        +quota: int
        +start_date: datetime
        +end_date: datetime
        +is_active: boolean
    }

    class Order {
        +id: int
        +order_number: string
        +user_id: int
        +customer_name: string
        +table_number: string
        +total_amount: decimal
        +status: enum
        +payment_method: enum
        +payment_status: enum
        +snap_token: string
    }

    class OrderItem {
        +id: int
        +order_id: int
        +menu_id: int
        +quantity: int
        +price: decimal
        +subtotal: decimal
        +notes: text
    }

    class VoucherUsage {
        +id: int
        +voucher_id: int
        +user_id: int
        +order_id: int
        +discount_amount: decimal
        +used_at: datetime
    }

    Category "1" -- "*" Menu : has
    User "1" -- "0..1" Customer : profile
    Order "1" -- "*" OrderItem : contains
    Menu "1" -- "*" OrderItem : in
    User "1" -- "*" Order : places
    Voucher "1" -- "*" VoucherUsage : used_in
    Order "1" -- "0..1" VoucherUsage : applies
    User "1" -- "*" VoucherUsage : redeems
```
