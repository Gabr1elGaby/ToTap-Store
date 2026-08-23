<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus semua produk yang statusnya empty, gangguan, atau error
        DB::table('game_products')
            ->whereRaw("LOWER(status) != 'available'")
            ->orWhereRaw("LOWER(status) = 'empty'")
            ->orWhereRaw("LOWER(status) = 'gangguan'")
            ->orWhereRaw("LOWER(status) = 'error'")
            ->orWhere('price_modal', '<=', 0)
            ->orWhereRaw("LOWER(name) LIKE '%open%'")
            ->orWhereRaw("LOWER(name) LIKE '%dummy%'")
            ->orWhereRaw("LOWER(name) LIKE '%testing%'")
            ->orWhereRaw("LOWER(name) LIKE '%info%'")
            ->orWhereRaw("LOWER(name) LIKE '%rate%'")
            ->delete();
    }

    public function down(): void
    {
    }
};
