<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah email menjadi nullable karena sekarang tidak wajib
            $table->string('email')->nullable()->change();
            
            // Tambah kolom untuk verifikasi phone
            $table->string('phone_verified_at')->nullable()->after('phone');
            $table->string('otp_code')->nullable()->after('phone_verified_at');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['phone_verified_at', 'otp_code', 'otp_expires_at']);
        });
    }
};
