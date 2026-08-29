<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VipResellerService;
use App\Models\GameProduct;
use App\Models\Game;
use Illuminate\Support\Facades\Log;

class SyncVipStock extends Command
{
    protected $signature = 'vip:sync-stock';
    protected $description = 'Otomatis sinkronisasi stok dan status available/empty dari VIP Reseller';

    public function handle(VipResellerService $vipService)
    {
        $this->info('Memulai Sinkronisasi Stok Otomatis dari VIP Reseller...');

        // Map nama game di sistem kita ke kata kunci filter di VIP Reseller
        $filterMappings = [
            'mobile-legend' => 'Mobile Legends',
            'roblox' => 'Roblox',
            'valorant' => 'Valorant',
            'free-fire' => 'Free Fire',
            'pubg' => 'PUBG',
            'marvel-rivals' => 'Marvel Rivals',
        ];

        $totalChecked = 0;
        $totalUpdated = 0;

        foreach ($filterMappings as $gameSlug => $filterKeyword) {
            $game = Game::where('slug', $gameSlug)->first();
            if (!$game) continue;

            try {
                $response = $vipService->getGameProducts($filterKeyword);
                if (!isset($response['result']) || !$response['result'] || empty($response['data'])) {
                    continue;
                }

                $apiServices = collect($response['data'])->keyBy('code');

                // Ambil semua produk di game ini
                $localProducts = GameProduct::where('game_id', $game->id)->get();

                foreach ($localProducts as $product) {
                    $totalChecked++;
                    if ($apiServices->has($product->product_code)) {
                        $apiItem = $apiServices->get($product->product_code);
                        $apiStatus = strtolower($apiItem['status']) === 'available' ? 'available' : 'empty';
                        $latestModal = $apiItem['price']['special'] ?? ($apiItem['price']['h2h'] ?? ($apiItem['price']['premium'] ?? ($apiItem['price']['basic'] ?? $product->price_modal)));

                        if ($product->status !== $apiStatus || $product->price_modal != $latestModal) {
                            $product->update([
                                'status' => $apiStatus,
                                'price_modal' => $latestModal,
                            ]);
                            $totalUpdated++;
                        }
                    } else {
                        // Jika sudah dihapus dari API, tandai empty
                        if ($product->status !== 'empty') {
                            $product->update(['status' => 'empty']);
                            $totalUpdated++;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Auto-sync stok gagal untuk game {$gameSlug}: " . $e->getMessage());
            }
        }

        $this->info("Sinkronisasi Selesai! Dicek: {$totalChecked} item | Diperbarui: {$totalUpdated} item.");
        return Command::SUCCESS;
    }
}
