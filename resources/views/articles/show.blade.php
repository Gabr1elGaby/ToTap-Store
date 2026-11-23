<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Primary Meta Tags -->
    <title>{{ $article->meta_title ?? $article->title . ' - ToTap Store' }}</title>
    <meta name="title" content="{{ $article->meta_title ?? $article->title . ' - ToTap Store' }}">
    <meta name="description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->excerpt ?? $article->content), 160) }}">
    @if($article->meta_keywords)
        <meta name="keywords" content="{{ $article->meta_keywords }}">
    @endif
    <meta name="author" content="{{ $article->author_name }}">
    <link rel="canonical" href="{{ route('articles.show', $article->slug) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('articles.show', $article->slug) }}">
    <meta property="og:title" content="{{ $article->meta_title ?? $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->excerpt ?? $article->content), 160) }}">
    <meta property="og:image" content="{{ $article->thumbnail ?? asset('images/logo-totap-v2.png') }}">
    <meta property="article:published_time" content="{{ $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $article->author_name }}">
    <meta property="article:section" content="{{ $article->category }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('articles.show', $article->slug) }}">
    <meta name="twitter:title" content="{{ $article->meta_title ?? $article->title }}">
    <meta name="twitter:description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->excerpt ?? $article->content), 160) }}">
    <meta name="twitter:image" content="{{ $article->thumbnail ?? asset('images/logo-totap-v2.png') }}">

    <!-- Schema.org JSON-LD Structured Data for Google -->
    <script type="application/ld+json">
    {!! json_encode([
        chr(64) . 'context' => 'https://schema.org',
        chr(64) . 'type' => 'Article',
        'headline' => $article->title,
        'image' => [
            $article->thumbnail ?? asset('images/logo-totap-v2.png')
        ],
        'datePublished' => $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String(),
        'dateModified' => $article->updated_at->toIso8601String(),
        'author' => [
            chr(64) . 'type' => 'Person',
            'name' => $article->author_name,
        ],
        'publisher' => [
            chr(64) . 'type' => 'Organization',
            'name' => 'ToTap Store',
            'logo' => [
                chr(64) . 'type' => 'ImageObject',
                'url' => asset('images/logo-totap-v2.png'),
            ]
        ],
        'description' => $article->meta_description ?? Str::limit(strip_tags($article->excerpt ?? $article->content), 160),
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

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

        /* Reading Progress Indicator */
        #reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(to right, #10b981, #14b8a6, #06b6d4);
            z-index: 100;
            transition: width 0.1s ease-out;
        }

        /* Typography enhancements for article body */
        .article-content {
            font-size: 1.05rem;
            line-height: 1.85;
            color: #cbd5e1;
        }
        .article-content h2 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #ffffff;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            line-height: 1.3;
            letter-spacing: -0.02em;
            position: relative;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.6);
        }
        .article-content h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 60px;
            height: 2px;
            background: #10b981;
            border-radius: 2px;
        }
        .article-content h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #34d399;
            margin-top: 1.85rem;
            margin-bottom: 0.75rem;
            line-height: 1.35;
        }
        .article-content p {
            margin-bottom: 1.4rem;
        }
        .article-content ul, .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
            color: #cbd5e1;
        }
        .article-content ul { list-style-type: disc; }
        .article-content ol { list-style-type: decimal; }
        .article-content li { margin-bottom: 0.6rem; }
        .article-content strong { color: #f8fafc; font-weight: 700; }
        .article-content a {
            color: #34d399;
            text-decoration: underline;
            text-underline-offset: 4px;
            font-weight: 600;
            transition: color 0.15s ease;
        }
        .article-content a:hover { color: #6ee7b7; }
        .article-content blockquote {
            border-left: 4px solid #10b981;
            padding: 1rem 1.25rem;
            margin: 1.75rem 0;
            font-style: italic;
            color: #94a3b8;
            background: rgba(15, 23, 42, 0.75);
            border-radius: 0 1rem 1rem 0;
        }
        .article-content code {
            background-color: #0f172a;
            color: #38bdf8;
            padding: 0.2rem 0.45rem;
            border-radius: 0.375rem;
            font-size: 0.88em;
            border: 1px solid #334155;
            font-family: monospace;
        }
        .article-content img {
            border-radius: 1.25rem;
            margin: 2rem auto;
            max-width: 100%;
            height: auto;
            border: 1px solid #334155;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white"
      x-data="{ showLogin: false, showRegister: false }"
      @open-login.window="showLogin = true"
      @open-register.window="showRegister = true"
      :class="{ 'overflow-hidden': showLogin || showRegister }">
    <!-- Reading Progress Bar -->
    <div id="reading-progress"></div>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVR2XKKS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- Official Standard ToTap Store Navbar (Consistent, Solid, No Bleed-Through) -->
    @include('layouts.navigation')

    @php
        $catMeta = [
            'Panduan & Tips' => ['icon' => 'fa-lightbulb', 'color' => 'text-amber-400', 'badge' => 'bg-amber-500/15 text-amber-300 border-amber-500/30'],
            'Tips Game' => ['icon' => 'fa-gamepad', 'color' => 'text-sky-400', 'badge' => 'bg-sky-500/15 text-sky-300 border-sky-500/30'],
            'Review & Keamanan' => ['icon' => 'fa-shield-alt', 'color' => 'text-emerald-400', 'badge' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
            'Promo' => ['icon' => 'fa-fire', 'color' => 'text-rose-400', 'badge' => 'bg-rose-500/15 text-rose-300 border-rose-500/30'],
            'Berita' => ['icon' => 'fa-bullhorn', 'color' => 'text-purple-400', 'badge' => 'bg-purple-500/15 text-purple-300 border-purple-500/30'],
        ];
        $currentBadge = $catMeta[$article->category]['badge'] ?? 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30';
    @endphp

    <!-- Main Article Body -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex-1 w-full">

        <!-- Top Navigation / Breadcrumbs -->
        <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
            <nav class="flex items-center gap-2 text-xs text-slate-400 flex-wrap" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-emerald-400 transition flex items-center gap-1">
                    <i class="fas fa-home text-[11px]"></i> Beranda
                </a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('articles.index') }}" class="hover:text-emerald-400 transition">Artikel</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('articles.index', ['category' => $article->category]) }}" class="text-emerald-400 hover:underline font-semibold">
                    {{ $article->category }}
                </a>
            </nav>

            <a href="{{ route('articles.index') }}" class="text-xs font-bold text-slate-400 hover:text-white inline-flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left text-[10px]"></i> Semua Artikel
            </a>
        </div>

        <!-- Article Header -->
        <header class="mb-8">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider backdrop-blur-md border shadow-sm {{ $currentBadge }}">
                    {{ $article->category }}
                </span>
                <span class="text-xs text-slate-400 flex items-center gap-1.5 bg-slate-900/80 px-3 py-1 rounded-full border border-slate-800">
                    <i class="far fa-clock text-amber-400"></i> {{ $article->reading_time }} menit baca
                </span>
                <span class="text-xs text-slate-400 flex items-center gap-1.5 bg-slate-900/80 px-3 py-1 rounded-full border border-slate-800">
                    <i class="far fa-eye text-sky-400"></i> {{ number_format($article->views) }} kali dibaca
                </span>
            </div>

            <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight sm:leading-tight mb-6">
                {{ $article->title }}
            </h1>

            <!-- Author & Share Info Bar -->
            <div class="flex items-center justify-between py-4 px-5 rounded-2xl bg-slate-900/80 border border-slate-800/90 text-xs text-slate-300 flex-wrap gap-4 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500/20 to-teal-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400 font-black text-sm">
                        {{ strtoupper(substr($article->author_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-extrabold text-white text-sm flex items-center gap-1.5">
                            {{ $article->author_name }}
                            <i class="fas fa-check-circle text-emerald-400 text-xs" title="Penulis Resmi ToTap Store"></i>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            Dipublikasikan {{ $article->published_at ? $article->published_at->format('d F Y, H:i') : $article->created_at->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-400 font-bold mr-1 hidden sm:inline">Bagikan:</span>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - Baca selengkapnya di: ' . url()->current()) }}" target="_blank" title="Bagikan ke WhatsApp" class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600 hover:text-white transition flex items-center justify-center">
                        <i class="fab fa-whatsapp text-sm"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" title="Bagikan ke X / Twitter" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" title="Bagikan ke Facebook" class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                        <i class="fab fa-facebook-f text-xs"></i>
                    </a>
                    <button onclick="copyArticleLink()" title="Salin Tautan Artikel" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-300 hover:bg-emerald-600 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-link text-xs"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($article->thumbnail)
            <div class="mb-8 rounded-3xl overflow-hidden border border-slate-800 shadow-2xl max-h-[480px] bg-slate-900 group">
                <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
            </div>
        @endif

        <!-- Excerpt Callout -->
        @if($article->excerpt)
            <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-slate-900/90 to-slate-900/60 border-l-4 border-emerald-500 mb-8 text-slate-200 text-sm sm:text-base leading-relaxed shadow-lg">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-1">
                    📌 Ringkasan Penting
                </span>
                {{ $article->excerpt }}
            </div>
        @endif

        <!-- Article Main Content -->
        <div class="article-content bg-slate-900/50 p-6 sm:p-10 rounded-3xl border border-slate-800/80 mb-10 shadow-xl">
            {!! $article->content !!}
        </div>

        <!-- High Conversion Store CTA Card -->
        <div class="bg-gradient-to-br from-emerald-950/70 via-slate-900 to-indigo-950/70 border-2 border-emerald-500/40 rounded-3xl p-6 sm:p-10 text-center space-y-4 shadow-2xl relative overflow-hidden my-12">
            <!-- Glow effect -->
            <div class="absolute -top-16 -left-16 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    <span>🛡️</span> Garansi Resmi 100% Anti Banned & Pengiriman Instan
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-white">
                    Siap Jadi Sultan Tanpa Khawatir Boncos?
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
                    Beli diamond game & langganan aplikasi premium favorit Anda di ToTap Store. Pembayaran otomatis via QRIS (BCA, Mandiri, BRI, DANA, GoPay, ShopeePay) proses kilat 24 jam nonstop!
                </p>
                <div class="pt-3 flex flex-wrap justify-center gap-3">
                    <a href="{{ url('/#games') }}" class="px-6 py-3 rounded-xl text-xs sm:text-sm font-black text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                        <i class="fas fa-gamepad"></i> Top Up Game Sekarang
                    </a>
                    <a href="{{ url('/aplikasi-premium') }}" class="px-6 py-3 rounded-xl text-xs sm:text-sm font-bold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 transition flex items-center gap-2">
                        <i class="fas fa-crown text-amber-400"></i> Beli Aplikasi Premium
                    </a>
                </div>
            </div>
        </div>

        <!-- Feedback & Share Bottom -->
        <div class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-slate-900 border border-slate-800 mb-12 flex-wrap gap-4 shadow-md">
            <div>
                <span class="text-xs sm:text-sm text-white font-extrabold block">Apakah artikel ini bermanfaat bagi Anda?</span>
                <span class="text-[11px] text-slate-400">Bantu teman mabar Anda dengan membagikan panduan ini!</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="https://api.whatsapp.com/send?text={{ urlencode('Baca artikel bermanfaat ini: ' . $article->title . ' ' . url()->current()) }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-extrabold bg-emerald-600 text-white hover:bg-emerald-500 transition flex items-center gap-2 shadow-md shadow-emerald-600/20">
                    <i class="fab fa-whatsapp text-sm"></i> Bagikan ke Teman
                </a>
            </div>
        </div>

        <!-- Related Articles -->
        @if($relatedArticles->isNotEmpty())
            <section class="border-t border-slate-800/80 pt-10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg sm:text-xl font-extrabold text-white flex items-center gap-2">
                        <span>📖</span> Artikel Terkait Lainnya
                    </h3>
                    <a href="{{ route('articles.index', ['category' => $article->category]) }}" class="text-xs text-emerald-400 font-bold hover:underline">
                        Lihat Kategori Ini →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $rel)
                        @php
                            $relBadge = $catMeta[$rel->category]['badge'] ?? 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30';
                        @endphp
                        <a href="{{ route('articles.show', $rel->slug) }}" class="bg-slate-900/80 hover:bg-slate-900 rounded-3xl border border-slate-800 hover:border-emerald-500/40 overflow-hidden group flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-950/40">
                            <div class="aspect-[16/10] bg-slate-950 overflow-hidden relative">
                                @if($rel->thumbnail)
                                    <img src="{{ $rel->thumbnail }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">🎮</div>
                                @endif
                                <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase backdrop-blur-md border {{ $relBadge }}">
                                    {{ $rel->category }}
                                </span>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h4 class="text-xs sm:text-sm font-extrabold text-white group-hover:text-emerald-400 transition line-clamp-2 mb-2 leading-snug">
                                    {{ $rel->title }}
                                </h4>
                                <div class="text-[10px] text-slate-400 flex items-center justify-between pt-2 border-t border-slate-800/60">
                                    <span>{{ $rel->published_at ? $rel->published_at->format('d M Y') : $rel->created_at->format('d M Y') }}</span>
                                    <span><i class="far fa-clock text-amber-400 mr-1"></i> {{ $rel->reading_time }} mnt</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- Floating Copy Toast Notification -->
    <div id="toast-notification" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 pointer-events-none transition-all duration-300 ease-out">
        <div class="bg-emerald-600 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-emerald-400/30 text-xs font-bold">
            <i class="fas fa-check-circle text-base"></i>
            <span>Tautan artikel berhasil disalin ke clipboard!</span>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 text-slate-500 py-10 text-xs mt-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-9 w-auto">
                <div class="flex flex-col">
                    <span class="logo-font text-white text-base tracking-wider">TOTAP STORE</span>
                    <span class="text-[10px] text-slate-400">Penyedia Top Up Game & Aplikasi Premium Terpercaya</span>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4 text-slate-400 font-medium">
                <a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a>
                <span>•</span>
                <a href="{{ route('articles.index') }}" class="text-emerald-400 font-bold hover:underline">Semua Artikel</a>
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

    <!-- Reading Progress and Copy Script -->
    <script>
        // Reading Progress Indicator
        window.addEventListener('scroll', () => {
            const docElement = document.documentElement;
            const docBody = document.body;
            const scrollTop = docElement['scrollTop'] || docBody['scrollTop'];
            const scrollBottom = (docElement['scrollHeight'] || docBody['scrollHeight']) - window.innerHeight;
            const scrollPercent = scrollBottom > 0 ? (scrollTop / scrollBottom) * 100 : 0;
            const progressBar = document.getElementById('reading-progress');
            if (progressBar) {
                progressBar.style.width = scrollPercent + '%';
            }
        });

        // Copy Article Link with Smooth Toast
        function copyArticleLink() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast();
                }).catch(() => {
                    fallbackCopy();
                });
            } else {
                fallbackCopy();
            }
        }

        function fallbackCopy() {
            const input = document.createElement('input');
            input.value = window.location.href;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showToast();
        }

        function showToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                toast.classList.add('translate-y-0', 'opacity-100');
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                }, 3000);
            }
        }
    </script>
    <!-- Auth Modals -->
    @include('components.auth-modals')
</body>
</html>