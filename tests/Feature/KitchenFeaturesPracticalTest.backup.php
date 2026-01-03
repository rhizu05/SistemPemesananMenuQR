<?php

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Kitchen Features - Practical', function () {
    
    beforeEach(function () {
        $this->kitchen = User::factory()->create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@akpl.com',
            'role' => 'kitchen',
        ]);

        $this->actingAs($this->kitchen);
    });

    // Test: Kitchen can view dashboard
    test('kitchen can view dashboard', function () {
        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('kitchen.dashboard');
    });

    // Test: Kitchen can see paid orders
    test('kitchen can see paid orders', function () {
        Order::factory()->count(3)->create([
            'payment_status' => 'paid',
            'status' => 'pending',
        ]);

        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
    });

    // Test: Kitchen can update order status
    test('kitchen can update order status to preparing', function () {
        $order = Order::factory()->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $response = $this->put("/kitchen/orders/{$order->id}/status", [
            'status' => 'preparing',
        ]);

        $response->assertRedirect();
        
        $order->refresh();
        expect($order->status)->toBe('preparing');
    });

    // Test: Kitchen can update order to ready
    test('kitchen can update order status to ready', function () {
        $order = Order::factory()->create([
            'status' => 'preparing',
            'payment_status' => 'paid',
        ]);

        $response = $this->put("/kitchen/orders/{$order->id}/status", [
            'status' => 'ready',
        ]);

        $response->assertRedirect();
        
        $order->refresh();
        expect($order->status)->toBe('ready');
    });

    // Test: Kitchen cannot access admin routes
    test('kitchen cannot access admin routes', function () {
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    // Test: Kitchen cannot access cashier routes
    test('kitchen cannot access cashier routes', function () {
        $response = $this->get('/cashier/pos');
        
        $response->assertStatus(403);
    });

    // Test: Only kitchen can access kitchen routes
    test('non-kitchen cannot access kitchen dashboard', function () {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier);
        
        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(403);
    });
});
