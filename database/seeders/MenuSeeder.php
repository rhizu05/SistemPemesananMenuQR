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
        if (Category::count() == 0) {
            $this->call(CategorySeeder::class);
        }

        $menus = [
            // --- MAKANAN UTAMA ---
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur mata sapi, ayam suwir, sosis, dan kerupuk udang.',
                'price' => 28000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan olesan madu spesial, disajikan dengan lalapan dan sambal terasi.',
                'price' => 32000,
                'stock' => 40,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Mie Goreng Jawa',
                'description' => 'Mie goreng khas Jawa dengan bumbu rempah pilihan, suwiran ayam, dan sayuran segar.',
                'price' => 24000,
                'stock' => 45,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Sate Ayam Madura',
                'description' => '10 tusuk sate ayam dengan bumbu kacang khas Madura dan lontong.',
                'price' => 35000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Soto Betawi',
                'description' => 'Soto dengan kuah santan susu yang gurih, potongan daging sapi empuk, dan emping.',
                'price' => 38000,
                'stock' => 25,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Iga Bakar Penyet',
                'description' => 'Iga sapi bakar yang dipenyet dengan sambal bawang pedas, lengkap dengan nasi hangat.',
                'price' => 55000,
                'stock' => 20,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Nasi Uduk Komplit',
                'description' => 'Nasi uduk harum dengan ayam goreng, tempe orek, telur dadar iris, dan bihun goreng.',
                'price' => 27000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_name' => 'Makanan Utama',
                'name' => 'Kwetiau Siram Sapi',
                'description' => 'Kwetiau siram dengan kuah kental gurih dan irisan daging sapi.',
                'price' => 30000,
                'stock' => 35,
                'is_available' => true,
            ],

            // --- MINUMAN ---
            [
                'category_name' => 'Minuman',
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin yang menyegarkan dahaga.',
                'price' => 6000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Es Jeruk Murni',
                'description' => 'Perasan jeruk asli dengan es batu segar.',
                'price' => 12000,
                'stock' => 80,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Kopi Susu Gula Aren',
                'description' => 'Kopi susu kekinian dengan campuran gula aren asli yang legit.',
                'price' => 22000,
                'stock' => 60,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Cappuccino Ice',
                'description' => 'Espresso dengan fresh milk dan foam tebal, disajikan dingin.',
                'price' => 25000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Jus Alpukat',
                'description' => 'Jus alpukat mentega kental dengan susu kental manis coklat.',
                'price' => 18000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Jus Mangga',
                'description' => 'Jus mangga harum manis yang segar dan kaya vitamin C.',
                'price' => 18000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Lemon Tea Ice',
                'description' => 'Teh dingin dengan perasan lemon segar.',
                'price' => 10000,
                'stock' => 70,
                'is_available' => true,
            ],
            [
                'category_name' => 'Minuman',
                'name' => 'Air Mineral',
                'description' => 'Air mineral kemasan botol 600ml.',
                'price' => 5000,
                'stock' => 200,
                'is_available' => true,
            ],

            // --- APPETIZER ---
            [
                'category_name' => 'Appetizer',
                'name' => 'Tahu Crispy',
                'description' => 'Tahu goreng tepung renyah dengan bumbu tabur pedas asin.',
                'price' => 12000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_name' => 'Appetizer',
                'name' => 'French Fries',
                'description' => 'Kentang goreng renyah disajikan dengan saus sambal dan mayones.',
                'price' => 15000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_name' => 'Appetizer',
                'name' => 'Jamur Enoki Crispy',
                'description' => 'Jamur enoki goreng tepung yang garing dan gurih.',
                'price' => 18000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_name' => 'Appetizer',
                'name' => 'Lumpia Semarang',
                'description' => '2 pcs lumpia isi rebung dan ayam, disajikan dengan saus tauco.',
                'price' => 20000,
                'stock' => 20,
                'is_available' => true,
            ],
            [
                'category_name' => 'Appetizer',
                'name' => 'Risoles Mayo',
                'description' => 'Risoles dengan isian smoked beef, telur, dan mayones creamy.',
                'price' => 15000,
                'stock' => 25,
                'is_available' => true,
            ],

            // --- DESSERT ---
            [
                'category_name' => 'Dessert',
                'name' => 'Pisang Bakar Coklat Keju',
                'description' => 'Pisang bakar manis dengan topping coklat meses dan parutan keju melimpah.',
                'price' => 18000,
                'stock' => 35,
                'is_available' => true,
            ],
            [
                'category_name' => 'Dessert',
                'name' => 'Roti Bakar Ovomaltine',
                'description' => 'Roti bakar tebal dengan selai Ovomaltine crunchy.',
                'price' => 22000,
                'stock' => 30,
                'is_available' => true,
            ],
            [
                'category_name' => 'Dessert',
                'name' => 'Es Campur Spesial',
                'description' => 'Es serut dengan berbagai macam topping buah, cincau, tape, dan susu.',
                'price' => 25000,
                'stock' => 40,
                'is_available' => true,
            ],
            [
                'category_name' => 'Dessert',
                'name' => 'Pudding Coklat Fla',
                'description' => 'Pudding coklat lembut disajikan dengan fla vanilla.',
                'price' => 12000,
                'stock' => 20,
                'is_available' => true,
            ],
             [
                'category_name' => 'Dessert',
                'name' => 'Choco Lava Cake',
                'description' => 'Kue coklat hangat dengan isian coklat lumer di dalamnya.',
                'price' => 28000,
                'stock' => 15,
                'is_available' => true,
            ],

            // --- PAKET HEMAT ---
            [
                'category_name' => 'Paket Hemat',
                'name' => 'Paket Kenyang 1',
                'description' => 'Nasi Ayam Geprek + Es Teh Manis.',
                'price' => 32000,
                'stock' => 25,
                'is_available' => true,
            ],
            [
                'category_name' => 'Paket Hemat',
                'name' => 'Paket Kenyang 2',
                'description' => 'Nasi Goreng Spesial + Es Jeruk.',
                'price' => 35000,
                'stock' => 25,
                'is_available' => true,
            ],
            [
                'category_name' => 'Paket Hemat',
                'name' => 'Paket Nongkrong',
                'description' => 'Kopi Susu Gula Aren + Roti Bakar Coklat.',
                'price' => 35000,
                'stock' => 30,
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menuData) {
            $category = Category::where('name', $menuData['category_name'])->first();
            
            if ($category) {
                Menu::firstOrCreate(
                    [
                        'name' => $menuData['name'],
                        'category_id' => $category->id
                    ],
                    [
                        'description' => $menuData['description'],
                        'price' => $menuData['price'],
                        'stock' => $menuData['stock'],
                        'is_available' => $menuData['is_available'],
                        'image' => null
                    ]
                );
            }
        }

        $this->command->info('✅ Menus seeded successfully!');
    }
}
