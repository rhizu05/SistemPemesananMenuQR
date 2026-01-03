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
        <div class="col-md-6">
            <div class="card text-white" style="background-color: #1976D2;">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-0">{{ $preparingOrders->count() }}</h1>
                    <p class="mb-0">Sedang Diproses</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white" style="background-color: #2A5C3F;">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-0">{{ $readyOrders->count() }}</h1>
                    <p class="mb-0">Siap Disajikan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sedang Diproses (Preparing) -->
    @if($preparingOrders->count() > 0)
    <div class="card mb-4" style="border-color: #1976D2;">
        <div class="card-header text-white" style="background-color: #1976D2;">
            <h4 class="mb-0"><i class="bi bi-fire"></i> Sedang Diproses ({{ $preparingOrders->count() }})</h4>
        </div>
        <div class="card-body p-0">
            <div class="row g-3 p-3">
                @foreach($preparingOrders as $order)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100" style="border: 2px solid #E3F2FD;">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="color: #1976D2;">{{ $order->order_number }}</strong>
                                <span class="badge" style="background-color: #1976D2;">{{ str_replace('_', ' ', strtoupper($order->order_type)) }}</span>
                            </div>
                            <small class="text-muted">Mulai: {{ $order->updated_at->format('H:i') }}</small>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-2">{{ $order->customer_name }}</h6>
                            @if($order->table_number)
                                <p class="mb-2" style="color: #1976D2;"><i class="bi bi-geo-alt"></i> Meja: <strong>{{ $order->table_number }}</strong></p>
                            @endif
                            <hr>
                            <ul class="list-unstyled mb-0">
                                @foreach($order->orderItems as $item)
                                <li class="mb-2">
                                    <strong style="color: #1976D2;">{{ $item->quantity }}x</strong> {{ $item->menu->name }}
                                    @if($item->special_instructions)
                                        <br><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $item->special_instructions }}</small>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn w-100 btn-mark-completed text-white" data-order-id="{{ $order->id }}"
                                    style="background-color: #2A5C3F; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
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

    @if($preparingOrders->count() == 0 && $readyOrders->count() == 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-check-circle" style="font-size: 5rem; color: #2A5C3F;"></i>
            <h3 class="mt-3">Semua Pesanan Selesai!</h3>
            <p class="text-muted">Tidak ada pesanan yang perlu diproses saat ini</p>
        </div>
    </div>
    @endif

    <!-- Siap Disajikan (Ready) -->
    @if($readyOrders->count() > 0)
    <div class="card mb-4" style="border-color: #2A5C3F;">
        <div class="card-header text-white" style="background-color: #2A5C3F;">
            <h4 class="mb-0"><i class="bi bi-check-circle-fill"></i> Siap Disajikan ({{ $readyOrders->count() }})</h4>
        </div>
        <div class="card-body p-0">
            <div class="row g-3 p-3">
                @foreach($readyOrders as $order)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100" style="border: 2px solid #C8E6C9;">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="color: #2A5C3F;">{{ $order->order_number }}</strong>
                                <span class="badge" style="background-color: #2A5C3F;">{{ str_replace('_', ' ', strtoupper($order->order_type)) }}</span>
                            </div>
                            <small class="text-muted">Selesai: {{ $order->updated_at->format('H:i') }}</small>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-2">{{ $order->customer_name }}</h6>
                            @if($order->table_number)
                                <p class="mb-2" style="color: #2A5C3F;"><i class="bi bi-geo-alt"></i> Meja: <strong>{{ $order->table_number }}</strong></p>
                            @endif
                            <hr>
                            <ul class="list-unstyled mb-0">
                                @foreach($order->orderItems as $item)
                                <li class="mb-2">
                                    <strong style="color: #2A5C3F;">{{ $item->quantity }}x</strong> {{ $item->menu->name }}
                                    @if($item->special_instructions)
                                        <br><small class="text-muted"><i class="bi bi-info-circle"></i> {{ $item->special_instructions }}</small>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Siap</span>
                                <a href="{{ route('kitchen.order.receipt', $order->id) }}" 
                                   class="btn btn-sm text-white"
                                   style="background-color: #1976D2;"
                                   target="_blank"
                                   title="Cetak Struk">
                                    <i class="bi bi-printer"></i> Cetak Struk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
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

// Real-time update for kitchen dashboard using AJAX polling
let previousPreparingCount = {{ $preparingOrders->count() }};
let previousReadyCount = {{ $readyOrders->count() }};

function updateKitchenCounts() {
    fetch('{{ route("kitchen.orders-count") }}')
        .then(response => response.json())
        .then(data => {
            // Update stat cards
            const preparingCard = document.querySelector('.col-md-6:nth-child(1) h1');
            const readyCard = document.querySelector('.col-md-6:nth-child(2) h1');
            
            if (preparingCard) {
                preparingCard.textContent = data.preparing;
            }
            if (readyCard) {
                readyCard.textContent = data.ready;
            }
            
            // Check for new orders
            if (data.preparing > previousPreparingCount) {
                console.log('New order detected in kitchen!');
                // Optional: Play sound notification
                // const audio = new Audio('/sounds/notification.mp3');
                // audio.play().catch(e => console.log('Audio play failed:', e));
                
                // Reload page to show new order cards
                location.reload();
            }
            
            // Check if order moved to ready
            if (data.ready > previousReadyCount) {
                console.log('Order moved to ready!');
                // Reload to update the display
                location.reload();
            }
            
            // Check if counts decreased (order was completed/removed)
            if (data.preparing < previousPreparingCount || data.ready < previousReadyCount) {
                console.log('Order status changed, refreshing...');
                location.reload();
            }
            
            previousPreparingCount = data.preparing;
            previousReadyCount = data.ready;
        })
        .catch(error => {
            console.error('Error fetching kitchen counts:', error);
        });
}

// Initial update
updateKitchenCounts();

// Poll every 3 seconds for real-time updates
setInterval(updateKitchenCounts, 3000);

// Sound notification for new orders (preparing orders that just arrived)
@if($preparingOrders->count() > 0)
    // Play notification sound
    // const audio = new Audio('/sounds/notification.mp3');
    // audio.play().catch(e => console.log('Audio play failed:', e));
@endif
</script>
@endsection