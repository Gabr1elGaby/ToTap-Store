<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('product_code')->unique();
            $table->string('name');
            $table->decimal('price_modal', 12, 2)->default(0);
            $table->decimal('price_sell', 12, 2)->default(0);
            $table->string('status')->default('available');
            $table->string('provider')->default('vip_reseller');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_products');
    }
};
