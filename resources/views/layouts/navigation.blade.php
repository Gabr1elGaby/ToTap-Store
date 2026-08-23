<style>
    .nav-item-glow {
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .nav-item-glow:hover {
        color: #60A5FA !important;
        border-bottom-color: #60A5FA !important;
    }
</style>
<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="no-underline">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo-totap-v2.png') }}" class="h-10 w-auto object-contain drop-shadow-md" alt="Logo">
                            <span class="text-xl text-white tracking-widest whitespace-nowrap" style="font-family: 'Righteous', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">TOTAP STORE</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::check() && Auth::user()->role === 'superadmin')
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
                    @else
                        <x-nav-link href="/">
                            {{ __('Beranda') }}
                        </x-nav-link>
                        <x-nav-link href="/software" :active="request()->is('software*')">
                            {{ __('Software & POS') }}
                        </x-nav-link>
                        <x-nav-link :href="route('topup.index')" :active="request()->routeIs('topup.*')">
                            {{ __('Top Up Game') }}
                        </x-nav-link>
                        @auth
                            <x-nav-link :href="route('transactions.history')" :active="request()->routeIs('transactions.*')">
                                {{ __('Riwayat Transaksi') }}
                            </x-nav-link>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Desktop User Dropdown / Login Buttons -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-gray-300 dark:text-white bg-gray-800 hover:text-gray-700 dark:hover:text-blue-400 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
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
                            <i class="fas fa-receipt mr-2 text-indigo-400"></i> {{ __('Riwayat Transaksi') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="space-x-4">
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="font-semibold text-gray-300 hover:text-gray-900 dark:text-white dark:nav-item-glow">Login</a>
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-register'))" class="font-semibold text-gray-300 hover:text-gray-900 dark:text-white dark:nav-item-glow">Register</a>
                </div>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile 3 Lines) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Navigation Drawer -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-800 border-t border-gray-700 pb-3">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::check() && Auth::user()->role === 'superadmin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <i class="fas fa-chart-line mr-2 text-indigo-400"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                    <i class="fas fa-boxes mr-2 text-blue-400"></i> {{ __('Products') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.games.index')" :active="request()->routeIs('admin.games.*')">
                    <i class="fas fa-gamepad mr-2 text-purple-400"></i> {{ __('Top Up Game') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                    <i class="fas fa-tags mr-2 text-green-400"></i> {{ __('Plans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')">
                    <i class="fas fa-receipt mr-2 text-yellow-400"></i> {{ __('Kelola Transaksi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')">
                    <i class="fas fa-star mr-2 text-amber-400"></i> {{ __('Ulasan') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link href="/" :active="request()->is('/')">
                    <i class="fas fa-home mr-2 text-indigo-400"></i> {{ __('Beranda') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/software" :active="request()->is('software*')">
                    <i class="fas fa-desktop mr-2 text-blue-400"></i> {{ __('Software & POS') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('topup.index')" :active="request()->routeIs('topup.*')">
                    <i class="fas fa-gamepad mr-2 text-purple-400"></i> {{ __('Top Up Game') }}
                </x-responsive-nav-link>
                @auth
                    <x-responsive-nav-link :href="route('transactions.history')" :active="request()->routeIs('transactions.*')">
                        <i class="fas fa-receipt mr-2 text-yellow-400"></i> {{ __('Riwayat Transaksi') }}
                    </x-responsive-nav-link>
                @endauth
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-2 border-t border-gray-700">
            @auth
            <div class="px-4 mb-3">
                <div class="font-bold text-base text-white flex items-center gap-2">
                    <i class="fas fa-user-circle text-indigo-400"></i> {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-xs text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user-cog mr-2 text-gray-400"></i> {{ __('Profile Saya') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('transactions.history')">
                    <i class="fas fa-history mr-2 text-indigo-400"></i> {{ __('Riwayat Transaksi') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="px-4 pt-2 space-y-2">
                <button @click="window.dispatchEvent(new CustomEvent('open-login')); open = false" class="w-full text-center py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition block">
                    Masuk (Login)
                </button>
                <button @click="window.dispatchEvent(new CustomEvent('open-register')); open = false" class="w-full text-center py-2.5 rounded-xl font-bold text-gray-200 bg-gray-700 hover:bg-gray-600 transition block">
                    Daftar Akun Baru
                </button>
            </div>
            @endauth
        </div>
    </div>
</nav>
