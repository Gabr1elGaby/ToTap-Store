<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldPromoHtml = <<<HTML
                    @php
                        \$hasPromo = \$game->products()->where('is_promo', true)->where('status', 'available')->exists();
                    @endphp
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                        @if(\$hasPromo)
                            <!-- BADGE PROMO -->
                            <div class="absolute z-10 top-2 right-2 bg-red-600 text-white text-[10px] md:text-xs font-extrabold px-3 py-1 rounded-full animate-pulse shadow-lg shadow-red-500/50">PROMO</div>
                        @endif
HTML;

$newPromoHtml = <<<HTML
                    @php
                        \$promoProduct = \$game->products()->where('is_promo', true)->where('status', 'available')->where('price_normal', '>', 0)->orderByRaw('((price_normal - price_sell) / price_normal) DESC')->first();
                        \$maxDiscount = 0;
                        if (\$promoProduct) {
                            \$maxDiscount = floor(((\$promoProduct->price_normal - \$promoProduct->price_sell) / \$promoProduct->price_normal) * 100);
                        }
                    @endphp
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                        @if(\$maxDiscount > 0)
                            <!-- BADGE PROMO RIBBON -->
                            <div class="absolute top-0 right-0 w-24 h-24 overflow-hidden z-20 pointer-events-none">
                                <div class="absolute top-5 -right-7 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[11px] font-extrabold px-8 py-1 text-center transform rotate-45 shadow-lg shadow-red-500/50 uppercase tracking-wider animate-pulse">
                                    SAVE {{ \$maxDiscount }}%
                                </div>
                            </div>
                        @endif
HTML;

$content = str_replace($oldPromoHtml, $newPromoHtml, $content);
file_put_contents($file, $content);
echo "Diagonal ribbon promo added.\n";
