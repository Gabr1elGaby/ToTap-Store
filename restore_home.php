<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$pattern = '/<!-- Top Up Games Section \(Wiboost Style\).*?<\/section>/s';

$originalSection = <<<BLADE
        <!-- Top Up Games Section -->
        <section id="topup" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Top Up Game Termurah</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Top up otomatis 24 jam untuk game favorit Anda.</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @php
                        \$activeGames = \App\Models\Game::where('is_active', true)->get();
                    @endphp
                    @foreach(\$activeGames as \$game)
                    @php
                        \$promoProduct = \$game->products()->where('is_promo', true)->where('status', 'available')->where('price_normal', '>', 0)->orderByRaw('((price_normal - price_sell) / price_normal) DESC')->first();
                        \$maxDiscount = 0;
                        if (\$promoProduct) {
                            \$maxDiscount = floor(((\$promoProduct->price_normal - \$promoProduct->price_sell) / \$promoProduct->price_normal) * 100);
                        }
                    @endphp
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    @else
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    @endauth
                        @if(\$maxDiscount > 0)
                            <!-- BADGE PROMO RIBBON -->
                            <div class="absolute top-5 -right-10 w-40 transform rotate-45 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[10px] font-extrabold py-1 text-center shadow-md z-20 animate-pulse tracking-wider">
                                DISKON {\$maxDiscount}%
                            </div>
                        @endif
                        <div class="w-full h-48 relative overflow-hidden bg-gray-200">
                            @if(\$game->thumbnail)
                            <img src="{{ \$game->thumbnail }}" alt="{{ \$game->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl">{{ substr(\$game->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-bold text-gray-900 truncate">{{ \$game->name }}</h3>
                            <p class="text-xs text-indigo-600 font-semibold mt-1">Beli Sekarang</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
BLADE;

$content = preg_replace($pattern, $originalSection, $content);
file_put_contents($file, $content);
echo "Restored original Top Up Game Termurah section.\n";
