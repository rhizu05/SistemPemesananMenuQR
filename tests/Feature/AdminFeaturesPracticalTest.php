<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Admin Features - Practical', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'name' => 'Admin AKPL',
            'email' => 'admin@akpl.com',
            'role' => 'admin',
        ]);

        $this->actingAs($this->admin);
    });

    // Test: Admin can view menu list
    test('admin can view menu list', function () {
        Menu::factory()->count(5)->create();
        
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.menu');
    });

    // Test: Admin can create menu
    test('admin can create menu item', function () {
        Storage::fake('public');
        
        $category = Category::factory()->create();
        
        $response = $this->post('/admin/menu', [
            'name' => 'Test Menu',
            'category_id' => $category->id,
            'price' => 50000,
            'stock' => 10,
            'description' => 'Test description',
            'is_available' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('menus', [
            'name' => 'Test Menu',
            'price' => 50000,
        ]);
    });

    // Test: Admin can view categories
    test('admin can view category list', function () {
        Category::factory()->count(3)->create();
        
        $response = $this->get('/admin/categories');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.categories');
    });

    // Test: Admin can create category
    test('admin can create category', function () {
        $response = $this->post('/admin/categories', [
            'name' => 'Test Category',
            'description' => 'Test description',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    });

    // Test: Admin can access dashboard
    test('admin can access dashboard', function () {
        $response = $this->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    });

    // Test: Admin can update menu
    test('admin can update menu item', function () {
        $menu = Menu::factory()->create(['name' => 'Old Name']);
        
        $response = $this->put("/admin/menu/{$menu->id}", [
            'name' => 'Updated Name',
            'category_id' => $menu->category_id,
            'price' => $menu->price,
            'stock' => $menu->stock,
            'description' => $menu->description,
            'is_available' => $menu->is_available,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'name' => 'Updated Name',
        ]);
    });

    // Test: Admin can delete menu
    test('admin can delete menu item', function () {
        $menu = Menu::factory()->create();
        
        $response = $this->delete("/admin/menu/{$menu->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    });

    // Test: Only admin can access admin routes
    test('non-admin cannot access admin menu', function () {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier);
        
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(403);
    });
});
