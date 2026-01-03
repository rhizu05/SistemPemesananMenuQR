<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Cashier Features - Practical', function () {
    
    beforeEach(function () {
        $this->cashier = User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@dapoerkatendjo.com',
            'role' => 'cashier',
        ]);

        $this->actingAs($this->cashier);
    });

    // Test: Cashier can view dashboard
    test('cashier can view dashboard', function () {
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.dashboard');
    });

    // Test: Cashier can access POS
    test('cashier can access POS interface', function () {
        $response = $this->get('/cashier/pos');
        
        $response->assertStatus(200);
    });

    // Test: Cashier can view orders
    test('cashier can view orders', function () {
        // Create some orders for today
        Order::factory()->count(3)->create([
            'created_at' => now(),
        ]);

        $response = $this->get('/cashier/orders');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.orders');
    });

    // Test: Cashier can view pending payments
    test('cashier can view pending payments', function () {
        Order::factory()->count(2)->create([
            'payment_status' => 'pending',
            'created_at' => now(),
        ]);

        $response = $this->get('/cashier/payments');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.payments');
    });

    // Test: Cashier can verify payment
    test('cashier can verify pending payment', function () {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
            'payment_method' => 'qris',
        ]);

        $response = $this->post("/cashier/payments/{$order->id}/verify");

        $response->assertRedirect();
        
        // Refresh order from database
        $order->refresh();
        expect($order->payment_status)->toBe('paid');
    });

    // Test: Cashier cannot access admin routes
    test('cashier cannot access admin routes', function () {
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    // Test: Only cashier can access cashier routes
    test('non-cashier cannot access cashier dashboard', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(403);
    });
});
