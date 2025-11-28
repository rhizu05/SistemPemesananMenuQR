<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin AKPL',
            'email' => 'admin@akpl.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1'
        ]);

        // Create Kitchen User
        User::create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@akpl.com',
            'password' => Hash::make('password'),
            'role' => 'kitchen',
            'phone' => '081234567891',
            'address' => 'Jl. Kitchen No. 2'
        ]);

        // Create Customer User
        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@akpl.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567892',
            'address' => 'Jl. Customer No. 3'
        ]);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('Admin: admin@akpl.com / password');
        $this->command->info('Kitchen: kitchen@akpl.com / password');
        $this->command->info('Customer: customer@akpl.com / password');
    }
}
