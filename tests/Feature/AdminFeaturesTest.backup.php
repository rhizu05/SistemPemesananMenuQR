<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Admin Features', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'name' => 'Admin AKPL',
            'email' => 'admin@akpl.com',
            'role' => 'admin',
        ]);

        $this->actingAs($this->admin);
    });

    // TC-005: Admin - Menu Management
    test('admin can create new menu item', function () {
        Storage::fake('public');
        
        $category = Category::factory()->create();
        
        $response = $this->post('/admin/menu', [
            'name' => 'Test Menu',
            'category_id' => $category->id,
            'price' => 50000,
            'stock' => 10,
            'description' => 'Test description',
            'image' => UploadedFile::fake()->image('menu.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('menus', [
            'name' => 'Test Menu',
            'price' => 50000,
            'stock' => 10,
        ]);
    });

    test('admin can view menu list', function () {
        Menu::factory()->count(5)->create();
        
        $response = $this->get('/admin/menu');
        
        $response->assertStatus(200);
        $response->assertViewHas('menus');
    });

    test('admin can update menu item', function () {
        $menu = Menu::factory()->create(['name' => 'Old Name']);
        
        $response = $this->put("/admin/menu/{$menu->id}", [
            'name' => 'Updated Name',
            'category_id' => $menu->category_id,
            'price' => $menu->price,
            'stock' => $menu->stock,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'name' => 'Updated Name',
        ]);
    });

    test('admin can delete menu item', function () {
        $menu = Menu::factory()->create();
        
        $response = $this->delete("/admin/menu/{$menu->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    });

    // TC-006: Admin - Category Management
    test('admin can create category', function () {
        $response = $this->post('/admin/categories', [
            'name' => 'Test Category',
            'description' => 'Test description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    });

    test('admin can view category list', function () {
        Category::factory()->count(3)->create();
        
        $response = $this->get('/admin/categories');
        
        $response->assertStatus(200);
        $response->assertViewHas('categories');
    });

    // TC-007: Admin - Voucher Management
    test('admin can create voucher', function () {
        $response = $this->post('/admin/vouchers', [
            'code' => 'TEST10',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 50000,
            'max_discount' => 20000,
            'valid_from' => now()->format('Y-m-d'),
            'valid_until' => now()->addMonth()->format('Y-m-d'),
            'usage_limit' => 100,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vouchers', [
            'code' => 'TEST10',
            'type' => 'percentage',
            'value' => 10,
        ]);
    });

    test('admin can view voucher list', function () {
        Voucher::factory()->count(3)->create();
        
        $response = $this->get('/admin/vouchers');
        
        $response->assertStatus(200);
        $response->assertViewHas('vouchers');
    });

    test('admin can view dashboard with analytics', function () {
        $response = $this->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    });

    // TC-009: Admin Cannot Access POS
    test('admin cannot access POS routes', function () {
        $response = $this->get('/admin/pos');
        
        $response->assertStatus(404);
    });
});
