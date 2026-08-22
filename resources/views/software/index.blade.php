<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Software Enterprise - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
    
    @include('layouts.navigation')

    <div class="py-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-blue-500 font-bold uppercase tracking-widest text-xs mb-2">SOFTWARE ENTERPRISE</h2>
                <h3 class="text-3xl font-extrabold text-white">Solusi Berbasis Lisensi</h3>
            </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($softwareProducts as $product)
                <!-- Card -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 shadow flex flex-col p-8 relative overflow-hidden">
                    @php
                        $bestPlan = $product->plans->first();
                        $discountPercent = 0;
                        if($bestPlan && $bestPlan->price_normal > 0 && $bestPlan->price_normal > $bestPlan->price) {
                            $discountPercent = round((($bestPlan->price_normal - $bestPlan->price) / $bestPlan->price_normal) * 100);
                        }
                    @endphp
                    
                    @if($discountPercent > 0)
                    <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                        Diskon s/d {{ $discountPercent }}%
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-white mb-2">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-400 mb-6">{{ $product->description }}</p>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        @if($product->features)
                            @foreach(explode("\n", str_replace("\r", "", $product->features)) as $feature)
                                @if(trim($feature))
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm text-gray-300">{{ trim($feature) }}</span>
                                </li>
                                @endif
                            @endforeach
                        @else
                            <li class="flex items-start"><span class="text-sm text-gray-500 italic">Fitur segera hadir...</span></li>
                        @endif
                    </ul>

                    <div class="mt-auto border-t border-gray-700 pt-6">
                        @if($product->slug === 'sistem-kasir-pos')
                            <a href="/produk/sistem-kasir-pos" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 12px 24px; letter-spacing: 0.5px;">Beli Layanan</a>
                        @else
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">HARGA MULAI</p>
                                    @if($bestPlan)
                                        @if($bestPlan->price_normal > 0 && $bestPlan->price_normal > $bestPlan->price)
                                            <div class="text-sm text-gray-500 line-through mb-1">Rp {{ number_format($bestPlan->price_normal, 0, ',', '.') }}</div>
                                        @endif
                                        <p class="text-3xl font-bold text-white">Rp {{ number_format($bestPlan->price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-xl font-bold text-white">Belum tersedia</p>
                                    @endif
                                </div>
                                <a href="/cv" class="bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 10px 24px; letter-spacing: 0.5px;">Beli Layanan</a>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
        </div>
    </div>

    <x-auth-modals />
</body>
</html>