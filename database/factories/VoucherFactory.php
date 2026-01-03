<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed_amount']);
        
        return [
            'code' => strtoupper(Str::random(8)),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'type' => $type,
            'value' => $type === 'percentage' ? fake()->numberBetween(5, 50) : fake()->numberBetween(5000, 50000),
            'min_transaction' => fake()->numberBetween(30000, 100000),
            'max_discount' => $type === 'percentage' ? fake()->numberBetween(10000, 100000) : null,
            'quota' => fake()->numberBetween(10, 100),
            'used_count' => 0,
            'user_limit' => fake()->numberBetween(1, 5),
            'user_type' => fake()->randomElement(['all', 'registered', 'new']),
            'valid_from' => now()->subDays(7),
            'valid_until' => now()->addDays(30),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the voucher is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => now()->subDay(),
        ]);
    }

    /**
     * Indicate that the voucher is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the voucher is percentage type.
     */
    public function percentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percentage',
            'value' => fake()->numberBetween(5, 50),
            'max_discount' => fake()->numberBetween(10000, 100000),
        ]);
    }

    /**
     * Indicate that the voucher is fixed amount type.
     */
    public function fixedAmount(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'fixed_amount',
            'value' => fake()->numberBetween(5000, 50000),
            'max_discount' => null,
        ]);
    }
}
