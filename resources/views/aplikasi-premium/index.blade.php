<x-app-layout>
    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-xl border border-gray-300 dark:border-gray-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <img src="{{ asset('images/aplikasi-premium-logo.png') }}" alt="Aplikasi Premium" class="w-16 h-16 object-contain">
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white" style="font-family: 'Righteous', cursive; letter-spacing: 2px;">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600 dark:from-amber-400 dark:to-orange-500">APLIKASI</span> PREMIUM
                    </h1>
                </div>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Langganan aplikasi premium terpercaya — Spotify, Netflix, YouTube, dan lainnya. Proses instan 24 jam via WhatsApp.</p>
            </div>

            @if($apps->isEmpty())
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">📱</div>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Belum Ada Layanan Tersedia</h3>
                    <p class="text-gray-500 dark:text-gray-400">Aplikasi premium sedang disiapkan. Segera hadir!</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($apps as $app)
                    @php
                        $promoProduct = $app->products()->where('is_promo', true)->where('status', 'available')->where('price_normal', '>', 0)->orderByRaw('((price_normal - price_sell) / price_normal) DESC')->first();
                        $maxDiscount = 0;
                        if ($promoProduct) {
                            $maxDiscount = floor((($promoProduct->price_normal - $promoProduct->price_sell) / $promoProduct->price_normal) * 100);
                        }
                    @endphp
                    <a href="{{ route('topup.show', $app->slug) }}" class="relative block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        @if($maxDiscount > 0)
                            <div class="absolute top-5 -right-10 w-40 transform rotate-45 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[10px] font-extrabold py-1 text-center shadow-md z-20 animate-pulse tracking-wider">
                                DISKON {{ $maxDiscount }}%
                            </div>
                        @endif
                        <div class="w-full h-48 relative overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            @if(!empty($app->thumbnail))
                                <img src="{{ $app->thumbnail }}" alt="{{ $app->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" onerror="this.classList.add('hidden')">
                            @endif
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-amber-600 to-orange-900 flex flex-col items-center justify-center text-white p-4 shadow-inner {{ !empty($app->thumbnail) ? 'hidden' : '' }}">
                                <span class="text-3xl mb-1">👑</span>
                                <span class="font-black text-sm tracking-wider uppercase text-center">{{ $app->name }}</span>
                            </div>
                        </div>
                        <div class="p-4 text-center bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white truncate" style="font-family: 'Orbitron', sans-serif; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">{{ $app->name }}</h3>
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-bold mt-1 tracking-wide">LANGGANAN SEKARANG</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
