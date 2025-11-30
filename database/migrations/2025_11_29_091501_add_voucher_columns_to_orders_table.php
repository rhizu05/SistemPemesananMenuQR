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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('voucher_id')->nullable()->after('snap_token')->constrained('vouchers')->onDelete('set null');
            $table->string('voucher_code', 50)->nullable()->after('voucher_id')->comment('Kode voucher yang dipakai');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('voucher_code')->comment('Jumlah diskon dari voucher');
            $table->decimal('subtotal', 10, 2)->after('discount_amount')->comment('Subtotal sebelum diskon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'voucher_code', 'discount_amount', 'subtotal']);
        });
    }
};
