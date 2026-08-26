<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $product->name }} - ToTap Store</title>
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
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-white bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" @open-register.window="showRegister = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
        <!-- Navbar -->
        @include('layouts.navigation')

        <!-- Product Header -->
        <section class="py-16 bg-slate-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 relative transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="absolute top-0 left-4 sm:left-6 lg:left-8">
                    <a href="{{ url('/software') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-xl border border-gray-300 dark:border-gray-700 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>
                <div class="md:w-2/3 mx-auto text-center mt-8 md:mt-0">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">{{ $product->name }}</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">{{ $product->description }}</p>
                    @if($product->demo_url)
                        <a href="{{ $product->demo_url }}" target="_blank" class="inline-block bg-blue-50 dark:bg-blue-600/20 border border-blue-500 text-blue-600 dark:text-blue-400 px-8 py-3 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition shadow-sm">Lihat Demo</a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Pricing Plans -->
        <section class="py-16 bg-slate-50 dark:bg-gray-900 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white mb-12 tracking-tight">Pilih Paket Anda</h2>
                <div class="flex flex-wrap justify-center gap-8 max-w-4xl mx-auto">
                    @forelse ($product->plans as $plan)
                        @php
                              $discountPercent = 0;
                              if($plan->price_normal > 0 && $plan->price_normal > $plan->price) {
                                  $discountPercent = round((($plan->price_normal - $plan->price) / $plan->price_normal) * 100);
                              }
                          @endphp
                          <div class="w-full md:w-[calc(50%-1rem)] max-w-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-8 shadow-sm dark:shadow-md hover:shadow-xl transition-all duration-200 flex flex-col relative overflow-hidden">
                              @if($discountPercent > 0)
                              <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                                  Diskon {{ $discountPercent }}%
                              </div>
                              @endif
                              
                              <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4 text-center" style="font-family: 'Orbitron', sans-serif;">{{ strtoupper($plan->name) }}</h3>
                              <div class="text-center mb-6">
                                  @if($plan->price_normal > 0 && $plan->price_normal > $plan->price)
                                      <div class="text-sm text-gray-400 line-through mb-1">Rp {{ number_format($plan->price_normal, 0, ',', '.') }}</div>
                                  @endif
                                  <span class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                  <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">/ {{ $plan->duration_days == 0 ? 'Selamanya' : $plan->duration_days . ' hari' }}</span>
                              </div>
                            
                            <ul class="text-gray-700 dark:text-gray-300 mb-8 flex-grow space-y-3">
                                @if(!empty($plan->features))
                                    @foreach(preg_split('/\r\n|\r|\n/', $plan->features) as $featureLine)
                                        @if(trim($featureLine))
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                {{ trim($featureLine) }}
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    @if($plan->user_limit)
                                        <li class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Maksimal {{ $plan->user_limit }} User
                                        </li>
                                    @endif
                                    @if($plan->transaction_limit)
                                        <li class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $plan->transaction_limit }} Transaksi
                                        </li>
                                    @endif
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Akses penuh sistem
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Bantuan CS WhatsApp Cepat
                                    </li>
                                @endif
                            </ul>

                            @auth
                                <form action="{{ route('checkout', $plan->id) }}" method="GET">
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/30 transition">Berlangganan Sekarang</button>
                                </form>
                            @else
                                <button type="button" @click.prevent="showLogin = true" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/30 transition">Berlangganan Sekarang</button>
                            @endauth
                        </div>
                    @empty
                        <div class="col-span-2 text-center text-gray-500 dark:text-gray-400 py-12">Belum ada paket harga untuk produk ini.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400 py-8 border-t border-gray-200 dark:border-gray-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                &copy; {{ date('Y') }} ToTap Store. All rights reserved.
            </div>
        </footer>

        <x-auth-modals />
    </body>
</html>
