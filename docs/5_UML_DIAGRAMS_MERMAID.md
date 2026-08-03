# UML Diagrams - Sistem Pemesanan Menu QR (Dapoer Katendjo)

Dokumen ini berisi semua diagram UML dalam format **Mermaid** untuk Sistem Pemesanan Restoran QR.

---

## 📋 Daftar Isi

1. [Use Case Diagram](#1-use-case-diagram)
2. [Activity Diagram Utama](#2-activity-diagram-utama)
3. [Sequence Diagram Utama](#3-sequence-diagram-utama)
4. [Class Diagram](#4-class-diagram)

---

## 1. Use Case Diagram

### 🎯 Aktor dan Use Case Lengkap

Sistem ini memiliki **4 aktor** dan **17 use case** dengan relasi `<<include>>` dan `<<extend>>`.

```mermaid
flowchart LR
    subgraph Actors
        Customer[👤 Pelanggan]
        Admin[👨‍💼 Admin]
        Cashier[💰 Kasir]
        Kitchen[👨‍🍳 Koki]
    end

    subgraph "Sistem Pemesanan Restoran QR"
        subgraph "Customer Use Cases"
            UC1((Scan QR Code))
            UC2((Lihat Menu Digital))
            UC3((Kelola Keranjang))
            UC4((Checkout Pesanan))
            UC5((Gunakan Voucher))
            UC6((Bayar QRIS/Tunai))
            UC7((Lacak Status Pesanan))
        end

        subgraph "Admin Use Cases"
            UC8((Login Admin))
            UC9((Manajemen Menu & Kategori))
            UC10((Manajemen Voucher))
            UC13((Lihat Laporan))
            UC16((Manajemen Customer))
        end

        subgraph "Cashier Use Cases"
            UC11((POS - Kasir Manual))
            UC12((Konfirmasi Pembayaran Tunai))
            UC17((Lihat Pending Payments))
        end

        subgraph "Kitchen Use Cases"
            UC14((Lihat Antrian Pesanan))
            UC15((Update Status Pesanan))
        end

        subgraph "Include Use Cases"
            INC1((Validasi Voucher))
            INC2((Hitung Total Harga))
            INC3((Generate Order Number))
            INC4((Cek Stok Menu))
            INC5((Autentikasi))
        end

        subgraph "Extend Use Cases"
            EXT1((Terapkan Diskon Voucher))
            EXT2((Generate QR Midtrans))
            EXT3((Cetak Struk))
            EXT4((Kirim Notifikasi))
        end
    end

    %% Customer Associations
    Customer --> UC1
    Customer --> UC2
    Customer --> UC3
    Customer --> UC4
    Customer --> UC5
    Customer --> UC6
    Customer --> UC7

    %% Admin Associations
    Admin --> UC8
    Admin --> UC9
    Admin --> UC10
    Admin --> UC13
    Admin --> UC16

    %% Cashier Associations
    Cashier --> UC8
    Cashier --> UC11
    Cashier --> UC12
    Cashier --> UC17

    %% Kitchen Associations
    Kitchen --> UC8
    Kitchen --> UC14
    Kitchen --> UC15

    %% Include Relationships
    UC4 -.->|include| INC2
    UC4 -.->|include| INC3
    UC5 -.->|include| INC1
    UC3 -.->|include| INC4
    UC8 -.->|include| INC5

    %% Extend Relationships
    EXT1 -.->|extend| UC4
    EXT2 -.->|extend| UC6
    EXT3 -.->|extend| UC11
    EXT4 -.->|extend| UC15
```

### 📊 Tabel Ringkasan Aktor

| Aktor | Role | Jumlah Use Case | Use Case Utama |
|-------|------|-----------------|----------------|
| **Pelanggan** | End User | 7 | Scan QR, Pesan Menu, Bayar |
| **Admin** | Administrator | 5 | Kelola Menu, Voucher, Laporan |
| **Kasir** | Staff | 4 | POS Manual, Konfirmasi Bayar |
| **Koki** | Staff | 3 | Lihat Antrian, Update Status |

---

## 2. Activity Diagram Utama

### 2.1 Activity Diagram: Proses Pemesanan (Customer Flow)

```mermaid
flowchart TD
    Start([🏁 Mulai]) --> A[Scan QR Code di Meja]
    A --> B{QR Valid?}
    B -->|Tidak| C[Tampilkan Error]
    C --> A
    B -->|Ya| D[Buka Halaman Menu Digital]
    
    D --> E[Pilih Kategori Menu]
    E --> F[Lihat Daftar Menu]
    F --> G{Pilih Menu?}
    G -->|Ya| H[Lihat Detail Menu]
    H --> I[Tentukan Jumlah & Catatan]
    I --> J{Stok Tersedia?}
    J -->|Tidak| K[Tampilkan Pesan Stok Habis]
    K --> F
    J -->|Ya| L[Tambah ke Keranjang]
    L --> M{Lanjut Belanja?}
    M -->|Ya| F
    M -->|Tidak| N[Buka Keranjang]
    G -->|Tidak| N
    
    N --> O[Review Pesanan]
    O --> P{Punya Voucher?}
    P -->|Ya| Q[Input Kode Voucher]
    Q --> R{Voucher Valid?}
    R -->|Tidak| S[Tampilkan Error Voucher]
    S --> Q
    R -->|Ya| T[Terapkan Diskon]
    T --> U[Pilih Metode Pembayaran]
    P -->|Tidak| U
    
    U --> V{Metode Bayar?}
    V -->|QRIS| W[Generate QR Pembayaran]
    W --> X[Scan & Bayar via E-Wallet]
    X --> Y{Pembayaran Sukses?}
    Y -->|Pending| X
    Y -->|Sukses| Z[Update Status: Paid]
    
    V -->|Tunai| AA[Tampilkan Instruksi Bayar ke Kasir]
    AA --> AB[Pelanggan ke Kasir]
    AB --> AC[Kasir Konfirmasi Bayar]
    AC --> Z
    
    Z --> AD[Pesanan Masuk ke Dapur]
    AD --> AE[Lacak Status Pesanan]
    AE --> AF{Status Pesanan?}
    AF -->|Processing| AG[Sedang Dimasak]
    AG --> AE
    AF -->|Ready| AH[Pesanan Siap Diambil]
    AH --> AI[Pesanan Selesai]
    AI --> End([🏁 Selesai])
```

### 2.2 Activity Diagram: Manajemen Menu (Admin)

```mermaid
flowchart TD
    Start([🏁 Mulai]) --> A[Login sebagai Admin]
    A --> B{Kredensial Valid?}
    B -->|Tidak| C[Tampilkan Error Login]
    C --> A
    B -->|Ya| D[Buka Dashboard Admin]
    
    D --> E[Pilih Menu Manajemen Menu]
    E --> F{Pilih Aksi?}
    
    F -->|Tambah| G[Klik Tambah Menu Baru]
    G --> H[Input Data Menu]
    H --> I[Upload Foto Menu]
    I --> J[Pilih Kategori]
    J --> K{Validasi Data?}
    K -->|Gagal| L[Tampilkan Error Validasi]
    L --> H
    K -->|Sukses| M[Simpan ke Database]
    
    F -->|Edit| N[Pilih Menu yang Akan Diedit]
    N --> O[Update Data Menu]
    O --> K
    
    F -->|Hapus| P[Pilih Menu yang Akan Dihapus]
    P --> Q[Konfirmasi Hapus]
    Q --> R{Konfirmasi?}
    R -->|Tidak| E
    R -->|Ya| S[Soft Delete Menu]
    S --> T[Tampilkan Pesan Sukses]
    
    M --> T
    T --> U{Kelola Lagi?}
    U -->|Ya| E
    U -->|Tidak| End([🏁 Selesai])
```

### 2.3 Activity Diagram: Proses Dapur (Kitchen)

```mermaid
flowchart TD
    Start([🏁 Mulai]) --> A[Login sebagai Koki]
    A --> B[Buka Dashboard Dapur]
    
    B --> C{Ada Pesanan Baru?}
    C -->|Tidak| D[Tunggu Pesanan Masuk]
    D --> C
    
    C -->|Ya| E[🔔 Notifikasi Pesanan Baru]
    E --> F[Tampilkan Kartu Pesanan]
    F --> G[Lihat Detail Menu & Notes]
    
    G --> H[Klik 'Proses Pesanan']
    H --> I[Update Status: Processing]
    I --> J[Siapkan Makanan]
    
    J --> K{Semua Item Selesai?}
    K -->|Belum| J
    K -->|Ya| L[Klik 'Pesanan Siap']
    
    L --> M[Update Status: Ready]
    M --> N[Kirim Notifikasi ke Pelanggan/Waiter]
    N --> O{Lanjut Pesanan Lain?}
    O -->|Ya| C
    O -->|Tidak| End([🏁 Selesai])
```

### 2.4 Activity Diagram: Proses Kasir

```mermaid
flowchart TD
    Start([🏁 Mulai]) --> A[Login sebagai Kasir]
    A --> B[Buka Dashboard Kasir]
    
    B --> C{Tipe Transaksi?}
    
    %% Alur POS Manual
    C -->|POS Manual| D[Pilih Menu dari List]
    D --> E[Input Jumlah Item]
    E --> F{Tambah Item Lain?}
    F -->|Ya| D
    F -->|Tidak| G[Checkout Pesanan POS]
    G --> H[Input Nama Pelanggan]
    H --> I[Terima Pembayaran Tunai]
    I --> J[Input Nominal yang Dibayar]
    J --> K[Sistem Hitung Kembalian]
    K --> L[Konfirmasi Pembayaran]
    L --> M[Cetak Struk]
    M --> N[Update Stok Menu]
    
    %% Alur Konfirmasi Pembayaran QR
    C -->|Konfirmasi QR| O[Lihat Daftar Pending Payments]
    O --> P{Ada Pembayaran Tunai Pending?}
    P -->|Tidak| Q[Tunggu Pelanggan]
    Q --> O
    P -->|Ya| R[Pelanggan Datang Membayar]
    R --> S[Terima Uang Tunai]
    S --> T[Klik 'Konfirmasi Bayar']
    T --> U[Update Status: Paid]
    U --> V[Kirim Notifikasi ke Dapur]
    
    N --> W{Transaksi Lain?}
    V --> W
    W -->|Ya| C
    W -->|Tidak| End([🏁 Selesai])
```

---

## 3. Sequence Diagram Utama

### 3.1 Sequence Diagram: Proses Pemesanan via QR

```mermaid
sequenceDiagram
    autonumber
    actor Customer as 👤 Pelanggan
    participant QR as 📱 QR Scanner
    participant Web as 🌐 Web App
    participant Cart as 🛒 Cart Service
    participant DB as 🗄️ Database
    participant Payment as 💳 Payment Gateway
    participant Kitchen as 👨‍🍳 Kitchen Display

    Customer->>QR: Scan QR Code Meja
    QR->>Web: Redirect ke URL + Table Number
    Web->>DB: Simpan Session (table_number)
    Web-->>Customer: Tampilkan Menu Digital

    Customer->>Web: Pilih Menu & Jumlah
    Web->>DB: Cek Stok Menu
    DB-->>Web: Stok Tersedia
    Web->>Cart: Tambah Item ke Keranjang
    Cart-->>Customer: Update Cart Counter

    Customer->>Web: Checkout Pesanan
    Web->>Cart: Ambil Data Keranjang
    Cart-->>Web: Return Cart Items
    Web->>Web: Hitung Subtotal

    opt Gunakan Voucher
        Customer->>Web: Input Kode Voucher
        Web->>DB: Validasi Voucher
        DB-->>Web: Voucher Valid
        Web->>Web: Hitung Diskon
        Web-->>Customer: Tampilkan Total Baru
    end

    Customer->>Web: Pilih Metode QRIS
    Web->>Payment: Request Create Transaction
    Payment-->>Web: Return Snap Token + QR URL
    Web-->>Customer: Tampilkan QR Code

    Customer->>Payment: Scan & Bayar via E-Wallet
    Payment->>Web: Webhook: Payment Success
    Web->>DB: Update Order Status (Paid)
    Web->>DB: Update Stok Menu
    Web->>Kitchen: Push Notifikasi Pesanan Baru
    Kitchen-->>Customer: Tampilkan Status: Processing
```

### 3.2 Sequence Diagram: Konfirmasi Pembayaran Tunai

```mermaid
sequenceDiagram
    autonumber
    actor Customer as 👤 Pelanggan
    actor Cashier as 💰 Kasir
    participant CashierWeb as 🖥️ Cashier Dashboard
    participant DB as 🗄️ Database
    participant Kitchen as 👨‍🍳 Kitchen Display
    participant CustomerWeb as 📱 Customer App

    Note over Customer,Cashier: Pelanggan sudah checkout dengan metode TUNAI

    Customer->>Cashier: Datang ke kasir dengan Order ID
    Cashier->>CashierWeb: Buka Pending Payments
    CashierWeb->>DB: Query Orders (payment_status = unpaid)
    DB-->>CashierWeb: Return Pending Orders
    
    Cashier->>CashierWeb: Cari Order by ID/Nama
    CashierWeb-->>Cashier: Tampilkan Detail Order
    
    Customer->>Cashier: Serahkan Uang Tunai
    Cashier->>Cashier: Hitung Kembalian
    Cashier->>Customer: Berikan Kembalian
    
    Cashier->>CashierWeb: Klik "Konfirmasi Bayar"
    CashierWeb->>DB: Update payment_status = paid
    CashierWeb->>DB: Update order_status = confirmed
    DB-->>CashierWeb: Success
    
    CashierWeb->>Kitchen: Push Notifikasi Pesanan Baru
    Kitchen-->>Kitchen: 🔔 Alert Pesanan Masuk
    
    CashierWeb->>CustomerWeb: Push Update Status
    CustomerWeb-->>Customer: Status: Pesanan Dikonfirmasi
```

### 3.3 Sequence Diagram: Update Status Pesanan (Dapur)

```mermaid
sequenceDiagram
    autonumber
    actor Kitchen as 👨‍🍳 Koki
    participant KitchenWeb as 🖥️ Kitchen Dashboard
    participant DB as 🗄️ Database
    participant Pusher as 📡 Realtime Service
    participant CustomerWeb as 📱 Customer App
    actor Customer as 👤 Pelanggan

    KitchenWeb->>DB: Query Orders (status = confirmed)
    DB-->>KitchenWeb: Return Pending Orders
    KitchenWeb-->>Kitchen: Tampilkan Antrian Pesanan

    Note over Kitchen: 🔔 Pesanan Baru Masuk

    Kitchen->>KitchenWeb: Lihat Detail Pesanan
    KitchenWeb-->>Kitchen: Tampilkan Menu + Notes
    
    Kitchen->>KitchenWeb: Klik "Proses Pesanan"
    KitchenWeb->>DB: Update status = processing
    DB-->>KitchenWeb: Success
    KitchenWeb->>Pusher: Broadcast Status Update
    Pusher->>CustomerWeb: Push Event: status_changed
    CustomerWeb-->>Customer: 🔄 Status: Sedang Dimasak

    Note over Kitchen: 👨‍🍳 Masak Makanan...

    Kitchen->>KitchenWeb: Klik "Pesanan Siap"
    KitchenWeb->>DB: Update status = ready
    DB-->>KitchenWeb: Success
    KitchenWeb->>Pusher: Broadcast Status Update
    Pusher->>CustomerWeb: Push Event: order_ready
    CustomerWeb-->>Customer: ✅ Pesanan Siap Diambil!
```

### 3.4 Sequence Diagram: Manajemen Menu (Admin)

```mermaid
sequenceDiagram
    autonumber
    actor Admin as 👨‍💼 Admin
    participant AdminWeb as 🖥️ Admin Dashboard
    participant Auth as 🔐 Auth Service
    participant DB as 🗄️ Database
    participant Storage as 📂 File Storage

    Admin->>AdminWeb: Akses Halaman Login
    Admin->>AdminWeb: Input Email & Password
    AdminWeb->>Auth: Validate Credentials
    Auth->>DB: Check User & Role
    DB-->>Auth: User Valid (role: admin)
    Auth-->>AdminWeb: Login Success + Token
    AdminWeb-->>Admin: Redirect to Dashboard

    Admin->>AdminWeb: Klik Menu "Manajemen Menu"
    AdminWeb->>DB: Query all Menu with Category
    DB-->>AdminWeb: Return Menu List
    AdminWeb-->>Admin: Tampilkan Daftar Menu

    Admin->>AdminWeb: Klik "Tambah Menu Baru"
    AdminWeb-->>Admin: Tampilkan Form Input
    Admin->>AdminWeb: Input: Nama, Harga, Stok, Kategori
    Admin->>AdminWeb: Upload Foto Menu
    AdminWeb->>Storage: Store Image
    Storage-->>AdminWeb: Return Image Path

    Admin->>AdminWeb: Klik "Simpan"
    AdminWeb->>AdminWeb: Validate Input
    AdminWeb->>DB: Insert New Menu
    DB-->>AdminWeb: Success
    AdminWeb-->>Admin: ✅ Menu Berhasil Ditambahkan
```

---

## 4. Class Diagram

```mermaid
classDiagram
    %% Main Classes
    class User {
        +int id
        +string name
        +string email
        +string password
        +string role
        +string phone
        +string address
        +datetime phone_verified_at
        +string otp_code
        +datetime otp_expires_at
        +boolean is_active
        +datetime created_at
        +datetime updated_at
        --
        +orders() Order[]
        +isAdmin() bool
        +isKitchen() bool
        +isCustomer() bool
        +isCashier() bool
        +hasRole(role) bool
    }

    class Category {
        +int id
        +string name
        +string description
        +boolean is_active
        +datetime created_at
        +datetime updated_at
        --
        +menus() Menu[]
    }

    class Menu {
        +int id
        +string name
        +string description
        +decimal price
        +string image
        +boolean is_available
        +int stock
        +int category_id
        +datetime created_at
        +datetime updated_at
        --
        +category() Category
        +orderItems() OrderItem[]
    }

    class Order {
        +int id
        +string order_number
        +int user_id
        +string status
        +decimal total_amount
        +string customer_name
        +string customer_phone
        +int table_number
        +string order_type
        +string special_requests
        +datetime completed_at
        +string payment_status
        +string payment_method
        +string payment_reference
        +datetime paid_at
        +decimal amount_paid
        +decimal change_amount
        +string snap_token
        +int voucher_id
        +string voucher_code
        +decimal discount_amount
        +decimal subtotal
        +datetime created_at
        +datetime updated_at
        --
        +user() User
        +voucher() Voucher
        +orderItems() OrderItem[]
        +menus() Menu[]
    }

    class OrderItem {
        +int id
        +int order_id
        +int menu_id
        +int quantity
        +decimal price
        +string special_instructions
        +datetime created_at
        +datetime updated_at
        --
        +order() Order
        +menu() Menu
    }

    class Voucher {
        +int id
        +string code
        +string name
        +string description
        +string type
        +decimal value
        +decimal min_transaction
        +decimal max_discount
        +int quota
        +int used_count
        +int user_limit
        +string user_type
        +datetime valid_from
        +datetime valid_until
        +boolean is_active
        +datetime created_at
        +datetime updated_at
        --
        +usages() VoucherUsage[]
        +orders() Order[]
        +isValid() bool
        +isAvailable() bool
        +canBeUsedBy(userId) bool
        +calculateDiscount(subtotal) decimal
    }

    class VoucherUsage {
        +int id
        +int voucher_id
        +int user_id
        +int order_id
        +decimal discount_amount
        +datetime used_at
        --
        +voucher() Voucher
        +user() User
        +order() Order
    }

    %% Relationships
    User "1" --> "*" Order : places
    Category "1" --> "*" Menu : contains
    Menu "1" --> "*" OrderItem : included in
    Order "1" --> "*" OrderItem : has
    Order "*" --> "0..1" Voucher : uses
    Order "*" --> "0..1" User : belongs to
    Voucher "1" --> "*" VoucherUsage : tracks
    VoucherUsage "*" --> "1" User : used by
    VoucherUsage "*" --> "1" Order : applied to

    %% Notes for roles
    note for User "Roles: admin, cashier, kitchen, customer"
    note for Order "Status: pending, confirmed, processing, ready, completed, cancelled"
    note for Voucher "Type: percentage, fixed_amount"
```

### 📊 Tabel Relasi Antar Class

| Class | Relasi | Class Target | Tipe | Keterangan |
|-------|--------|--------------|------|------------|
| User | hasMany | Order | 1:N | User dapat memiliki banyak pesanan |
| Category | hasMany | Menu | 1:N | Kategori memiliki banyak menu |
| Menu | belongsTo | Category | N:1 | Menu termasuk dalam satu kategori |
| Menu | hasMany | OrderItem | 1:N | Menu dapat dipesan berkali-kali |
| Order | belongsTo | User | N:1 | Pesanan milik satu user (opsional) |
| Order | belongsTo | Voucher | N:1 | Pesanan menggunakan satu voucher (opsional) |
| Order | hasMany | OrderItem | 1:N | Pesanan memiliki banyak item |
| OrderItem | belongsTo | Order | N:1 | Item termasuk dalam satu pesanan |
| OrderItem | belongsTo | Menu | N:1 | Item merujuk ke satu menu |
| Voucher | hasMany | VoucherUsage | 1:N | Voucher dapat digunakan berkali-kali |
| Voucher | hasMany | Order | 1:N | Voucher dapat dipakai di banyak order |
| VoucherUsage | belongsTo | Voucher | N:1 | Usage merujuk ke satu voucher |
| VoucherUsage | belongsTo | User | N:1 | Usage oleh satu user |
| VoucherUsage | belongsTo | Order | N:1 | Usage di satu order |

---

## 📝 Catatan Implementasi

### Status Order
- `pending` - Menunggu pembayaran
- `confirmed` - Pembayaran dikonfirmasi, masuk antrian dapur
- `processing` - Sedang dimasak
- `ready` - Pesanan siap diambil
- `completed` - Pesanan selesai
- `cancelled` - Pesanan dibatalkan

### Status Pembayaran
- `unpaid` - Belum dibayar
- `paid` - Sudah dibayar

### Metode Pembayaran
- `qris` - Pembayaran via QRIS/Midtrans
- `cash` - Pembayaran tunai di kasir

### Tipe Voucher
- `percentage` - Diskon persentase
- `fixed_amount` - Diskon nominal tetap

### User Roles
- `admin` - Full akses sistem
- `cashier` - Akses kasir dan POS
- `kitchen` - Akses dashboard dapur
- `customer` - Pelanggan terdaftar

---

## 🔗 Referensi File Terkait

- [USE_CASE_DIAGRAM_COMPLETE.md](./USE_CASE_DIAGRAM_COMPLETE.md) - Diagram Use Case lengkap format PlantUML
- [ACTIVITY_DIAGRAMS_COMPLETE.md](./ACTIVITY_DIAGRAMS_COMPLETE.md) - Activity Diagram per Use Case
- [DOCUMENTATION.md](./DOCUMENTATION.md) - Dokumentasi teknis lengkap

