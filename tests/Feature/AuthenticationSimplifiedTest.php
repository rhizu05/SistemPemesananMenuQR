<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Authentication & Authorization - Simplified', function () {
    
    beforeEach(function () {
        // Create test users
        $this->admin = User::factory()->create([
            'name' => 'Admin AKPL',
            'email' => 'admin@akpl.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->cashier = User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@dapoerkatendjo.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        $this->kitchen = User::factory()->create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@akpl.com',
            'password' => bcrypt('password'),
            'role' => 'kitchen',
        ]);
    });

    // Test: Admin can access admin dashboard when authenticated
    test('admin can access admin dashboard when authenticated', function () {
        $this->actingAs($this->admin);
        
        $response = $this->get('/admin/dashboard');
        
        $response->assertStatus(200);
    });

    // Test: Cashier can access cashier dashboard when authenticated
    test('cashier can access cashier dashboard when authenticated', function () {
        $this->actingAs($this->cashier);
        
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(200);
    });

    // Test: Kitchen can access kitchen dashboard when authenticated
    test('kitchen staff can access kitchen dashboard when authenticated', function () {
        $this->actingAs($this->kitchen);
        
        $response = $this->get('/kitchen/dashboard');
        
        $response->assertStatus(200);
    });

    // Test: Admin cannot access cashier routes
    test('admin cannot access cashier routes', function () {
        $this->actingAs($this->admin);
        
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(403);
    });

    // Test: Cashier cannot access admin routes
    test('cashier cannot access admin routes', function () {
        $this->actingAs($this->cashier);
        
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    // Test: Unauthenticated users are redirected to login
    test('unauthenticated users are redirected to login', function () {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/admin/login');
    });

    // Test: Users can be created with different roles
    test('users can be created with different roles', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $kitchen = User::factory()->create(['role' => 'kitchen']);
        $customer = User::factory()->create(['role' => 'customer']);

        expect($admin->role)->toBe('admin');
        expect($cashier->role)->toBe('cashier');
        expect($kitchen->role)->toBe('kitchen');
        expect($customer->role)->toBe('customer');
    });
});
