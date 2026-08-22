<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('developer')->nullable();
            $table->string('category')->default('Mobile Game');
            $table->string('thumbnail')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('guide_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_zone_id')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
