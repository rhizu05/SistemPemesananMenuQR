@extends('layouts.app')

@section('title', 'Menu Restoran')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2><i class="bi bi-card-list"></i> Menu Restoran</h2>
                <p class="mb-0">Pilih menu favorit Anda</p>
            </div>
        </div>
    </div>

    <!-- Floating Cart Button (Always in DOM, hidden by default) -->
    @php
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
    @endphp
    <a href="{{ route('customer.cart') }}" class="btn btn-success position-fixed shadow-lg" 
       style="bottom: 20px; right: 20px; z-index: 1000; border-radius: 50px; padding: 15px 25px; {{ $totalItems > 0 ? '' : 'display: none;' }}"
       id="floatingCartBtn">
        <i class="bi bi-cart3 fs-5"></i>
        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" id="cartBadge">
            {{ $totalItems }}
        </span>
        <span class="ms-2 fw-bold">Keranjang</span>
    </a>

    <!-- Category Filter (Scrollable with Arrows) -->
    <div class="mb-4 sticky-top bg-body py-2" style="top: 0; z-index: 99;">
        <div class="d-flex align-items-center">
            <!-- Scroll Left Button -->
            <button class="btn btn-light btn-sm rounded-circle shadow-sm me-2 d-none d-md-block" id="scrollLeft" style="width: 40px; height: 40px;">
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <!-- Category Buttons Container -->
            <div class="d-flex gap-2 overflow-auto pb-2 category-scroll flex-grow-1" id="categoryContainer" style="scrollbar-width: none; -ms-overflow-style: none; scroll-behavior: smooth;">
                <button class="btn btn-outline-dark rounded-pill px-4 active flex-shrink-0" data-category="all">
                    <i class="bi bi-grid-fill me-1"></i> Semua
                </button>
                @foreach($categories as $category)
                    <button class="btn btn-outline-dark rounded-pill px-4 flex-shrink-0" data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            
            <!-- Scroll Right Button -->
            <button class="btn btn-light btn-sm rounded-circle shadow-sm ms-2 d-none d-md-block" id="scrollRight" style="width: 40px; height: 40px;">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="row g-3" id="menuGrid">
        @foreach($menus as $menu)
            <div class="col-lg-3 col-md-4 col-sm-6 menu-item" data-category="{{ $menu->category_id }}">
                <div class="card h-100 menu-card">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" 
                             class="card-img-top" 
                             alt="{{ $menu->name }}"
                             style="height: 180px; object-fit: cover; {{ (!$menu->is_available || $menu->stock <= 0) ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 180px; {{ (!$menu->is_available || $menu->stock <= 0) ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}">
                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $menu->name }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($menu->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-dark fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                        @if($menu->is_available && $menu->stock > 0)
                            <button class="btn btn-success btn-sm w-100 btn-add-to-cart" 
                                    data-menu-id="{{ $menu->id }}"
                                    data-menu-name="{{ $menu->name }}"
                                    data-menu-price="{{ $menu->price }}">
                                <i class="bi bi-cart-plus"></i> Tambah
                            </button>
                        @else
                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                <i class="bi bi-x-circle"></i> Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($menus->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="mt-3">Belum Ada Menu</h4>
            <p class="text-muted">Menu akan segera ditambahkan</p>
        </div>
    @endif
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Animate.css for smooth animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
#floatingCartBtn {
    transition: all 0.3s ease;
}

#floatingCartBtn:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important;
}

.menu-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.category-filter .btn {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

.category-filter .btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

@media (max-width: 768px) {
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .category-filter {
        overflow-x: auto;
        flex-wrap: nowrap;
        -webkit-overflow-scrolling: touch;
    }
    
    .category-filter .btn {
        white-space: nowrap;
    }
}

/* Hide scrollbar for Chrome, Safari and Opera */
.category-scroll::-webkit-scrollbar {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Scroll Buttons
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
    
    // Category Filter
    document.querySelectorAll('.category-scroll button').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            console.log('Selected category:', category); // Debug
            
            // Update active button
            document.querySelectorAll('.category-scroll button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter menu items
            document.querySelectorAll('.menu-item').forEach(item => {
                const itemCategory = item.dataset.category;
                
                // Convert to string for comparison to avoid type mismatch
                if (category === 'all' || String(itemCategory) === String(category)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Log filtered results
            const visibleItems = document.querySelectorAll('.menu-item[style=""]').length;
            const hiddenItems = document.querySelectorAll('.menu-item[style*="none"]').length;
            console.log(`Visible: ${visibleItems}, Hidden: ${hiddenItems}`); // Debug
        });
    });
    
    // Add to Cart
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const menuId = this.dataset.menuId;
            const menuName = this.dataset.menuName;
            const menuPrice = this.dataset.menuPrice;
            
            // Save button reference
            const button = this;
            
            // Disable button
            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            // Send to server
            fetch('{{ route("customer.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart badge and floating button
                    const badge = document.getElementById('cartBadge');
                    const floatingBtn = document.getElementById('floatingCartBtn');
                    
                    console.log('Cart updated:', data); // Debug
                    console.log('Badge element:', badge); // Debug
                    console.log('Floating button:', floatingBtn); // Debug
                    
                    if (badge && floatingBtn) {
                        // Update badge number
                        badge.textContent = data.total_items;
                        
                        // Show floating button if it was hidden
                        if (data.total_items > 0) {
                            floatingBtn.style.display = 'block';
                            
                            // Add animation
                            floatingBtn.classList.add('animate__animated', 'animate__bounceIn');
                            setTimeout(() => {
                                floatingBtn.classList.remove('animate__animated', 'animate__bounceIn');
                            }, 1000);
                        } else {
                            floatingBtn.style.display = 'none';
                        }
                    } else {
                        console.error('Badge or floating button not found!');
                    }
                    
                    // Show toast
                    showToast(menuName + ' ditambahkan ke keranjang');
                    
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                } else {
                    alert(data.message || 'Gagal menambahkan ke keranjang');
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    });
});

function showToast(message) {
    document.getElementById('toastMessage').textContent = message;
    const toast = new bootstrap.Toast(document.getElementById('successToast'));
    toast.show();
}
</script>
@endsection