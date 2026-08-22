<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $product->name }} - ToTap Store</title>
        <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">        <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    </head>
    <body class="font-sans antialiased text-white bg-gray-900" x-data="{ showLogin: {{ $errors->has('email') && !old('name') ? 'true' : 'false' }}, showRegister: {{ $errors->any() && old('name') ? 'true' : 'false' }} }" :class="{ 'overflow-hidden': showLogin || showRegister }">
        <!-- Navbar -->
        @include('layouts.navigation')

        <!-- Product Header -->
        <section class="py-16 bg-gray-800 border-gray-700 border-b relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="absolute top-0 left-4 sm:left-6 lg:left-8">
                    <a href="{{ url('/software') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-900 hover:bg-gray-700 hover:text-white rounded-lg border border-gray-700 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>
                <div class="md:w-2/3 mx-auto text-center mt-8 md:mt-0">
                    <h1 class="text-4xl font-extrabold text-white mb-4">{{ $product->name }}</h1>
                    <p class="text-xl text-gray-400 mb-8">{{ $product->description }}</p>
                    @if($product->demo_url)
                        <a href="{{ $product->demo_url }}" target="_blank" class="bg-blue-600/20 border border-blue-500 text-blue-400 px-8 py-3 rounded hover:bg-blue-600 hover:text-white transition shadow-[0_0_15px_rgba(59,130,246,0.3)]">Lihat Demo</a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Pricing Plans -->
        <section class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-white mb-12">Pilih Paket Anda</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto justify-center">
                    @forelse ($product->plans as $plan)
                        @php
                              $discountPercent = 0;
                              if($plan->price_normal > 0 && $plan->price_normal > $plan->price) {
                                  $discountPercent = round((($plan->price_normal - $plan->price) / $plan->price_normal) * 100);
                              }
                          @endphp
                          <div class="bg-gray-800 border-gray-700 border rounded-lg p-8 shadow-sm hover:shadow-lg transition flex flex-col relative overflow-hidden">
                              @if($discountPercent > 0)
                              <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                                  Diskon {{ $discountPercent }}%
                              </div>
                              @endif
                              
                              <h3 class="text-2xl font-bold text-white mb-4 text-center">{{ strtoupper($plan->name) }}</h3>
                              <div class="text-center mb-6">
                                  @if($plan->price_normal > 0 && $plan->price_normal > $plan->price)
                                      <div class="text-sm text-gray-500 line-through mb-1">Rp {{ number_format($plan->price_normal, 0, ',', '.') }}</div>
                                  @endif
                                  <span class="text-4xl font-extrabold text-white">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                  <span class="text-gray-400">/ {{ $plan->duration_days == 0 ? 'Selamanya' : $plan->duration_days . ' hari' }}</span>
                              </div>
                            
                            <ul class="text-gray-400 mb-8 flex-grow space-y-3">
                                @if($plan->user_limit)
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Maksimal {{ $plan->user_limit }} User
                                    </li>
                                @endif
                                @if($plan->transaction_limit)
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $plan->transaction_limit }} Transaksi
                                    </li>
                                @endif
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Akses penuh sistem
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Support email
                                </li>
                            </ul>

                            @auth
                                <form action="{{ route('checkout', $plan->id) }}" method="GET">
                                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded hover:bg-indigo-700 transition">Berlangganan Sekarang</button>
                                </form>
                            @else
                                <button type="button" @click.prevent="showLogin = true" class="w-full bg-indigo-600 text-white font-bold py-3 rounded hover:bg-indigo-700 transition">Berlangganan Sekarang</button>
                            @endauth
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-gray-400">Belum ada paket harga untuk produk ini.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} ToTap Store. All rights reserved.
                </p>
            </div>
        </footer>

        <x-auth-modals />
    </body>
</html>
