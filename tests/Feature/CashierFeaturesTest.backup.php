<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Cashier Features', function () {
    
    beforeEach(function () {
        $this->cashier = User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@dapoerkatendjo.com',
            'role' => 'cashier',
        ]);

        $this->actingAs($this->cashier);
    });

    // TC-010: Cashier Dashboard
    test('cashier can view dashboard with today stats', function () {
        // Create today's orders
        Order::factory()->count(3)->create([
            'created_at' => now(),
            'status' => 'completed',
        ]);

        // Create old orders (should not appear)
        Order::factory()->count(2)->create([
            'created_at' => now()->subDays(2),
        ]);

        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.dashboard');
    });

    // TC-011: Cashier POS Access
    test('cashier can access POS interface', function () {
        $response = $this->get('/cashier/pos');
        
        $response->assertStatus(200);
        $response->assertViewIs('cashier.pos');
    });

    // TC-012: Cashier Create Walk-in Order
    test('cashier can create walk-in order', function () {
        $menu = Menu::factory()->create(['price' => 25000, 'stock' => 10]);
        
        $response = $this->post('/cashier/orders', [
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'quantity' => 2,
                    'price' => $menu->price,
                ]
            ],
            'payment_method' => 'cash',
            'table_number' => 0, // Walk-in
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'payment_method' => 'cash',
            'table_number' => 0,
        ]);
    });

    // TC-013: Cashier Payment Verification
    test('cashier can verify pending payment', function () {
        $order = Order::factory()->create([
            'payment_status' => 'pending',
            'payment_method' => 'qris',
        ]);

        $response = $this->post("/cashier/payments/{$order->id}/verify");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
        ]);
    });

    test('cashier can view pending payments', function () {
        Order::factory()->count(3)->create([
            'payment_status' => 'pending',
            'created_at' => now(),
        ]);

        $response = $this->get('/cashier/payments');
        
        $response->assertStatus(200);
        $response->assertViewHas('orders');
    });

    // TC-014: Cashier View Today's Orders Only
    test('cashier can only view today orders', function () {
        // Today's orders
        Order::factory()->count(3)->create([
            'created_at' => now(),
        ]);

        // Old orders
        Order::factory()->count(2)->create([
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->get('/cashier/orders');
        
        $response->assertStatus(200);
        // Should only see today's orders (3)
        $orders = $response->viewData('orders');
        expect($orders)->toHaveCount(3);
    });

    test('cashier can print receipt', function () {
        $order = Order::factory()->create([
            'created_at' => now(),
        ]);

        $response = $this->get("/cashier/orders/{$order->id}/receipt");
        
        $response->assertStatus(200);
    });

    // TC-015: Already tested in AuthenticationTest
    test('cashier cannot access admin menu management', function () {
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });
});
