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
<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="no-underline">
                        <div class="flex items-center gap-2"><img src="{{ asset('images/logo-totap-v2.png') }}" class="h-10 w-auto object-contain drop-shadow-md"><span class="text-xl text-white tracking-widest whitespace-nowrap" style="font-family: 'Righteous', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">TOTAP STORE</span></div>
                    </a>
                </div>

                <!-- Navigation Links -->
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
                        
                        <!-- Other admin links will go here -->
                    @else
                        <x-nav-link href="/">
                            {{ __('Beranda') }}
                        </x-nav-link>
                        @if(request()->is('/'))
                            <x-nav-link href="#keunggulan">
                                {{ __('Keunggulan') }}
                            </x-nav-link>
                            
                        @endif
                    @endif
                </div>
            </div>

                        <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
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
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
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
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::check() && Auth::user()->role === 'superadmin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link href="/">
                    {{ __('Beranda') }}
                </x-responsive-nav-link>
            @endif
        </div>

                <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))">Login</x-responsive-nav-link>
                <x-responsive-nav-link href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))">Register</x-responsive-nav-link>
            </div>
            @endauth
        </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
