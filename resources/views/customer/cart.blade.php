@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container">
    <style>
        .form-control:focus, .form-select:focus {
            border-color: #4A7F5A;
            box-shadow: 0 0 0 0.2rem rgba(74, 127, 90, 0.25);
        }
    </style>
    <div class="page-header">
        <h2><i class="bi bi-cart3"></i> Keranjang Belanja</h2>
        <p>Review pesanan Anda sebelum checkout</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-white" style="background-color: #2A5C3F;">
                        <i class="bi bi-list-ul"></i> Item Pesanan ({{ count($cart) }} item)
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="15%">Gambar</th>
                                        <th width="25%">Menu</th>
                                        <th width="15%">Harga</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Catatan</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $menuId => $item)
                                        @php $subtotal = $item['price'] * $item['quantity']; @endphp
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
                                                <small class="fw-bold item-subtotal" style="color: #2A5C3F;">Rp {{ number_format($subtotal, 0, ',', '.') }}</small>
                                            </td>
                                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 110px;">
                                                    <button class="btn btn-outline-secondary btn-decrease" type="button" style="border-color: #4A7F5A; color: #4A7F5A;">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control text-center quantity-input" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" 
                                                           readonly
                                                           style="border-color: #4A7F5A;">
                                                    <button class="btn btn-outline-secondary btn-increase" type="button" style="border-color: #4A7F5A; color: #4A7F5A;">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="form-control form-control-sm item-notes" 
                                                          rows="2" 
                                                          placeholder="Catatan khusus..."
                                                          data-menu-id="{{ $menuId }}"
                                                          style="font-size: 0.85rem; resize: none;">{{ $item['notes'] ?? '' }}</textarea>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-remove text-white" type="button" 
                                                        style="background-color: #D32F2F; transition: all 0.3s;"
                                                        onmouseover="this.style.backgroundColor='#B71C1C'; this.style.transform='translateY(-2px)';'" 
                                                        onmouseout="this.style.backgroundColor='#D32F2F'; this.style.transform='translateY(0)';">
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
                    <a href="{{ route('customer.menu') }}" class="btn text-white" 
                       style="background-color: #2A5C3F; transition: all 0.3s;"
                       onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                       onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                    <button type="button" class="btn text-white" id="clearCartBtn" 
                            style="background-color: #D32F2F; transition: all 0.3s;"
                            onmouseover="this.style.backgroundColor='#B71C1C'; this.style.transform='translateY(-2px)';" 
                            onmouseout="this.style.backgroundColor='#D32F2F'; this.style.transform='translateY(0)';">
                        <i class="bi bi-trash"></i> Kosongkan
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 80px; z-index: 100;">
                    <div class="card-header text-white" style="background-color: #2A5C3F;">
                        <i class="bi bi-receipt"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body">
                        <!-- Voucher Section -->
                        @auth
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-gift"></i> Punya Kode Voucher?
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" 
                                           class="form-control" 
                                           id="voucherCode" 
                                           placeholder="KODE VOUCHER"
                                           style="text-transform: uppercase;"
                                           value="{{ session('applied_voucher_code') ?? '' }}">
                                    <button class="btn btn-success" type="button" id="applyVoucher">
                                        <i class="bi bi-check-circle"></i> Gunakan
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <a href="{{ route('customer.vouchers') }}" target="_blank">
                                        <i class="bi bi-ticket-perforated"></i> Lihat voucher tersedia
                                    </a>
                                </small>
                                
                                <!-- Voucher Message -->
                                <div id="voucherMessage" class="mt-2"></div>
                                
                                <!-- Applied Voucher Display -->
                                @if(session('applied_voucher'))
                                    <div id="voucherApplied" class="alert alert-success alert-sm mt-2 p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <small class="fw-bold">{{ session('applied_voucher')['name'] }}</small>
                                                <br><small class="text-success">Diskon: Rp {{ number_format(session('applied_voucher')['discount'], 0, ',', '.') }}</small>
                                            </div>
                                            <a href="{{ route('customer.cart') }}?remove_voucher=1" class="btn-close btn-sm"></a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="alert" style="background-color: #E3F2FD; color: #1976D2; border-color: #BBDEFB;">
                                    <i class="bi bi-info-circle"></i> 
                                    <strong>Mau pakai voucher?</strong>
                                    <br><small>Silakan <a href="{{ route('login') }}" class="alert-link" style="color: #1565C0;">login</a> terlebih dahulu untuk menggunakan voucher diskon.</small>
                                </div>
                            </div>
                        @endauth

                        <hr>

                        <!-- Price Summary -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="cartSubtotal">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        
                        <div id="cartDiscountRow" class="d-flex justify-content-between mb-2 text-success" style="{{ session('applied_voucher') ? '' : 'display: none !important;' }}">
                            <span><i class="bi bi-gift"></i> Diskon Voucher:</span>
                            <strong id="cartDiscountAmount">- Rp {{ number_format(session('applied_voucher')['discount'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        @php
                            $discount = session('applied_voucher')['discount'] ?? 0;
                            $afterDiscount = $total - $discount;
                        @endphp
                        
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <h4 class="mb-0 fw-bold" id="cartTotal" style="color: #2A5C3F;">Rp {{ number_format($afterDiscount, 0, ',', '.') }}</h4>
                        </div>
                        <button type="button" class="btn text-white w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#checkoutModal"
                                style="background-color: #2A5C3F; transition: all 0.3s;"
                                onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
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
                <a href="{{ route('customer.menu') }}" class="btn text-white mt-3"
                   style="background-color: #2A5C3F; transition: all 0.3s;"
                   onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                   onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
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
                <h5 class="modal-title" style="color: #2A5C3F;"><i class="bi bi-check-circle"></i> Konfirmasi Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="checkoutForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person" style="color: #4A7F5A;"></i> Informasi Pelanggan</h6>
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                       required>
                            </div>
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
                                           style="background-color: #F3F7F4; border-color: #ced4da;">
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
                            <h6 class="fw-bold mb-3"><i class="bi bi-bag" style="color: #4A7F5A;"></i> Detail Pesanan</h6>
                            
                            <!-- Hidden input untuk order type (selalu dine_in) -->
                            <input type="hidden" name="order_type" value="dine_in">
                            
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="cash" selected>Tunai</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                            style="background-color: #ffffff; color: #4A7F5A; border: 1px solid #4A7F5A; transition: all 0.3s;"
                            onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateY(-2px)';" 
                            onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)';">
                        Batal
                    </button>
                    <button type="submit" class="btn text-white btn-lg"
                            style="background-color: #2A5C3F; transition: all 0.3s;"
                            onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                            onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
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
            
            input.value = quantity;
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
    
    // Handle item notes with debounce
    let notesTimeout;
    document.querySelectorAll('.item-notes').forEach(textarea => {
        textarea.addEventListener('input', function() {
            const menuId = this.dataset.menuId;
            const notes = this.value;
            
            // Clear previous timeout
            clearTimeout(notesTimeout);
            
            // Set new timeout (wait 500ms after user stops typing)
            notesTimeout = setTimeout(() => {
                updateItemNotes(menuId, notes);
            }, 500);
        });
    });
    
    function updateItemNotes(menuId, notes) {
        fetch('{{ route("customer.cart.update-notes") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ menu_id: menuId, notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Notes updated for menu', menuId);
            }
        })
        .catch(error => console.error('Error updating notes:', error));
    }
    
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
                // Update item subtotal
                const row = document.querySelector(`tr[data-menu-id="${menuId}"]`);
                if (row) {
                    const subtotalEl = row.querySelector('.item-subtotal');
                    if (subtotalEl) {
                        subtotalEl.textContent = formatCurrency(data.item_subtotal);
                    }
                }

                // Update cart subtotal
                const cartSubtotalEl = document.getElementById('cartSubtotal');
                if (cartSubtotalEl) {
                    cartSubtotalEl.textContent = formatCurrency(data.cart_subtotal);
                }

                // Update discount
                const discountRow = document.getElementById('cartDiscountRow');
                const discountAmountEl = document.getElementById('cartDiscountAmount');
                
                if (data.discount_amount > 0) {
                    if (discountRow) discountRow.style.setProperty('display', 'flex', 'important');
                    if (discountAmountEl) discountAmountEl.textContent = '- ' + formatCurrency(data.discount_amount);
                } else {
                    if (discountRow) discountRow.style.setProperty('display', 'none', 'important');
                }

                // Update cart total
                const cartTotalEl = document.getElementById('cartTotal');
                if (cartTotalEl) {
                    cartTotalEl.textContent = formatCurrency(data.cart_total);
                }
                
                // Update header badge if exists (optional, if header is dynamic)
                // location.reload() was doing this, but now we are SPA-ish here.
            } else {
                // Handle Error (e.g. Stock Insufficient)
                alert('⚠️ ' + (data.message || 'Gagal mengupdate keranjang'));
                
                // Reset input to previous valid value or max stock if provided
                const row = document.querySelector(`tr[data-menu-id="${menuId}"]`);
                if (row) {
                    const input = row.querySelector('.quantity-input');
                    // data.current_qty sent from backend if available
                    if (data.current_qty !== undefined) {
                        input.value = data.current_qty;
                    } else {
                        // Fallback: reload to sync with server state
                        location.reload(); 
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Tidak dapat terhubung ke server');
        });
    }

    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
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
    
    // Voucher Application
    const applyVoucherBtn = document.getElementById('applyVoucher');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const code = document.getElementById('voucherCode').value.trim().toUpperCase();
            const subtotal = {{ $total ?? 0 }};
            const messageDiv = document.getElementById('voucherMessage');
            
            if (!code) {
                messageDiv.innerHTML = '<small class="text-danger">Masukkan kode voucher</small>';
                return;
            }
            
            // Show loading
            applyVoucherBtn.disabled = true;
            applyVoucherBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Validasi...';
            
            // Redirect to cart with voucher parameter
            window.location.href = '{{ route("customer.cart") }}?apply_voucher=' + code;
        });
    }
});
</script>
@endsection