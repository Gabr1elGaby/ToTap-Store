<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PromoHelper
{
    public static $dayNames = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    public static $availableCategories = [
        'all'      => '✨ Semua Kategori Produk',
        'games'    => '🎮 Top Up Game (MLBB, FF, Valorant, Roblox, dll.)',
        'apps'     => '🎬 Aplikasi Premium (Bstation, Netflix, Spotify, Canva, dll.)',
        'software' => '💻 Software & Source Code',
        'voucher'  => '🎟️ Voucher Game & Digital',
    ];

    /**
     * Get all promo settings with clean defaults
     */
    public static function getSettings(): array
    {
        $dayPromoDaysRaw = Setting::get('promo_day_days', json_encode([5])); // default Jumat
        $dayPromoDays = is_string($dayPromoDaysRaw) ? json_decode($dayPromoDaysRaw, true) : $dayPromoDaysRaw;
        if (!is_array($dayPromoDays)) {
            $dayPromoDays = [5];
        }

        $firstUserCatsRaw = Setting::get('promo_first_user_categories', json_encode(['all']));
        $firstUserCats = is_string($firstUserCatsRaw) ? json_decode($firstUserCatsRaw, true) : $firstUserCatsRaw;
        if (!is_array($firstUserCats) || empty($firstUserCats)) {
            $firstUserCats = ['all'];
        }

        $dayPromoCatsRaw = Setting::get('promo_day_categories', json_encode(['all']));
        $dayPromoCats = is_string($dayPromoCatsRaw) ? json_decode($dayPromoCatsRaw, true) : $dayPromoCatsRaw;
        if (!is_array($dayPromoCats) || empty($dayPromoCats)) {
            $dayPromoCats = ['all'];
        }

        return [
            // 1. Pengguna Pertama (First Time User)
            'first_user_active'       => (bool) Setting::get('promo_first_user_active', false),
            'first_user_title'        => Setting::get('promo_first_user_title', 'Diskon Spesial Pengguna Baru'),
            'first_user_type'         => Setting::get('promo_first_user_type', 'percent'), // 'percent' or 'fixed'
            'first_user_value'        => (float) Setting::get('promo_first_user_value', 10), // e.g. 10% or Rp5.000
            'first_user_max_discount' => (float) Setting::get('promo_first_user_max_discount', 10000), // Max cap Rp
            'first_user_min_spend'    => (float) Setting::get('promo_first_user_min_spend', 10000), // Min belanja Rp
            'first_user_min_profit'   => (float) Setting::get('promo_first_user_min_profit', 2), // Target profit minimal toko %
            'first_user_categories'   => array_values($firstUserCats), // e.g. ['all'] or ['games', 'apps']

            // 2. Diskon Hari Tertentu (Recurring Day Promo)
            'day_promo_active'        => (bool) Setting::get('promo_day_active', false),
            'promo_day_title'         => Setting::get('promo_day_title', 'Promo Hari Spesial'),
            'day_promo_days'          => array_map('intval', $dayPromoDays), // e.g. [5] for Friday, [0,6] for Weekend
            'day_promo_type'          => Setting::get('promo_day_type', 'percent'), // 'percent' or 'fixed'
            'day_promo_value'         => (float) Setting::get('promo_day_value', 5), // e.g. 5% or Rp2.000
            'day_promo_max_discount'  => (float) Setting::get('promo_day_max_discount', 5000), // Max cap Rp
            'day_promo_min_spend'     => (float) Setting::get('promo_day_min_spend', 15000), // Min belanja Rp
            'day_promo_min_profit'    => (float) Setting::get('promo_day_min_profit', 2), // Target profit minimal toko %
            'day_promo_categories'    => array_values($dayPromoCats), // e.g. ['all'] or ['games', 'apps']
        ];
    }

    /**
     * Resolve category code ('games', 'apps', 'software', 'voucher') from any input model or string
     */
    public static function resolveCategoryCode($context): string
    {
        if (!$context) {
            return 'games';
        }

        if (is_string($context)) {
            $c = strtolower(trim($context));
            if (str_contains($c, 'soft') || str_contains($c, 'source') || str_contains($c, 'template') || str_contains($c, 'cv') || str_contains($c, 'pos')) {
                return 'software';
            }
            if (str_contains($c, 'app') || str_contains($c, 'aplikasi') || str_contains($c, 'stream') || str_contains($c, 'premium') || str_contains($c, 'bstation') || str_contains($c, 'netflix') || str_contains($c, 'spotify') || str_contains($c, 'canva') || str_contains($c, 'youtube')) {
                return 'apps';
            }
            if (str_contains($c, 'voucher') || str_contains($c, 'google play') || str_contains($c, 'steam')) {
                return 'voucher';
            }
            return 'games';
        }

        if ($context instanceof \App\Models\Game) {
            $cat = strtolower($context->category ?? '');
            $name = strtolower($context->name ?? '');
            $slug = strtolower($context->slug ?? '');

            if (
                str_contains($cat, 'app') || str_contains($cat, 'aplikasi') || str_contains($cat, 'stream') ||
                str_contains($name, 'premium') || str_contains($name, 'app') || str_contains($slug, 'bstation') ||
                str_contains($slug, 'netflix') || str_contains($slug, 'spotify') || str_contains($slug, 'canva') ||
                str_contains($slug, 'youtube') || str_contains($slug, 'vidio') || str_contains($slug, 'iqiyi') ||
                str_contains($slug, 'vision') || str_contains($slug, 'viu') || str_contains($slug, 'wetv') ||
                str_contains($slug, 'capcut') || str_contains($slug, 'alight')
            ) {
                return 'apps';
            }

            if (str_contains($cat, 'voucher') || str_contains($name, 'voucher') || str_contains($slug, 'voucher') || str_contains($slug, 'gplay') || str_contains($slug, 'steam')) {
                return 'voucher';
            }

            return 'games';
        }

        if ($context instanceof \App\Models\Product || $context instanceof \App\Models\Plan) {
            return 'software';
        }

        return 'games';
    }

    /**
     * Check if a product category is eligible under promo allowed categories
     */
    public static function isCategoryEligible($context, array $allowedCategories): bool
    {
        if (empty($allowedCategories) || in_array('all', $allowedCategories, true)) {
            return true;
        }

        $code = self::resolveCategoryCode($context);
        return in_array($code, $allowedCategories, true);
    }

    /**
     * Check if a given user is making their first-ever purchase
     */
    public static function isFirstTimeUser($user): bool
    {
        if ($user === 'simulate_first_time' || $user === true) {
            return true;
        }

        if (!$user) {
            // Pengunjung guest / belum login diperlakukan sebagai calon pengguna baru (preview promo)
            return true;
        }

        $userId = is_object($user) ? $user->id : $user;

        // Check paid/success top-up transactions
        $topupCount = Transaction::where('user_id', $userId)
            ->whereIn('status', ['success', 'paid', 'processing'])
            ->count();

        if ($topupCount > 0) {
            return false;
        }

        // Check paid software orders
        $orderCount = Order::where('user_id', $userId)
            ->whereIn('payment_status', ['PAID', 'SUCCESS', 'paid', 'success'])
            ->count();

        return $orderCount === 0;
    }

    /**
     * Check if today matches the active Day Promo days in WIB (Asia/Jakarta)
     */
    public static function isDayPromoActiveToday(): array
    {
        $settings = self::getSettings();
        if (!$settings['day_promo_active']) {
            return ['active' => false, 'day_name' => '', 'day_num' => -1];
        }

        $today = Carbon::now('Asia/Jakarta');
        $dayOfWeek = $today->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        $dayName = self::$dayNames[$dayOfWeek] ?? '';

        $isMatchingDay = in_array($dayOfWeek, $settings['day_promo_days'], true);

        return [
            'active'   => $isMatchingDay,
            'day_name' => $dayName,
            'day_num'  => $dayOfWeek,
        ];
    }

    /**
     * Calculate discount for a user & transaction amount with category check and minimum profit protection
     */
    public static function calculateDiscount($user, float $originalAmount, $context = null, float $priceModal = 0): array
    {
        $settings = self::getSettings();
        $originalAmount = max(0, $originalAmount);

        // Auto-detect modal price if context is a GameProduct
        if ($priceModal <= 0 && $context instanceof \App\Models\GameProduct) {
            $priceModal = (float) $context->price_modal;
        }

        $eligiblePromos = [];

        // 1. Check First-Time User Discount
        if ($settings['first_user_active'] && self::isFirstTimeUser($user)) {
            // Check Category Eligibility
            if (self::isCategoryEligible($context, $settings['first_user_categories'])) {
                if ($originalAmount >= $settings['first_user_min_spend']) {
                    $discount = 0;
                    if ($settings['first_user_type'] === 'percent') {
                        $discount = ($originalAmount * $settings['first_user_value']) / 100;
                        if ($settings['first_user_max_discount'] > 0 && $discount > $settings['first_user_max_discount']) {
                            $discount = $settings['first_user_max_discount'];
                        }
                    } else {
                        $discount = min($settings['first_user_value'], $originalAmount);
                    }

                    // Proteksi Keuntungan Minimal Toko (Anti-Rugi)
                    if ($priceModal > 0) {
                        $minProfitPercent = max(0, $settings['first_user_min_profit']);
                        $floorPrice = ceil($priceModal * (1 + ($minProfitPercent / 100)));
                        $maxAllowedDiscount = max(0, (int)$originalAmount - (int)$floorPrice);
                        $discount = min($discount, $maxAllowedDiscount);
                    }

                    $discount = ceil($discount);
                    if ($discount > 0) {
                        $eligiblePromos[] = [
                            'type'            => 'first_user',
                            'title'           => $settings['first_user_title'] . ($settings['first_user_type'] === 'percent' ? " ({$settings['first_user_value']}%)" : ""),
                            'discount_amount' => $discount,
                            'badge'           => 'Diskon Pengguna Baru',
                        ];
                    }
                }
            }
        }

        // 2. Check Recurring Day Promo
        $dayCheck = self::isDayPromoActiveToday();
        if ($dayCheck['active']) {
            // Check Category Eligibility
            if (self::isCategoryEligible($context, $settings['day_promo_categories'])) {
                if ($originalAmount >= $settings['day_promo_min_spend']) {
                    $discount = 0;
                    if ($settings['day_promo_type'] === 'percent') {
                        $discount = ($originalAmount * $settings['day_promo_value']) / 100;
                        if ($settings['day_promo_max_discount'] > 0 && $discount > $settings['day_promo_max_discount']) {
                            $discount = $settings['day_promo_max_discount'];
                        }
                    } else {
                        $discount = min($settings['day_promo_value'], $originalAmount);
                    }

                    // Proteksi Keuntungan Minimal Toko (Anti-Rugi)
                    if ($priceModal > 0) {
                        $minProfitPercent = max(0, $settings['day_promo_min_profit']);
                        $floorPrice = ceil($priceModal * (1 + ($minProfitPercent / 100)));
                        $maxAllowedDiscount = max(0, (int)$originalAmount - (int)$floorPrice);
                        $discount = min($discount, $maxAllowedDiscount);
                    }

                    $discount = ceil($discount);
                    if ($discount > 0) {
                        $promoName = $settings['promo_day_title'];
                        $eligiblePromos[] = [
                            'type'            => 'day_promo',
                            'title'           => $promoName . ($settings['day_promo_type'] === 'percent' ? " ({$settings['day_promo_value']}%)" : ""),
                            'discount_amount' => $discount,
                            'badge'           => "Promo Hari {$dayCheck['day_name']}",
                        ];
                    }
                }
            }
        }

        // 3. Choose the Best Promo (Highest Discount for Customer)
        if (empty($eligiblePromos)) {
            return [
                'has_discount'    => false,
                'original_amount' => (int) $originalAmount,
                'discount_amount' => 0,
                'final_amount'    => (int) $originalAmount,
                'promo_type'      => 'none',
                'promo_title'     => null,
                'promo_badge'     => null,
                'savings_text'    => null,
            ];
        }

        // Sort descending by discount_amount
        usort($eligiblePromos, function ($a, $b) {
            return $b['discount_amount'] <=> $a['discount_amount'];
        });

        $best = $eligiblePromos[0];
        $discountAmount = min($best['discount_amount'], (int) $originalAmount);
        $finalAmount = max(0, (int) $originalAmount - $discountAmount);

        return [
            'has_discount'    => true,
            'original_amount' => (int) $originalAmount,
            'discount_amount' => (int) $discountAmount,
            'final_amount'    => (int) $finalAmount,
            'promo_type'      => $best['type'],
            'promo_title'     => $best['title'],
            'promo_badge'     => $best['badge'],
            'savings_text'    => 'Hemat Rp' . number_format($discountAmount, 0, ',', '.'),
        ];
    }

    /**
     * Ensure database columns exist for discount breakdown
     */
    public static function ensureSchema(): void
    {
        try {
            if (Schema::hasTable('transactions')) {
                if (!Schema::hasColumn('transactions', 'discount_amount')) {
                    Schema::table('transactions', function (Blueprint $table) {
                        $table->integer('discount_amount')->default(0)->after('amount');
                    });
                }
                if (!Schema::hasColumn('transactions', 'original_amount')) {
                    Schema::table('transactions', function (Blueprint $table) {
                        $table->integer('original_amount')->nullable()->after('discount_amount');
                    });
                }
                if (!Schema::hasColumn('transactions', 'promo_title')) {
                    Schema::table('transactions', function (Blueprint $table) {
                        $table->string('promo_title')->nullable()->after('original_amount');
                    });
                }
            }
        } catch (\Throwable $e) {
            // Ignore if columns already exist
        }
    }
}
