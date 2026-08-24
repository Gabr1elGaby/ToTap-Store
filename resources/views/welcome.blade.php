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
                            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-register'))" class="px-8 py-3 bg-white dark:bg-transparent text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800 transition shadow-sm cursor-pointer">
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
