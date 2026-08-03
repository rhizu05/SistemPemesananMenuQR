<?php

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest without a table number is redirected to scan QR', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('customer.scan-qr'));
});

test('guest with a table number can view the menu', function () {
    Category::factory()->create(['name' => 'Makanan']);
    Menu::factory()->count(3)->create(['is_available' => true]);

    $response = $this->get('/?table=1');

    $response->assertStatus(200);
});
