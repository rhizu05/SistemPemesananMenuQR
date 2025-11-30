<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'name' => 'Voucher Welcome 10%',
                'description' => 'Diskon 10% untuk semua pelanggan baru',
                'type' => 'percentage',
                'value' => 10,
                'min_transaction' => 50000,
                'max_discount' => 50000,
                'quota' => null, // Unlimited
                'user_limit' => 1,
                'user_type' => 'all',
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'LOYAL50K',
                'name' => 'Voucher Pelanggan Setia Rp 50.000',
                'description' => 'Diskon Rp 50.000 untuk min. belanja Rp 200.000',
                'type' => 'fixed_amount',
                'value' => 50000,
                'min_transaction' => 200000,
                'max_discount' => null,
                'quota' => 100,
                'user_limit' => 1,
                'user_type' => 'registered',
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'FLASH20',
                'name' => 'Flash Sale 20%',
                'description' => 'Flash sale 20% max Rp 100.000 (Limited!)',
                'type' => 'percentage',
                'value' => 20,
                'min_transaction' => 100000,
                'max_discount' => 100000,
                'quota' => 50,
                'user_limit' => 1,
                'user_type' => 'all',
                'valid_from' => now(),
                'valid_until' => now()->addWeek(),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Gratis Ongkir Rp 15.000',
                'description' => 'Potongan Rp 15.000 untuk ongkir',
                'type' => 'fixed_amount',
                'value' => 15000,
                'min_transaction' => 75000,
                'max_discount' => null,
                'quota' => null,
                'user_limit' => 2,
                'user_type' => 'all',
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
