<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50000, 500000);
        $discountAmount = 0;
        $totalAmount = $subtotal - $discountAmount;

        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'preparing', 'ready', 'completed', 'cancelled']),
            'total_amount' => $totalAmount,
            'customer_name' => fake()->name(),
            'customer_phone' => '08' . fake()->numerify('##########'),
            'table_number' => fake()->numberBetween(1, 20),
            'order_type' => fake()->randomElement(['dine-in', 'takeaway']),
            'special_requests' => fake()->optional()->sentence(),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'payment_method' => fake()->randomElement(['cash', 'qris', 'transfer']),
            'payment_reference' => fake()->optional()->uuid(),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
        ];
    }

    /**
     * Indicate that the order is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_status' => 'paid',
            'completed_at' => now(),
        ]);
    }
}
