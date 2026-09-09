<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('transactions')
            ->where('target_field_1', 'like', '%ameliacs5758@gmail.com%')
            ->orWhere('id', 'INV/APKPRE/TTS/004/IX/2026')
            ->update([
                'provider_trx_id' => 'VP6AA194E791AC6935433684',
                'provider_sn' => 'Tim Ranissa - Silakan Join Canva Team dan Link Jangan di Share - Link Team : https://www.canva.com/brand/join?token=BQu-9pmSGNUOgjDijs71NQ&invitationDestinationType=group',
                'status' => 'success',
            ]);
    }

    public function down(): void
    {
    }
};
