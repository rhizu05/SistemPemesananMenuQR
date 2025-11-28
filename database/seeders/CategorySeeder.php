<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan Utama',
                'description' => 'Menu makanan utama seperti nasi, mie, dan hidangan berat lainnya',
                'is_active' => true
            ],
            [
                'name' => 'Minuman',
                'description' => 'Berbagai pilihan minuman segar, kopi, teh, dan jus',
                'is_active' => true
            ],
            [
                'name' => 'Appetizer',
                'description' => 'Hidangan pembuka dan camilan ringan',
                'is_active' => true
            ],
            [
                'name' => 'Dessert',
                'description' => 'Hidangan penutup manis seperti es krim, kue, dan pudding',
                'is_active' => true
            ],
            [
                'name' => 'Paket Hemat',
                'description' => 'Paket bundling makanan dan minuman dengan harga spesial',
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully!');
        $this->command->info('Created ' . count($categories) . ' default categories.');
    }
}
