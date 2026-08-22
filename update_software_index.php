<?php
$file = 'resources/views/software/index.blade.php';
$content = file_get_contents($file);

// 1. Change Ribbon text to "Diskon s/d X%"
$content = str_replace(
    'Diskon {{ $discountPercent }}%',
    'Diskon s/d {{ $discountPercent }}%',
    $content
);

// 2. Hide price for 'sistem-kasir-pos', but keep it for others? 
// Or better yet, they said "jadi yang ini button beli layanan aja tanpa ada harga" - I will hide it specifically for Kasir, or make it dynamic.
// Actually, let's just make the button take up full width for Kasir, and hide the price block.

$oldPricingBlock = <<<BLADE
                    <div class="mt-auto border-t border-gray-700 pt-6 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">
                                {{ \$product->slug === 'sistem-kasir-pos' ? 'LISENSI BULANAN' : 'HARGA MULAI' }}
                            </p>
                            @if(\$bestPlan)
                                @if(\$bestPlan->price_normal > 0 && \$bestPlan->price_normal > \$bestPlan->price)
                                    <div class="text-sm text-gray-500 line-through mb-1">Rp {{ number_format(\$bestPlan->price_normal, 0, ',', '.') }}</div>
                                @endif
                                <p class="text-3xl font-bold text-white">Rp {{ number_format(\$bestPlan->price, 0, ',', '.') }}</p>
                            @else
                                <p class="text-xl font-bold text-white">Belum tersedia</p>
                            @endif
                        </div>
                        <a href="{{ \$product->slug === 'sistem-kasir-pos' ? '/produk/sistem-kasir-pos' : '/cv' }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 10px 24px; letter-spacing: 0.5px;">Beli Layanan</a>
                    </div>
BLADE;

$newPricingBlock = <<<BLADE
                    <div class="mt-auto border-t border-gray-700 pt-6">
                        @if(\$product->slug === 'sistem-kasir-pos')
                            <a href="/produk/sistem-kasir-pos" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 12px 24px; letter-spacing: 0.5px;">Beli Layanan</a>
                        @else
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">HARGA MULAI</p>
                                    @if(\$bestPlan)
                                        @if(\$bestPlan->price_normal > 0 && \$bestPlan->price_normal > \$bestPlan->price)
                                            <div class="text-sm text-gray-500 line-through mb-1">Rp {{ number_format(\$bestPlan->price_normal, 0, ',', '.') }}</div>
                                        @endif
                                        <p class="text-3xl font-bold text-white">Rp {{ number_format(\$bestPlan->price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-xl font-bold text-white">Belum tersedia</p>
                                    @endif
                                </div>
                                <a href="/cv" class="bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 10px 24px; letter-spacing: 0.5px;">Beli Layanan</a>
                            </div>
                        @endif
                    </div>
BLADE;

$content = str_replace($oldPricingBlock, $newPricingBlock, $content);
file_put_contents($file, $content);
echo "Updated software index blade.\n";
