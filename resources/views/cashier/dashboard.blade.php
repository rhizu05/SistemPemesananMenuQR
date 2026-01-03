@extends('layouts.app')

@section('title', 'Dashboard Kasir')

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
    
    /* Quick Action Buttons */
    .quick-action-btn {
        padding: 1.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: var(--radius-md);
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }
    
    /* Notification Badge Animation */
    .notification-badge {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard Kasir</h2>
        <p>Selamat datang, {{ Auth::user()->name }}! Berikut ringkasan hari ini.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="background-color: #F3F7F4; border-left: 4px solid #2A5C3F;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">ORDERS HARI INI</p>
                        <h2 style="color: #2A5C3F;">{{ $todayOrders->count() }}</h2>
                    </div>
                    <div class="stat-icon" style="color: #2A5C3F;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left: 4px solid #1976D2;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">REVENUE HARI INI</p>
                        <h2 style="color: #1976D2;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
                        <small class="text-muted" style="font-size: 0.7rem;">Hanya pesanan yang sudah dibayar</small>
                    </div>
                    <div class="stat-icon" style="color: #1976D2;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left: 4px solid #FBC02D;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-2">PENDING PAYMENTS</p>
                        <h2 style="color: #FBC02D;">{{ $pendingPayments }}</h2>
                    </div>
                    <div class="stat-icon" style="color: #FBC02D;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header" style="background: #2A5C3F; color: white;">
            <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('cashier.pos') }}" class="btn btn-primary btn-lg w-100 quick-action-btn" style="background: #2A5C3F; border: none;">
                        <i class="bi bi-calculator me-2"></i>POS
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('cashier.payments') }}" class="btn btn-warning btn-lg w-100 quick-action-btn position-relative">
                        <i class="bi bi-credit-card me-2"></i>Verify Payment
                        @if($pendingPayments > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="font-size: 0.75rem;">
                            {{ $pendingPayments }}
                            <span class="visually-hidden">pending payments</span>
                        </span>
                        @endif
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('cashier.orders') }}" class="btn btn-info btn-lg w-100 quick-action-btn">
                        <i class="bi bi-list-check me-2"></i>View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Info:</strong> Anda login sebagai Kasir. Anda dapat mengakses POS, verifikasi pembayaran, dan melihat pesanan hari ini.
    </div>
</div>

<script>
// Real-time update for pending payment badge using AJAX polling
function updatePendingCount() {
    fetch('{{ route("cashier.pending-count") }}')
        .then(response => response.json())
        .then(data => {
            const count = data.count;
            const badge = document.querySelector('.notification-badge');
            const statCount = document.querySelector('.stat-card h2[style*="FBC02D"]');
            
            // Update stat card
            if (statCount) {
                statCount.textContent = count;
            }
            
            // Update or create badge
            if (count > 0) {
                if (badge) {
                    // Update existing badge
                    badge.textContent = count;
                } else {
                    // Create new badge if it doesn't exist
                    const verifyBtn = document.querySelector('a[href="{{ route("cashier.payments") }}"]');
                    if (verifyBtn && !verifyBtn.querySelector('.notification-badge')) {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge';
                        newBadge.style.fontSize = '0.75rem';
                        newBadge.innerHTML = count + '<span class="visually-hidden">pending payments</span>';
                        verifyBtn.appendChild(newBadge);
                    }
                }
            } else {
                // Remove badge if count is 0
                if (badge) {
                    badge.remove();
                }
            }
        })
        .catch(error => {
            console.error('Error fetching pending count:', error);
        });
}

// Initial update
updatePendingCount();

// Poll every 3 seconds for real-time updates
setInterval(updatePendingCount, 3000);

// Optional: Play sound notification when new pending payment arrives
let previousCount = {{ $pendingPayments }};
function checkNewPayment() {
    fetch('{{ route("cashier.pending-count") }}')
        .then(response => response.json())
        .then(data => {
            if (data.count > previousCount) {
                // New payment detected! You can add sound here
                // const audio = new Audio('/sounds/notification.mp3');
                // audio.play();
                console.log('New pending payment detected!');
            }
            previousCount = data.count;
        });
}
setInterval(checkNewPayment, 3000);
</script>
@endsection
