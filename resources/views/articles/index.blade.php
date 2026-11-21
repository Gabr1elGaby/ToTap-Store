<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog & Panduan Top Up Game Termurah - ToTap Store</title>
    <meta name="description" content="Kumpulan panduan, tips trik game, review keamanan, dan berita promo top up Mobile Legends, Free Fire, Valorant, serta aplikasi premium termurah di ToTap Store.">
    <meta name="keywords" content="blog top up game, panduan diamond ml, tips free fire murah, review totap store, promo top up game">
    
    <!-- Open Graph / SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Blog & Panduan Top Up Game Termurah - ToTap Store">
    <meta property="og:description" content="Kumpulan panduan, tips trik game, dan berita promo top up game otomatis 24 jam di ToTap Store.">
    <meta property="og:image" content="{{ asset('images/logo-totap-v2.png') }}">
    <meta property="og:url" content="{{ url('/artikel') }}">

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

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .logo-font { font-family: 'Righteous', cursive; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-emerald-500 selection:text-white">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVR2XKKS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 text-white text-[11px] sm:text-xs py-2 px-4 text-center font-bold tracking-wide shadow-sm flex items-center justify-center gap-2">
        <span>⚡ Promo Spesial Hari Ini! Top Up Game & Aplikasi Premium Diskon s/d 50%</span>
        <a href="{{ url('/') }}" class="underline hover:text-emerald-200 transition">Beli Sekarang →</a>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-10 w-auto object-contain drop-shadow group-hover:scale-105 transition-transform duration-200">
                <span class="logo-font text-xl text-white tracking-wider">TOTAP STORE</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800 transition flex items-center gap-1.5">
                    <i class="fas fa-home"></i> Beranda Toko
                </a>
                <a href="{{ url('/#games') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-sm shadow-emerald-500/20 flex items-center gap-1.5">
                    <i class="fas fa-gamepad"></i> Top Up Game
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-12 md:py-16 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-4">
                <span>📚</span> Pusat Pengetahuan & Artikel Resmi ToTap Store
            </div>
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight mb-4">
                Panduan, Tips, & Info Terkini <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">Dunia Game</span>
            </h1>
            <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-400">
                Temukan tips top up hemat, panduan event game, review keamanan akun, hingga trik berlangganan aplikasi premium favorit Anda.
            </p>

            <!-- Search Form -->
            <div class="max-w-xl mx-auto mt-6">
                <form action="{{ route('articles.index') }}" method="GET" class="relative flex items-center">
                    <i class="fas fa-search absolute left-4 text-slate-400 text-sm"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik artikel (contoh: Diamond ML, Free Fire, QRIS)..."
                        class="w-full pl-11 pr-24 py-3 bg-slate-800/90 border border-slate-700 rounded-2xl text-sm text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30 shadow-lg">
                    <button type="submit" class="absolute right-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1 w-full">

        <!-- Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 scrollbar-none">
            @foreach($categories as $cat)
                @php
                    $isActive = ($selectedCategory === $cat) || ($cat === 'Semua' && empty($selectedCategory));
                @endphp
                <a href="{{ route('articles.index', ['category' => $cat !== 'Semua' ? $cat : null, 'q' => request('q')]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 {{ $isActive ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 hover:text-white hover:bg-slate-800 border border-slate-800' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        @if(request('q'))
            <div class="mb-6 flex items-center justify-between p-3 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300">
                <span>Hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $articles->total() }} artikel ditemukan)</span>
                <a href="{{ route('articles.index') }}" class="text-emerald-400 hover:underline">Hapus Filter</a>
            </div>
        @endif

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($articles as $article)
                <article class="bg-slate-900/80 rounded-2xl border border-slate-800 hover:border-slate-700 overflow-hidden flex flex-col group transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/40">
                    <a href="{{ route('articles.show', $article->slug) }}" class="relative aspect-video overflow-hidden bg-slate-950 block">
                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-900 to-indigo-950 text-4xl">
                                🎮
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider bg-slate-900/90 backdrop-blur-sm text-emerald-400 border border-emerald-500/30">
                            {{ $article->category }}
                        </span>
                    </a>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-3 text-[11px] text-slate-400 mb-2">
                            <span><i class="far fa-calendar-alt mr-1"></i> {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span><i class="far fa-clock mr-1"></i> {{ $article->reading_time }} mnt baca</span>
                            <span>•</span>
                            <span><i class="far fa-eye mr-1"></i> {{ number_format($article->views) }} views</span>
                        </div>

                        <h2 class="text-base font-bold text-white group-hover:text-emerald-400 transition mb-2 line-clamp-2">
                            <a href="{{ route('articles.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h2>

                        <p class="text-xs text-slate-400 line-clamp-3 mb-4 leading-relaxed flex-1">
                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                        </p>

                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-400 text-[11px] flex items-center gap-1.5">
                                <i class="fas fa-user-edit text-emerald-400"></i> {{ $article->author_name }}
                            </span>
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-emerald-400 group-hover:text-emerald-300 font-bold inline-flex items-center gap-1">
                                Baca <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 bg-slate-900/50 rounded-2xl border border-slate-800">
                    <div class="text-4xl mb-3">📰</div>
                    <p class="text-sm font-semibold">Belum ada artikel pada kategori ini.</p>
                    <a href="{{ route('articles.index') }}" class="mt-3 inline-block px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-500">
                        Lihat Semua Artikel
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="mt-10">
                {{ $articles->links() }}
            </div>
        @endif

        <!-- Floating CTA Banner -->
        <section class="mt-16 bg-gradient-to-r from-emerald-950/60 via-slate-900 to-indigo-950/60 border border-emerald-500/30 rounded-3xl p-6 sm:p-8 text-center relative overflow-hidden">
            <div class="relative z-10 max-w-2xl mx-auto space-y-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    ⚡ Siap Jadi Juara?
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-white">
                    Top Up Diamond Game & Aplikasi Premium Tanpa Ribet
                </h3>
                <p class="text-xs sm:text-sm text-slate-300">
                    Proses kilat otomatis 24 jam via QRIS, harga termurah langsung dari tangan pertama, dan 100% legal garansi uang kembali.
                </p>
                <div class="pt-2 flex flex-wrap justify-center gap-3">
                    <a href="{{ url('/#games') }}" class="px-6 py-2.5 rounded-xl text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                        <i class="fas fa-gamepad"></i> Mulai Top Up Sekarang
                    </a>
                    <a href="{{ url('/aplikasi-premium') }}" class="px-6 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 transition">
                        Lihat Aplikasi Premium
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 text-slate-500 py-8 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-8 w-auto">
                <span class="logo-font text-white text-base">TOTAP STORE</span>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4 text-slate-400">
                <a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a>
                <span>•</span>
                <a href="{{ route('articles.index') }}" class="text-emerald-400 font-bold hover:underline">Artikel & Blog</a>
                <span>•</span>
                <a href="{{ url('/#games') }}" class="hover:text-white transition">Top Up Games</a>
                <span>•</span>
                <a href="{{ url('/sitemap.xml') }}" target="_blank" class="hover:text-white transition">Sitemap XML</a>
            </div>
            <div>
                &copy; {{ date('Y') }} ToTap Store. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>
