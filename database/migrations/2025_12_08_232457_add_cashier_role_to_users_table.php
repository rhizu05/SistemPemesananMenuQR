<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if we're using SQLite (for testing) or MySQL (for production)
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support ENUM modification, but it accepts any string value
            // The role column already exists from previous migration, no change needed
            // SQLite will accept 'cashier' value even though it wasn't in original enum
        } else {
            // MySQL/MariaDB: Alter the enum to add 'cashier' role
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cashier', 'kitchen', 'customer') DEFAULT 'customer'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using SQLite (for testing) or MySQL (for production)
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite: No action needed
        } else {
            // MySQL/MariaDB: Revert back to original enum (remove cashier)
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kitchen', 'customer') DEFAULT 'customer'");
        }
    }
};
