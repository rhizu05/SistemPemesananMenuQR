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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode voucher unik');
            $table->string('name')->comment('Nama voucher');
            $table->text('description')->nullable()->comment('Deskripsi voucher');
            $table->enum('type', ['percentage', 'fixed_amount'])->default('percentage')->comment('Tipe: persentase atau nominal tetap');
            $table->decimal('value', 10, 2)->comment('Nilai diskon (10 = 10% atau Rp 10.000)');
            $table->decimal('min_transaction', 10, 2)->default(0)->comment('Minimal belanja untuk pakai voucher');
            $table->decimal('max_discount', 10, 2)->nullable()->comment('Max diskon untuk percentage (opsional)');
            $table->integer('quota')->nullable()->comment('Total quota voucher (null = unlimited)');
            $table->integer('used_count')->default(0)->comment('Sudah dipakai berapa kali');
            $table->integer('user_limit')->default(1)->comment('Limit per user (berapa kali bisa dipakai)');
            $table->enum('user_type', ['all', 'registered', 'new'])->default('all')->comment('Untuk siapa: all/registered/new');
            $table->dateTime('valid_from')->nullable()->comment('Berlaku mulai');
            $table->dateTime('valid_until')->nullable()->comment('Berlaku sampai');
            $table->boolean('is_active')->default(true)->comment('Status aktif/nonaktif');
            $table->timestamps();
            
            // Index
            $table->index('code');
            $table->index('is_active');
            $table->index(['valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
