<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-white mb-4" style="font-family: 'Righteous', cursive; letter-spacing: 2px;">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">GAMING</span> CENTER
                </h1>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">Top up otomatis 24 jam untuk berbagai game favorit Anda. Pilih game di bawah ini untuk melihat paket yang tersedia.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @php
                    $activeGames = \App\Models\Game::where('is_active', true)->get();
                @endphp
                @foreach($activeGames as $game)
                @php
                    $promoProduct = $game->products()->where('is_promo', true)->where('status', 'available')->where('price_normal', '>', 0)->orderByRaw('((price_normal - price_sell) / price_normal) DESC')->first();
                    $maxDiscount = 0;
                    if ($promoProduct) {
                        $maxDiscount = floor((($promoProduct->price_normal - $promoProduct->price_sell) / $promoProduct->price_normal) * 100);
                    }
                @endphp
                <a href="{{ route('topup.show', $game->slug) }}" class="relative block bg-gray-800 border border-gray-700 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    @if($maxDiscount > 0)
                        <!-- BADGE PROMO RIBBON -->
                        <div class="absolute top-5 -right-10 w-40 transform rotate-45 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[10px] font-extrabold py-1 text-center shadow-md z-20 animate-pulse tracking-wider">
                            DISKON {{ $maxDiscount }}%
                        </div>
                    @endif
                    <div class="w-full h-48 relative overflow-hidden bg-gray-700">
                        @if($game->thumbnail)
                        <img src="{{ $game->thumbnail }}" alt="{{ $game->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-4xl shadow-inner">{{ substr($game->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="p-4 text-center bg-gray-800 border border-gray-700 border-t border-gray-700">
                        <h3 class="font-bold text-white truncate" style="font-family: 'Orbitron', sans-serif; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">{{ $game->name }}</h3>
                        <p class="text-xs text-blue-400 font-semibold mt-1 tracking-wide">BELI SEKARANG</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
