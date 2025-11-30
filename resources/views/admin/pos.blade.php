@extends('layouts.app')

@section('title', 'POS - Point of Sale')

@section('content')
<div class="container-fluid">
    <div class="page-header mb-4">
        <h2><i class="bi bi-cash-register"></i> Point of Sale (POS)</h2>
        <p>Sistem kasir untuk pesanan takeaway dan dine-in</p>
    </div>

    <div class="row g-4">
        <!-- Menu Section (Left) -->
        <div class="col-lg-8">
            <!-- Category Filter -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm me-2 d-none d-md-block" id="scrollLeft" style="width: 32px; height: 32px;">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        
                        <div class="d-flex gap-2 overflow-auto category-scroll flex-grow-1" id="categoryContainer" style="scrollbar-width: none; -ms-overflow-style: none; white-space: nowrap; scroll-behavior: smooth;">
                            <button type="button" class="btn rounded-pill px-3 active flex-shrink-0 text-white" data-category="all" style="background-color: #2A5C3F; border-color: #2A5C3F;">
                                <i class="bi bi-grid-fill me-1"></i> Semua
                            </button>
                            @foreach($categories as $category)
                                <button type="button" class="btn btn-outline rounded-pill px-3 flex-shrink-0" data-category="{{ $category->id }}" style="color: #4A7F5A; border-color: #4A7F5A;">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                        
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm ms-2 d-none d-md-block" id="scrollRight" style="width: 32px; height: 32px;">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menu Grid -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-card-list"></i> Daftar Menu
                </div>
                <div class="card-body">
                    <div class="row g-3" id="menuGrid">
                        @foreach($menus as $menu)
                            <div class="col-md-4 col-sm-6 menu-item" data-category="{{ $menu->category_id }}">
                                <div class="card h-100 menu-card" style="cursor: pointer;" 
                                     data-menu-id="{{ $menu->id }}"
                                     data-menu-name="{{ $menu->name }}"
                                     data-menu-price="{{ $menu->price }}"
                                     data-menu-stock="{{ $menu->stock }}">
                                    @if($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" 
                                             class="card-img-top" 
                                             alt="{{ $menu->name }}"
                                             style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 120px;">
                                            <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1" style="font-size: 0.9rem;">{{ $menu->name }}</h6>
                                        <p class="fw-bold mb-1" style="color: #2A5C3F;">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                        <small class="{{ $menu->stock < 5 ? 'text-dark' : '' }}" style="{{ $menu->stock < 5 ? 'background-color: #FBC02D; padding: 2px 6px; border-radius: 3px;' : 'color: #4A7F5A;' }}">Stok: {{ $menu->stock }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section (Right) -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header text-white" style="background-color: #1976D2;">
                    <i class="bi bi-cart3"></i> Keranjang Pesanan
                </div>
                <div class="card-body">
                    <!-- Customer Info -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Informasi Pelanggan</label>
                        <input type="text" class="form-control form-control-sm mb-2" id="customerName" placeholder="Nama Pelanggan" required>
                    </div>

                    <!-- Order Type -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Pesanan</label>
                        <input type="text" class="form-control form-control-sm" value="Takeaway" readonly>
                        <input type="hidden" id="orderType" value="takeaway">
                    </div>

                    <!-- Cart Items -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Pesanan</label>
                        <div id="cartItems" style="max-height: 250px; overflow-y: auto;">
                            <div class="text-center text-muted py-4" id="emptyCart">
                                <i class="bi bi-cart-x" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Keranjang kosong</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-top pt-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <h5 class="fw-bold mb-0" id="total" style="color: #2A5C3F;">Rp 0</h5>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <select class="form-select form-select-sm" id="paymentMethod">
                            <option value="cash" selected>Tunai (Cash)</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <!-- Payment Details (hanya untuk Cash) -->
                    <div class="mb-3" id="cashPaymentSection">
                        <label class="form-label fw-bold">Uang Dibayar</label>
                        <input type="number" class="form-control form-control-sm mb-2" id="amountPaid" placeholder="0">
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                            <span class="fw-bold">Kembalian:</span>
                            <strong class="text-success h5 mb-0" id="changeAmountDisplay">Rp 0</strong>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <button type="button" class="btn text-white" id="processOrder" 
                                style="background-color: #2A5C3F; flex: 7; transition: all 0.3s;"
                                onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';" 
                                onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="bi bi-check-circle"></i> Proses Pesanan
                        </button>
                        <button type="button" class="btn btn-sm text-white" id="clearCart" 
                                style="background-color: #D32F2F; flex: 3; transition: all 0.3s;"
                                onmouseover="this.style.backgroundColor='#B71C1C'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';" 
                                onmouseout="this.style.backgroundColor='#D32F2F'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="bi bi-trash"></i> Kosongkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle"></i> Pesanan Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Pesanan Berhasil Dibuat!</h4>
                <p class="mb-2">Nomor Pesanan:</p>
                <h3 class="text-primary" id="orderNumber"></h3>
                <p class="mt-3">Total Pembayaran:</p>
                <h4 class="text-primary" id="orderTotal"></h4>
                
                <div class="row mt-3">
                    <div class="col-6 text-end border-end">
                        <small class="text-muted">Uang Dibayar</small>
                        <h5 id="modalAmountPaid"></h5>
                    </div>
                    <div class="col-6 text-start">
                        <small class="text-muted">Kembalian</small>
                        <h5 class="text-success" id="modalChangeAmount"></h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="printReceipt" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>

<!-- QRIS Modal -->
<div class="modal fade" id="qrisModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-qr-code-scan"></i> Scan QRIS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2">Silakan scan QR Code di bawah ini:</p>
                <div class="bg-light p-2 d-inline-block rounded mb-3">
                    <img id="qrisImage" src="" alt="QRIS Code" class="img-fluid" style="max-width: 200px;">
                </div>
                <p class="text-muted small">Total Pembayaran</p>
                <h4 class="text-primary" id="qrisTotal"></h4>
                <div class="alert alert-info small py-2">
                    <i class="bi bi-info-circle"></i> Menunggu pembayaran...
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-sm" onclick="checkPaymentStatus(true)">
                    <i class="bi bi-arrow-clockwise"></i> Cek Status Manual
                </button>
                <a href="#" id="printReceiptQris" class="btn btn-primary btn-sm d-none" target="_blank">
                    <i class="bi bi-printer"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.menu-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    border-color: #2A5C3F;
}

.menu-card:active {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    border-color: #2A5C3F;
    background-color: transparent !important;
}

.menu-card:focus,
.menu-card:focus-visible {
    outline: none;
    background-color: transparent !important;
}

/* Prevent any background on menu card and all children */
.menu-card,
.menu-card *,
.menu-card:hover,
.menu-card:active,
.menu-card:focus,
.menu-card:visited {
    background-color: transparent !important;
}

.menu-card .card-body,
.menu-card .card-img-top {
    background-color: white !important;
}

.cart-item {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

.cart-item:last-child {
    border-bottom: none;
}

.btn-group .btn {
    font-size: 0.875rem;
}

.sticky-top {
    z-index: 100;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.category-scroll::-webkit-scrollbar {
    display: none;
}
</style>

<script>
let cart = [];
let paymentCheckInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    // Event listener saat modal QRIS ditutup
    const qrisModalEl = document.getElementById('qrisModal');
    qrisModalEl.addEventListener('hidden.bs.modal', function () {
        stopPolling();
    });

    // Toggle payment section based on payment method
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const cashPaymentSection = document.getElementById('cashPaymentSection');
    
    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'qris') {
            cashPaymentSection.style.display = 'none';
            document.getElementById('amountPaid').value = '';
            document.getElementById('changeAmountDisplay').textContent = 'Rp 0';
        } else {
            cashPaymentSection.style.display = 'block';
        }
    });

    // Category scroll
    const categoryContainer = document.getElementById('categoryContainer');
    const scrollLeftBtn = document.getElementById('scrollLeft');
    const scrollRightBtn = document.getElementById('scrollRight');
    
    if (scrollLeftBtn && scrollRightBtn && categoryContainer) {
        scrollLeftBtn.addEventListener('click', () => {
            categoryContainer.scrollBy({ left: -200, behavior: 'smooth' });
        });
        
        scrollRightBtn.addEventListener('click', () => {
            categoryContainer.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }

    // Category filter
    document.querySelectorAll('[data-category]').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button with custom colors
            document.querySelectorAll('[data-category]').forEach(b => {
                b.classList.remove('active');
                // Reset to inactive state
                b.style.backgroundColor = 'transparent';
                b.style.color = '#4A7F5A';
                b.style.borderColor = '#4A7F5A';
                b.classList.remove('text-white');
            });
            
            // Set active state
            this.classList.add('active');
            this.style.backgroundColor = '#2A5C3F';
            this.style.borderColor = '#2A5C3F';
            this.style.color = '#ffffff';
            this.classList.add('text-white');
            
            // Filter menu items
            document.querySelectorAll('.menu-item').forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Add to cart
    document.querySelectorAll('.menu-card').forEach(card => {
        card.addEventListener('click', function() {
            const menuId = this.dataset.menuId;
            const menuName = this.dataset.menuName;
            const menuPrice = parseFloat(this.dataset.menuPrice);
            const menuStock = parseInt(this.dataset.menuStock);
            
            // Check if already in cart
            const existingItem = cart.find(item => item.menu_id === menuId);
            
            if (existingItem) {
                if (existingItem.quantity < menuStock) {
                    existingItem.quantity++;
                } else {
                    alert('Stok tidak mencukupi!');
                    return;
                }
            } else {
                cart.push({
                    menu_id: menuId,
                    name: menuName,
                    price: menuPrice,
                    quantity: 1,
                    stock: menuStock,
                    notes: ''
                });
            }
            
            updateCart();
        });
    });
    
    // Clear cart
    document.getElementById('clearCart').addEventListener('click', function() {
        if (confirm('Kosongkan keranjang?')) {
            cart = [];
            updateCart();
        }
    });
    
    // Calculate change on input
    document.getElementById('amountPaid').addEventListener('input', function() {
        const amountPaid = parseFloat(this.value) || 0;
        
        // Calculate total
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        const change = amountPaid - total;
        const changeDisplay = document.getElementById('changeAmountDisplay');
        
        if (change >= 0) {
            changeDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
            changeDisplay.classList.remove('text-danger');
            changeDisplay.classList.add('text-success');
        } else {
            changeDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
            changeDisplay.classList.remove('text-success');
            changeDisplay.classList.add('text-danger');
        }
    });

    // Process order
    document.getElementById('processOrder').addEventListener('click', function() {
        if (cart.length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }

        // Get values from form
        const customerName = document.getElementById('customerName').value.trim();
        const paymentMethod = document.getElementById('paymentMethod').value;
        const amountPaidInput = parseFloat(document.getElementById('amountPaid').value) || 0;
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        // Validasi customer name
        if (!customerName) {
            alert('Nama pelanggan harus diisi!');
            document.getElementById('customerName').focus();
            return;
        }

        // Validasi uang dibayar hanya untuk cash
        if (paymentMethod === 'cash' && amountPaidInput < total) {
            alert('Uang yang dibayar kurang!');
            document.getElementById('amountPaid').focus();
            return;
        }
        
        const orderData = {
            customer_name: customerName,
            customer_phone: '-',
            table_number: null,
            order_type: document.getElementById('orderType').value,
            payment_method: paymentMethod,
            amount_paid: paymentMethod === 'qris' ? total : amountPaidInput,
            items: cart.map(item => ({
                menu_id: item.menu_id,
                quantity: item.quantity,
                notes: item.notes || ''
            }))
        };
        
        // Disable button
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
        // Send to server
        fetch('{{ route("admin.pos.create-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(orderData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || `Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Cek jika QRIS
                if (data.payment_method === 'qris' && data.qris_url) {
                    // Show QRIS Modal
                    document.getElementById('qrisImage').src = data.qris_url;
                    document.getElementById('qrisTotal').textContent = 'Rp ' + data.total_amount.toLocaleString('id-ID');
                    document.getElementById('printReceiptQris').href = `/admin/order/${data.order_id}/receipt`;
                    
                    // Store order_id globally for status check
                    window.currentOrderId = data.order_id;

                    // Reset form
                    cart = [];
                    updateCart();
                    document.getElementById('customerName').value = '';
                    document.getElementById('amountPaid').value = '';
                    document.getElementById('changeAmountDisplay').textContent = 'Rp 0';

                    const qrisModal = new bootstrap.Modal(document.getElementById('qrisModal'));
                    qrisModal.show();
                    
                    // Mulai polling otomatis
                    startPolling();

                } else {
                    // Show Success Modal (Cash)
                    document.getElementById('orderNumber').textContent = data.order_number;
                    document.getElementById('orderTotal').textContent = 'Rp ' + data.total_amount.toLocaleString('id-ID');
                    
                    // Show amount paid and change
                    const amountPaidVal = parseFloat(document.getElementById('amountPaid').value) || 0;
                    const changeVal = amountPaidVal - data.total_amount;
                    
                    document.getElementById('modalAmountPaid').textContent = 'Rp ' + amountPaidVal.toLocaleString('id-ID');
                    document.getElementById('modalChangeAmount').textContent = 'Rp ' + changeVal.toLocaleString('id-ID');
    
                    document.getElementById('printReceipt').href = `/admin/order/${data.order_id}/receipt`;
                    
                    const modal = new bootstrap.Modal(document.getElementById('successModal'));
                    modal.show();
                    
                    // Reset form
                    cart = [];
                    updateCart();
                    document.getElementById('customerName').value = '';
                    document.getElementById('amountPaid').value = '';
                    document.getElementById('changeAmountDisplay').textContent = 'Rp 0';
                }
            } else {
                alert('Gagal membuat pesanan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            console.error('Order Data:', orderData);
            alert('Terjadi kesalahan saat memproses pesanan: ' + error.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class=\"bi bi-check-circle\"></i> Proses Pesanan';
        });
    });
});

function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    
    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        cartItemsDiv.querySelectorAll('.cart-item').forEach(item => item.remove());
    } else {
        emptyCart.style.display = 'none';
        
        // Clear existing items
        cartItemsDiv.querySelectorAll('.cart-item').forEach(item => item.remove());
        
        // Add items
        cart.forEach((item, index) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'cart-item';
            itemDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="flex-grow-1">
                        <strong style="font-size: 0.9rem;">${item.name}</strong>
                        <br>
                        <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</small>
                    </div>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQty(${index})">-</button>
                    <span class="fw-bold">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="increaseQty(${index})">+</button>
                    <span class="ms-auto fw-bold">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                </div>
            `;
            cartItemsDiv.appendChild(itemDiv);
        });
    }
    
    // Update totals
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    // Trigger change calculation if amount paid is filled
    const amountPaidInput = document.getElementById('amountPaid');
    if (amountPaidInput && amountPaidInput.value) {
        amountPaidInput.dispatchEvent(new Event('input'));
    }
}

function increaseQty(index) {
    if (cart[index].quantity < cart[index].stock) {
        cart[index].quantity++;
        updateCart();
    } else {
        alert('Stok tidak mencukupi!');
    }
}

function decreaseQty(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity--;
        updateCart();
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Fungsi untuk memulai polling
function startPolling() {
    stopPolling();
    paymentCheckInterval = setInterval(() => {
        checkPaymentStatus(false);
    }, 3000);
}

// Fungsi untuk menghentikan polling
function stopPolling() {
    if (paymentCheckInterval) {
        clearInterval(paymentCheckInterval);
        paymentCheckInterval = null;
    }
}

// Fungsi untuk mengecek status pembayaran
function checkPaymentStatus(isManual = false) {
    if (!window.currentOrderId) {
        return;
    }

    const btn = document.querySelector('#qrisModal .btn-success');
    let originalText = '';
    
    if (isManual && btn) {
        originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cek...';
    }

    fetch(`/admin/order/${window.currentOrderId}/check-status`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                stopPolling();

                // Tutup modal QRIS
                const qrisModal = bootstrap.Modal.getInstance(document.getElementById('qrisModal'));
                qrisModal.hide();

                // Tampilkan modal sukses
                document.getElementById('orderNumber').textContent = data.order_number;
                document.getElementById('orderTotal').textContent = 'Rp ' + data.total_amount.toLocaleString('id-ID');
                document.getElementById('modalAmountPaid').textContent = 'Rp ' + data.amount_paid.toLocaleString('id-ID');
                document.getElementById('modalChangeAmount').textContent = 'Rp 0';
                document.getElementById('printReceipt').href = `/admin/order/${window.currentOrderId}/receipt`;
                
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            } else {
                if (isManual) {
                    alert('Pembayaran belum diterima. Status saat ini: ' + data.status);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (isManual) alert('Gagal mengecek status');
        })
        .finally(() => {
            if (isManual && btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
}
</script>

    <div class="mt-3 ms-3">
        <a href="{{ route('admin.dashboard') }}" class="btn text-white" 
           style="background-color: #4A7F5A; transition: all 0.3s;"
           onmouseover="this.style.backgroundColor='#3d6b4a'; this.style.transform='translateY(-2px)';" 
           onmouseout="this.style.backgroundColor='#4A7F5A'; this.style.transform='translateY(0)';">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
