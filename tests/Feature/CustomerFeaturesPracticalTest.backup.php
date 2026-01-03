<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Customer Features - Practical', function () {
    
    beforeEach(function () {
        $this->customer = User::factory()->create([
            'role' => 'customer',
            'phone' => '081234567890',
        ]);
    });

    // Test: Guest can view menu
    test('guest can view menu', function () {
        Menu::factory()->count(10)->create(['is_available' => true]);
        
        $response = $this->get('/menu');
        
        $response->assertStatus(200);
    });

    // Test: Customer can view menu when authenticated
    test('customer can view menu when authenticated', function () {
        $this->actingAs($this->customer);
        
        Menu::factory()->count(5)->create(['is_available' => true]);
        
        $response = $this->get('/menu');
        
        $response->assertStatus(200);
    });

    // Test: Customer can view cart
    test('customer can view cart', function () {
        $response = $this->get('/cart');
        
        $response->assertStatus(200);
    });

    // Test: Customer can add item to cart
    test('customer can add item to cart', function () {
        $menu = Menu::factory()->create([
            'is_available' => true,
            'stock' => 10,
        ]);

        $response = $this->post('/cart/add', [
            'menu_id' => $menu->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
    });

    // Test: Authenticated customer can view orders
    test('authenticated customer can view their orders', function () {
        $this->actingAs($this->customer);
        
        Order::factory()->count(3)->create([
            'user_id' => $this->customer->id,
        ]);

        $response = $this->get('/orders');
        
        $response->assertStatus(200);
    });

    // Test: Customer can only see their own orders
    test('customer can only see their own orders', function () {
        $this->actingAs($this->customer);
        
        // Create orders for this customer
        $myOrders = Order::factory()->count(2)->create([
            'user_id' => $this->customer->id,
        ]);

        // Create orders for another customer
        $otherCustomer = User::factory()->create(['role' => 'customer']);
        $otherOrders = Order::factory()->count(3)->create([
            'user_id' => $otherCustomer->id,
        ]);

        $response = $this->get('/orders');
        
        $response->assertStatus(200);
        
        // Should only see own orders (2), not others (3)
        $orders = $response->viewData('orders');
        expect($orders)->toHaveCount(2);
    });

    // Test: Menu items are displayed with categories
    test('menu items are displayed with categories', function () {
        $category = Category::factory()->create(['name' => 'Makanan']);
        Menu::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_available' => true,
        ]);

        $response = $this->get('/menu');
        
        $response->assertStatus(200);
        $response->assertSee('Makanan');
    });
});
