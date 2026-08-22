<nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-16 w-auto object-contain drop-shadow-md">
                <span class="ml-3 text-2xl text-white tracking-widest whitespace-nowrap" style="font-family: 'Righteous', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">TOTAP STORE</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="/#beranda" class="text-gray-300 text-sm font-semibold hover:text-white transition">Beranda</a>
                <a href="/#keunggulan" class="text-gray-300 text-sm font-semibold hover:text-white transition">Keunggulan</a>
                <a href="/#products" class="text-gray-300 text-sm font-semibold hover:text-white transition">Solusi Produk</a>
            </div>
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role === 'superadmin')
                            <a href="{{ url('/admin/dashboard') }}" class="text-gray-300 hover:text-white font-semibold transition">Dashboard</a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="text-gray-300 hover:text-white font-semibold transition">Dashboard</a>
                        @endif
                    @else
                        <button @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="text-gray-300 hover:text-white font-semibold px-4 py-2 rounded-lg transition">Log in</button>
                        @if (Route::has('register'))
                            <button @click.prevent="window.dispatchEvent(new CustomEvent('open-register'))" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition shadow-lg shadow-blue-500/30">Register</button>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>
