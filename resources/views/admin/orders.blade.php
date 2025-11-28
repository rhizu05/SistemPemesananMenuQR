@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container-fluid">
    <div class="page-header mb-4">
        <h2><i class="bi bi-receipt"></i> Daftar Pesanan</h2>
        <p>Kelola semua pesanan pelanggan</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="preparing">Diproses</option>
                        <option value="ready">Siap</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tipe Pesanan</label>
                    <select class="form-select" id="filterType">
                        <option value="all">Semua Tipe</option>
                        <option value="dine_in">Dine In</option>
                        <option value="takeaway">Takeaway</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Metode Pembayaran</label>
                    <select class="form-select" id="filterPayment">
                        <option value="all">Semua Metode</option>
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Cari</label>
                    <input type="text" class="form-control" id="searchOrder" placeholder="Nomor pesanan atau nama...">
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="countPending">{{ $orders->where('status', 'pending')->count() }}</h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="countPreparing">{{ $orders->where('status', 'preparing')->count() }}</h3>
                    <small>Diproses</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="countReady">{{ $orders->where('status', 'ready')->count() }}</h3>
                    <small>Siap</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="countTotal">{{ $totalOrders }}</h3>
                    <small>Total Pesanan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul"></i> Daftar Pesanan</span>
            <button class="btn btn-sm btn-primary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Waktu</th>
                            <th>Tipe</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="order-row" 
                            data-status="{{ $order->status }}" 
                            data-type="{{ $order->order_type }}"
                            data-payment="{{ $order->payment_method }}"
                            data-search="{{ strtolower($order->order_number . ' ' . $order->customer_name) }}">
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                                @if($order->table_number)
                                    <br><small class="text-muted">Meja: {{ $order->table_number }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $order->customer_name }}
                                <br><small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td>
                                <small>{{ $order->created_at->format('d/m/Y') }}</small>
                                <br><strong>{{ $order->created_at->format('H:i') }}</strong>
                            </td>
                            <td>
                                @if($order->order_type === 'dine_in')
                                    <span class="badge bg-info"><i class="bi bi-shop"></i> Dine In</span>
                                @elseif($order->order_type === 'takeaway')
                                    <span class="badge bg-warning"><i class="bi bi-bag"></i> Takeaway</span>
                                @else
                                    <span class="badge bg-primary"><i class="bi bi-truck"></i> Delivery</span>
                                @endif
                            </td>
                            <td>
                                <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ ucfirst($order->payment_method ?? 'cash') }}</small>
                                <br>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Belum</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->status === 'confirmed')
                                    <span class="badge bg-info">Dikonfirmasi</span>
                                @elseif($order->status === 'preparing')
                                    <span class="badge bg-primary">Diproses</span>
                                @elseif($order->status === 'ready')
                                    <span class="badge bg-success">Siap</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.order.detail', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Belum ada pesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterStatus = document.getElementById('filterStatus');
    const filterType = document.getElementById('filterType');
    const filterPayment = document.getElementById('filterPayment');
    const searchOrder = document.getElementById('searchOrder');
    
    function filterOrders() {
        const status = filterStatus.value;
        const type = filterType.value;
        const payment = filterPayment.value;
        const search = searchOrder.value.toLowerCase();
        
        document.querySelectorAll('.order-row').forEach(row => {
            const rowStatus = row.dataset.status;
            const rowType = row.dataset.type;
            const rowPayment = row.dataset.payment;
            const rowSearch = row.dataset.search;
            
            const matchStatus = status === 'all' || rowStatus === status;
            const matchType = type === 'all' || rowType === type;
            const matchPayment = payment === 'all' || rowPayment === payment;
            const matchSearch = search === '' || rowSearch.includes(search);
            
            if (matchStatus && matchType && matchPayment && matchSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    filterStatus.addEventListener('change', filterOrders);
    filterType.addEventListener('change', filterOrders);
    filterPayment.addEventListener('change', filterOrders);
    searchOrder.addEventListener('input', filterOrders);
    
    // Auto refresh every 30 seconds
    setInterval(() => {
        location.reload();
    }, 30000);
});

function updateStatus(orderId, status) {
    if (!confirm('Update status pesanan?')) return;
    
    fetch(`/admin/order/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal update status: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Meskipun ada error, coba reload untuk melihat apakah update berhasil
        location.reload();
    });
}

function markAsPaid(orderId) {
    if (!confirm('Tandai pesanan ini sebagai LUNAS?')) return;
    
    fetch(`/admin/order/${orderId}/payment`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            payment_status: 'paid',
            payment_method: 'cash' // Default cash, bisa disesuaikan
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal update status pembayaran: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Meskipun ada error, coba reload untuk melihat apakah update berhasil
        location.reload();
    });
}
</script>
@endsection