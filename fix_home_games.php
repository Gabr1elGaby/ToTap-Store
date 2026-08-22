<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// 1. Hapus section kategori lama yang rusak
$pattern = '/<!-- Kategori Section.*?<\/section>/s';

$newSection = <<<BLADE
        <!-- Top Up Games Section (Wiboost Style) -->
        <section id="topup" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-12 text-center" data-aos="fade-up">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 text-blue-600 font-bold text-xs mb-4 shadow-sm">Katalog Utama</span>
                    <h2 class="text-3xl font-extrabold text-gray-900">Pilih Game</h2>
                </div>

                <div class="flex flex-wrap justify-center gap-6 max-w-5xl mx-auto">
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
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="relative w-40 h-48 bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden border border-gray-100" data-aos="fade-up">
                    @else
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative w-40 h-48 bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden border border-gray-100" data-aos="fade-up">
                    @endauth
                    
                        @if(\$maxDiscount > 0)
                            <!-- Ribbon -->
                            <div class="absolute top-4 -right-8 w-32 transform rotate-45 bg-blue-600 text-white text-[10px] font-bold py-1 text-center shadow-sm z-10">
                                DISKON {\$maxDiscount}%
                            </div>
                        @endif
                        
                        <!-- Icon Circle -->
                        <div class="w-20 h-20 bg-white rounded-full shadow flex items-center justify-center mb-4 group-hover:-translate-y-1 transition-transform border border-gray-100 p-2 overflow-hidden">
                            @if(\$game->thumbnail)
                                <img src="{{ \$game->thumbnail }}" alt="{{ \$game->name }}" class="w-full h-full object-cover rounded-full opacity-90">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl rounded-full">{{ substr(\$game->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <h3 class="text-gray-800 font-bold text-sm text-center px-2">{{ \$game->name }}</h3>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
BLADE;

$content = preg_replace($pattern, $newSection, $content);
file_put_contents($file, $content);
echo "New Game Categories applied.\n";
