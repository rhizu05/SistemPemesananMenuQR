<?php

use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

describe('Voucher System - Comprehensive Tests', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
    });

    // ==================== VOUCHER CREATION ====================
    
    test('admin can create percentage voucher', function () {
        $this->actingAs($this->admin);
        
        $voucher = Voucher::factory()->create([
            'code' => 'DISKON20',
            'type' => 'percentage',
            'value' => 20,
            'min_transaction' => 50000,
            'max_discount' => 10000,
        ]);

        expect($voucher)->toBeInstanceOf(Voucher::class);
        expect($voucher->code)->toBe('DISKON20');
        expect($voucher->type)->toBe('percentage');
        expect($voucher->value)->toBe(20.00);
    });

    test('admin can create fixed amount voucher', function () {
        $this->actingAs($this->admin);
        
        $voucher = Voucher::factory()->create([
            'code' => 'HEMAT5K',
            'type' => 'fixed_amount',
            'value' => 5000,
            'min_transaction' => 25000,
        ]);

        expect($voucher->type)->toBe('fixed_amount');
        expect($voucher->value)->toBe(5000.00);
    });

    // ==================== VOUCHER VALIDATION ====================
    
    test('voucher is valid when active and within date range', function () {
        $voucher = Voucher::factory()->create([
            'is_active' => true,
            'valid_from' => Carbon::now()->subDays(1),
            'valid_until' => Carbon::now()->addDays(7),
        ]);

        expect($voucher->isValid())->toBeTrue();
    });

    test('voucher is invalid when inactive', function () {
        $voucher = Voucher::factory()->create([
            'is_active' => false,
        ]);

        expect($voucher->isValid())->toBeFalse();
    });

    test('voucher is invalid when expired', function () {
        $voucher = Voucher::factory()->create([
            'is_active' => true,
            'valid_from' => Carbon::now()->subDays(10),
            'valid_until' => Carbon::now()->subDays(1),
        ]);

        expect($voucher->isValid())->toBeFalse();
    });

    test('voucher is invalid when not yet started', function () {
        $voucher = Voucher::factory()->create([
            'is_active' => true,
            'valid_from' => Carbon::now()->addDays(1),
            'valid_until' => Carbon::now()->addDays(7),
        ]);

        expect($voucher->isValid())->toBeFalse();
    });

    // ==================== VOUCHER AVAILABILITY ====================
    
    test('voucher is available when quota not reached', function () {
        $voucher = Voucher::factory()->create([
            'quota' => 100,
            'used_count' => 50,
        ]);

        expect($voucher->isAvailable())->toBeTrue();
    });

    test('voucher is unavailable when quota reached', function () {
        $voucher = Voucher::factory()->create([
            'quota' => 100,
            'used_count' => 100,
        ]);

        expect($voucher->isAvailable())->toBeFalse();
    });

    test('voucher with no quota is always available', function () {
        $voucher = Voucher::factory()->create([
            'quota' => null,
            'used_count' => 1000,
        ]);

        expect($voucher->isAvailable())->toBeTrue();
    });

    // ==================== DISCOUNT CALCULATION ====================
    
    test('percentage voucher calculates discount correctly', function () {
        $voucher = Voucher::factory()->create([
            'type' => 'percentage',
            'value' => 20, // 20%
            'min_transaction' => 50000,
            'max_discount' => null,
        ]);

        $discount = $voucher->calculateDiscount(100000);
        
        expect($discount)->toBe(20000.0); // 20% of 100000
    });

    test('percentage voucher respects max discount', function () {
        $voucher = Voucher::factory()->create([
            'type' => 'percentage',
            'value' => 20, // 20%
            'min_transaction' => 50000,
            'max_discount' => 15000,
        ]);

        $discount = $voucher->calculateDiscount(100000);
        
        // 20% of 100000 = 20000, but max is 15000
        expect($discount)->toBe(15000.0);
    });

    test('percentage voucher returns zero when below minimum transaction', function () {
        $voucher = Voucher::factory()->create([
            'type' => 'percentage',
            'value' => 20,
            'min_transaction' => 50000,
        ]);

        $discount = $voucher->calculateDiscount(30000);
        
        expect($discount)->toBe(0.0);
    });

    test('fixed amount voucher calculates discount correctly', function () {
        $voucher = Voucher::factory()->create([
            'type' => 'fixed_amount',
            'value' => 10000,
            'min_transaction' => 50000,
        ]);

        $discount = $voucher->calculateDiscount(100000);
        
        expect($discount)->toBe(10000.0);
    });

    test('fixed amount voucher cannot exceed subtotal', function () {
        $voucher = Voucher::factory()->create([
            'type' => 'fixed_amount',
            'value' => 10000,
            'min_transaction' => 5000,
        ]);

        $discount = $voucher->calculateDiscount(8000);
        
        // Discount should be limited to subtotal
        expect($discount)->toBe(8000.0);
    });

    // ==================== USER LIMIT ====================
    
    test('voucher can be used by user within limit', function () {
        $voucher = Voucher::factory()->create([
            'user_limit' => 3,
        ]);

        // User has used voucher 2 times
        VoucherUsage::factory()->count(2)->create([
            'voucher_id' => $voucher->id,
            'user_id' => $this->customer->id,
        ]);

        expect($voucher->canBeUsedBy($this->customer->id))->toBeTrue();
    });

    test('voucher cannot be used when user limit reached', function () {
        $voucher = Voucher::factory()->create([
            'user_limit' => 3,
        ]);

        // User has used voucher 3 times (limit reached)
        VoucherUsage::factory()->count(3)->create([
            'voucher_id' => $voucher->id,
            'user_id' => $this->customer->id,
        ]);

        expect($voucher->canBeUsedBy($this->customer->id))->toBeFalse();
    });

    // ==================== USER TYPE RESTRICTION ====================
    
    test('registered-only voucher cannot be used by guest', function () {
        $voucher = Voucher::factory()->create([
            'user_type' => 'registered',
        ]);

        expect($voucher->canBeUsedBy(null))->toBeFalse();
    });

    test('all-users voucher can be used by guest', function () {
        $voucher = Voucher::factory()->create([
            'user_type' => 'all',
        ]);

        expect($voucher->canBeUsedBy(null))->toBeTrue();
    });

    // ==================== VOUCHER USAGE TRACKING ====================
    
    test('voucher usage is tracked correctly', function () {
        $voucher = Voucher::factory()->create([
            'used_count' => 0,
        ]);

        VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $this->customer->id,
            'order_id' => Order::factory()->create()->id,
        ]);

        $voucher->increment('used_count');
        $voucher->refresh();

        expect($voucher->used_count)->toBe(1);
    });

    // ==================== SCOPE TESTS ====================
    
    test('active scope returns only active vouchers', function () {
        Voucher::factory()->create(['is_active' => true]);
        Voucher::factory()->create(['is_active' => true]);
        Voucher::factory()->create(['is_active' => false]);

        $activeVouchers = Voucher::active()->get();

        expect($activeVouchers)->toHaveCount(2);
    });

    test('valid scope returns only valid vouchers', function () {
        // Valid voucher
        Voucher::factory()->create([
            'valid_from' => Carbon::now()->subDays(1),
            'valid_until' => Carbon::now()->addDays(7),
        ]);

        // Expired voucher
        Voucher::factory()->create([
            'valid_from' => Carbon::now()->subDays(10),
            'valid_until' => Carbon::now()->subDays(1),
        ]);

        $validVouchers = Voucher::valid()->get();

        expect($validVouchers)->toHaveCount(1);
    });

    test('available scope returns only available vouchers', function () {
        // Available voucher
        Voucher::factory()->create([
            'quota' => 100,
            'used_count' => 50,
        ]);

        // Quota reached voucher
        Voucher::factory()->create([
            'quota' => 100,
            'used_count' => 100,
        ]);

        $availableVouchers = Voucher::available()->get();

        expect($availableVouchers)->toHaveCount(1);
    });

    // ==================== INTEGRATION TESTS ====================
    
    test('complete voucher workflow works correctly', function () {
        // Create voucher
        $voucher = Voucher::factory()->create([
            'code' => 'PROMO50',
            'type' => 'percentage',
            'value' => 50,
            'min_transaction' => 100000,
            'max_discount' => 50000,
            'quota' => 10,
            'used_count' => 0,
            'user_limit' => 1,
            'is_active' => true,
            'valid_from' => Carbon::now()->subDays(1),
            'valid_until' => Carbon::now()->addDays(7),
        ]);

        // Validate voucher
        expect($voucher->isValid())->toBeTrue();
        expect($voucher->isAvailable())->toBeTrue();
        expect($voucher->canBeUsedBy($this->customer->id))->toBeTrue();

        // Calculate discount
        $subtotal = 200000;
        $discount = $voucher->calculateDiscount($subtotal);
        expect($discount)->toBe(50000.0); // 50% of 200000 = 100000, but max is 50000

        // Use voucher
        $order = Order::factory()->create([
            'user_id' => $this->customer->id,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $subtotal - $discount,
            'voucher_id' => $voucher->id,
        ]);

        VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $this->customer->id,
            'order_id' => $order->id,
        ]);

        $voucher->increment('used_count');
        $voucher->refresh();

        // Verify usage
        expect($voucher->used_count)->toBe(1);
        expect($voucher->canBeUsedBy($this->customer->id))->toBeFalse(); // User limit reached
    });
});
