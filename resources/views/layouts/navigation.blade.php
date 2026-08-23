<style>
    .nav-item-glow {
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .nav-item-glow:hover {
        color: #3B82F6 !important;
        border-bottom-color: #3B82F6 !important;
    }
</style>
<nav x-data="{ open: false, theme: localStorage.getItem('totap_theme') || 'dark' }" 
     @theme-changed.window="theme = $event.detail"
     class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="no-underline">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo-totap-v2.png') }}" class="h-10 w-auto object-contain drop-shadow-md" alt="Logo">
                            <span class="text-xl text-gray-900 dark:text-white tracking-widest whitespace-nowrap" style="font-family: 'Righteous', cursive; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">TOTAP STORE</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links (Super Admin Only) -->
                @if(Auth::check() && Auth::user()->role === 'superadmin')
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.games.index')" :active="request()->routeIs('admin.games.*')">
                        {{ __('Top Up Game') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                        {{ __('Plans') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                        {{ __('Transaksi') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">
                        <span class="mr-1 text-amber-400">⭐</span> {{ __('Ulasan') }}
                    </x-nav-link>
                </div>
                @endif
            </div>

            <!-- Desktop Right Actions (Theme Toggle + Auth Dropdown/Buttons) -->
            <div class="hidden sm:flex sm:items-center sm:gap-3 sm:ms-6">
                
                <!-- Light / Dark Mode Toggle Button (Desktop) -->
                <button type="button"
                    @click="
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            document.documentElement.style.backgroundColor = '#f8fafc';
                            localStorage.setItem('totap_theme', 'light');
                            $dispatch('theme-changed', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            document.documentElement.style.backgroundColor = '#111827';
                            localStorage.setItem('totap_theme', 'dark');
                            $dispatch('theme-changed', 'dark');
                        }
                    "
                    class="p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 transition focus:outline-none flex items-center justify-center border border-gray-200 dark:border-gray-700 shadow-sm"
                    :title="theme === 'dark' ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'">
                    <!-- Sun Icon (Active in Dark mode) -->
                    <template x-if="theme === 'dark'">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </template>
                    <!-- Moon Icon (Active in Light mode) -->
                    <template x-if="theme === 'light'">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </template>
                </button>

                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 dark:border-gray-700 text-sm leading-4 font-semibold rounded-xl text-gray-700 dark:text-white bg-gray-50 dark:bg-gray-800 hover:text-indigo-600 dark:hover:text-blue-400 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user mr-2 text-gray-400"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('transactions.history')">
                            <i class="fas fa-receipt mr-2 text-indigo-500"></i> {{ __('Riwayat Transaksi & Invoice') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="space-x-3">
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="font-semibold text-sm text-gray-700 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-white px-3 py-2 rounded-lg transition">Login</a>
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-register'))" class="font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-md transition">Register</a>
                </div>
                @endauth
            </div>

            <!-- Mobile Action Buttons (Theme Toggle + Hamburger 3 Lines) -->
            <div class="-me-2 flex items-center gap-1 sm:hidden">
                <!-- Theme Toggle Button (Mobile) -->
                <button type="button"
                    @click="
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            document.documentElement.style.backgroundColor = '#f8fafc';
                            localStorage.setItem('totap_theme', 'light');
                            $dispatch('theme-changed', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            document.documentElement.style.backgroundColor = '#111827';
                            localStorage.setItem('totap_theme', 'dark');
                            $dispatch('theme-changed', 'dark');
                        }
                    "
                    class="p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                    <template x-if="theme === 'dark'">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </template>
                    <template x-if="theme === 'light'">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </template>
                </button>

                <!-- Hamburger Button (Mobile 3 Lines) -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Navigation Drawer -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 pb-3 transition-colors duration-200">
        @if(Auth::check() && Auth::user()->role === 'superadmin')
            <!-- Khusus Super Admin: Menu Dashboard Lengkap -->
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <i class="fas fa-chart-line mr-2 text-indigo-500"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                    <i class="fas fa-boxes mr-2 text-blue-500"></i> {{ __('Products') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.games.index')" :active="request()->routeIs('admin.games.*')">
                    <i class="fas fa-gamepad mr-2 text-purple-500"></i> {{ __('Top Up Game') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                    <i class="fas fa-tags mr-2 text-green-500"></i> {{ __('Plans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                    <i class="fas fa-receipt mr-2 text-yellow-500"></i> {{ __('Kelola Transaksi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">
                    <i class="fas fa-star mr-2 text-amber-500"></i> {{ __('Ulasan') }}
                </x-responsive-nav-link>
            </div>
            <div class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-700 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user-cog mr-2 text-gray-400"></i> {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        @else
            <!-- Khusus Customer: Profile, Riwayat Transaksi & Invoice, dan Log Out -->
            @auth
            <div class="pt-3 pb-2 space-y-1">
                <div class="px-4 mb-3">
                    <div class="font-bold text-base text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-user-circle text-indigo-500"></i> {{ Auth::user()->name }}
                    </div>
                    <div class="font-medium text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user mr-2 text-gray-400"></i> {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('transactions.history')">
                    <i class="fas fa-receipt mr-2 text-indigo-500"></i> {{ __('Riwayat Transaksi & Invoice') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="px-4 pt-4 pb-2 space-y-2">
                <button @click="window.dispatchEvent(new CustomEvent('open-login')); open = false" class="w-full text-center py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition block">
                    Masuk (Login)
                </button>
                <button @click="window.dispatchEvent(new CustomEvent('open-register')); open = false" class="w-full text-center py-2.5 rounded-xl font-bold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition block">
                    Daftar Akun Baru
                </button>
            </div>
            @endauth
        @endif
    </div>
</nav>
