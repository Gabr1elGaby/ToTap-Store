<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('transactions') && !Schema::hasColumn('transactions', 'provider_sn')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->text('provider_sn')->nullable()->after('provider_trx_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transactions') && Schema::hasColumn('transactions', 'provider_sn')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('provider_sn');
            });
        }
    }
};
