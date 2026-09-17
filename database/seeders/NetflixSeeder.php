<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameProduct;
use Illuminate\Database\Seeder;

class NetflixSeeder extends Seeder
{
    public function run(): void
    {
        $game = Game::updateOrCreate(
            ['slug' => 'netflix-premium'],
            [
                'name' => 'Netflix Premium',
                'developer' => 'Netflix, Inc.',
                'description' => 'Langganan Netflix Premium Ultra HD 4K 1 Profile Private/Sharing Garansi Resmi.',
                'category' => 'Aplikasi Premium',
                'thumbnail' => 'https://assets.nflxext.com/ffe/siteui/common/icons/nficon2023.ico',
                'cover_image' => 'https://images.ctfassets.net/y2ske730sjqp/1aM9WgkR8hgPlRAYRojrVC/b852c0e41a77196635737e4f09d8bd38/BrandAssets_Logos_01-Wordmark.jpg',
                'guide_text' => 'Pilih tipe perangkat (Android / iPhone / iPad) dan masukkan model HP/Tablet Anda. Detail akun/link login akan langsung muncul di invoice setelah pembayaran.',
                'is_active' => true,
                'requires_zone_id' => true,
                'target_field_1' => 'Nama / Model Device',
                'target_field_2' => 'Tipe Login',
                'target_field_1_help' => 'Khusus perangkat HP/Tablet (Android, iPhone, iPad). Masukkan seri perangkat contoh: iPhone 13 atau Samsung A54.',
            ]
        );

        // Paket Produk Netflix Awal dari VIP Reseller
        $products = [
            [
                'product_code' => 'NFLX-S14D',
                'name' => 'Profile Sharing 30 Hari [1 Profile] [VIA LINK LOGIN] [GARANSI 14 HARI]',
                'price_modal' => 12750,
                'price_sell' => 15500,
                'price_normal' => 18000,
                'is_promo' => true,
                'status' => 'available',
                'provider' => 'vip_reseller',
            ],
            [
                'product_code' => 'NFLX-S28D',
                'name' => 'Profile Sharing 30 Hari [1 Profile] [VIA LINK LOGIN] [GARANSI 28 HARI]',
                'price_modal' => 18750,
                'price_sell' => 22000,
                'price_normal' => 25000,
                'is_promo' => true,
                'status' => 'available',
                'provider' => 'vip_reseller',
            ],
        ];

        foreach ($products as $p) {
            GameProduct::updateOrCreate(
                ['product_code' => $p['product_code']],
                array_merge($p, ['game_id' => $game->id])
            );
        }
    }
}
