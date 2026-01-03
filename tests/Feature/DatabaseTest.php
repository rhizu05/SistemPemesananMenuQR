<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Database and Factories', function () {
    
    test('can create user with factory', function () {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        expect($user)->toBeInstanceOf(User::class);
        expect($user->role)->toBe('admin');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin',
        ]);
    });

    test('can create category with factory', function () {
        $category = Category::factory()->create([
            'name' => 'Test Category',
        ]);

        expect($category)->toBeInstanceOf(Category::class);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    });

    test('can create menu with factory', function () {
        $menu = Menu::factory()->create([
            'name' => 'Test Menu',
            'price' => 50000,
        ]);

        expect($menu)->toBeInstanceOf(Menu::class);
        expect($menu->price)->toBe(50000);
        $this->assertDatabaseHas('menus', [
            'name' => 'Test Menu',
        ]);
    });

    test('can create order with factory', function () {
        $order = Order::factory()->create();

        expect($order)->toBeInstanceOf(Order::class);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
        ]);
    });

    test('can create voucher with factory', function () {
        $voucher = Voucher::factory()->create([
            'code' => 'TEST123',
        ]);

        expect($voucher)->toBeInstanceOf(Voucher::class);
        expect($voucher->code)->toBe('TEST123');
        $this->assertDatabaseHas('vouchers', [
            'code' => 'TEST123',
        ]);
    });

    test('user factory creates different roles', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        $cashier = User::factory()->create(['role' => 'cashier']);
        $kitchen = User::factory()->create(['role' => 'kitchen']);
        $customer = User::factory()->create(['role' => 'customer']);

        expect($admin->role)->toBe('admin');
        expect($cashier->role)->toBe('cashier');
        expect($kitchen->role)->toBe('kitchen');
        expect($customer->role)->toBe('customer');
    });

    test('menu belongs to category', function () {
        $category = Category::factory()->create();
        $menu = Menu::factory()->create([
            'category_id' => $category->id,
        ]);

        expect($menu->category)->toBeInstanceOf(Category::class);
        expect($menu->category->id)->toBe($category->id);
    });

    test('order belongs to user', function () {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        expect($order->user)->toBeInstanceOf(User::class);
        expect($order->user->id)->toBe($user->id);
    });
});
