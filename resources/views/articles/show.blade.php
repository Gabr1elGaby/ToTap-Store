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
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "{{ addslashes($article->title) }}",
      "image": [
        "{{ $article->thumbnail ?? asset('images/logo-totap-v2.png') }}"
      ],
      "datePublished": "{{ $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String() }}",
      "dateModified": "{{ $article->updated_at->toIso8601String() }}",
      "author": {
        "@type": "Person",
        "name": "{{ addslashes($article->author_name) }}"
      },
      "publisher": {
        "@type": "Organization",
        "name": "ToTap Store",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('images/logo-totap-v2.png') }}"
        }
      },
      "description": "{{ addslashes($article->meta_description ?? Str::limit(strip_tags($article->excerpt ?? $article->content), 160)) }}"
    }
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

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .logo-font { font-family: 'Righteous', cursive; }

        /* Custom rich article typography */
        .article-content h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            margin-top: 2rem;
            margin-bottom: 0.85rem;
            line-height: 1.3;
            border-bottom: 1px solid rgba(51, 65, 85, 0.6);
            padding-bottom: 0.5rem;
        }
        .article-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #34d399;
            margin-top: 1.5rem;
            margin-bottom: 0.6rem;
            line-height: 1.35;
        }
        .article-content p {
            font-size: 0.95rem;
            line-height: 1.75;
            color: #cbd5e1;
            margin-bottom: 1.25rem;
        }
        .article-content ul, .article-content ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
            color: #cbd5e1;
            font-size: 0.95rem;
            line-height: 1.75;
        }
        .article-content ul { list-style-type: disc; }
        .article-content ol { list-style-type: decimal; }
        .article-content li { margin-bottom: 0.5rem; }
        .article-content strong { color: #f8fafc; font-weight: 700; }
        .article-content a {
            color: #34d399;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        .article-content a:hover { color: #6ee7b7; }
        .article-content blockquote {
            border-left: 4px solid #10b981;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #94a3b8;
            background: rgba(15, 23, 42, 0.6);
            border-radius: 0 0.75rem 0.75rem 0;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .article-content code {
            background-color: #0f172a;
            color: #38bdf8;
            padding: 0.15rem 0.4rem;
            border-radius: 0.35rem;
            font-size: 0.85em;
            border: 1px solid #334155;
            font-family: monospace;
        }
        .article-content img {
            border-radius: 1rem;
            margin: 1.5rem auto;
            max-width: 100%;
            height: auto;
            border: 1px solid #334155;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-emerald-500 selection:text-white">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVR2XKKS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 text-white text-[11px] sm:text-xs py-2 px-4 text-center font-bold tracking-wide shadow-sm flex items-center justify-center gap-2">
        <span>⚡ Butuh Top Up Instan? Proses 24 Jam Otomatis via QRIS</span>
        <a href="{{ url('/') }}" class="underline hover:text-emerald-200 transition">Beli Sekarang →</a>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-10 w-auto object-contain drop-shadow group-hover:scale-105 transition-transform duration-200">
                <span class="logo-font text-xl text-white tracking-wider">TOTAP STORE</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('articles.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-800 transition flex items-center gap-1.5">
                    <i class="fas fa-book-open"></i> Semua Artikel
                </a>
                <a href="{{ url('/#games') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-sm shadow-emerald-500/20 flex items-center gap-1.5">
                    <i class="fas fa-shopping-cart"></i> Belanja
                </a>
            </div>
        </div>
    </header>

    <!-- Main Article Body -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 flex-1 w-full">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6 flex-wrap" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-emerald-400 transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('articles.index') }}" class="hover:text-emerald-400 transition">Artikel</a>
            <span>/</span>
            <a href="{{ route('articles.index', ['category' => $article->category]) }}" class="text-emerald-400 hover:underline">{{ $article->category }}</a>
        </nav>

        <!-- Article Header -->
        <header class="mb-8">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                    {{ $article->category }}
                </span>
                <span class="text-xs text-slate-400 flex items-center gap-1.5">
                    <i class="far fa-clock"></i> {{ $article->reading_time }} menit baca
                </span>
                <span class="text-xs text-slate-400 flex items-center gap-1.5">
                    <i class="far fa-eye"></i> {{ number_format($article->views) }} kali dibaca
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-snug mb-4">
                {{ $article->title }}
            </h1>

            <div class="flex items-center justify-between py-4 border-y border-slate-800 text-xs text-slate-400 flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-bold">
                        {{ strtoupper(substr($article->author_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-white">{{ $article->author_name }}</div>
                        <div class="text-[11px] text-slate-400">
                            Dipublikasikan {{ $article->published_at ? $article->published_at->format('d F Y') : $article->created_at->format('d F Y') }}
                        </div>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="flex items-center gap-2">
                    <span class="text-[11px] text-slate-400 font-bold mr-1">Bagikan:</span>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" title="Share ke WhatsApp" class="w-8 h-8 rounded-lg bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600 hover:text-white transition flex items-center justify-center">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" title="Share ke X / Twitter" class="w-8 h-8 rounded-lg bg-sky-600/20 text-sky-400 hover:bg-sky-600 hover:text-white transition flex items-center justify-center">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" title="Share ke Facebook" class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link artikel berhasil disalin!');" title="Salin Link" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition flex items-center justify-center">
                        <i class="fas fa-link text-xs"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($article->thumbnail)
            <div class="mb-8 rounded-2xl overflow-hidden border border-slate-800 shadow-xl max-h-[440px] bg-slate-900">
                <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Excerpt Callout -->
        @if($article->excerpt)
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-900/90 border-l-4 border-emerald-500 mb-8 text-slate-300 text-sm leading-relaxed shadow-sm">
                <strong>Ringkasan:</strong> {{ $article->excerpt }}
            </div>
        @endif

        <!-- Article Content -->
        <div class="article-content bg-slate-900/40 p-6 sm:p-8 rounded-3xl border border-slate-800/80 mb-10">
            {!! $article->content !!}
        </div>

        <!-- High Conversion CTA Card -->
        <div class="bg-gradient-to-r from-emerald-900/60 via-slate-900 to-indigo-900/60 border-2 border-emerald-500/40 rounded-3xl p-6 sm:p-8 text-center space-y-4 shadow-2xl relative overflow-hidden my-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                <span>🛡️</span> Garansi Resmi 100% Anti Banned
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-white">
                Mau Top Up Game & Beli Aplikasi Premium Murah?
            </h3>
            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto">
                Beli langsung di ToTap Store! Pembayaran otomatis via QRIS (BCA, BRI, Mandiri, DANA, GoPay, OVO, ShopeePay) 24 jam nonstop.
            </p>
            <div class="pt-2 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/#games') }}" class="px-6 py-3 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-500 transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                    <i class="fas fa-gamepad"></i> Top Up Game Sekarang
                </a>
                <a href="{{ url('/aplikasi-premium') }}" class="px-6 py-3 rounded-xl text-xs font-bold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 transition">
                    Beli Aplikasi Premium
                </a>
            </div>
        </div>

        <!-- Share Bottom -->
        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-900 border border-slate-800 mb-12">
            <span class="text-xs text-slate-400 font-bold">Apakah artikel ini bermanfaat?</span>
            <div class="flex items-center gap-2">
                <a href="https://api.whatsapp.com/send?text={{ urlencode('Baca artikel bermanfaat ini: ' . $article->title . ' ' . url()->current()) }}" target="_blank" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 transition flex items-center gap-1.5">
                    <i class="fab fa-whatsapp"></i> Bagikan ke Teman
                </a>
            </div>
        </div>

        <!-- Related Articles -->
        @if($relatedArticles->isNotEmpty())
            <section class="border-t border-slate-800 pt-10">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                    <span class="text-emerald-400">📖</span> Artikel Terkait Lainnya
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $rel)
                        <a href="{{ route('articles.show', $rel->slug) }}" class="bg-slate-900/70 rounded-2xl border border-slate-800 hover:border-slate-700 overflow-hidden group flex flex-col transition hover:-translate-y-1">
                            <div class="aspect-video bg-slate-950 overflow-hidden relative">
                                @if($rel->thumbnail)
                                    <img src="{{ $rel->thumbnail }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">🎮</div>
                                @endif
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[9px] font-bold bg-slate-900/90 text-emerald-400">
                                    {{ $rel->category }}
                                </span>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h4 class="text-xs font-bold text-white group-hover:text-emerald-400 transition line-clamp-2 mb-2">
                                    {{ $rel->title }}
                                </h4>
                                <span class="text-[10px] text-slate-400">
                                    {{ $rel->published_at ? $rel->published_at->format('d M Y') : $rel->created_at->format('d M Y') }} • {{ $rel->reading_time }} mnt baca
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 text-slate-500 py-8 text-xs mt-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
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
