@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container">
    <div class="page-header">
        <h2><i class="bi bi-cart3"></i> Keranjang Belanja</h2>
        <p>Review pesanan Anda sebelum checkout</p>
    </div>

    @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-list-ul"></i> Item Pesanan ({{ count($cart) }} item)
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="15%">Gambar</th>
                                        <th width="30%">Menu</th>
                                        <th width="20%">Harga</th>
                                        <th width="20%">Jumlah</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($cart as $menuId => $item)
                                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                        <tr data-menu-id="{{ $menuId }}">
                                            <td>
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                                         alt="{{ $item['name'] }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 70px; height: 70px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 70px; height: 70px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item['name'] }}</strong>
                                                <br>
                                                <small class="text-dark fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</small>
                                            </td>
                                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 110px;">
                                                    <button class="btn btn-outline-secondary btn-decrease" type="button">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control text-center quantity-input" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" 
                                                           readonly>
                                                    <button class="btn btn-outline-secondary btn-increase" type="button">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger btn-remove" type="button">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('customer.menu') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                    <button type="button" class="btn btn-danger" id="clearCartBtn">
                        <i class="bi bi-trash"></i> Kosongkan
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-success">
                        <i class="bi bi-receipt"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="cartSubtotal">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pajak (10%):</span>
                            <strong id="cartTax">Rp {{ number_format($total * 0.1, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <h4 class="text-dark mb-0" id="cartTotal">Rp {{ number_format($total * 1.1, 0, ',', '.') }}</h4>
                        </div>
                        <button type="button" class="btn btn-success w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <i class="bi bi-check-circle"></i> Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: #ccc;"></i>
                <h4 class="mt-3">Keranjang Kosong</h4>
                <p class="text-muted">Belum ada menu yang ditambahkan ke keranjang</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-card-list"></i> Lihat Menu
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-check-circle"></i> Konfirmasi Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="checkoutForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person"></i> Informasi Pelanggan</h6>
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                       required>
                            </div>
                            @if(auth()->check())
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       value="{{ auth()->user()->phone }}"
                                       required>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="table_number" class="form-label">No. Meja</label>
                                @php
                                    $tableNumber = session('table_number');
                                @endphp
                                @if($tableNumber)
                                    <input type="text" 
                                           class="form-control" 
                                           id="table_number" 
                                           name="table_number" 
                                           value="{{ $tableNumber }}" 
                                           readonly
                                           style="background-color: #e9ecef;">
                                    <small class="text-muted">Nomor meja dari QR Code scan</small>
                                @else
                                    <input type="text" 
                                           class="form-control" 
                                           id="table_number" 
                                           name="table_number" 
                                           placeholder="Opsional">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-bag"></i> Detail Pesanan</h6>
                            
                            <!-- Hidden input untuk order type (selalu dine_in) -->
                            <input type="hidden" name="order_type" value="dine_in">
                            
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="cash" selected>Tunai</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="special_requests" class="form-label">Catatan Pesanan</label>
                                <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Catatan umum untuk seluruh pesanan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update quantity
    document.querySelectorAll('.btn-increase, .btn-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const menuId = row.dataset.menuId;
            const input = row.querySelector('.quantity-input');
            let quantity = parseInt(input.value);
            
            if (this.classList.contains('btn-increase')) {
                quantity++;
            } else if (this.classList.contains('btn-decrease') && quantity > 1) {
                quantity--;
            }
            
            updateCartQuantity(menuId, quantity);
        });
    });
    
    // Remove item
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Hapus item ini dari keranjang?')) {
                const row = this.closest('tr');
                const menuId = row.dataset.menuId;
                removeFromCart(menuId);
            }
        });
    });
    
    // Clear cart
    document.getElementById('clearCartBtn')?.addEventListener('click', function() {
        if (confirm('Kosongkan semua item di keranjang?')) {
            clearCart();
        }
    });
    
    // Checkout
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Disable submit button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
        fetch('{{ route("customer.order.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
                modal.hide();
                
                // Redirect to order success page
                window.location.href = `/order/${data.order_number}/success`;
            } else {
                alert('❌ Gagal membuat pesanan: ' + (data.message || 'Terjadi kesalahan'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan saat membuat pesanan');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    function updateCartQuantity(menuId, quantity) {
        fetch('{{ route("customer.cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ menu_id: menuId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function removeFromCart(menuId) {
        fetch(`/cart/remove/${menuId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function clearCart() {
        fetch('{{ route("customer.cart.clear") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>
@endsection