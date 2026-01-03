<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Nasi Goreng', 'Mie Goreng', 'Ayam Bakar', 'Sate Ayam',
                'Es Teh Manis', 'Es Jeruk', 'Kopi Hitam', 'Jus Alpukat'
            ]),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(15000, 75000),
            'image' => 'menu-' . fake()->numberBetween(1, 10) . '.jpg',
            'is_available' => true,
            'stock' => fake()->numberBetween(5, 50),
            'category_id' => Category::factory(),
        ];
    }
}
