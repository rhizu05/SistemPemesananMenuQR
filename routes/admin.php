<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\CustomerController;

// Routes untuk Admin
Route::prefix('admin')->middleware(['auth', 'role:admin', 'prevent_kitchen'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Category Routes
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    
    // Menu Routes
    Route::get('/menu', [AdminController::class, 'menu'])->name('admin.menu');
    Route::get('/menu/create', [AdminController::class, 'createMenu'])->name('admin.menu.create');
    Route::post('/menu', [AdminController::class, 'storeMenu'])->name('admin.menu.store');
    Route::get('/menu/{id}/edit', [AdminController::class, 'editMenu'])->name('admin.menu.edit');
    Route::put('/menu/{id}', [AdminController::class, 'updateMenu'])->name('admin.menu.update');
    Route::delete('/menu/{id}', [AdminController::class, 'deleteMenu'])->name('admin.menu.delete');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/order/{id}', [AdminController::class, 'orderDetail'])->name('admin.order.detail');
    Route::put('/order/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.update-status');
    Route::put('/order/{id}/payment', [AdminController::class, 'verifyPayment'])->name('admin.order.verify-payment');
    Route::get('/order/{id}/receipt', [AdminController::class, 'printReceipt'])->name('admin.order.receipt');
    Route::get('/order/{id}/check-status', [AdminController::class, 'checkStatus'])->name('admin.order.check-status');
    Route::post('/order/{id}/simulate-pay', [AdminController::class, 'simulatePay'])->name('admin.order.simulate-pay');

    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    // POS Routes
    Route::get('/pos', [AdminController::class, 'showPOS'])->name('admin.pos');
    Route::post('/pos/order', [AdminController::class, 'createPOSOrder'])->name('admin.pos.create-order');
    
    // QR Code Routes
    Route::get('/qr-codes', [AdminController::class, 'qrCodeManager'])->name('admin.qr-codes');
    Route::get('/qr-codes/table/{tableNumber}', [AdminController::class, 'generateTableQR'])->name('admin.qr-code.table');
    
    // Customer Management Routes
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/customers/{id}', [AdminController::class, 'customerDetail'])->name('admin.customer.detail');
    Route::post('/customers/{id}/toggle-status', [AdminController::class, 'toggleCustomerStatus'])->name('admin.customer.toggle-status');
    Route::delete('/customers/{id}', [AdminController::class, 'deleteCustomer'])->name('admin.customer.delete');
});

// Routes untuk Kitchen
Route::prefix('kitchen')->middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/', [KitchenController::class, 'dashboard'])->name('kitchen.dashboard');
    Route::put('/orders/{id}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.order.update-status');
});

// Routes untuk Customer (Guest & Authenticated) - Prevent Kitchen Access
Route::middleware(['prevent_kitchen'])->group(function () {
    Route::get('/scan-qr', [CustomerController::class, 'scanQR'])->name('customer.scan-qr');
    Route::get('/menu', [CustomerController::class, 'index'])->name('customer.menu');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('customer.cart');
    Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('customer.cart.add');
    Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('customer.cart.update');
    Route::delete('/cart/remove/{menuId}', [CustomerController::class, 'removeFromCart'])->name('customer.cart.remove');
    Route::post('/cart/clear', [CustomerController::class, 'clearCart'])->name('customer.cart.clear');
    Route::post('/order', [CustomerController::class, 'createOrder'])->name('customer.order.create');
    Route::get('/order/{orderNumber}/status', [CustomerController::class, 'orderStatus'])->name('customer.order.status');
    Route::get('/order/{orderNumber}/success', [CustomerController::class, 'orderSuccess'])->name('customer.order.success');
    Route::get('/orders', [CustomerController::class, 'myOrders'])->name('customer.my-orders');
});

// Routes untuk Customer (Authenticated Only)
Route::middleware(['auth', 'role:customer', 'prevent_kitchen'])->group(function () {
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
});

// Routes QR Ordering - Prevent Kitchen Access
Route::middleware(['prevent_kitchen'])->group(function () {
    Route::get('/qr/table/{tableNumber}', [QRController::class, 'showMenu'])->name('qr.menu');
    Route::post('/qr/table/{tableNumber}', [QRController::class, 'createOrder'])->name('qr.order.create');
    Route::get('/qr/generate/{tableNumber}', [QRController::class, 'generateQR'])->name('qr.generate');
});

// Routes umum (tanpa login)
Route::get('/', [CustomerController::class, 'index'])->name('home');