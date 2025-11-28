@extends('layouts.app')

@section('title', 'Dapur - Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header mb-4">
        <h2><i class="bi bi-fire"></i> Dashboard Dapur</h2>
        <p>Kelola pesanan yang perlu diproses</p>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-0">{{ $confirmedOrders->count() }}</h1>
                    <p class="mb-0">Pesanan Baru</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-0">{{ $preparingOrders->count() }}</h1>
                    <p class="mb-0">Sedang Diproses</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-0">{{ $readyOrders->count() }}</h1>
                    <p class="mb-0">Siap Disajikan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sedang Diproses (Preparing) -->
    @if($preparingOrders->count() > 0)
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-fire"></i> Sedang Diproses ({{ $preparingOrders->count() }})</h4>
        </div>
        <div class="card-body p-0">
            <div class="row g-3 p-3">
                @foreach($preparingOrders as $order)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-primary">{{ $order->order_number }}</strong>
                                <span class="badge bg-primary">{{ $order->order_type }}</span>
                            </div>
                            <small class="text-muted">Mulai: {{ $order->updated_at->format('H:i') }}</small>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-2">{{ $order->customer_name }}</h6>
                            @if($order->table_number)
                                <p class="mb-2"><i class="bi bi-geo-alt"></i> Meja: <strong>{{ $order->table_number }}</strong></p>
                            @endif
                            <hr>
                            <ul class="list-unstyled mb-0">
                                @foreach($order->orderItems as $item)
                                <li class="mb-2">
                                    <strong class="text-primary">{{ $item->quantity }}x</strong> {{ $item->menu->name }}
                                    @if($item->special_instructions)
                                        <br><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $item->special_instructions }}</small>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success w-100 btn-mark-completed" data-order-id="{{ $order->id }}">
                                <i class="bi bi-check-circle-fill"></i> Tandai Selesai
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($preparingOrders->count() == 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-check-circle" style="font-size: 5rem; color: #28a745;"></i>
            <h3 class="mt-3">Semua Pesanan Selesai!</h3>
            <p class="text-muted">Tidak ada pesanan yang perlu diproses saat ini</p>
        </div>
    </div>
    @endif
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

@media print {
    .page-header, .btn, .card-footer form {
        display: none;
    }
}
</style>

<script>
// Handle Mulai Proses button
document.querySelectorAll('.btn-start-process').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.dataset.orderId;
        
        if (!confirm('Mulai memproses pesanan ini?')) return;
        
        fetch(`/kitchen/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: 'preparing' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
});

// Handle Tandai Selesai button
document.querySelectorAll('.btn-mark-completed').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.dataset.orderId;
        
        if (!confirm('Tandai pesanan ini sebagai SELESAI (Siap Disajikan)?')) return;
        
        fetch(`/kitchen/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: 'ready' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
});

// Auto refresh every 15 seconds
setInterval(() => {
    location.reload();
}, 15000);

// Sound notification for new orders (preparing orders that just arrived)
@if($preparingOrders->count() > 0)
    // Play notification sound
    // const audio = new Audio('/sounds/notification.mp3');
    // audio.play().catch(e => console.log('Audio play failed:', e));
@endif
</script>
@endsection