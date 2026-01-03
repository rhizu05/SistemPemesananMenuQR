<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Customer Features', function () {
    
    beforeEach(function () {
        $this->customer = User::factory()->create([
            'role' => 'customer',
            'phone' => '081234567890',
        ]);
    });

    // TC-019: Customer QR Code Ordering
    test('customer can access menu via QR code', function () {
        $response = $this->get('/qr/table/1');
        
        $response->assertStatus(200);
        $response->assertViewIs('customer.menu');
    });

    // TC-020: Customer Browse Menu
    test('customer can browse menu', function () {
        Menu::factory()->count(10)->create(['is_available' => true]);
        
        $response = $this->get('/menu');
        
        $response->assertStatus(200);
        $response->assertViewHas('menus');
    });

    test('customer can filter menu by category', function () {
        $category = Category::factory()->create(['name' => 'Makanan']);
        Menu::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_available' => true,
        ]);
        
        $response = $this->get("/menu?category={$category->id}");
        
        $response->assertStatus(200);
    });

    test('customer can search menu', function () {
        Menu::factory()->create([
            'name' => 'Nasi Goreng',
            'is_available' => true,
        ]);
        
        $response = $this->get('/menu?search=Nasi');
        
        $response->assertStatus(200);
        $response->assertSee('Nasi Goreng');
    });

    test('customer can view menu details', function () {
        $menu = Menu::factory()->create(['is_available' => true]);
        
        $response = $this->get("/menu/{$menu->id}");
        
        $response->assertStatus(200);
        $response->assertSee($menu->name);
    });

    // TC-021: Customer Apply Voucher
    test('customer can apply valid voucher', function () {
        $this->actingAs($this->customer);
        
        $voucher = Voucher::factory()->create([
            'code' => 'TEST10',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 50000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        $response = $this->post('/cart/apply-voucher', [
            'voucher_code' => 'TEST10',
            'total' => 100000,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'discount' => 10000, // 10% of 100000
        ]);
    });

    test('customer cannot apply expired voucher', function () {
        $this->actingAs($this->customer);
        
        $voucher = Voucher::factory()->create([
            'code' => 'EXPIRED',
            'valid_until' => now()->subDay(),
        ]);

        $response = $this->post('/cart/apply-voucher', [
            'voucher_code' => 'EXPIRED',
            'total' => 100000,
        ]);

        $response->assertStatus(422);
    });

    test('customer cannot apply voucher below minimum purchase', function () {
        $this->actingAs($this->customer);
        
        $voucher = Voucher::factory()->create([
            'code' => 'MIN100K',
            'min_purchase' => 100000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addMonth(),
        ]);

        $response = $this->post('/cart/apply-voucher', [
            'voucher_code' => 'MIN100K',
            'total' => 50000,
        ]);

        $response->assertStatus(422);
    });

    // TC-022: Customer QRIS Payment
    test('customer can create order with QRIS payment', function () {
        $this->actingAs($this->customer);
        
        $menu = Menu::factory()->create(['price' => 50000, 'stock' => 10]);
        
        $response = $this->post('/orders', [
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'quantity' => 2,
                ]
            ],
            'payment_method' => 'qris',
            'table_number' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'payment_method' => 'qris',
            'table_number' => 5,
        ]);
    });

    // TC-023: Customer Order Tracking
    test('customer can track order status', function () {
        $this->actingAs($this->customer);
        
        $order = Order::factory()->create([
            'user_id' => $this->customer->id,
            'status' => 'preparing',
        ]);

        $response = $this->get("/orders/{$order->id}/track");
        
        $response->assertStatus(200);
        $response->assertSee('preparing');
    });

    // TC-024: Customer Order History
    test('customer can view order history', function () {
        $this->actingAs($this->customer);
        
        Order::factory()->count(5)->create([
            'user_id' => $this->customer->id,
        ]);

        $response = $this->get('/orders');
        
        $response->assertStatus(200);
        $response->assertViewHas('orders');
    });

    test('customer can only view their own orders', function () {
        $this->actingAs($this->customer);
        
        $myOrder = Order::factory()->create([
            'user_id' => $this->customer->id,
        ]);

        $otherOrder = Order::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->get('/orders');
        
        $response->assertSee($myOrder->id);
        $response->assertDontSee($otherOrder->id);
    });
});
