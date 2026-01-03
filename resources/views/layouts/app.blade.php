<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AKPL Restaurant') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            /* Dapoer Katendjo Color Palette - Konsisten */
            --primary-color: #2A5C3F;         /* Hijau Tua Utama */
            --primary-dark: #1E3B2C;          /* Hijau Lebih Gelap (Neutral Dark) */
            --primary-light: #3d7556;         /* Hijau Lebih Terang */
            
            /* Warna Status - Turunan dari Primary */
            /* Warna Status - Turunan dari Primary */
            --status-pending: #F7C948;        /* Kuning Emas untuk Pending */
            --status-pending-bg: #fef9e7;     /* Background Kuning Lembut */
            --status-processing: #FFA552;     /* Oranye untuk Diproses */
            --status-processing-bg: #fff5e8;  /* Background Oranye Lembut */
            --status-ready: #46A36B;          /* Hijau Medium untuk Siap */
            --status-ready-bg: #eafaf1;       /* Background Hijau Muda */
            --status-completed: #2A5C3F;      /* Primary untuk Selesai */
            --status-cancelled: #e74c3c;      /* Merah untuk Dibatalkan */
            --status-total: #4D7B66;          /* Hijau Medium untuk Total Pesanan */
            
            /* Warna Sekunder */
            --secondary-color: #4A7F5A;       /* Hijau Lebih Terang (Secondary) */
            --accent-color: #8FC69A;          /* Accent Lembut */
            
            /* Warna Teks */
            --text-dark: #1E3B2C;             /* Neutral Dark (Judul/Teks Tebal) */
            --text-medium: #333333;           /* Teks Medium */
            --text-light: #666666;            /* Teks Light */
            --text-muted: #999999;            /* Teks Muted */
            
            /* Background */
            --bg-light: #F3F7F4;              /* Neutral Light Background */
            --bg-white: #ffffff;              /* Background Putih */
            
            /* Warna Bootstrap Override */
            --success-color: #58d68d;
            --danger-color: #e74c3c;
            --warning-color: #f9ca24;
            --info-color: #45b39d;
            
            /* Shadow - Konsisten */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 12px 32px rgba(0, 0, 0, 0.12);
            
            /* Spacing - Konsisten */
            --spacing-sm: 8px;
            --spacing-md: 16px;
            --spacing-lg: 24px;
            --spacing-xl: 32px;
            
            /* Border Radius - Konsisten */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .serif-font {
            font-family: 'Playfair Display', serif;
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: var(--shadow-sm);
            padding: var(--spacing-md) 0;
            border-bottom: 3px solid var(--accent-color);
            position: relative;
            z-index: 1050; /* Ensure navbar is on top */
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 50px;
            width: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        .navbar-brand:hover {
            color: #ffffff !important;
        }

        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            padding: var(--spacing-sm) var(--spacing-md) !important;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: var(--accent-color);
            color: #ffffff !important;
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow-hover);
            border-radius: var(--radius-md);
            padding: var(--spacing-sm);
            margin-top: var(--spacing-sm);
        }

        .dropdown-item {
            border-radius: var(--radius-md);
            padding: var(--spacing-sm) var(--spacing-md);
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 20px;
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 76px);
            padding: var(--spacing-xl) 0;
        }

        .container {
            max-width: 1200px;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: var(--spacing-lg);
        }
        
        .page-header h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
        }
        
        .page-header p {
            color: var(--text-dark);  /* Ubah ke warna yang lebih terang */
            font-size: 1rem;
            margin-bottom: 0;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background-color: var(--primary-color);
            color: #ffffff;
            border-bottom: none;
            padding: var(--spacing-md) var(--spacing-lg);
            font-weight: 600;
        }

        .card-header.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        .card-header.bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .card-header.bg-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        }

        .card-header.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        }

        .card-body {
            padding: var(--spacing-lg);
        }

        /* Button Styling */
        .btn {
            border-radius: var(--radius-md);
            padding: var(--spacing-sm) var(--spacing-md);
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #3730a3 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: #475569;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--light-bg);
            color: var(--dark-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: var(--spacing-md);
            border: none;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
            transform: scale(1.01);
        }

        .table tbody td {
            padding: var(--spacing-md);
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Badge Styling */
        .badge {
            padding: var(--spacing-sm) 12px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: var(--spacing-sm);
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-md);
            padding: 12px var(--spacing-md);
            transition: all 0.3s ease;
            font-size: 0.9375rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: var(--spacing-md) 20px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Progress Bar */
        .progress {
            border-radius: var(--radius-md);
            background-color: #e2e8f0;
        }

        .progress-bar {
            border-radius: var(--radius-md);
            font-weight: 600;
        }

        /* Stat Card */
        .stat-card {
            border-radius: var(--radius-md);
            padding: var(--spacing-lg);
            background: white;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card.border-primary {
            border-left-color: var(--primary-color);
        }

        .stat-card.border-success {
            border-left-color: var(--success-color);
        }

        .stat-card.border-warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.border-info {
            border-left-color: var(--info-color);
        }

        /* Quick Menu Card */
        .quick-menu-card {
            transition: all 0.3s ease;
            border-radius: var(--radius-md);
            overflow: hidden;
            height: 100%;
        }

        .quick-menu-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        /* Page Header */
        .page-header {
            margin-bottom: var(--spacing-lg);
        }

        .page-header h2 {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: var(--spacing-sm);
        }

        .page-header p {
            color: var(--text-dark);
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding: 1rem 0;
            }

            .card-body {
                padding: var(--spacing-md);
            }

            .btn {
                padding: var(--spacing-sm) var(--spacing-md);
                font-size: 0.875rem;
            }
        }

        /* Loading Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Dapoer Katendjo Logo">
                Dapoer Katendjo
            </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.menu') }}">
                                    <i class="bi bi-card-list"></i> Menu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.my-orders') }}">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-muted small" href="{{ route('admin.login') }}">
                                    <i class="bi bi-shield-lock"></i> Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false" onclick="return false;">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="bi bi-speedometer2"></i> Dashboard Admin
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @elseif(Auth::user()->isCashier())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('cashier.dashboard') }}">
                                                <i class="bi bi-speedometer2"></i> Dashboard Kasir
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @elseif(Auth::user()->isKitchen())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('kitchen.dashboard') }}">
                                                <i class="bi bi-speedometer2"></i> Dashboard Kitchen
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{ route('customer.menu') }}">
                                                <i class="bi bi-card-list"></i> Menu
                                            </a>
                                        </li>
                                        @if(!in_array(Route::currentRouteName(), ['customer.menu', 'customer.scan-qr']))
                                            <li>
                                                <a class="dropdown-item" href="{{ route('customer.cart') }}">
                                                    <i class="bi bi-cart3"></i> Keranjang
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item" href="{{ route('customer.my-orders') }}">
                                                <i class="bi bi-clock-history"></i> Riwayat Pesanan
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                                <i class="bi bi-person"></i> Profil Saya
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </a>
                                    </li>
                                </ul>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="fade-in">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Force Initialize Dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl, {
                    autoClose: true
                })
            });
        });
    </script>