<?php

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Kitchen Features - Optimized', function () {
    
    beforeEach(function () {
        $this->kitchen = User::factory()->create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@akpl.com',
            'role' => 'kitchen',
        ]);

        $this->actingAs($this->kitchen);
    });

    test('kitchen can view dashboard', function () {
        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('kitchen.dashboard');
    });

    test('kitchen dashboard shows orders', function () {
        // Create paid orders
        Order::factory()->count(3)->create([
            'payment_status' => 'paid',
            'status' => 'pending',
        ]);

        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewHas('preparingOrders');
    });

    test('kitchen can update order status via JSON', function () {
        $order = Order::factory()->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $response = $this->putJson("/kitchen/orders/{$order->id}/status", [
            'status' => 'preparing',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $order->refresh();
        expect($order->status)->toBe('preparing');
    });

    test('kitchen can update order to ready', function () {
        $order = Order::factory()->create([
            'status' => 'preparing',
            'payment_status' => 'paid',
        ]);

        $response = $this->putJson("/kitchen/orders/{$order->id}/status", [
            'status' => 'ready',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $order->refresh();
        expect($order->status)->toBe('ready');
    });

    test('kitchen cannot access admin routes', function () {
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    test('kitchen cannot access cashier routes', function () {
        $response = $this->get('/cashier/pos');
        
        $response->assertStatus(403);
    });

    test('non-kitchen cannot access kitchen dashboard', function () {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier);
        
        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(403);
    });
});
