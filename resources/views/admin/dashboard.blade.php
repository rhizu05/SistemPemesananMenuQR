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
    
    .stat-card.border-primary {
        border-left-color: var(--primary-color);
    }
    
    .stat-card.border-warning {
        border-left-color: var(--status-pending);
    }
    
    .stat-card.border-info {
        border-left-color: var(--status-processing);
    }
    
    .stat-card.border-success {
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
            <div class="stat-card border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">TOTAL PESANAN</p>
                        <h2 style="color: var(--primary-color);">{{ $totalOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: var(--primary-color);">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">PENDING</p>
                        <h2 style="color: var(--status-pending);">{{ $pendingOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: var(--status-pending);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">DIPROSES</p>
                        <h2 style="color: var(--status-processing);">{{ $preparingOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: var(--status-processing);">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">SIAP DIANTAR</p>
                        <h2 style="color: var(--status-ready);">{{ $readyOrders }}</h2>
                    </div>
                    <div class="stat-icon" style="color: var(--status-ready);">
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
                                <div class="mb-3" style="font-size: 3rem; color: var(--primary-color);">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <h5 class="mb-2">Kategori Menu</h5>
                                <p class="small mb-0">Kelola kategori makanan & minuman</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.menu') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: var(--status-ready);">
                                    <i class="bi bi-card-list"></i>
                                </div>
                                <h5 class="mb-2">Menu</h5>
                                <p class="small mb-0">Kelola daftar menu restoran</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: var(--status-pending);">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <h5 class="mb-2">Pesanan</h5>
                                <p class="small mb-0">Kelola pesanan customer</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.reports') }}" class="text-decoration-none">
                        <div class="quick-menu-card h-100">
                            <div class="card-body text-center">
                                <div class="mb-3" style="font-size: 3rem; color: var(--status-processing);">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h5 class="mb-2">Laporan</h5>
                                <p class="small mb-0">Lihat laporan penjualan</p>
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
                    <li>
                        <i class="bi bi-check-circle-fill me-2" style="color: var(--status-ready);"></i>
                        Manajemen kategori dan menu makanan/minuman
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill me-2" style="color: var(--status-ready);"></i>
                        Pemantauan pesanan pelanggan secara real-time
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill me-2" style="color: var(--status-ready);"></i>
                        Pengelolaan stok dan harga menu
                    </li>
                    <li>
                        <i class="bi bi-check-circle-fill me-2" style="color: var(--status-ready);"></i>
                        Laporan penjualan dan analisis
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-gear me-2"></i>Status Sistem</h6>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background-color: var(--status-ready);">Online</span>
                    <span class="small" style="color: var(--text-light);">Sistem berjalan normal</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background-color: var(--status-processing);">{{ \App\Models\Category::count() }} Kategori</span>
                    <span class="small" style="color: var(--text-light);">Kategori menu tersedia</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background-color: var(--primary-color);">{{ \App\Models\Menu::count() }} Menu</span>
                    <span class="small" style="color: var(--text-light);">Menu tersedia</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge me-2" style="background-color: var(--status-pending);">{{ \App\Models\User::count() }} Users</span>
                    <span class="small" style="color: var(--text-light);">Pengguna terdaftar</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection