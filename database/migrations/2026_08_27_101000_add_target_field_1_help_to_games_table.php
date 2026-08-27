<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            if (!Schema::hasColumn('games', 'target_field_1_help')) {
                $table->text('target_field_1_help')->nullable()->after('target_field_2');
            }
            if (!Schema::hasColumn('games', 'description')) {
                $table->text('description')->nullable()->after('developer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            if (Schema::hasColumn('games', 'target_field_1_help')) {
                $table->dropColumn('target_field_1_help');
            }
            if (Schema::hasColumn('games', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
