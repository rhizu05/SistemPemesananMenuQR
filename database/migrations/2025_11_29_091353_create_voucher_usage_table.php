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
        Schema::create('voucher_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Null jika guest');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2)->comment('Jumlah diskon yang didapat');
            $table->timestamp('used_at')->useCurrent();
            
            // Index
            $table->index('voucher_id');
            $table->index('user_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_usage');
    }
};
