<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            if (!Schema::hasColumn('cvs', 'access_token')) {
                $table->string('access_token', 64)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('cvs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('template_id');
            }
        });

        // Generate access tokens for any existing records
        $existingCvs = DB::table('cvs')->whereNull('access_token')->orWhere('access_token', '')->get();
        foreach ($existingCvs as $cvRecord) {
            DB::table('cvs')->where('id', $cvRecord->id)->update([
                'access_token' => 'cv_' . Str::random(24),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            if (Schema::hasColumn('cvs', 'access_token')) {
                $table->dropColumn('access_token');
            }
            if (Schema::hasColumn('cvs', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
