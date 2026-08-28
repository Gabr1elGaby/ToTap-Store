<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Software Enterprise - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    
    <!-- Early Theme Initialization -->
    <script>
        if (localStorage.getItem('totap_theme') === 'light') {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.backgroundColor = '#f8fafc';
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.style.backgroundColor = '#111827';
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white font-sans antialiased min-h-screen transition-colors duration-200" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" @open-register.window="showRegister = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
    
    @include('layouts.navigation')

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-xl border border-gray-300 dark:border-gray-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="mb-14 text-center">
                <h2 class="text-blue-600 dark:text-blue-500 font-bold uppercase tracking-widest text-xs mb-2">SOFTWARE ENTERPRISE</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Solusi Berbasis Lisensi</h3>
            </div>

            <div class="flex flex-wrap justify-center gap-8 max-w-4xl mx-auto">
                @foreach($softwareProducts as $product)
                <!-- Card -->
                <div class="w-full md:w-[calc(50%-1rem)] max-w-md bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-md flex flex-col p-8 relative overflow-hidden transition-all duration-200 hover:shadow-xl">
                    @php
                        $discountPercent = 0;
                        $minPrice = null;
                        $minPriceNormal = null;

                        $promoSettings = \App\Helpers\PromoHelper::getSettings();
                        $activePromoPct = 0;
                        if (!empty($promoSettings['first_user_active']) && $promoSettings['first_user_type'] === 'percent') {
                            $activePromoPct = max($activePromoPct, (int)$promoSettings['first_user_value']);
                        }
                        if (!empty($promoSettings['day_promo_active']) && $promoSettings['day_promo_type'] === 'percent') {
                            $activePromoPct = max($activePromoPct, (int)$promoSettings['day_promo_value']);
                        }

                        if (str_contains(strtolower($product->name), 'cv') || str_contains(strtolower($product->slug ?? ''), 'cv')) {
                            $minTpl = \Illuminate\Support\Facades\DB::table('cv_templates')->where('status', 'active')->orderBy('price')->first();
                            if ($minTpl) {
                                $minPrice = $minTpl->price;
                                $minPriceNormal = $minTpl->price_normal;
                            }
                            $discountPercent = $activePromoPct;
                        } else {
                            $bestPlan = $product->plans->first();
                            if ($bestPlan) {
                                $minPrice = $bestPlan->price;
                                $minPriceNormal = $bestPlan->price_normal;
                                if ($bestPlan->price_normal > 0 && $bestPlan->price_normal > $bestPlan->price) {
                                    $discountPercent = round((($bestPlan->price_normal - $bestPlan->price) / $bestPlan->price_normal) * 100);
                                } else {
                                    $discountPercent = $activePromoPct;
                                }
                            } else {
                                $discountPercent = $activePromoPct;
                            }
                        }
                    @endphp
                    
                    @if($discountPercent > 0)
                    <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                        Diskon s/d {{ $discountPercent }}%
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">{{ $product->description }}</p>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        @if($product->features)
                            @foreach(explode("\n", str_replace("\r", "", $product->features)) as $feature)
                                @if(trim($feature))
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ trim($feature) }}</span>
                                </li>
                                @endif
                            @endforeach
                        @else
                            <li class="flex items-start"><span class="text-sm text-gray-400 italic">Fitur segera hadir...</span></li>
                        @endif
                    </ul>

                    <div class="mt-auto border-t border-gray-100 dark:border-gray-700/80 pt-6">
                        @if($product->slug === 'sistem-kasir-pos')
                            <a href="/produk/sistem-kasir-pos" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition text-sm shadow-md py-3" style="letter-spacing: 0.5px;">Beli Layanan</a>
                        @else
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">HARGA MULAI</p>
                                    @if(!is_null($minPrice))
                                        @if($minPriceNormal > 0 && $minPriceNormal > $minPrice)
                                            <div class="text-xs text-gray-400 line-through mb-0.5">Rp {{ number_format($minPriceNormal, 0, ',', '.') }}</div>
                                        @endif
                                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">Belum tersedia</p>
                                    @endif
                                </div>
                                <a href="/cv" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition text-sm shadow-md px-6 py-2.5" style="letter-spacing: 0.5px;">Beli Layanan</a>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-auth-modals />
</body>
</html>