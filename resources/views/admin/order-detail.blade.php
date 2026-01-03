@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color: #2F6A48;">
                    <h4>Detail Pesanan #{{ $order->order_number }}</h4>
                    <div class="d-flex gap-2">
                        @if($order->payment_status === 'paid')
                        <a href="{{ route('admin.order.receipt', $order->id) }}" class="btn text-white" target="_blank"
                           style="background-color: #2563EB; transition: all 0.3s;"
                           onmouseover="this.style.backgroundColor='#1d4ed8'; this.style.transform='translateY(-2px)';" 
                           onmouseout="this.style.backgroundColor='#2563EB'; this.style.transform='translateY(0)';">
                            <i class="bi bi-printer"></i> Cetak Struk
                        </a>
                        @endif
                        <a href="{{ route('admin.orders') }}" class="btn"
                           style="background-color: #E5E7EB; color: #333; transition: all 0.3s;"
                           onmouseover="this.style.backgroundColor='#d1d5db'; this.style.transform='translateY(-2px)';" 
                           onmouseout="this.style.backgroundColor='#E5E7EB'; this.style.transform='translateY(0)';">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Pelanggan</h5>
                            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                            @if($order->table_number)
                                <p><strong>No. Meja:</strong> {{ $order->table_number }}</p>
                            @endif
                            <p><strong>Tipe Pesanan:</strong> 
                                @if($order->order_type === 'dine_in')
                                    Dine In
                                @elseif($order->order_type === 'takeaway')
                                    Takeaway
                                @else
                                    Delivery
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Status Pesanan</h5>
                            <p>
                                <strong>Status saat ini:</strong> 
                                <span class="badge text-white" style="
                                    @if($order->status === 'pending') background-color: #F7C948;
                                    @elseif($order->status === 'confirmed') background-color: #3B82F6;
                                    @elseif($order->status === 'preparing') background-color: #FFA552;
                                    @elseif($order->status === 'ready') background-color: #46A36B;
                                    @elseif($order->status === 'delivered') background-color: #46A36B;
                                    @else background-color: #D9534F;
                                    @endif
                                ">
                                    @if($order->status === 'pending') Pending
                                    @elseif($order->status === 'confirmed') Confirmed
                                    @elseif($order->status === 'preparing') Sedang Diproses
                                    @elseif($order->status === 'ready') Siap Diantar
                                    @elseif($order->status === 'delivered') Selesai
                                    @else Dibatalkan
                                    @endif
                                </span>
                            </p>
                            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                            <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                            <p>
                                <strong>Metode Pembayaran:</strong> 
                                <span class="badge text-white" style="background-color: {{ $order->payment_method === 'qris' ? '#6B7280' : '#9CA3AF' }};">
                                    @if($order->payment_method === 'cash')
                                        Tunai
                                    @elseif($order->payment_method === 'qris')
                                        QRIS
                                    @elseif($order->payment_method === 'card')
                                        Kartu
                                    @else
                                        {{ strtoupper($order->payment_method) }}
                                    @endif
                                </span>
                            </p>
                            <p>
                                <strong>Status Pembayaran:</strong> 
                                <span class="badge text-white" style="background-color: {{ $order->payment_status === 'paid' ? '#46A36B' : '#FFA552' }};">
                                    {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($order->special_requests)
                        <div class="alert alert-info mb-4">
                            <h6>Permintaan Khusus:</h6>
                            <p>{{ $order->special_requests }}</p>
                        </div>
                    @endif
                    
                    <h5>Item Pesanan:</h5>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        {{ $item->menu->name }}
                                        @if($item->special_instructions)
                                            <br><small class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $item->special_instructions }}</small>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if($order->voucher_code)
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal Item:</th>
                                        <th>Rp {{ number_format($order->subtotal ?? $order->total_amount + $order->discount_amount, 0, ',', '.') }}</th>
                                    </tr>
                                    <tr style="background-color: #D7EDE0;">
                                        <th colspan="3" class="text-end">
                                            <i class="bi bi-ticket-perforated"></i> Diskon Voucher ({{ $order->voucher_code }}):
                                        </th>
                                        <th class="text-success">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</th>
                                    </tr>
                                    <tr style="background-color: #E2F1E7;">
                                        <th colspan="3" class="text-end">Total Bayar:</th>
                                        <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                    </tr>
                                @endif
                                
                                @if($order->payment_status === 'paid' && $order->payment_method === 'cash')
                                <tr>
                                    <th colspan="3" class="text-end">Uang Dibayar:</th>
                                    <th>Rp {{ number_format($order->amount_paid ?? $order->total_amount, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Kembalian:</th>
                                    <th class="text-success">Rp {{ number_format($order->change_amount ?? 0, 2, ',', '.') }}</th>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    <!-- Tombol Konfirmasi Pesanan -->
                    @if($order->payment_status !== 'paid' && $order->payment_method !== 'qris')
                    <div class="mt-4">
                        <button type="button" class="btn btn-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="bi bi-cash-coin"></i> Konfirmasi Pembayaran
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Uang Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Total Pembayaran:</strong><br>
                    <h3 class="mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
                </div>

                <form id="paymentForm">
                    <input type="hidden" id="payment_method" name="payment_method" value="cash">

                    <div id="cashPaymentSection">
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Uang Diterima <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="amount_paid" 
                                   name="amount_paid" 
                                   placeholder="Masukkan jumlah uang"
                                   min="{{ $order->total_amount }}"
                                   required>
                        </div>

                        <div class="alert alert-warning" id="changeAlert" style="display: none;">
                            <strong>Kembalian:</strong><br>
                            <h4 class="mb-0" id="changeAmount">Rp 0</h4>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmPaymentBtn">
                    <i class="bi bi-check-circle"></i> Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountPaidInput = document.getElementById('amount_paid');
    const changeAlert = document.getElementById('changeAlert');
    const changeAmount = document.getElementById('changeAmount');
    const totalAmount = {{ $order->total_amount }};

    // Calculate change
    amountPaidInput.addEventListener('input', function() {
        const paid = parseFloat(this.value) || 0;
        const change = paid - totalAmount;

        if (change >= 0) {
            changeAlert.style.display = 'block';
            changeAmount.textContent = 'Rp ' + change.toLocaleString('id-ID');
        } else {
            changeAlert.style.display = 'none';
        }
    });

    // Confirm payment
    document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
        const paymentMethod = document.getElementById('payment_method').value;
        const amountPaid = parseFloat(amountPaidInput.value) || 0;

        // Validate
        if (amountPaid < totalAmount) {
            alert('Uang yang diterima kurang dari total pembayaran!');
            return;
        }

        if (!confirm('Konfirmasi pembayaran pesanan ini?')) {
            return;
        }

        // Disable button
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        const requestData = {
            payment_status: 'paid',
            payment_method: paymentMethod,
            amount_paid: amountPaid
        };

        console.log('Sending payment data:', requestData);
        console.log('Total amount:', totalAmount);
        console.log('Expected change:', amountPaid - totalAmount);

        // Send request
        fetch('{{ route("admin.order.verify-payment", $order->id) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                modal.hide();

                // Show print receipt button
                alert('Pembayaran berhasil dikonfirmasi!');
                location.reload();
            } else {
                alert('Gagal: ' + (data.message || 'Unknown error'));
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-circle"></i> Konfirmasi';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
    });
});
</script>
@endsection