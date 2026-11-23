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

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/logo-totap-v2.png') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .logo-font { font-family: 'Righteous', cursive; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Force dark background on search input to prevent browser/plugin white input bug */
        .article-search-input {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border-color: #334155 !important;
        }
        .article-search-input:focus {
            background-color: #0f172a !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25) !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-emerald-500 selection:text-white">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVR2XKKS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    @php
        $catIcons = [
            'Semua' => 'fa-layer-group',
            'Panduan & Tips' => 'fa-lightbulb',
            'Tips Game' => 'fa-gamepad',
            'Review & Keamanan' => 'fa-shield-alt',
            'Promo' => 'fa-fire',
            'Berita' => 'fa-bullhorn',
        ];

        $catBadgeStyles = [
            'Panduan & Tips' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
            'Tips Game' => 'bg-sky-500/15 text-sky-300 border-sky-500/30',
            'Review & Keamanan' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
            'Promo' => 'bg-rose-500/15 text-rose-300 border-rose-500/30',
            'Berita' => 'bg-purple-500/15 text-purple-300 border-purple-500/30',
        ];
    @endphp

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-slate-950/85 backdrop-blur-xl border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-10 sm:h-11 w-auto object-contain drop-shadow group-hover:scale-105 transition-transform duration-300">
                <div class="flex flex-col">
                    <span class="logo-font text-lg sm:text-2xl text-white tracking-wider flex items-center gap-1.5">
                        TOTAP STORE
                    </span>
                    <span class="text-[10px] text-emerald-400 font-semibold tracking-widest uppercase -mt-1 hidden sm:block">
                        Media & Knowledge Hub
                    </span>
                </div>
            </a>

            <nav class="flex items-center gap-2 sm:gap-3">
                <a href="{{ url('/') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-900 border border-slate-800/80 transition flex items-center gap-2">
                    <i class="fas fa-home text-slate-400 text-xs"></i>
                    <span>Beranda Toko</span>
                </a>
                <a href="{{ url('/#games') }}" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                    <i class="fas fa-gamepad"></i>
                    <span>Top Up Game</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Modern Hero Section -->
    <section class="relative py-14 sm:py-20 overflow-hidden bg-gradient-to-b from-slate-900 via-slate-950 to-slate-950 border-b border-slate-800/80">
        <!-- Subtle Ambient Lighting -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-80 bg-gradient-to-b from-emerald-500/15 via-teal-500/5 to-transparent blur-3xl pointer-events-none rounded-full"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Modern Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 mb-5 shadow-lg shadow-emerald-950/40 backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Pusat Pengetahuan & Artikel Resmi ToTap Store</span>
            </div>
            
            <!-- Hero Title -->
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-tight mb-5 max-w-4xl mx-auto">
                Panduan, Tips, & Info Terkini <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">
                    Dunia Game & Digital
                </span>
            </h1>
            
            <!-- Hero Description -->
            <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-300/80 leading-relaxed font-normal">
                Temukan tips top up hemat, panduan event game, review keamanan akun, hingga trik berlangganan aplikasi premium favorit Anda dengan mudah dan aman.
            </p>

            <!-- Search Form (Modern, Glassmorphic, 100% Dark Guaranteed) -->
            <div class="max-w-2xl mx-auto mt-8">
                <form action="{{ route('articles.index') }}" method="GET" class="relative">
                    @if(request('category') && request('category') !== 'Semua')
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative flex items-center">
                        <i class="fas fa-search absolute left-4 text-emerald-400 text-sm pointer-events-none z-10"></i>
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Cari artikel (contoh: Diamond ML, Aplikasi Premium, QRIS)..."
                               class="article-search-input w-full pl-11 pr-28 py-3.5 sm:py-4 rounded-2xl text-sm placeholder-slate-400 focus:outline-none shadow-2xl transition-all">
                        
                        @if(request('q'))
                            <a href="{{ route('articles.index', ['category' => request('category')]) }}"
                               class="absolute right-24 text-slate-400 hover:text-white transition p-1" title="Hapus pencarian">
                                <i class="fas fa-times-circle text-base"></i>
                            </a>
                        @endif

                        <button type="submit"
                                class="absolute right-2 px-5 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs sm:text-sm font-black rounded-xl shadow-md shadow-emerald-600/30 transition-all duration-200">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 flex-1 w-full">

        <!-- Modern Category Tabs Navigation -->
        <div class="mb-10">
            <div class="flex items-center gap-2.5 overflow-x-auto pb-3 no-scrollbar">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($selectedCategory === $cat) || ($cat === 'Semua' && empty($selectedCategory));
                        $icon = $catIcons[$cat] ?? 'fa-tag';
                    @endphp
                    <a href="{{ route('articles.index', ['category' => $cat !== 'Semua' ? $cat : null, 'q' => request('q')]) }}"
                       class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-extrabold whitespace-nowrap transition-all duration-200 flex items-center gap-2 {{ $isActive ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/25 ring-1 ring-emerald-400/40 scale-[1.02]' : 'bg-slate-900/90 text-slate-300 hover:text-white hover:bg-slate-800/90 border border-slate-800/90 hover:border-slate-700' }}">
                        <i class="fas {{ $icon }} {{ $isActive ? 'text-white' : 'text-emerald-400' }}"></i>
                        <span>{{ $cat }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Search Status / Filter Active Bar -->
        @if(request('q'))
            <div class="mb-8 p-4 rounded-2xl bg-slate-900/90 border border-emerald-500/30 flex items-center justify-between text-xs sm:text-sm shadow-lg">
                <div class="flex items-center gap-2 text-slate-300">
                    <i class="fas fa-filter text-emerald-400"></i>
                    <span>Hasil pencarian kata kunci: <strong class="text-white">"{{ request('q') }}"</strong> ({{ $articles->total() }} artikel ditemukan)</span>
                </div>
                <a href="{{ route('articles.index', ['category' => request('category')]) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-400 hover:text-emerald-300 font-bold transition flex items-center gap-1.5">
                    <i class="fas fa-undo text-xs"></i> Reset Filter
                </a>
            </div>
        @endif

        <!-- Articles Grid (Polished, Non-Rigid, Modern 3D Lift Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
            @forelse($articles as $article)
                @php
                    $badgeStyle = $catBadgeStyles[$article->category] ?? 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30';
                @endphp
                <article class="bg-gradient-to-b from-slate-900/95 to-slate-950/95 hover:from-slate-900 hover:to-slate-900 rounded-3xl border border-slate-800/90 hover:border-emerald-500/40 overflow-hidden flex flex-col group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-950/40">
                    <!-- Thumbnail with Gradient Vignette -->
                    <a href="{{ route('articles.show', $article->slug) }}" class="relative aspect-[16/10] overflow-hidden bg-slate-950 block">
                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-900 to-indigo-950 text-4xl">
                                🎮
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-transparent to-transparent opacity-75"></div>

                        <!-- Category Badge -->
                        <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full text-[11px] font-extrabold tracking-wide uppercase backdrop-blur-md border shadow-md {{ $badgeStyle }}">
                            {{ $article->category }}
                        </span>

                        <!-- Reading Time Pill -->
                        <span class="absolute top-3.5 right-3.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-950/80 backdrop-blur-md text-slate-300 border border-slate-800/80 shadow-md">
                            <i class="far fa-clock text-amber-400 mr-1"></i> {{ $article->reading_time }} mnt
                        </span>
                    </a>

                    <!-- Card Body -->
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <!-- Meta Row -->
                            <div class="flex items-center gap-3 text-[11px] text-slate-400 mb-3">
                                <span><i class="far fa-calendar-alt mr-1 text-slate-400"></i> {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                                <span>•</span>
                                <span><i class="far fa-eye mr-1 text-sky-400"></i> {{ number_format($article->views) }} views</span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base sm:text-lg font-extrabold text-white group-hover:text-emerald-400 transition leading-snug mb-3 line-clamp-2">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-xs text-slate-300/80 line-clamp-3 mb-4 leading-relaxed">
                                {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-[10px] font-black">
                                    {{ strtoupper(substr($article->author_name, 0, 1)) }}
                                </div>
                                <span class="text-slate-400 text-[11px] font-semibold truncate max-w-[120px]">
                                    {{ $article->author_name }}
                                </span>
                            </div>

                            <a href="{{ route('articles.show', $article->slug) }}" class="text-emerald-400 group-hover:text-emerald-300 font-extrabold inline-flex items-center gap-1.5 transition">
                                <span>Baca Artikel</span>
                                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 px-6 text-center bg-slate-900/60 rounded-3xl border border-slate-800">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-800/80 border border-slate-700 flex items-center justify-center text-3xl">
                        🔍
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-white mb-2">
                        Tidak Ada Artikel Ditemukan
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-md mx-auto mb-6">
                        Kami tidak dapat menemukan artikel yang sesuai dengan filter atau kata kunci pencarian Anda. Silakan coba kata kunci lain.
                    </p>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-emerald-600/30">
                        <i class="fas fa-layer-group"></i> Lihat Semua Artikel
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @endif

        <!-- Floating High-Conversion Banner -->
        <section class="mt-16 sm:mt-20 relative overflow-hidden bg-gradient-to-r from-emerald-950/70 via-slate-900 to-teal-950/70 border border-emerald-500/30 rounded-3xl p-6 sm:p-10 text-center shadow-2xl">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -left-24 w-60 h-60 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-60 h-60 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    ⚡ Pengiriman Kilat 24 Jam Nonstop
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    Top Up Game & Berlangganan Aplikasi Jadi Lebih Hemat
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Nikmati kemudahan transaksi instan via QRIS, harga termurah langsung dari tangan pertama, dan 100% legal garansi resmi.
                </p>
                <div class="pt-3 flex flex-wrap justify-center gap-3">
                    <a href="{{ url('/#games') }}" class="px-6 py-3 rounded-2xl text-xs sm:text-sm font-black text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                        <i class="fas fa-gamepad"></i> Mulai Top Up Sekarang
                    </a>
                    <a href="{{ url('/aplikasi-premium') }}" class="px-6 py-3 rounded-2xl text-xs sm:text-sm font-bold text-slate-200 bg-slate-800/90 hover:bg-slate-700 border border-slate-700 transition flex items-center gap-2">
                        <i class="fas fa-crown text-amber-400"></i> Beli Aplikasi Premium
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 text-slate-500 py-10 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-9 w-auto">
                <div class="flex flex-col">
                    <span class="logo-font text-white text-base tracking-wider">TOTAP STORE</span>
                    <span class="text-[10px] text-slate-400">Penyedia Top Up Game & Aplikasi Premium Terpercaya</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4 text-slate-400 text-xs font-medium">
                <a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a>
                <span>•</span>
                <a href="{{ route('articles.index') }}" class="text-emerald-400 font-bold hover:underline">Pusat Artikel & Panduan</a>
                <span>•</span>
                <a href="{{ url('/#games') }}" class="hover:text-white transition">Top Up Games</a>
                <span>•</span>
                <a href="{{ url('/aplikasi-premium') }}" class="hover:text-white transition">Aplikasi Premium</a>
            </div>
            <div class="text-slate-400 text-center md:text-right">
                &copy; {{ date('Y') }} ToTap Store. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>