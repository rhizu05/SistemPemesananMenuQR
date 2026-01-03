<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Updating user emails...');

        // Update admin email
        $admin = User::where('email', 'admin@akpl.com')->first();
        if ($admin) {
            $admin->email = 'admin@dapoerkatendjo.com';
            $admin->name = 'Admin Dapoer Katendjo';
            $admin->save();
            $this->command->info('✅ Admin updated: admin@dapoerkatendjo.com');
        } else {
            $this->command->warn('⚠️ Admin not found with email admin@akpl.com');
        }

        // Update kitchen email
        $kitchen = User::where('email', 'kitchen@akpl.com')->first();
        if ($kitchen) {
            $kitchen->email = 'kitchen@dapoerkatendjo.com';
            $kitchen->save();
            $this->command->info('✅ Kitchen updated: kitchen@dapoerkatendjo.com');
        } else {
            $this->command->warn('⚠️ Kitchen not found with email kitchen@akpl.com');
        }

        // Update cashier email (kasir1 -> cashier)
        $cashier = User::where('email', 'kasir1@dapoerkatendjo.com')->first();
        if ($cashier) {
            $cashier->email = 'cashier@dapoerkatendjo.com';
            $cashier->name = 'Kasir 1';
            $cashier->save();
            $this->command->info('✅ Cashier updated: cashier@dapoerkatendjo.com');
        } else {
            $this->command->warn('⚠️ Cashier not found with email kasir1@dapoerkatendjo.com');
        }

        // Delete kasir2
        $kasir2 = User::where('email', 'kasir2@dapoerkatendjo.com')->first();
        if ($kasir2) {
            $kasir2->delete();
            $this->command->info('✅ Kasir 2 deleted: kasir2@dapoerkatendjo.com');
        } else {
            $this->command->warn('⚠️ Kasir 2 not found');
        }

        $this->command->info('');
        $this->command->info('🎉 All users updated successfully!');
        $this->command->info('');
        $this->command->info('📋 Current credentials:');
        $this->command->info('   Admin:   admin@dapoerkatendjo.com / password');
        $this->command->info('   Kitchen: kitchen@dapoerkatendjo.com / password');
        $this->command->info('   Cashier: cashier@dapoerkatendjo.com / password');
    }
}
