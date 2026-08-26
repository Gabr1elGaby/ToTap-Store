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
        if (!Schema::hasColumn('plans', 'features')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->text('features')->nullable()->after('transaction_limit');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('plans', 'features')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('features');
            });
        }
    }
};
