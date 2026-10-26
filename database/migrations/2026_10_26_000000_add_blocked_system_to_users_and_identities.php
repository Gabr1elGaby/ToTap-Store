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
        if (!Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_blocked')->default(false)->after('role');
            });
        }

        if (!Schema::hasTable('blocked_identities')) {
            Schema::create('blocked_identities', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // 'email' or 'phone'
                $table->string('value')->index(); // the blocked email or normalized phone
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('reason')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_identities');

        if (Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_blocked');
            });
        }
    }
};
