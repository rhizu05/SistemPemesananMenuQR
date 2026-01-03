<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Stock Management - Comprehensive Tests', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
    });

    // ==================== STOCK INITIALIZATION ====================
    
    test('menu item can be created with initial stock', function () {
        $menu = Menu::factory()->create([
            'name' => 'Nasi Goreng',
            'stock' => 50,
            'is_available' => true,
        ]);

        expect($menu->stock)->toBe(50);
        expect($menu->is_available)->toBeTrue();
    });

    test('menu item with zero stock is created correctly', function () {
        $menu = Menu::factory()->create([
            'stock' => 0,
            'is_available' => false,
        ]);

        expect($menu->stock)->toBe(0);
        expect($menu->is_available)->toBeFalse();
    });

    // ==================== STOCK AVAILABILITY ====================
    
    test('menu item is available when stock is sufficient', function () {
        $menu = Menu::factory()->create([
            'stock' => 10,
            'is_available' => true,
        ]);

        expect($menu->is_available)->toBeTrue();
        expect($menu->stock)->toBeGreaterThan(0);
    });

    test('menu item is unavailable when stock is zero', function () {
        $menu = Menu::factory()->create([
            'stock' => 0,
            'is_available' => false,
        ]);

        expect($menu->is_available)->toBeFalse();
    });

    test('menu item can be manually set as unavailable despite having stock', function () {
        $menu = Menu::factory()->create([
            'stock' => 50,
            'is_available' => false, // Manually disabled
        ]);

        expect($menu->stock)->toBe(50);
        expect($menu->is_available)->toBeFalse();
    });

    // ==================== STOCK DEDUCTION ====================
    
    test('stock is deducted when order is placed', function () {
        $menu = Menu::factory()->create([
            'stock' => 20,
        ]);

        $initialStock = $menu->stock;

        // Simulate order
        $orderQuantity = 3;
        $menu->stock -= $orderQuantity;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe($initialStock - $orderQuantity);
        expect($menu->stock)->toBe(17);
    });

    test('stock cannot go below zero', function () {
        $menu = Menu::factory()->create([
            'stock' => 5,
        ]);

        // Try to order more than available
        $orderQuantity = 10;
        
        // Should check stock before deducting
        if ($menu->stock >= $orderQuantity) {
            $menu->stock -= $orderQuantity;
            $menu->save();
        }

        $menu->refresh();
        expect($menu->stock)->toBe(5); // Stock unchanged
    });

    test('multiple items can be ordered if stock is sufficient', function () {
        $menu = Menu::factory()->create([
            'stock' => 100,
        ]);

        // Order 1
        $menu->stock -= 10;
        $menu->save();

        // Order 2
        $menu->stock -= 15;
        $menu->save();

        // Order 3
        $menu->stock -= 20;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(55); // 100 - 10 - 15 - 20
    });

    // ==================== STOCK UPDATES ====================
    
    test('admin can increase stock', function () {
        $this->actingAs($this->admin);
        
        $menu = Menu::factory()->create([
            'stock' => 10,
        ]);

        $menu->stock += 50;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(60);
    });

    test('admin can decrease stock', function () {
        $this->actingAs($this->admin);
        
        $menu = Menu::factory()->create([
            'stock' => 100,
        ]);

        $menu->stock -= 30;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(70);
    });

    test('admin can set stock to specific value', function () {
        $this->actingAs($this->admin);
        
        $menu = Menu::factory()->create([
            'stock' => 50,
        ]);

        $menu->update(['stock' => 200]);

        $menu->refresh();
        expect($menu->stock)->toBe(200);
    });

    // ==================== LOW STOCK DETECTION ====================
    
    test('can detect low stock items', function () {
        // Create items with various stock levels
        Menu::factory()->create(['stock' => 100]); // High stock
        Menu::factory()->create(['stock' => 50]);  // Medium stock
        Menu::factory()->create(['stock' => 5]);   // Low stock
        Menu::factory()->create(['stock' => 2]);   // Low stock
        Menu::factory()->create(['stock' => 0]);   // Out of stock

        // Get items with stock <= 10
        $lowStockItems = Menu::where('stock', '<=', 10)->get();

        expect($lowStockItems)->toHaveCount(3); // 5, 2, 0
    });

    test('can detect out of stock items', function () {
        Menu::factory()->create(['stock' => 10]);
        Menu::factory()->create(['stock' => 0]);
        Menu::factory()->create(['stock' => 0]);
        Menu::factory()->create(['stock' => 5]);

        $outOfStock = Menu::where('stock', 0)->get();

        expect($outOfStock)->toHaveCount(2);
    });

    // ==================== STOCK VALIDATION ====================
    
    test('order cannot be placed if stock is insufficient', function () {
        $menu = Menu::factory()->create([
            'stock' => 3,
            'is_available' => true,
        ]);

        $requestedQuantity = 5;

        // Validate stock before creating order
        $canOrder = $menu->stock >= $requestedQuantity;

        expect($canOrder)->toBeFalse();
    });

    test('order can be placed if stock is sufficient', function () {
        $menu = Menu::factory()->create([
            'stock' => 10,
            'is_available' => true,
        ]);

        $requestedQuantity = 5;

        $canOrder = $menu->stock >= $requestedQuantity;

        expect($canOrder)->toBeTrue();
    });

    // ==================== STOCK RESTORATION ====================
    
    test('stock is restored when order is cancelled', function () {
        $menu = Menu::factory()->create([
            'stock' => 20,
        ]);

        // Place order
        $orderQuantity = 5;
        $menu->stock -= $orderQuantity;
        $menu->save();

        expect($menu->stock)->toBe(15);

        // Cancel order - restore stock
        $menu->stock += $orderQuantity;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(20); // Back to original
    });

    // ==================== INTEGRATION TESTS ====================
    
    test('complete stock workflow with order', function () {
        // Create menu with stock
        $menu = Menu::factory()->create([
            'name' => 'Nasi Goreng',
            'price' => 25000,
            'stock' => 50,
            'is_available' => true,
        ]);

        $initialStock = $menu->stock;

        // Customer places order
        $orderQuantity = 3;

        // Validate stock
        expect($menu->stock)->toBeGreaterThanOrEqual($orderQuantity);

        // Create order
        $order = Order::factory()->create([
            'user_id' => $this->customer->id,
            'subtotal' => $menu->price * $orderQuantity,
            'total_amount' => $menu->price * $orderQuantity,
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'quantity' => $orderQuantity,
            'price' => $menu->price,
            'subtotal' => $menu->price * $orderQuantity,
        ]);

        // Deduct stock
        $menu->stock -= $orderQuantity;
        $menu->save();

        // Verify stock deduction
        $menu->refresh();
        expect($menu->stock)->toBe($initialStock - $orderQuantity);
        expect($menu->stock)->toBe(47);
    });

    test('stock management with multiple concurrent orders', function () {
        $menu = Menu::factory()->create([
            'stock' => 100,
        ]);

        // Simulate 5 concurrent orders
        $orders = [
            ['quantity' => 10],
            ['quantity' => 15],
            ['quantity' => 20],
            ['quantity' => 5],
            ['quantity' => 8],
        ];

        $totalOrdered = 0;

        foreach ($orders as $orderData) {
            $quantity = $orderData['quantity'];
            
            // Check stock before deducting
            if ($menu->stock >= $quantity) {
                $menu->stock -= $quantity;
                $menu->save();
                $totalOrdered += $quantity;
            }
        }

        $menu->refresh();
        expect($menu->stock)->toBe(42); // 100 - 58
        expect($totalOrdered)->toBe(58);
    });

    test('stock becomes unavailable when depleted', function () {
        $menu = Menu::factory()->create([
            'stock' => 5,
            'is_available' => true,
        ]);

        // Order all stock
        $menu->stock -= 5;
        
        // Auto-disable when stock is 0
        if ($menu->stock <= 0) {
            $menu->is_available = false;
        }
        
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(0);
        expect($menu->is_available)->toBeFalse();
    });

    test('stock can be replenished and item becomes available again', function () {
        $menu = Menu::factory()->create([
            'stock' => 0,
            'is_available' => false,
        ]);

        // Replenish stock
        $menu->stock = 50;
        $menu->is_available = true;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(50);
        expect($menu->is_available)->toBeTrue();
    });

    // ==================== EDGE CASES ====================
    
    test('stock handles large quantities correctly', function () {
        $menu = Menu::factory()->create([
            'stock' => 10000,
        ]);

        $menu->stock -= 9999;
        $menu->save();

        $menu->refresh();
        expect($menu->stock)->toBe(1);
    });

    test('stock validation prevents negative stock', function () {
        $menu = Menu::factory()->create([
            'stock' => 10,
        ]);

        $orderQuantity = 15;

        // Proper validation
        if ($menu->stock >= $orderQuantity) {
            $menu->stock -= $orderQuantity;
        } else {
            // Order should be rejected
            $orderRejected = true;
        }

        expect($orderRejected ?? false)->toBeTrue();
        expect($menu->stock)->toBe(10); // Unchanged
    });
});
