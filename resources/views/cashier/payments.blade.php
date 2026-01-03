@extends('layouts.app')

@section('title', 'Pending Payments')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-credit-card me-2"></i>Pending Payments</h2>
        <a href="{{ route('cashier.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header" style="background: #FBC02D; color: #333;">
            <i class="bi bi-clock-history me-2"></i>Pembayaran Pending ({{ $orders->count() }} pesanan)
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment Method</th>
                                <th>Order Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->customer_name ?? ($order->user->name ?? 'Guest') }}</td>
                                <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($order->payment_method == 'qris')
                                        <span class="badge bg-info">QRIS</span>
                                    @elseif($order->payment_method == 'cash')
                                        <span class="badge bg-success">Cash</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_method) }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#paymentModal{{ $order->id }}"
                                            data-order-id="{{ $order->id }}"
                                            data-order-total="{{ $order->total_amount }}">
                                        <i class="bi bi-check-circle"></i> Verify Payment
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                    <p class="text-muted mt-3">Semua pembayaran sudah terverifikasi! 🎉</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Payment Modals -->
@foreach($orders as $order)
<div class="modal fade" id="paymentModal{{ $order->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel{{ $order->id }}">
                    <i class="bi bi-cash-coin"></i> Verifikasi Pembayaran Cash
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cashier.payments.verify', $order->id) }}" method="POST" id="paymentForm{{ $order->id }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Order #{{ $order->id }}</strong><br>
                        Customer: {{ $order->customer_name ?? ($order->user->name ?? 'Guest') }}
                    </div>

                    <!-- Order Items Details -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detail Pesanan</label>
                        <div class="border rounded p-2" style="background-color: #f8f9fa; max-height: 200px; overflow-y: auto;">
                            <table class="table table-sm table-borderless mb-0">
                                <thead class="text-muted small">
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                {{ $item->menu->name }}
                                                @if($item->special_instructions)
                                                    <br><small class="text-muted fst-italic">Note: {{ $item->special_instructions }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    
                                    @if($order->voucher_id)
                                        <tr class="border-top">
                                            <td colspan="2" class="text-success">Voucher ({{ $order->voucher_code }})</td>
                                            <td class="text-end text-success">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Pembayaran</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control form-control-lg fw-bold" 
                                   value="{{ number_format($order->total_amount, 0, ',', '.') }}" 
                                   readonly style="background-color: #f8f9fa;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amountPaid{{ $order->id }}" class="form-label fw-bold">Uang Dibayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="amountPaid{{ $order->id }}" 
                                   name="amount_paid"
                                   min="{{ $order->total_amount }}"
                                   step="1000"
                                   required
                                   placeholder="Masukkan jumlah uang"
                                   onkeyup="calculateChange{{ $order->id }}()">
                        </div>
                        <small class="text-muted">Minimal: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kembalian</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" 
                                   class="form-control form-control-lg fw-bold" 
                                   id="changeAmount{{ $order->id }}" 
                                   value="0" 
                                   readonly 
                                   style="background-color: #d4edda; color: #155724;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn{{ $order->id }}" disabled>
                        <i class="bi bi-check-circle"></i> Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateChange{{ $order->id }}() {
    const total = {{ $order->total_amount }};
    const paid = parseFloat(document.getElementById('amountPaid{{ $order->id }}').value) || 0;
    const change = paid - total;
    const changeInput = document.getElementById('changeAmount{{ $order->id }}');
    const submitBtn = document.getElementById('submitBtn{{ $order->id }}');
    
    if (change >= 0) {
        changeInput.value = change.toLocaleString('id-ID');
        changeInput.style.backgroundColor = '#d4edda';
        changeInput.style.color = '#155724';
        submitBtn.disabled = false;
    } else {
        changeInput.value = 'Uang kurang!';
        changeInput.style.backgroundColor = '#f8d7da';
        changeInput.style.color = '#721c24';
        submitBtn.disabled = true;
    }
}
</script>
@endforeach
@endsection
