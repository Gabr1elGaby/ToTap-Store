<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'discount_amount')) {
                $table->integer('discount_amount')->default(0)->after('amount');
            }
            if (!Schema::hasColumn('transactions', 'original_amount')) {
                $table->integer('original_amount')->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('transactions', 'promo_title')) {
                $table->string('promo_title')->nullable()->after('original_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
            if (Schema::hasColumn('transactions', 'original_amount')) {
                $table->dropColumn('original_amount');
            }
            if (Schema::hasColumn('transactions', 'promo_title')) {
                $table->dropColumn('promo_title');
            }
        });
    }
};
