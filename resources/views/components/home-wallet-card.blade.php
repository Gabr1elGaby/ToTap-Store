@php
    $currentUser = Auth::user();
    $currentHour = (int) date('H');
    $greeting = 'Selamat Malam';
    if ($currentHour >= 4 && $currentHour < 11) {
        $greeting = 'Selamat Pagi';
    } elseif ($currentHour >= 11 && $currentHour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($currentHour >= 15 && $currentHour < 18) {
        $greeting = 'Selamat Sore';
    }

    $userOrdersMonthCount = 0;
    $userSpendMonth = 0;
    $userProcessingCount = 0;

    if ($currentUser) {
        $startOfMonth = now()->startOfMonth();
        try {
            $topupsMonth = \App\Models\Transaction::where('user_id', $currentUser->id)
                ->where('created_at', '>=', $startOfMonth)
                ->get();
            
            $userOrdersMonthCount += $topupsMonth->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
            $userSpendMonth += $topupsMonth->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->sum('total_amount');
            $userProcessingCount += $topupsMonth->whereIn('status', ['PENDING', 'pending', 'PROCESSING', 'processing'])->count();

            if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                $ordersMonth = \App\Models\Order::where('user_id', $currentUser->id)
                    ->where('created_at', '>=', $startOfMonth)
                    ->get();
                $userOrdersMonthCount += $ordersMonth->whereIn('payment_status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
                $userSpendMonth += $ordersMonth->whereIn('payment_status', ['PAID', 'paid', 'SUCCESS', 'success'])->sum('total_price');
                $userProcessingCount += $ordersMonth->whereIn('payment_status', ['PENDING', 'pending', 'PROCESSING', 'processing', 'UNPAID', 'unpaid'])->count();
            }
        } catch (\Throwable $e) {}
    }
@endphp

<!-- Hero Wallet & Stats Card (Dashboard Beranda) -->
<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 pt-6 sm:pt-8 pb-4">
    
    <!-- 1. KARTU UTAMA (WALLET CARD) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0c1322] via-[#16233d] to-[#0c1322] border border-blue-900/40 p-5 sm:p-7 text-white shadow-2xl transition-all duration-300">
        <!-- Glow Orbs Decorative -->
        <div class="absolute -right-12 -top-12 w-44 h-44 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-44 h-44 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header Row: Greeting & Member Badge -->
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm text-gray-300 font-medium">
                        {{ $greeting }}, <span class="font-extrabold text-white text-sm sm:text-base">{{ $currentUser ? $currentUser->name : 'Sobat Gamers' }}</span>
                    </p>
                </div>
                <div>
                    @auth
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-white/10 border border-white/20 text-blue-300 shadow-sm backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ $currentUser->role === 'superadmin' ? 'Super Admin' : ($currentUser->role === 'reseller' ? 'Reseller VIP' : 'Member') }}
                        </span>
                    @else
                        <button type="button" onclick="openLoginModal()" class="px-3.5 py-1 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition shadow-sm cursor-pointer">
                            Masuk Akun
                        </button>
                    @endauth
                </div>
            </div>

            <!-- Middle Row: Saldo kamu & Nominal Besar -->
            <div class="mt-3">
                <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Saldo kamu</span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span class="text-3xl sm:text-4xl font-black tracking-tight" style="font-family: 'Orbitron', sans-serif;">
                        Rp{{ number_format($currentUser->balance ?? 0, 0, ',', '.') }}
                    </span>
                    @auth
                        <a href="{{ route('deposit.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold ml-2 transition">
                            + Tambah Saldo
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mini Promo / Info Banner Bar di Dalam Kartu -->
            <a href="#kategori" class="mt-4 p-2.5 sm:p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl flex items-center justify-between text-xs transition group cursor-pointer">
                <div class="flex items-center gap-2 overflow-hidden">
                    <span class="px-2 py-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-black text-[10px] rounded-md uppercase tracking-wider shrink-0 shadow-sm">
                        ToTap VIP
                    </span>
                    <span class="text-gray-200 truncate font-medium">
                        Top Up Game & Aplikasi Premium Termurah, Otomatis 24 Jam via QRIS
                    </span>
                </div>
                <div class="flex items-center gap-1 text-gray-400 group-hover:text-white shrink-0 ml-2 transition-transform group-hover:translate-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- 4 Quick Action Buttons di Bawah Kartu -->
            <div class="grid grid-cols-4 gap-2 sm:gap-4 mt-5 pt-5 border-t border-white/10 text-center">
                
                <!-- Aksi 1: Isi Saldo -->
                @auth
                    <a href="{{ route('deposit.index') }}" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-white/5 transition group">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                            <i class="fas fa-wallet text-base"></i>
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-gray-200 group-hover:text-white">Isi Saldo</span>
                    </a>
                @else
                    <button type="button" onclick="openLoginModal()" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-white/5 transition group cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                            <i class="fas fa-wallet text-base"></i>
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-gray-200 group-hover:text-white">Isi Saldo</span>
                    </button>
                @endauth

                <!-- Aksi 2: Top Up (Langsung Scroll ke Kategori) -->
                <a href="#kategori" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-white/5 transition group">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                        <i class="fas fa-gamepad text-base"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-gray-200 group-hover:text-white">Top Up</span>
                </a>

                <!-- Aksi 3: Pesanan -->
                @auth
                    <a href="{{ route('transactions.history') }}" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-white/5 transition group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                            <i class="fas fa-receipt text-base"></i>
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-gray-200 group-hover:text-white">Pesanan</span>
                    </a>
                @else
                    <button type="button" onclick="openLoginModal()" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-white/5 transition group cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                            <i class="fas fa-receipt text-base"></i>
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-gray-200 group-hover:text-white">Pesanan</span>
                    </button>
                @endauth

                <!-- Aksi 4: Mutasi / Aplikasi -->
                <a href="{{ route('aplikasi-premium.index') }}" class="flex flex-col items-center justify-center p-2 rounded-xl hover:bg-white/5 transition group">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition-transform">
                        <i class="fas fa-tv text-base"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-gray-200 group-hover:text-white">Aplikasi</span>
                </a>

            </div>
        </div>
    </div>

    <!-- 2. TIGA KOTAK STATISTIK BULAN INI (DI BAWAH KARTU) -->
    <div class="grid grid-cols-3 gap-2.5 sm:gap-4 mt-3">
        <!-- Box 1: Pesanan Bulan Ini -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-3 sm:p-4 shadow-sm text-left transition-colors">
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-semibold block truncate">Pesanan bulan ini</span>
            <p class="text-base sm:text-2xl font-black text-gray-900 dark:text-white mt-1" style="font-family: 'Orbitron', sans-serif;">
                {{ $userOrdersMonthCount }}
            </p>
        </div>

        <!-- Box 2: Belanja Bulan Ini -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-3 sm:p-4 shadow-sm text-left transition-colors">
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-semibold block truncate">Belanja bulan ini</span>
            <p class="text-base sm:text-xl font-black text-blue-600 dark:text-blue-400 mt-1 truncate" style="font-family: 'Orbitron', sans-serif;">
                Rp{{ number_format($userSpendMonth, 0, ',', '.') }}
            </p>
        </div>

        <!-- Box 3: Sedang Diproses -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-3 sm:p-4 shadow-sm text-left transition-colors">
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-semibold block truncate">Sedang diproses</span>
            <p class="text-base sm:text-2xl font-black {{ $userProcessingCount > 0 ? 'text-amber-500 animate-pulse' : 'text-gray-900 dark:text-white' }} mt-1" style="font-family: 'Orbitron', sans-serif;">
                {{ $userProcessingCount }}
            </p>
        </div>
    </div>

</div>
