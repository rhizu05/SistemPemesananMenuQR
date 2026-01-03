<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Authentication & Authorization', function () {
    
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

    // TC-001: Admin Login
    test('admin can login and access admin dashboard', function () {
        $response = $this->post('/admin/login', [
            'email' => 'admin@akpl.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    });

    // TC-002: Cashier Login
    test('cashier can login and access cashier dashboard', function () {
        $response = $this->post('/admin/login', [
            'email' => 'kasir1@dapoerkatendjo.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/cashier/dashboard');
        $this->assertAuthenticatedAs($this->cashier);
    });

    // TC-003: Kitchen Login
    test('kitchen staff can login and access kitchen dashboard', function () {
        $response = $this->post('/admin/login', [
            'email' => 'kitchen@akpl.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/kitchen/dashboard');
        $this->assertAuthenticatedAs($this->kitchen);
    });

    // TC-008: Admin Cannot Access Cashier Routes
    test('admin cannot access cashier routes', function () {
        $this->actingAs($this->admin);
        
        $response = $this->get('/cashier/dashboard');
        
        $response->assertStatus(403);
    });

    // TC-015: Cashier Cannot Access Admin Routes
    test('cashier cannot access admin routes', function () {
        $this->actingAs($this->cashier);
        
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });

    test('unauthenticated users are redirected to login', function () {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect('/admin/login');
    });

    test('invalid credentials are rejected', function () {
        $response = $this->post('/admin/login', [
            'email' => 'admin@akpl.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    });
});
