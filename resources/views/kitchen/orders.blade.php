@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Pesanan Sedang Diproses</h4>
                </div>
                <div class="card-body">
                    @forelse($preparingOrders as $order)
                    <div class="border p-3 mb-3 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                            <span class="badge bg-primary">Sedang Diproses</span>
                        </div>
                        <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        @if($order->table_number)
                            <p class="mb-1"><strong>No. Meja:</strong> {{ $order->table_number }}</p>
                        @endif
                        
                        <h6>Item Pesanan:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($order->orderItems as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ $item->menu->name }}</div>
                                    <small>Jumlah: {{ $item->quantity }}</small>
                                    @if($item->special_instructions)
                                        <div class="text-muted"><small>Instruksi: {{ $item->special_instructions }}</small></div>
                                    @endif
                                </div>
                                <span class="badge bg-secondary rounded-pill">Rp {{ number_format($item->price, 2, ',', '.') }}</span>
                            </li>
                            @endforeach
                        </ul>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-update-status" 
                                    data-order-id="{{ $order->id }}" 
                                    data-status="ready">
                                Tandai Siap
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">Tidak ada pesanan sedang diproses</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Pesanan Siap Diantar</h4>
                </div>
                <div class="card-body">
                    @forelse($readyOrders as $order)
                    <div class="border p-3 mb-3 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                            <span class="badge bg-success">Siap Diantar</span>
                        </div>
                        <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        @if($order->table_number)
                            <p class="mb-1"><strong>No. Meja:</strong> {{ $order->table_number }}</p>
                        @endif
                        
                        <h6>Item Pesanan:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($order->orderItems as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ $item->menu->name }}</div>
                                    <small>Jumlah: {{ $item->quantity }}</small>
                                    @if($item->special_instructions)
                                        <div class="text-muted"><small>Instruksi: {{ $item->special_instructions }}</small></div>
                                    @endif
                                </div>
                                <span class="badge bg-secondary rounded-pill">Rp {{ number_format($item->price, 2, ',', '.') }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @empty
                    <p class="text-center text-muted">Tidak ada pesanan siap diantar</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan event listener untuk tombol update status
    document.querySelectorAll('.btn-update-status').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const status = this.dataset.status;
            
            fetch(`/kitchen/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status pesanan berhasil diperbarui');
                    location.reload();
                } else {
                    alert('Gagal memperbarui status pesanan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
    });
});
</script>
@endsection