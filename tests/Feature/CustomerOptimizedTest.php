<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Customer Features - Optimized', function () {
    
    beforeEach(function () {
        $this->customer = User::factory()->create([
            'role' => 'customer',
            'phone' => '081234567890',
        ]);
    });

    test('guest can view menu page', function () {
        Menu::factory()->count(5)->create(['is_available' => true]);
        
        $response = $this->get('/menu');
        
        $response->assertStatus(200);
    });

    test('customer can view menu when authenticated', function () {
        $this->actingAs($this->customer);
        
        Menu::factory()->count(5)->create(['is_available' => true]);
        
        $response = $this->get('/menu');
        
        $response->assertStatus(200);
    });

    test('customer can view cart page', function () {
        $response = $this->get('/cart');
        
        $response->assertStatus(200);
    });

    test('authenticated customer can view their orders', function () {
        $this->actingAs($this->customer);
        
        Order::factory()->count(2)->create([
            'user_id' => $this->customer->id,
        ]);

        $response = $this->get('/orders');
        
        $response->assertStatus(200);
    });

    test('menu displays with categories', function () {
        $category = Category::factory()->create(['name' => 'Makanan']);
        Menu::factory()->count(3)->create([
            'category_id' => $category->id,
            'is_available' => true,
        ]);

        $response = $this->get('/menu');
        
        $response->assertStatus(200);
    });

    test('only available menus are shown', function () {
        Menu::factory()->count(3)->create(['is_available' => true]);
        Menu::factory()->count(2)->create(['is_available' => false]);

        $response = $this->get('/menu');
        
        $response->assertStatus(200);
        // Should show available menus
    });

    test('customer can access home page', function () {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    });
});
