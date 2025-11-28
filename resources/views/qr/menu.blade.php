<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Restoran - Meja {{ $tableNumber ?? 'Tamu' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .menu-card {
            transition: transform 0.2s;
        }
        .menu-card:hover {
            transform: translateY(-5px);
        }
        .cart-item {
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1>SELAMAT DATANG</h1>
            <h3>Meja {{ $tableNumber ?? 'Tamu' }}</h3>
            <p>Silakan pilih menu yang tersedia</p>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="row mb-3">
                    <div class="col">
                        <h4>Menu</h4>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cartModal">
                            Keranjang <span id="cart-count" class="badge bg-light text-dark">0</span>
                        </button>
                    </div>
                </div>
                
                <div class="row" id="menu-container">
                    @foreach($menus as $menu)
                    <div class="col-md-6 mb-3">
                        <div class="card menu-card">
                            <div class="row g-0">
                                @if($menu->image)
                                    <div class="col-4">
                                        <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $menu->name }}">
                                    </div>
                                @endif
                                <div class="col {{ $menu->image ? '8' : '12' }}">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $menu->name }}</h6>
                                        <p class="card-text text-muted">{{ Str::limit($menu->description, 50) }}</p>
                                        <p class="card-text fw-bold">Rp {{ number_format($menu->price, 2, ',', '.') }}</p>
                                        @if($menu->is_available && $menu->stock > 0)
                                            <button class="btn btn-sm btn-success btn-add-to-cart" 
                                                    data-menu-id="{{ $menu->id }}" 
                                                    data-menu-name="{{ $menu->name }}" 
                                                    data-menu-price="{{ $menu->price }}"
                                                    data-menu-stock="{{ $menu->stock }}">
                                                Tambahkan
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                Tidak Tersedia
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div id="order-summary">
                            <p class="text-muted">Keranjang masih kosong</p>
                        </div>
                        <div id="order-total" class="mt-3 fw-bold" style="display: none;">
                            Total: <span id="total-amount">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Keranjang -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keranjang Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cart-items">
                        <p class="text-center text-muted">Keranjang kosong</p>
                    </div>
                    <div id="cart-total" class="mt-3 text-end fw-bold" style="display: none;">
                        Total: <span id="modal-total-amount">Rp 0</span>
                    </div>
                    
                    <form id="orderForm" class="mt-4" method="POST" action="{{ route('qr.order.create', $tableNumber) }}">
                        @csrf
                        <input type="hidden" name="table_number" value="{{ $tableNumber }}">
                        
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Permintaan Khusus</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="checkoutBtn" disabled>
                                Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = [];
            
            // Fungsi untuk menambahkan ke keranjang
            document.querySelectorAll('.btn-add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.dataset.menuId;
                    const menuName = this.dataset.menuName;
                    const menuPrice = parseFloat(this.dataset.menuPrice);
                    const menuStock = parseInt(this.dataset.menuStock);
                    
                    // Cek apakah menu sudah ada di keranjang
                    const existingItem = cart.find(item => item.menu_id == menuId);
                    
                    if (existingItem) {
                        if (existingItem.quantity < menuStock) {
                            existingItem.quantity += 1;
                        } else {
                            alert('Stok tidak mencukupi!');
                            return;
                        }
                    } else {
                        if (1 <= menuStock) {
                            cart.push({
                                menu_id: menuId,
                                name: menuName,
                                price: menuPrice,
                                quantity: 1
                            });
                        } else {
                            alert('Stok tidak mencukupi!');
                            return;
                        }
                    }
                    
                    updateCart();
                    updateOrderSummary();
                    updateModalCart();
                });
            });
            
            // Fungsi untuk mengupdate tampilan keranjang
            function updateCart() {
                document.getElementById('cart-count').textContent = cart.reduce((total, item) => total + item.quantity, 0);
            }
            
            // Fungsi untuk mengupdate ringkasan pesanan
            function updateOrderSummary() {
                const orderSummary = document.getElementById('order-summary');
                const orderTotal = document.getElementById('order-total');
                const totalAmount = document.getElementById('total-amount');
                
                if (cart.length === 0) {
                    orderSummary.innerHTML = '<p class="text-muted">Keranjang masih kosong</p>';
                    orderTotal.style.display = 'none';
                    return;
                }
                
                let html = '<ul class="list-unstyled">';
                let total = 0;
                
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    html += `
                        <li class="d-flex justify-content-between">
                            <span>${item.name} (${item.quantity}x)</span>
                            <span>Rp ${itemTotal.toLocaleString('id-ID')}</span>
                        </li>
                    `;
                });
                
                html += '</ul>';
                
                orderSummary.innerHTML = html;
                totalAmount.textContent = 'Rp ' + total.toLocaleString('id-ID');
                orderTotal.style.display = 'block';
            }
            
            // Fungsi untuk mengupdate keranjang di modal
            function updateModalCart() {
                const cartItems = document.getElementById('cart-items');
                const cartTotal = document.getElementById('cart-total');
                const modalTotalAmount = document.getElementById('modal-total-amount');
                const checkoutBtn = document.getElementById('checkoutBtn');
                
                if (cart.length === 0) {
                    cartItems.innerHTML = '<p class="text-center text-muted">Keranjang kosong</p>';
                    cartTotal.style.display = 'none';
                    checkoutBtn.disabled = true;
                    return;
                }
                
                let html = '<div class="list-group">';
                
                cart.forEach((item, index) => {
                    const itemTotal = item.price * item.quantity;
                    
                    html += `
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">${item.name}</h6>
                                    <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-outline-secondary btn-quantity" data-index="${index}" data-action="decrease">-</button>
                                    <span class="mx-2">${item.quantity}</span>
                                    <button class="btn btn-sm btn-outline-secondary btn-quantity" data-index="${index}" data-action="increase">+</button>
                                    <button class="btn btn-sm btn-danger ms-2 btn-remove" data-index="${index}">Hapus</button>
                                </div>
                            </div>
                            <div class="mt-1 text-end">
                                <strong>Rp ${itemTotal.toLocaleString('id-ID')}</strong>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                
                cartItems.innerHTML = html;
                
                // Hitung total
                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                modalTotalAmount.textContent = 'Rp ' + total.toLocaleString('id-ID');
                
                cartTotal.style.display = 'block';
                checkoutBtn.disabled = false;
                
                // Tambahkan event listener untuk tombol kuantitas
                document.querySelectorAll('.btn-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        const action = this.dataset.action;
                        
                        if (action === 'increase') {
                            const maxQty = parseInt(document.querySelector('.btn-add-to-cart[data-menu-id="' + cart[index].menu_id + '"]').dataset.menuStock);
                            if (cart[index].quantity < maxQty) {
                                cart[index].quantity += 1;
                            } else {
                                alert('Stok tidak mencukupi!');
                                return;
                            }
                        } else if (action === 'decrease' && cart[index].quantity > 1) {
                            cart[index].quantity -= 1;
                        }
                        
                        // Update form input
                        updateFormInput();
                        updateCart();
                        updateOrderSummary();
                        updateModalCart();
                    });
                });
                
                // Tambahkan event listener untuk tombol hapus
                document.querySelectorAll('.btn-remove').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.dataset.index);
                        cart.splice(index, 1);
                        
                        // Update form input
                        updateFormInput();
                        updateCart();
                        updateOrderSummary();
                        updateModalCart();
                    });
                });
                
                // Update form input
                updateFormInput();
            }
            
            // Fungsi untuk memperbarui input form
            function updateFormInput() {
                const form = document.getElementById('orderForm');
                // Hapus input lama
                const existingInputs = form.querySelectorAll('input[name^="items"]');
                existingInputs.forEach(input => input.remove());
                
                // Tambahkan input baru
                cart.forEach((item, index) => {
                    // Menu ID
                    const menuIdInput = document.createElement('input');
                    menuIdInput.type = 'hidden';
                    menuIdInput.name = `items[${index}][menu_id]`;
                    menuIdInput.value = item.menu_id;
                    form.appendChild(menuIdInput);
                    
                    // Quantity
                    const quantityInput = document.createElement('input');
                    quantityInput.type = 'hidden';
                    quantityInput.name = `items[${index}][quantity]`;
                    quantityInput.value = item.quantity;
                    form.appendChild(quantityInput);
                    
                    // Special instructions (kosong untuk sekarang)
                    const specialInput = document.createElement('input');
                    specialInput.type = 'hidden';
                    specialInput.name = `items[${index}][special_instructions]`;
                    specialInput.value = '';
                    form.appendChild(specialInput);
                });
            }
            
            // Tambahkan event listener untuk form submit
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                if (cart.length === 0) {
                    e.preventDefault();
                    alert('Keranjang masih kosong!');
                    return;
                }
                
                // Pastikan form input terbaru
                updateFormInput();
            });
        });
    </script>
</body>
</html>