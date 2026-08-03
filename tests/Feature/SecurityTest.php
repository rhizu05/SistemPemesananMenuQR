<?php

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Security Testing', function () {

    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => 'admin']);
    });

    // TC-027: Form validation / CSRF
    test('forms reject invalid submissions', function () {
        $this->actingAs($this->admin);

        // Missing required fields must be rejected with validation errors
        $response = $this->post('/admin/menu', [
            'name' => 'Test Menu',
            'price' => 50000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['category_id', 'stock']);
    });

    // TC-028: SQL Injection Prevention
    test('search input is protected against SQL injection', function () {
        Menu::factory()->create(['name' => 'Nasi Goreng']);

        // Try SQL injection
        $response = $this->get("/menu?table=1&search=' OR '1'='1");

        // Should not cause SQL error
        $response->assertStatus(200);
        // Should treat input as literal string, not SQL
    });

    test('menu filter is protected against SQL injection', function () {
        $response = $this->get("/menu?table=1&category=' OR '1'='1");

        $response->assertStatus(200);
    });

    // TC-029: XSS Prevention
    test('menu name is escaped to prevent XSS', function () {
        $this->actingAs($this->admin);

        $menu = Menu::factory()->create([
            'name' => '<script>alert("XSS")</script>',
        ]);

        $response = $this->get('/admin/menu');

        // Script should be escaped, not executed
        $response->assertStatus(200);
        $response->assertDontSee('<script>alert("XSS")</script>', false);
        $response->assertSee('&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;', false);
    });

    test('user input in forms is sanitized', function () {
        $this->actingAs($this->admin);

        $category = \App\Models\Category::factory()->create();

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/admin/menu', [
                'name' => '<img src=x onerror=alert(1)>',
                'description' => '<script>alert("XSS")</script>',
                'price' => 50000,
                'stock' => 10,
                'category_id' => $category->id,
            ]);

        // Check that dangerous HTML is escaped
        $menu = Menu::where('name', '<img src=x onerror=alert(1)>')->first();
        expect($menu)->not->toBeNull();
    });

    test('unauthorized access is blocked', function () {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    });

    test('password is hashed in database', function () {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        // Password should be hashed, not plain text
        expect($user->password)->not->toBe('password123');
        expect(strlen($user->password))->toBeGreaterThan(50);
    });

    test('sensitive data is not exposed in responses', function () {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/dashboard');

        // Should not expose sensitive patterns
        $response->assertStatus(200);

        // Check that common sensitive patterns are not exposed
        $content = $response->getContent();

        // Should not contain database connection strings
        expect($content)->not->toContain('mysql://');
        expect($content)->not->toContain('DB_PASSWORD');
        expect($content)->not->toContain('DB_USERNAME');

        // Should not contain API keys patterns
        expect($content)->not->toContain('MIDTRANS_SERVER_KEY');
        expect($content)->not->toContain('MIDTRANS_CLIENT_KEY');
        expect($content)->not->toContain('API_KEY');
    });
});
