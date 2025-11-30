@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    /* Dashboard Specific Styles */
    .page-header {
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-md);
        border-bottom: 2px solid #e0e0e0;
    }
    
    .page-header h2 {
        color: var(--text-dark);
        font-weight: 700;
        margin-bottom: var(--spacing-sm);
    }
    
    .page-header p {
        color: var(--text-light);
        margin: 0;
    }
    
    /* Statistics Cards */
    .stat-card {
        background: var(--bg-white);
        border-radius: var(--radius-md);
        padding: var(--spacing-lg);
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        border-left: 4px solid;
        height: 100%;
    }
    
    .stat-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-4px);
    }
    
    .stat-card.border-total {
        border-left-color: var(--status-total);
    }
    
    .stat-card.border-pending {
        border-left-color: var(--status-pending);
    }
    
    .stat-card.border-processing {
        border-left-color: var(--status-processing);
    }
    
    .stat-card.border-ready {
        border-left-color: var(--status-ready);
    }
    
    .stat-card .stat-icon {
        font-size: 3rem;
        opacity: 0.15;
    }
    
    .stat-card h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .stat-card p {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin: 0;
    }
    
    /* Quick Menu Cards */
    .quick-menu-card {
        background: var(--bg-white);
        border-radius: var(--radius-md);
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .quick-menu-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }
    
    .quick-menu-card .card-body {
        padding: var(--spacing-lg);
    }
    
    .quick-menu-card h5 {
        color: var(--text-dark);
        font-weight: 700;
    }
    
    .quick-menu-card p {
        color: var(--text-light);
    }
    
    /* System Info Card */
    .system-info-card {
        background: var(--bg-white);
        border-left: 4px solid var(--primary-color);
        padding: var(--spacing-lg);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
    }
    
    .system-info-card h6 {
        color: var(--text-dark);
        font-weight: 700;
        margin-bottom: var(--spacing-md);
    }
    
    .system-info-card .list-unstyled li {
        padding: var(--spacing-sm) 0;
        color: var(--text-medium);
    }
    
    .system-info-card .badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        font-weight: 600;
    }
    
    /* Card Headers */
    .card {
        border: none;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: var(--spacing-lg);
    }
    
    .card-header {
        background: var(--primary-color);
        color: white;
        font-weight: 700;
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        border: none;
    }
    
    .card-body {
        padding: var(--spacing-lg);
    }
    
    /* Icon Sizes */
    .bi {
        font-size: 24px;
    }
    
    .bi-check-circle-fill {
        font-size: 20px;
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
        <p>Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan sistem pemesanan restoran.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background-color: #F3F7F4; border-left: 4px solid #2A5C3F;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">TOTAL PESANAN</p>
                        <h2 style="color: #2A5C3F;">{{ $totalOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: #2A5C3F;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #FBC02D;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">PENDING</p>
                        <h2 style="color: #FBC02D;">{{ $pendingOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: #FBC02D;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #1976D2;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">DIPROSES</p>
                        <h2 style="color: #1976D2;">{{ $preparingOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: #1976D2;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left: 4px solid #4A7F5A;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">SIAP DIANTAR</p>
                        <h2 style="color: #4A7F5A;">{{ $readyOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: #4A7F5A;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Menu -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-grid-3x3-gap me-2"></i>Menu Cepat
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.categories') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">Kategori Menu</h5>
                                <p class="small mb-0">Kelola kategori makanan & minuman</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.menu') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-card-list"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">Menu</h5>
                                <p class="small mb-0">Kelola daftar menu restoran</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">Pesanan</h5>
                                <p class="small mb-0">Kelola pesanan customer</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.reports') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">Laporan</h5>
                                <p class="small mb-0">Lihat laporan penjualan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.vouchers.index') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">Voucher</h5>
                                <p class="small mb-0">Kelola voucher diskon</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.pos') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-calculator"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">POS</h5>
                                <p class="small mb-0">Point of Sale kasir</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.qr-codes') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-qr-code"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">QR Code Meja</h5>
                                <p class="small mb-0">Generate QR code untuk meja</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.customers') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: #4A7F5A;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h5 class="mb-2" style="color: #1E3B2C;">Pelanggan</h5>
                                <p class="small mb-0">Kelola data pelanggan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="system-info-card">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-info-circle me-2"></i>Fitur Utama</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill me-2" style="color: #2A5C3F;"></i>
                        Sistem Voucher & Diskon Pelanggan
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill me-2" style="color: #2A5C3F;"></i>
                        Integrasi Pembayaran QRIS Otomatis
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill me-2" style="color: #2A5C3F;"></i>
                        Login Pelanggan via WhatsApp OTP
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle-fill me-2" style="color: #2A5C3F;"></i>
                        Point of Sale (POS) & Manajemen Stok
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-gear me-2"></i>Status Sistem</h6>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background-color: #2A5C3F;">Online</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background-color: #1976D2;">Platform</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background-color: #FBC02D;">In Trend</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style="background-color: #7E57C2;">v1.0.0</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection