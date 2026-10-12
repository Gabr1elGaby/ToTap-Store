<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Game;
use App\Models\GameProduct;

return new class extends Migration
{
    public function up(): void
    {
        $game = Game::updateOrCreate(
            ['slug' => 'claude-ai'],
            [
                'name' => 'Claude AI API (Claude Code CLI)',
                'developer' => 'Anthropic / MWAPI',
                'category' => 'Aplikasi Premium',
                'description' => 'Saldo API Claude AI resmi $25 - $100. Support Claude Code CLI di PowerShell/Terminal, Cursor, dan VS Code untuk koding website & aplikasi otomatis.',
                'thumbnail' => 'https://upload.wikimedia.org/wikipedia/commons/7/78/Anthropic_logo.svg',
                'cover_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
                'guide_text' => 'Setelah pembayaran berhasil, Anda akan langsung mendapatkan 3 baris script pengaktifan otomatis untuk PowerShell di halaman invoice ini.',
                'is_active' => true,
                'requires_zone_id' => false,
                'target_field_1' => 'No. WhatsApp / Email Penerima',
                'target_field_2' => 'Catatan Perangkat (Opsional)',
                'target_field_1_help' => 'Masukkan nomor WhatsApp atau alamat email aktif untuk pengiriman riwayat pesanan.',
            ]
        );

        GameProduct::updateOrCreate(
            ['product_code' => 'CLAUDE-API-25'],
            [
                'game_id' => $game->id,
                'name' => 'Saldo API Claude $25 [1 Key Quota]',
                'price_modal' => 12500,
                'price_sell' => 20000,
                'price_normal' => 35000,
                'is_promo' => 1,
                'status' => 'available',
                'provider' => 'manual',
            ]
        );

        GameProduct::updateOrCreate(
            ['product_code' => 'CLAUDE-API-100'],
            [
                'game_id' => $game->id,
                'name' => 'Saldo API Claude $100 [Full 4 Keys / 100$]',
                'price_modal' => 25000,
                'price_sell' => 50000,
                'price_normal' => 85000,
                'is_promo' => 1,
                'status' => 'available',
                'provider' => 'manual',
            ]
        );
    }

    public function down(): void
    {
        $game = Game::where('slug', 'claude-ai')->first();
        if ($game) {
            $game->products()->delete();
            $game->delete();
        }
    }
};
