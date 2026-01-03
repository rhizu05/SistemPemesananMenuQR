<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CleanupAndUpdateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Cleaning up and updating users...');

        // Delete duplicate admin@dapoerkatendjo.com if exists
        $duplicateAdmin = User::where('email', 'admin@dapoerkatendjo.com')->first();
        if ($duplicateAdmin) {
            $duplicateAdmin->delete();
            $this->command->info('🗑️ Deleted duplicate admin@dapoerkatendjo.com');
        }

        // Delete duplicate kitchen@dapoerkatendjo.com if exists
        $duplicateKitchen = User::where('email', 'kitchen@dapoerkatendjo.com')->first();
        if ($duplicateKitchen) {
            $duplicateKitchen->delete();
            $this->command->info('🗑️ Deleted duplicate kitchen@dapoerkatendjo.com');
        }

        // Delete kasir2
        $kasir2 = User::where('email', 'kasir2@dapoerkatendjo.com')->first();
        if ($kasir2) {
            $kasir2->delete();
            $this->command->info('✅ Kasir 2 deleted: kasir2@dapoerkatendjo.com');
        }

        // Now update the original users
        // Update admin email
        $admin = User::where('email', 'admin@akpl.com')->first();
        if ($admin) {
            $admin->email = 'admin@dapoerkatendjo.com';
            $admin->name = 'Admin Dapoer Katendjo';
            $admin->save();
            $this->command->info('✅ Admin updated: admin@dapoerkatendjo.com');
        }

        // Update kitchen email
        $kitchen = User::where('email', 'kitchen@akpl.com')->first();
        if ($kitchen) {
            $kitchen->email = 'kitchen@dapoerkatendjo.com';
            $kitchen->save();
            $this->command->info('✅ Kitchen updated: kitchen@dapoerkatendjo.com');
        }

        // Update cashier email (kasir1 -> cashier)
        $cashier = User::where('email', 'kasir1@dapoerkatendjo.com')->first();
        if ($cashier) {
            $cashier->email = 'cashier@dapoerkatendjo.com';
            $cashier->name = 'Kasir 1';
            $cashier->save();
            $this->command->info('✅ Cashier updated: cashier@dapoerkatendjo.com');
        }

        $this->command->info('');
        $this->command->info('🎉 All users cleaned up and updated!');
        $this->command->info('');
        $this->command->info('📋 Final credentials:');
        $this->command->info('   Admin:   admin@dapoerkatendjo.com / password');
        $this->command->info('   Kitchen: kitchen@dapoerkatendjo.com / password');
        $this->command->info('   Cashier: cashier@dapoerkatendjo.com / password');
        $this->command->info('   Customer: customer@akpl.com / password');
    }
}
