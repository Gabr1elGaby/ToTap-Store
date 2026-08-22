<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable()->index();
            $table->string('order_type')->default('topup'); // 'topup', 'software', 'cv'
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name')->default('Pelanggan ToTap');
            $table->string('customer_contact')->nullable();
            $table->string('product_name')->nullable();
            $table->unsignedTinyInteger('rating')->default(5); // 1 - 5
            $table->text('review_text')->nullable(); // Saran & Kritik
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_reviews');
    }
};
