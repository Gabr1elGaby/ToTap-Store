<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('game_id')->constrained();
            $table->foreignId('game_product_id')->constrained();
            
            // Customer Info
            $table->string('target_field_1');
            $table->string('target_field_2')->nullable();
            
            // Payment Info
            $table->integer('amount');
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('snap_token')->nullable();
            $table->string('payment_method')->nullable();
            
            // Reseller VIP API Info
            $table->string('provider_trx_id')->nullable();
            $table->string('provider_status')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
