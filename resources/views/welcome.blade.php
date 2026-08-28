<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ToTap Store - Enterprise Software Solutions & Top Up Game</title>

        <!-- Early Theme Initialization to prevent flicker -->
        <script>
            if (localStorage.getItem('totap_theme') === 'light') {
                document.documentElement.classList.remove('dark');
                document.documentElement.style.backgroundColor = '#f8fafc';
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#111827';
            }
        </script>

        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <style>
            html { scroll-behavior: smooth; }
            body { font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif; }
            .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(0,0,0,0.04) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(0,0,0,0.04) 1px, transparent 1px);
                background-size: 40px 40px;
            }
            .dark .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
            }
        </style>
        <link rel="icon" href="{{ asset('images/logo-totap-v2.png') . '?v=' . time() }}" type="image/png">
        <style>
            .nav-item-glow {
                border-bottom: 2px solid transparent;
                transition: all 0.3s ease;
            }
            .nav-item-glow:hover {
                color: #3B82F6 !important;
                border-bottom-color: #3B82F6 !important;
            }
            .category-card-glow {
                transition: all 0.3s ease;
            }
            .category-card-glow:hover {
                border-color: #3B82F6 !important;
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.4) !important;
            }
        </style>
    </head>
    <body class="antialiased text-gray-900 dark:text-white bg-slate-50 dark:bg-gray-900 transition-colors duration-200" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" @open-register.window="showRegister = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
        
        <!-- Navbar -->
        @include('layouts.navigation')

        <!-- Hero Section -->
        <section class="relative bg-slate-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 pt-20 pb-24 overflow-hidden transition-colors duration-200" id="beranda" 
            x-data="{ scrollY: 0 }" 
            @scroll.window="scrollY = window.scrollY">
            
            <!-- Parallax Background -->
            <div class="absolute inset-0 bg-grid-pattern opacity-30 dark:opacity-20" 
                 :style="`transform: translateY(${scrollY * 0.5}px);`"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" 
                 :style="`transform: translateY(${scrollY * 0.3}px); opacity: ${1 - (scrollY / 400)};`">
                <div class="text-center max-w-4xl mx-auto">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6 tracking-tight">
                        Pusat Layanan Digital <br>
                        <span class="text-blue-600 dark:text-blue-500">& Top Up Terlengkap.</span>
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-2xl mx-auto">
                        Platform terpercaya untuk kebutuhan top up game instant dan solusi software profesional. Transaksi otomatis, harga bersahabat, dan aman 100%.
                    </p>
                    <div class="flex justify-center items-center gap-4">
                        <a href="#kategori" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                            Pilih Kategori
                        </a>
                        @auth
                            <a href="/profile" class="px-8 py-3 bg-white dark:bg-transparent text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800 transition shadow-sm">
                                Profil Saya
                            </a>
                        @else
                            <button type="button" onclick="openRegisterModal()" class="px-8 py-3 bg-white dark:bg-transparent text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800 transition shadow-sm cursor-pointer">
                                Daftar Sekarang
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 py-12 relative z-20 shadow-sm dark:shadow-xl transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div class="p-4" data-aos="fade-up" data-aos-delay="100">
                        <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2" style="font-family: 'Orbitron', sans-serif;">{{ number_format($totalUsers) }}<span class="text-blue-500"></span></p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Pengguna</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="200">
                        <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2" style="font-family: 'Orbitron', sans-serif;">{{ number_format($totalTransactions) }}<span class="text-blue-500"></span></p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Transaksi Berhasil</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="300">
                        @if($totalReviews > 0)
                            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2 flex items-center justify-center gap-2" style="font-family: 'Orbitron', sans-serif;">
                                <span class="text-amber-400 text-3xl">⭐</span> {{ number_format($avgRating, 1) }}<span class="text-blue-500 text-2xl font-normal">/5.0</span>
                            </p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold tracking-wider uppercase">Rating Kepuasan ({{ $totalReviews }} Ulasan)</p>
                        @else
                            <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2 flex items-center justify-center gap-2" style="font-family: 'Orbitron', sans-serif;">
                                <span class="text-amber-400 text-3xl">⭐</span> -<span class="text-blue-500 text-2xl font-normal">/5.0</span>
                            </p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold tracking-wider uppercase">Belum Ada Ulasan</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @php
            $promoSettings = \App\Helpers\PromoHelper::getSettings();
            $dayPromoCheck = \App\Helpers\PromoHelper::isDayPromoActiveToday();
            $showFirstUserPromo = !empty($promoSettings['first_user_active']);
            $showDayPromo = !empty($promoSettings['day_promo_active']) && !empty($dayPromoCheck['active']);
        @endphp

        @if($showFirstUserPromo || $showDayPromo)
        <!-- Promo & Event Spesial Section -->
        <section id="promo-spesial" class="py-12 bg-slate-50 dark:bg-gray-900/90 border-b border-gray-200 dark:border-gray-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8 text-center" data-aos="fade-up">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-pink-100 dark:bg-pink-950 text-pink-700 dark:text-pink-300 font-extrabold text-xs uppercase tracking-widest rounded-full border border-pink-200 dark:border-pink-800/60 mb-2 shadow-sm">
                        <i class="fas fa-bullhorn text-pink-500"></i> Event Promo Aktif Hari Ini
                    </span>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Klaim Promo & Diskon Spesial</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Gunakan kesempatan promo untuk top up game dan langganan aplikasi dengan harga paling hemat!</p>
                </div>

                <div class="grid grid-cols-1 {{ ($showFirstUserPromo && $showDayPromo) ? 'lg:grid-cols-2' : 'max-w-3xl mx-auto' }} gap-6">

                    <!-- BANNER 1: Diskon Khusus Pengguna Pertama -->
                    @if($showFirstUserPromo)
                    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-900 text-white border-2 border-indigo-500/40 shadow-xl shadow-indigo-900/20 flex flex-col justify-between" data-aos="fade-up" data-aos-delay="100">
                        <!-- Decorative background glow -->
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-10 -top-10 w-48 h-48 bg-pink-500/15 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="px-3 py-1 bg-pink-500 text-white font-black text-[10px] sm:text-xs rounded-full uppercase tracking-wider shadow-md">
                                    🎁 Pengguna Baru
                                </span>
                                <span class="text-xs font-black text-indigo-300 flex items-center gap-1">
                                    <i class="fas fa-check-circle text-emerald-400"></i> Otomatis di Checkout
                                </span>
                            </div>

                            <div>
                                <h4 class="text-xl sm:text-2xl font-black text-white leading-snug">
                                    {{ $promoSettings['first_user_title'] ?: 'Diskon Spesial Pengguna Baru' }}
                                </h4>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-3xl sm:text-4xl font-black text-amber-300 font-mono">
                                        {{ $promoSettings['first_user_type'] === 'percent' ? $promoSettings['first_user_value'].'%' : 'Rp'.number_format($promoSettings['first_user_value'],0,',','.') }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-300">Potongan Harga</span>
                                </div>
                            </div>

                            <!-- Syarat & Ketentuan -->
                            <div class="p-4 rounded-2xl bg-slate-900/80 border border-indigo-500/30 text-xs space-y-2 text-slate-200">
                                <div class="font-bold text-indigo-300 flex items-center gap-1.5 border-b border-indigo-500/20 pb-1.5">
                                    <i class="fas fa-info-circle text-indigo-400"></i> Syarat & Ketentuan:
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">• Minimal Pembelian:</span>
                                    <span class="font-black text-white">
                                        {{ $promoSettings['first_user_min_spend'] > 0 ? 'Rp'.number_format($promoSettings['first_user_min_spend'],0,',','.') : 'Tanpa Minimal Belanja' }}
                                    </span>
                                </div>
                                @if($promoSettings['first_user_type'] === 'percent' && $promoSettings['first_user_max_discount'] > 0)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">• Maksimal Potongan:</span>
                                    <span class="font-black text-emerald-400">s.d. Rp{{ number_format($promoSettings['first_user_max_discount'],0,',','.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-slate-400">• Berlaku Untuk:</span>
                                    <span class="font-bold text-white">Transaksi Pertama Akun Baru</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10 pt-5 mt-4 border-t border-indigo-500/20">
                            <a href="#kategori" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-pink-600 to-indigo-600 hover:from-pink-500 hover:to-indigo-500 text-white font-black text-xs sm:text-sm text-center shadow-lg shadow-pink-600/30 transition flex items-center justify-center gap-2">
                                <span>Beli Sekarang & Dapatkan Diskon</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- BANNER 2: Diskon Hari Tertentu Otomatis -->
                    @if($showDayPromo)
                    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-emerald-950 via-teal-950 to-slate-900 text-white border-2 border-emerald-500/40 shadow-xl shadow-emerald-900/20 flex flex-col justify-between" data-aos="fade-up" data-aos-delay="200">
                        <!-- Decorative background glow -->
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-10 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="px-3 py-1 bg-emerald-500 text-white font-black text-[10px] sm:text-xs rounded-full uppercase tracking-wider shadow-md">
                                    🔥 Promo Spesial {{ $dayPromoCheck['day_name'] }}
                                </span>
                                <span class="text-xs font-black text-emerald-300 flex items-center gap-1 animate-pulse">
                                    <i class="fas fa-clock text-amber-400"></i> Aktif Hari Ini (s.d. 23:59 WIB)
                                </span>
                            </div>

                            <div>
                                <h4 class="text-xl sm:text-2xl font-black text-white leading-snug">
                                    {{ $promoSettings['promo_day_title'] ?: 'Promo Hari '.$dayPromoCheck['day_name'] }}
                                </h4>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-3xl sm:text-4xl font-black text-amber-300 font-mono">
                                        {{ $promoSettings['day_promo_type'] === 'percent' ? $promoSettings['day_promo_value'].'%' : 'Rp'.number_format($promoSettings['day_promo_value'],0,',','.') }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-300">Potongan Otomatis</span>
                                </div>
                            </div>

                            <!-- Syarat & Ketentuan -->
                            <div class="p-4 rounded-2xl bg-slate-900/80 border border-emerald-500/30 text-xs space-y-2 text-slate-200">
                                <div class="font-bold text-emerald-300 flex items-center gap-1.5 border-b border-emerald-500/20 pb-1.5">
                                    <i class="fas fa-info-circle text-emerald-400"></i> Syarat & Ketentuan:
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">• Minimal Pembelian:</span>
                                    <span class="font-black text-white">
                                        {{ $promoSettings['day_promo_min_spend'] > 0 ? 'Rp'.number_format($promoSettings['day_promo_min_spend'],0,',','.') : 'Tanpa Minimal Belanja' }}
                                    </span>
                                </div>
                                @if($promoSettings['day_promo_type'] === 'percent' && $promoSettings['day_promo_max_discount'] > 0)
                                <div class="flex justify-between">
                                    <span class="text-slate-400">• Maksimal Potongan:</span>
                                    <span class="font-black text-emerald-400">s.d. Rp{{ number_format($promoSettings['day_promo_max_discount'],0,',','.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-slate-400">• Periode Promo:</span>
                                    <span class="font-bold text-amber-300">Setiap Hari {{ $dayPromoCheck['day_name'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10 pt-5 mt-4 border-t border-emerald-500/20">
                            <a href="#kategori" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xs sm:text-sm text-center shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2">
                                <span>Pilih Produk & Klaim Promo</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </section>
        @endif

        <!-- Kenapa ToTap Store Section -->
        <section id="keunggulan" class="py-20 bg-slate-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16" data-aos="fade-right">
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">Kenapa ToTap Store?</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-blue-500 transition shadow-sm bg-white dark:bg-gray-800" data-aos="fade-up" data-aos-delay="0">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-gray-900 rounded-xl flex items-center justify-center text-blue-600 dark:text-white mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Proses Instan 24/7</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Semua pesanan mulai dari top up game hingga lisensi software diproses secara otomatis dalam hitungan detik tanpa perlu menunggu.</p>
                    </div>
                    
                    <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-blue-500 transition shadow-sm bg-white dark:bg-gray-800" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-gray-900 rounded-xl flex items-center justify-center text-blue-600 dark:text-white mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Aman & Terpercaya</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Sistem pembayaran kami menggunakan enkripsi tingkat tinggi untuk menjamin keamanan setiap transaksi dan privasi data Anda.</p>
                    </div>
                    
                    <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl hover:border-blue-500 transition shadow-sm bg-white dark:bg-gray-800" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-gray-900 rounded-xl flex items-center justify-center text-blue-600 dark:text-white mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Harga Termurah</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Dapatkan harga paling kompetitif untuk seluruh layanan digital kami. Hemat lebih banyak untuk semua kebutuhan harian Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tutorial Section -->
        <section id="tutorial" class="py-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center" data-aos="fade-up">
                    <h2 class="text-blue-600 dark:text-blue-500 font-bold uppercase tracking-widest text-xs mb-2">Cara Kerja</h2>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">Panduan Menggunakan ToTap Store</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                    <!-- Connector Line for Desktop -->
                    <div class="hidden md:block absolute top-12 left-1/8 right-1/8 h-0.5 bg-gray-200 dark:bg-gray-800" style="width: 75%; left: 12.5%;"></div>

                    <!-- Step 1 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="0">
                        <div class="w-24 h-24 mx-auto bg-slate-100 dark:bg-gray-800 border-4 border-white dark:border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-500/10 dark:shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3"><span class="text-blue-600 dark:text-blue-500 mr-2">1.</span>Buat Akun</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">Daftar dan buat akun ToTap Store Anda secara gratis untuk mempermudah transaksi.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-24 h-24 mx-auto bg-slate-100 dark:bg-gray-800 border-4 border-white dark:border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-500/10 dark:shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3"><span class="text-blue-600 dark:text-blue-500 mr-2">2.</span>Pilih Kategori</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">Pilih layanan yang Anda butuhkan: Top Up Game instant atau lisensi Software bisnis.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-24 h-24 mx-auto bg-slate-100 dark:bg-gray-800 border-4 border-white dark:border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-500/10 dark:shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3"><span class="text-blue-600 dark:text-blue-500 mr-2">3.</span>Pembayaran</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">Selesaikan pesanan Anda menggunakan metode pembayaran terenkripsi yang aman & otomatis.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-24 h-24 mx-auto bg-slate-100 dark:bg-gray-800 border-4 border-white dark:border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-500/10 dark:shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3"><span class="text-blue-600 dark:text-blue-500 mr-2">4.</span>Selesai</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">Layanan langsung aktif dan pesanan otomatis masuk ke akun Anda dalam hitungan detik!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Keamanan & Kebijakan Transaksi (Dedicated Section) -->
        <section id="keamanan" class="py-20 bg-slate-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-14 text-center" data-aos="fade-up">
                    <h2 class="text-blue-600 dark:text-blue-400 font-bold uppercase tracking-widest text-xs mb-2">KEAMANAN & KEBIJAKAN</h2>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Jaminan Transaksi Aman & Terpercaya</h3>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        Kenyamanan dan keamanan data Anda adalah prioritas utama kami. Harap perhatikan ketentuan resmi berikut sebelum bertransaksi.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1: Nomor Admin Resmi -->
                    <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 hover:border-emerald-500 transition-all shadow-sm hover:shadow-lg flex flex-col justify-between group" data-aos="fade-up" data-aos-delay="0">
                        <div>
                            <div class="w-12 h-12 bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Nomor Admin Resmi</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                Hanya nomor WhatsApp resmi: <a href="https://wa.me/6285198503253" target="_blank" class="font-mono font-bold text-emerald-600 dark:text-emerald-400 hover:underline">0851-9850-3253</a> yang digunakan untuk menghubungi Customer Service & Admin ToTap Store.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><i class="fas fa-check-circle"></i> CS Resmi 24 Jam</span>
                            <a href="https://wa.me/6285198503253" target="_blank" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold transition shadow flex items-center gap-1">
                                <i class="fab fa-whatsapp"></i> Chat Admin
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Pembayaran Resmi -->
                    <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 hover:border-blue-500 transition-all shadow-sm hover:shadow-lg flex flex-col justify-between group" data-aos="fade-up" data-aos-delay="100">
                        <div>
                            <div class="w-12 h-12 bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Pembayaran Resmi</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                Pembayaran hanya melalui metode yang tersedia resmi di website (QRIS & Saldo Akun). Jangan pernah transfer ke rekening luar.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 text-[11px] font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1.5">
                            <i class="fas fa-lock"></i> Otomatis & Terenkripsi
                        </div>
                    </div>

                    <!-- Card 3: Peringatan Penipuan -->
                    <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 hover:border-amber-500 transition-all shadow-sm hover:shadow-lg flex flex-col justify-between group" data-aos="fade-up" data-aos-delay="200">
                        <div>
                            <div class="w-12 h-12 bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Peringatan Penipuan</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                Admin ToTap Store <strong class="text-amber-600 dark:text-amber-400">tidak pernah</strong> meminta kode OTP, PIN, atau Password akun Anda untuk alasan apapun.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 text-[11px] font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt"></i> Jaga Kerahasiaan Data
                        </div>
                    </div>

                    <!-- Card 4: Kebijakan Refund -->
                    <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 hover:border-purple-500 transition-all shadow-sm hover:shadow-lg flex flex-col justify-between group" data-aos="fade-up" data-aos-delay="300">
                        <div>
                            <div class="w-12 h-12 bg-purple-500/10 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                                <i class="fas fa-undo-alt"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Kebijakan Refund</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                Ketentuan pengembalian dana sesuai jenis transaksi, dana refund akan dikembalikan utuh langsung ke <strong class="text-purple-600 dark:text-purple-400">Saldo Akun ToTap Store</strong> Anda.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 text-[11px] font-bold text-purple-600 dark:text-purple-400 flex items-center gap-1.5">
                            <i class="fas fa-wallet"></i> Saldo Langsung Kembali
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kategori Utama Section -->
        <section id="kategori" class="py-16 bg-slate-100 dark:bg-gray-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-10 text-center" data-aos="fade-up">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Pilih Kategori</h2>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Silakan pilih layanan yang Anda butuhkan di bawah ini.</p>
                </div>

                <!-- Wrapper Centered -->
                <div class="flex flex-wrap justify-center items-center gap-8">
                    
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-300 dark:border-gray-700 category-card-glow bg-white dark:bg-gray-900" 
                       style="width: 180px; height: 200px; text-decoration: none; border-radius: 24px;"
                       data-aos="zoom-in">
                        @if(isset($maxGameDiscount) && $maxGameDiscount > 0)
                        <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-top-right-radius: 24px; border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                            Diskon s/d {{ $maxGameDiscount }}%
                        </div>
                        @endif
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/kategori-game-4.png') }}" alt="Top Up Game" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.1));">
                        </div>
                        
                        <h3 class="text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">TOP UP GAME</h3>
                    </a>

                    <!-- KATEGORI: SOFTWARE ENTERPRISE -->
                    <a href="/software" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-300 dark:border-gray-700 category-card-glow bg-white dark:bg-gray-900" 
                       style="width: 180px; height: 200px; text-decoration: none; border-radius: 24px;"
                       data-aos="zoom-in" data-aos-delay="100">
                        @if(isset($maxSoftwareDiscount) && $maxSoftwareDiscount > 0)
                        <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-top-right-radius: 24px; border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                            Diskon s/d {{ $maxSoftwareDiscount }}%
                        </div>
                        @endif
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/software-logo.png') }}" alt="Software" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.1));">
                        </div>
                        
                        <h3 class="text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">SOFTWARE</h3>
                    </a>

                    <!-- KATEGORI: APLIKASI PREMIUM -->
                    <a href="{{ url('/aplikasi-premium') }}" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-300 dark:border-gray-700 category-card-glow bg-white dark:bg-gray-900" 
                       style="width: 180px; height: 200px; text-decoration: none; border-radius: 24px;"
                       data-aos="zoom-in" data-aos-delay="200">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/aplikasi-premium-logo.png') }}?v={{ time() }}" alt="Aplikasi Premium" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.1));" onerror="this.onerror=null; this.src='{{ asset('images/aplikasi-premium-logo.jpg') }}?v={{ time() }}';">
                        </div>
                        
                        <h3 class="text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors text-center" style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 13px; letter-spacing: 0.5px;">APLIKASI PREMIUM</h3>
                    </a>

                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 py-8 border-t border-gray-200 dark:border-gray-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center" data-aos="fade-in">
                <div class="flex items-center gap-1 mb-4 md:mb-0">
                    <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-16 w-auto object-contain drop-shadow-md">
                    <span class="ml-3 text-2xl text-gray-900 dark:text-white tracking-widest whitespace-nowrap" style="font-family: 'Righteous', cursive; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">TOTAP STORE</span>
                </div>
                <div class="text-sm">&copy; {{ date('Y') }} ToTap Store. Hak Cipta Dilindungi.</div>
            </div>
        </footer>

        <x-auth-modals />
    </body>
</html>
