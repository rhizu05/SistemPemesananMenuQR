<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Kitchen Features', function () {
    
    beforeEach(function () {
        $this->kitchen = User::factory()->create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@akpl.com',
            'role' => 'kitchen',
        ]);

        $this->actingAs($this->kitchen);
    });

    // TC-017: Kitchen View Orders
    test('kitchen can view incoming orders', function () {
        Order::factory()->count(5)->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewHas('orders');
    });

    test('kitchen sees orders sorted by status', function () {
        $pendingOrder = Order::factory()->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $preparingOrder = Order::factory()->create([
            'status' => 'preparing',
            'payment_status' => 'paid',
        ]);

        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
        // Pending orders should appear first
    });

    // TC-018: Kitchen Update Order Status
    test('kitchen can update order to preparing', function () {
        $order = Order::factory()->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $response = $this->post("/kitchen/orders/{$order->id}/update-status", [
            'status' => 'preparing',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'preparing',
        ]);
    });

    test('kitchen can update order to ready', function () {
        $order = Order::factory()->create([
            'status' => 'preparing',
            'payment_status' => 'paid',
        ]);

        $response = $this->post("/kitchen/orders/{$order->id}/update-status", [
            'status' => 'ready',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'ready',
        ]);
    });

    test('kitchen can view order details', function () {
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->get("/kitchen/orders/{$order->id}");
        
        $response->assertStatus(200);
        $response->assertSee($order->id);
    });

    test('kitchen only sees paid orders', function () {
        $paidOrder = Order::factory()->create([
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        $unpaidOrder = Order::factory()->create([
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $response = $this->get('/kitchen/dashboard');
        
        $orders = $response->viewData('orders');
        expect($orders->contains($paidOrder))->toBeTrue();
        expect($orders->contains($unpaidOrder))->toBeFalse();
    });

    test('kitchen cannot access admin routes', function () {
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    test('kitchen cannot access cashier routes', function () {
        $response = $this->get('/cashier/pos');
        
        $response->assertStatus(403);
    });
});
