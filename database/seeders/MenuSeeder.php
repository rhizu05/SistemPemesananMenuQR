<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories exist first
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please run CategorySeeder first.');
            return;
        }

        $menus = [
            // Makanan Utama
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur mata sapi, ayam suwir, dan kerupuk udang.',
                'price' => 25000,
                'stock' => 50,
                'is_available' => true,
                'image' => null // Placeholder or null
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan olesan madu spesial, disajikan dengan lalapan dan sambal.',
                'price' => 30000,
                'stock' => 40,
                'is_available' => true,
                'image' => null
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Mie Goreng Jawa',
                'description' => 'Mie goreng khas Jawa dengan bumbu rempah pilihan dan sayuran segar.',
                'price' => 22000,
                'stock' => 45,
                'is_available' => true,
                'image' => null
            ],
            
            // Minuman
            [
                'category_name' => 'Minuman',
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin yang menyegarkan.',
                'price' => 5000,
                'stock' => 100,
                'is_available' => true,
                'image' => null
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Kopi Susu Gula Aren',
                'description' => 'Kopi susu kekinian dengan gula aren asli.',
                'price' => 18000,
                'stock' => 60,
                'is_available' => true,
                'image' => null
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Jus Alpukat',
                'description' => 'Jus alpukat kental dengan susu coklat.',
                'price' => 15000,
                'stock' => 30,
                'is_available' => true,
                'image' => null
            ],

            // Appetizer
            [
                'category_name' => 'Appetizer',
                'name' => 'Tahu Crispy',
                'description' => 'Tahu goreng tepung yang renyah dengan bumbu tabur.',
                'price' => 10000,
                'stock' => 50,
                'is_available' => true,
                'image' => null
            ],
            [
                'category_name' => 'Appetizer',
                'name' => 'French Fries',
                'description' => 'Kentang goreng renyah disajikan dengan saus sambal.',
                'price' => 12000,
                'stock' => 50,
                'is_available' => true,
                'image' => null
            ],

            // Dessert
            [
                'category_name' => 'Dessert',
                'name' => 'Pisang Bakar Coklat Keju',
                'description' => 'Pisang bakar dengan topping coklat meses dan parutan keju.',
                'price' => 15000,
                'stock' => 35,
                'is_available' => true,
                'image' => null
            ],

            // Paket Hemat
            [
                'category_name' => 'Paket Hemat',
                'name' => 'Paket Kenyang 1',
                'description' => 'Nasi Ayam Geprek + Es Teh Manis.',
                'price' => 28000,
                'stock' => 25,
                'is_available' => true,
                'image' => null
            ],
        ];

        foreach ($menus as $menuData) {
            // Find category ID by name
            $category = Category::where('name', $menuData['category_name'])->first();
            
            if ($category) {
                Menu::create([
                    'category_id' => $category->id,
                    'name' => $menuData['name'],
                    'description' => $menuData['description'],
                    'price' => $menuData['price'],
                    'stock' => $menuData['stock'],
                    'is_available' => $menuData['is_available'],
                    'image' => $menuData['image']
                ]);
            }
        }

        $this->command->info('✅ Menus seeded successfully!');
        $this->command->info('Created ' . count($menus) . ' sample menu items.');
    }
}
