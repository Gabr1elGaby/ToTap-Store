<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerReview;

class CustomerReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (CustomerReview::count() === 0) {
            $reviews = [
                [
                    'order_id' => 'TOPUP-ML-98214',
                    'order_type' => 'topup',
                    'customer_name' => 'Dimas Pratama',
                    'customer_contact' => '081234567890',
                    'product_name' => 'Mobile Legends - 86 Diamonds',
                    'rating' => 5,
                    'review_text' => 'Proses cepat banget gak sampe 10 detik diamond langsung masuk ke akun ML. Mantap ToTap Store!',
                    'created_at' => now()->subDays(2),
                ],
                [
                    'order_id' => 'POS-SUB-51201',
                    'order_type' => 'software',
                    'customer_name' => 'Budi Santoso (Kedai Kopi)',
                    'customer_contact' => 'budi.santoso@gmail.com',
                    'product_name' => 'Sistem Kasir (POS) - Paket Enterprise Bulanan',
                    'rating' => 5,
                    'review_text' => 'Sistem kasirnya sangat membantu pencatatan stok dan laporan penjualan harian cafe saya. Fitur demo sangat membantu sebelum langganan.',
                    'created_at' => now()->subDays(3),
                ],
                [
                    'order_id' => 'TOPUP-FF-34012',
                    'order_type' => 'topup',
                    'customer_name' => 'Rian Hidayat',
                    'customer_contact' => '085712349876',
                    'product_name' => 'Free Fire - 140 Diamonds',
                    'rating' => 5,
                    'review_text' => 'Harga bersahabat, pembayaran pakai QRIS lancar tanpa kendala.',
                    'created_at' => now()->subDays(4),
                ],
                [
                    'order_id' => 'TOPUP-PUBG-71029',
                    'order_type' => 'topup',
                    'customer_name' => 'Kevin Tan',
                    'customer_contact' => '081987654321',
                    'product_name' => 'PUBG Mobile - 60 UC',
                    'rating' => 4,
                    'review_text' => 'Bagus dan terpercaya. Saran agar metode pembayaran virtual account ditambah lagi variasinya.',
                    'created_at' => now()->subDays(5),
                ],
                [
                    'order_id' => 'TOPUP-VALO-88123',
                    'order_type' => 'topup',
                    'customer_name' => 'Andi Wijaya',
                    'customer_contact' => 'andi.w@gmail.com',
                    'product_name' => 'Valorant - 1125 VP',
                    'rating' => 5,
                    'review_text' => 'Pelayanan top tier! CS ramah dan voucher langsung aktif.',
                    'created_at' => now()->subDays(6),
                ]
            ];

            foreach ($reviews as $rev) {
                CustomerReview::create($rev);
            }
        }
    }
}
