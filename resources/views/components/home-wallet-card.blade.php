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
<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 pt-4 pb-2">
    
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
                    <span class="text-3xl sm:text-4xl font-black tracking-tight text-white" style="font-family: 'Orbitron', sans-serif;">
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
            <a href="#kategori" class="mt-4 p-2.5 sm:p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl flex items-center justify-between text-xs transition group cursor-pointer" style="text-decoration: none;">
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

            <!-- 4 Quick Action Buttons di Bawah Kartu (Dijamin 100% Selalu Sejajar Horizontal) -->
            <div class="mt-5 pt-5 border-t border-white/10 text-center" style="display: flex; justify-content: space-between; align-items: stretch; gap: 8px; width: 100%;">
                
                <!-- Aksi 1: Isi Saldo -->
                @auth
                    <a href="{{ route('deposit.index') }}" style="flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none;" class="p-2 sm:p-2.5 rounded-2xl hover:bg-white/5 transition group">
                        <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(59, 130, 246, 0.2); color: #60a5fa; display: flex; align-items: center; justify-content: center; margin-bottom: 6px;" class="group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-gray-200 group-hover:text-white whitespace-nowrap">Isi Saldo</span>
                    </a>
                @else
                    <button type="button" onclick="openLoginModal()" style="flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; padding: 0.5rem;" class="rounded-2xl hover:bg-white/5 transition group">
                        <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(59, 130, 246, 0.2); color: #60a5fa; display: flex; align-items: center; justify-content: center; margin-bottom: 6px;" class="group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-gray-200 group-hover:text-white whitespace-nowrap">Isi Saldo</span>
                    </button>
                @endauth

                <!-- Aksi 2: Top Up -->
                <a href="#kategori" style="flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none;" class="p-2 sm:p-2.5 rounded-2xl hover:bg-white/5 transition group">
                    <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(99, 102, 241, 0.2); color: #818cf8; display: flex; align-items: center; justify-content: center; margin-bottom: 6px;" class="group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-gray-200 group-hover:text-white whitespace-nowrap">Top Up</span>
                </a>

                <!-- Aksi 3: Pesanan -->
                @auth
                    <a href="{{ route('transactions.history') }}" style="flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none;" class="p-2 sm:p-2.5 rounded-2xl hover:bg-white/5 transition group">
                        <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(16, 185, 129, 0.2); color: #34d399; display: flex; align-items: center; justify-content: center; margin-bottom: 6px;" class="group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-gray-200 group-hover:text-white whitespace-nowrap">Pesanan</span>
                    </a>
                @else
                    <button type="button" onclick="openLoginModal()" style="flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; padding: 0.5rem;" class="rounded-2xl hover:bg-white/5 transition group">
                        <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(16, 185, 129, 0.2); color: #34d399; display: flex; align-items: center; justify-content: center; margin-bottom: 6px;" class="group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-gray-200 group-hover:text-white whitespace-nowrap">Pesanan</span>
                    </button>
                @endauth

                <!-- Aksi 4: Artikel -->
                <a href="{{ route('articles.index') }}" style="flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none;" class="p-2 sm:p-2.5 rounded-2xl hover:bg-white/5 transition group">
                    <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(245, 158, 11, 0.2); color: #fbbf24; display: flex; align-items: center; justify-content: center; margin-bottom: 6px;" class="group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-gray-200 group-hover:text-white whitespace-nowrap">Artikel</span>
                </a>

            </div>
        </div>
    </div>

    <!-- 2. TIGA KOTAK STATISTIK BULAN INI (DIJAMIN 100% SEJAJAR 3 KOLOM) -->
    <div class="mt-3.5" style="display: flex; justify-content: space-between; gap: 8px; width: 100%;">
        <!-- Box 1: Pesanan Bulan Ini -->
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-200 dark:border-gray-700/80 rounded-2xl p-3 sm:p-4 shadow-sm text-left transition-all hover:border-blue-500/40" style="flex: 1; min-width: 0;">
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-bold block leading-tight truncate">Pesanan bulan ini</span>
            <p class="text-base sm:text-2xl font-black text-gray-900 dark:text-white mt-1" style="font-family: 'Orbitron', sans-serif;">
                {{ $userOrdersMonthCount }}
            </p>
        </div>

        <!-- Box 2: Belanja Bulan Ini -->
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-200 dark:border-gray-700/80 rounded-2xl p-3 sm:p-4 shadow-sm text-left transition-all hover:border-blue-500/40" style="flex: 1; min-width: 0;">
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-bold block leading-tight truncate">Belanja bulan ini</span>
            <p class="text-xs sm:text-xl font-black text-blue-600 dark:text-blue-400 mt-1 truncate" style="font-family: 'Orbitron', sans-serif;">
                Rp{{ number_format($userSpendMonth, 0, ',', '.') }}
            </p>
        </div>

        <!-- Box 3: Sedang Diproses -->
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-gray-200 dark:border-gray-700/80 rounded-2xl p-3 sm:p-4 shadow-sm text-left transition-all hover:border-blue-500/40" style="flex: 1; min-width: 0;">
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-bold block leading-tight truncate">Sedang diproses</span>
            <p class="text-base sm:text-2xl font-black {{ $userProcessingCount > 0 ? 'text-amber-500 animate-pulse' : 'text-gray-900 dark:text-white' }} mt-1" style="font-family: 'Orbitron', sans-serif;">
                {{ $userProcessingCount }}
            </p>
        </div>
    </div>

</div>
