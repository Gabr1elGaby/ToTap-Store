<!-- Mobile Bottom Navigation Bar (Dock) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-t border-gray-200 dark:border-gray-800 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] px-2 py-1.5 transition-colors duration-200" style="padding-bottom: max(0.4rem, env(safe-area-inset-bottom));">
    <div class="grid grid-cols-5 items-end justify-items-center text-center max-w-md mx-auto">
        
        <!-- 1. BERANDA -->
        <a href="{{ url('/') }}" class="flex flex-col items-center justify-center w-full py-1 group transition-colors {{ request()->is('/') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
            <div class="relative">
                <svg class="w-5 h-5 mx-auto group-active:scale-90 transition-transform" fill="{{ request()->is('/') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                @if(request()->is('/'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] block mt-1 tracking-tight leading-none">Beranda</span>
        </a>

        <!-- 2. KATEGORI (Scroll halus ke #kategori atau buka pilihan) -->
        <a href="javascript:void(0)" onclick="handleMobileNavKategori(event)" class="flex flex-col items-center justify-center w-full py-1 group transition-colors {{ (request()->is('topup*') || request()->is('aplikasi-premium*') || request()->is('software*')) ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
            <div class="relative">
                <svg class="w-5 h-5 mx-auto group-active:scale-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <span class="text-[10px] block mt-1 tracking-tight leading-none">Kategori</span>
        </a>

        <!-- 3. ISI SALDO (Tombol Tengah Timbul / Floating Elevated Button) -->
        <div class="relative flex flex-col items-center justify-center -mt-5">
            @auth
                <a href="{{ route('deposit.index') }}" class="group flex flex-col items-center focus:outline-none" title="Isi Saldo (Deposit)">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/40 ring-4 ring-white dark:ring-gray-900 group-hover:scale-105 group-active:scale-95 transition-all duration-200">
                        <svg class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-blue-600 dark:text-blue-400 block mt-1 tracking-tight leading-none whitespace-nowrap">Isi Saldo</span>
                </a>
            @else
                <button type="button" onclick="openLoginModal()" class="group flex flex-col items-center focus:outline-none" title="Masuk untuk Isi Saldo">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/40 ring-4 ring-white dark:ring-gray-900 group-hover:scale-105 group-active:scale-95 transition-all duration-200">
                        <svg class="w-6 h-6 stroke-white" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-blue-600 dark:text-blue-400 block mt-1 tracking-tight leading-none whitespace-nowrap">Isi Saldo</span>
                </button>
            @endauth
        </div>

        <!-- 4. PESANAN (Riwayat Transaksi & Invoice) -->
        @auth
            <a href="{{ route('transactions.history') }}" class="flex flex-col items-center justify-center w-full py-1 group transition-colors {{ request()->routeIs('transactions.history*') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <div class="relative">
                    <svg class="w-5 h-5 mx-auto group-active:scale-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    @if(request()->routeIs('transactions.history*'))
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[10px] block mt-1 tracking-tight leading-none">Pesanan</span>
            </a>
        @else
            <button type="button" onclick="openLoginModal()" class="flex flex-col items-center justify-center w-full py-1 group transition-colors text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                <svg class="w-5 h-5 mx-auto group-active:scale-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="text-[10px] block mt-1 tracking-tight leading-none">Pesanan</span>
            </button>
        @endauth

        <!-- 5. AKUN (Profil Pengguna / Tombol Masuk) -->
        @auth
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-full py-1 group transition-colors {{ request()->routeIs('profile.*') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <div class="relative">
                    <svg class="w-5 h-5 mx-auto group-active:scale-90 transition-transform" fill="{{ request()->routeIs('profile.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    @if(request()->routeIs('profile.*'))
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[10px] block mt-1 tracking-tight leading-none truncate max-w-[55px]">Akun</span>
            </a>
        @else
            <button type="button" onclick="openLoginModal()" class="flex flex-col items-center justify-center w-full py-1 group transition-colors text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                <svg class="w-5 h-5 mx-auto group-active:scale-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] block mt-1 tracking-tight leading-none">Masuk</span>
            </button>
        @endauth

    </div>
</nav>

<script>
    function handleMobileNavKategori(e) {
        if (e) e.preventDefault();
        const currentPath = window.location.pathname;
        if (currentPath === '/' || currentPath === '') {
            const kategoriSection = document.getElementById('kategori');
            if (kategoriSection) {
                kategoriSection.scrollIntoView({ behavior: 'smooth' });
                return;
            }
        }
        window.location.href = "{{ url('/#kategori') }}";
    }
</script>
