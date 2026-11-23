<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog & Panduan Top Up Game Termurah - ToTap Store</title>
    <meta name="description" content="Kumpulan panduan, tips trik game, review keamanan akun, dan berita promo top up Mobile Legends, Free Fire, Valorant, serta aplikasi premium termurah di ToTap Store.">
    <meta name="keywords" content="blog top up game, panduan diamond ml, tips free fire murah, review totap store, promo top up game, aplikasi premium murah">
    <link rel="canonical" href="{{ url('/artikel') }}">

    <!-- Open Graph / SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Blog & Panduan Top Up Game Termurah - ToTap Store">
    <meta property="og:description" content="Kumpulan panduan, tips trik game, dan berita promo top up game otomatis 24 jam di ToTap Store.">
    <meta property="og:image" content="{{ asset('images/logo-totap-v2.png') }}">
    <meta property="og:url" content="{{ url('/artikel') }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Blog & Panduan Top Up Game Termurah - ToTap Store">
    <meta name="twitter:description" content="Kumpulan panduan, tips trik game, dan berita promo top up game otomatis 24 jam di ToTap Store.">
    <meta name="twitter:image" content="{{ asset('images/logo-totap-v2.png') }}">

    <!-- Google Tag Manager / Analytics -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NVR2XKKS');</script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JN1N5Z9NFH"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-JN1N5Z9NFH');
    </script>

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

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/logo-totap-v2.png') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .logo-font { font-family: 'Righteous', cursive; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white"
      x-data="{ showLogin: false, showRegister: false }"
      @open-login.window="showLogin = true"
      @open-register.window="showRegister = true"
      :class="{ 'overflow-hidden': showLogin || showRegister }">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVR2XKKS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- Official Standard ToTap Store Navbar (Consistent, Solid, No Bleed-Through) -->
    @include('layouts.navigation')

    <!-- Hero Section (Spacious, Elegant, Roomy) -->
    <section class="relative py-16 sm:py-24 bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 mb-6 shadow-sm">
                <span>📚</span> Pusat Pengetahuan & Artikel Resmi ToTap Store
            </div>
            
            <!-- Headline with Generous Line Height and Clear Contrast -->
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-snug sm:leading-relaxed mb-6 max-w-4xl mx-auto">
                Panduan, Tips, & Info Terkini <br class="hidden sm:inline">
                <span class="text-emerald-400">Dunia Game & Digital</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="max-w-2xl mx-auto text-sm sm:text-base text-gray-400 leading-relaxed font-normal mb-8">
                Temukan tips top up hemat, panduan event game, review keamanan akun, hingga trik berlangganan aplikasi premium favorit Anda.
            </p>

            <!-- Search Form (Spacious, Clean, Solid Dark Background) -->
            <div class="max-w-xl mx-auto">
                <form action="{{ route('articles.index') }}" method="GET" class="relative flex items-center">
                    @if(request('category') && request('category') !== 'Semua')
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <i class="fas fa-search absolute left-4 text-gray-400 text-sm pointer-events-none"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik artikel (contoh: Diamond ML, Free Fire, QRIS)..."
                        style="background-color: #1f2937 !important; color: #ffffff !important;"
                        class="w-full pl-11 pr-24 py-3.5 bg-gray-800 border border-gray-700 rounded-2xl text-sm text-white placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 shadow-lg">
                    @if(request('q'))
                        <a href="{{ route('articles.index', ['category' => request('category')]) }}"
                           class="absolute right-20 text-gray-400 hover:text-white transition p-1" title="Hapus pencarian">
                            <i class="fas fa-times-circle text-sm"></i>
                        </a>
                    @endif
                    <button type="submit" class="absolute right-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition shadow-md shadow-emerald-600/30">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content Area (Spacious & Breathable) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 flex-1 w-full">

        <!-- Category Tabs Navigation (Clean Pills, Generous Spacing) -->
        <div class="mb-10 sm:mb-12">
            <div class="flex items-center gap-2.5 overflow-x-auto pb-3 no-scrollbar">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($selectedCategory === $cat) || ($cat === 'Semua' && empty($selectedCategory));
                    @endphp
                    <a href="{{ route('articles.index', ['category' => $cat !== 'Semua' ? $cat : null, 'q' => request('q')]) }}"
                       class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 {{ $isActive ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 scale-[1.02]' : 'bg-gray-800 hover:bg-gray-700/80 text-gray-300 hover:text-white border border-gray-700/80' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        @if(request('q'))
            <div class="mb-8 flex items-center justify-between p-4 rounded-xl bg-gray-800 border border-gray-700 text-xs sm:text-sm text-gray-300 shadow-sm">
                <span>Hasil pencarian untuk: <strong class="text-white">"{{ request('q') }}"</strong> ({{ $articles->total() }} artikel ditemukan)</span>
                <a href="{{ route('articles.index', ['category' => request('category')]) }}" class="text-emerald-400 hover:underline font-bold">
                    Hapus Filter
                </a>
            </div>
        @endif

        <!-- Articles Grid (Generous 8-Unit Gap, Comfortable Breathing Room) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <article class="bg-gray-800/90 hover:bg-gray-800 rounded-2xl border border-gray-700/70 hover:border-emerald-500/50 overflow-hidden flex flex-col group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-black/50">
                    <a href="{{ route('articles.show', $article->slug) }}" class="relative aspect-video overflow-hidden bg-gray-900 block">
                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-900 to-indigo-950 text-4xl">
                                🎮
                            </div>
                        @endif
                        <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-gray-900/90 backdrop-blur-sm text-emerald-400 border border-emerald-500/30">
                            {{ $article->category }}
                        </span>
                    </a>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-[11px] text-gray-400 mb-2.5">
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                                <span>•</span>
                                <span><i class="far fa-clock mr-1"></i> {{ $article->reading_time }} mnt baca</span>
                                <span>•</span>
                                <span><i class="far fa-eye mr-1"></i> {{ number_format($article->views) }} views</span>
                            </div>

                            <h2 class="text-base sm:text-lg font-bold text-white group-hover:text-emerald-400 transition leading-snug mb-3 line-clamp-2">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h2>

                            <p class="text-xs text-gray-400 line-clamp-3 mb-5 leading-relaxed">
                                {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-700/70 flex items-center justify-between text-xs font-semibold">
                            <span class="text-gray-400 text-[11px] flex items-center gap-1.5">
                                <i class="fas fa-user-edit text-emerald-400"></i> {{ $article->author_name }}
                            </span>
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-emerald-400 group-hover:text-emerald-300 font-bold inline-flex items-center gap-1">
                                <span>Baca Artikel</span>
                                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center text-gray-400 bg-gray-800/50 rounded-2xl border border-gray-700">
                    <div class="text-4xl mb-3">📰</div>
                    <p class="text-sm font-semibold">Belum ada artikel pada kategori ini.</p>
                    <a href="{{ route('articles.index') }}" class="mt-4 inline-block px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-500 transition shadow-md shadow-emerald-600/25">
                        Lihat Semua Artikel
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="mt-14 mb-8">
                {{ $articles->links() }}
            </div>
        @endif

        <!-- Spacer (Menambah Ruang Kosong Tanpa Garis) -->
        <div class="h-24 sm:h-36"></div>

        <!-- Floating CTA Banner (Spacious, Roomy Padding) -->
        <section class="bg-gradient-to-r from-emerald-950/70 via-gray-900 to-indigo-950/70 border border-emerald-500/30 rounded-3xl p-8 sm:p-14 text-center relative overflow-hidden shadow-2xl mb-16">
            <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    ⚡ Siap Jadi Juara?
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    Top Up Diamond Game & Aplikasi Premium Tanpa Ribet
                </h3>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    Proses kilat otomatis 24 jam via QRIS, harga termurah langsung dari tangan pertama, dan 100% legal garansi uang kembali.
                </p>
                <div class="pt-3 flex flex-wrap justify-center gap-3">
                    <a href="{{ url('/#games') }}" class="px-6 py-3 rounded-xl text-xs sm:text-sm font-extrabold text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                        <i class="fas fa-gamepad"></i> Mulai Top Up Sekarang
                    </a>
                    <a href="{{ url('/aplikasi-premium') }}" class="px-6 py-3 rounded-xl text-xs sm:text-sm font-bold text-gray-200 bg-gray-800 hover:bg-gray-700 border border-gray-700 transition flex items-center gap-2">
                        <i class="fas fa-crown text-amber-400"></i> Lihat Aplikasi Premium
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 text-gray-400 py-10 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-9 w-auto">
                <div class="flex flex-col">
                    <span class="logo-font text-white text-base tracking-wider">TOTAP STORE</span>
                    <span class="text-[10px] text-gray-400">Pusat Layanan Game & Digital Terpercaya</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4 text-gray-400 font-medium">
                <a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a>
                <span>•</span>
                <a href="{{ route('articles.index') }}" class="text-emerald-400 font-bold hover:underline">Artikel & Blog</a>
                <span>•</span>
                <a href="{{ url('/#games') }}" class="hover:text-white transition">Top Up Games</a>
                <span>•</span>
                <a href="{{ url('/aplikasi-premium') }}" class="hover:text-white transition">Aplikasi Premium</a>
            </div>
            <div class="text-gray-400 text-center md:text-right">
                &copy; {{ date('Y') }} ToTap Store. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <!-- Auth Modals -->
    @include('components.auth-modals')
</body>
</html>