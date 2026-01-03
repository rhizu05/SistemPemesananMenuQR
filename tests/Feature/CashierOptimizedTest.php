<?php

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Cashier Features - Optimized', function () {
    
    beforeEach(function () {
        $this->cashier = User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@dapoerkatendjo.com',
            'role' => 'cashier',
        ]);

        $this->actingAs($this->cashier);
    });

    test('cashier can view dashboard', function () {
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.dashboard');
    });

    test('cashier can access POS interface', function () {
        $response = $this->get('/cashier/pos');
        
        $response->assertStatus(200);
    });

    test('cashier can view orders page', function () {
        $response = $this->get('/cashier/orders');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.orders');
    });

    test('cashier can view pending payments page', function () {
        $response = $this->get('/cashier/payments');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.payments');
    });

    test('cashier can verify payment', function () {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
            'payment_method' => 'qris',
        ]);

        $response = $this->post("/cashier/payments/{$order->id}/verify");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $order->refresh();
        expect($order->payment_status)->toBe('paid');
    });

    test('cashier cannot access admin menu', function () {
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    test('admin cannot access cashier dashboard', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(403);
    });
});
