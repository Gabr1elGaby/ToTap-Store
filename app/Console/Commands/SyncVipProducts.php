<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VipResellerService;
use App\Models\GameProduct;
use App\Models\Game;
use Illuminate\Support\Str;

class SyncVipProducts extends Command
{
    protected $signature = 'vip:sync';
    protected $description = 'Sync products from VIP Reseller to the database';

    public function handle(VipResellerService $vipService)
    {
        $this->info('Starting VIP Reseller Product Sync...');

        try {
            $response = $vipService->getGameProducts();
            
            if (!$response['result']) {
                $this->error('Failed to fetch from VIP Reseller: ' . ($response['message'] ?? 'Unknown error'));
                return Command::FAILURE;
            }

            $products = $response['data'];
            $this->info('Fetched ' . count($products) . ' products from VIP Reseller.');

            $updatedCount = 0;
            $newCount = 0;

            foreach ($products as $item) {
                
                $vipGameName = $item['game'] ?? 'Unknown Game';
                
                // MAPPING MANUAL
                // Pastikan nama dari VIP map ke slug di DB kita
                $mapping = [
                    'Mobile Legends A' => 'mobile-legend',
                    'Mobile Legends B' => 'mobile-legend',
                    'Mobile Legends (Global)' => 'mobile-legend',
                    'Mobile Legends Vilog' => 'mobile-legend',
                    'Valorant' => 'valorant',
                    'Free Fire Max' => 'free-fire',
                    'Free Fire Global' => 'free-fire',
                    'PUBGM INDO A' => 'pubg',
                    'PUBGM INDO B' => 'pubg',
                    'Roblox Via Login' => 'roblox',
                ];
                
                $targetSlug = $mapping[$vipGameName] ?? null;

                if (!$targetSlug) {
                    continue; // Skip jika tidak ada di mapping
                }

                $game = Game::where('slug', $targetSlug)->first();

                if (!$game) {
                    continue; // Skip jika game belum dibuat di tabel games
                }

                // Update or Create the Product
                $product = GameProduct::updateOrCreate(
                    ['product_code' => $item['code']],
                    [
                        'game_id' => $game->id,
                        'name' => $item['name'],
                        'provider' => 'vip_reseller',
                        'price_modal' => $item['price']['special'] ?? ($item['price']['h2h'] ?? ($item['price']['premium'] ?? ($item['price']['basic'] ?? ($item['price'] ?? 0)))),
                        'price_sell' => ceil(($item['price']['special'] ?? ($item['price']['h2h'] ?? ($item['price']['premium'] ?? ($item['price']['basic'] ?? ($item['price'] ?? 0))))) * 1.1),
                        'status' => $item['status'], 
                        'is_active' => ($item['status'] === 'available'),
                    ]
                );

                if ($product->wasRecentlyCreated) {
                    $newCount++;
                } else {
                    $updatedCount++;
                }
            }

            $this->info("Sync Complete! New: $newCount | Updated: $updatedCount");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
