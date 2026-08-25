<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pembuat CV & Resume Profesional - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    
    <!-- Early Theme Initialization -->
    <script>
        if (localStorage.getItem('totap_theme') === 'light') {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.backgroundColor = '#f8fafc';
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.style.backgroundColor = '#111827';
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Righteous&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white font-sans antialiased min-h-screen transition-colors duration-200" 
      x-data="{ showLogin: false, showRegister: false, previewOpen: false, previewSlug: '', activeLang: 'all', scale: 1 }" 
      @open-login.window="showLogin = true" 
      @open-register.window="showRegister = true" 
      :class="{ 'overflow-hidden': showLogin || showRegister || previewOpen }">
    
    <!-- Navbar -->
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ url('/software') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-xl border border-gray-300 dark:border-gray-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 mb-3">
                <i class="fas fa-file-alt"></i> CV & Resume Builder ATS-Friendly Bilingual
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                Pilih Template CV & Resume Profesional
            </h1>
            <p class="text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Tersedia format khusus standar <strong>Bahasa Indonesia (BUMN & Nasional)</strong> dan standar <strong>Bahasa Inggris (International & Global ATS)</strong> yang sesuai dengan kaidah rekrutmen HRD.
            </p>
        </div>

        <!-- INTERACTIVE LANGUAGE FILTER TABS -->
        <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
            <button @click="activeLang = 'all'"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center gap-2 shadow-sm"
                    :class="activeLang === 'all' ? 'bg-indigo-600 text-white shadow-indigo-600/30' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <i class="fas fa-layer-group"></i> Semua Template ({{ count($templates) }})
            </button>

            <button @click="activeLang = 'id'"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center gap-2 shadow-sm"
                    :class="activeLang === 'id' ? 'bg-red-600 text-white shadow-red-600/30' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <span>🇮🇩</span> Bahasa Indonesia (Standar BUMN & Nasional)
            </button>

            <button @click="activeLang = 'en'"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center gap-2 shadow-sm"
                    :class="activeLang === 'en' ? 'bg-blue-600 text-white shadow-blue-600/30' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'">
                <span>🇬🇧</span> English Resume (International & Global ATS)
            </button>
        </div>

        <!-- EDUCATIONAL GUIDANCE CARDS -->
        <div class="mb-10 bg-gradient-to-r from-slate-100 to-indigo-50/50 dark:from-gray-800/80 dark:to-indigo-950/30 rounded-2xl p-6 border border-gray-200 dark:border-gray-700/80 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <!-- ID Info -->
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-base flex-shrink-0 shadow-sm">
                        🇮🇩
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Standar CV Bahasa Indonesia</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Dirancang khusus untuk melamar di <strong>BUMN, CPNS/Instansi Pemerintah, Korporat Swasta, dan Startup Indonesia</strong>. Mengikuti tata letak baku: Data Pribadi lengkap, Ringkasan Profil, Pengalaman Kerja, Riwayat Pendidikan & IPK, Pengalaman Organisasi Kampus/Masyarakat, dan Sertifikasi Profesi (BNSP).
                        </p>
                    </div>
                </div>

                <!-- EN Info -->
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-base flex-shrink-0 shadow-sm">
                        🇬🇧
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">International English Resume Rules</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Strictly complies with <strong>US/UK/EU Recruiter Standards & Global ATS Scanners</strong>. Highlights Quantifiable Achievements (XYZ Method & Action Verbs), Tech Stack/Projects Portfolio, Core Competencies, without personal sensitive data (No DOB/marital status).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid of CV Templates -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($templates as $template)
                @php
                    $tLang = $template->language ?? (str_starts_with($template->slug, 'en-') ? 'en' : 'id');
                @endphp
                <div x-show="activeLang === 'all' || activeLang === '{{ $tLang }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl overflow-hidden flex flex-col group hover:border-indigo-500 transition-all duration-300 transform hover:-translate-y-1">
                    
                    <!-- Preview Container -->
                    <div class="relative bg-slate-100 dark:bg-gray-950 overflow-hidden cursor-pointer h-72 flex items-start justify-center border-b border-gray-200 dark:border-gray-700 group"
                         @click="previewOpen = true; previewSlug = '{{ $template->slug }}'">
                        
                        <!-- Mini Interactive Iframe / Thumbnail Centered -->
                        <div class="w-[794px] h-[1123px] transform scale-[0.44] origin-top pointer-events-none opacity-90 group-hover:opacity-100 group-hover:scale-[0.46] transition duration-300 shadow-2xl bg-white flex-shrink-0 mt-3 rounded-t-lg overflow-hidden">
                            <iframe src="{{ route('cv.previewExample', $template->slug) }}" class="w-full h-full border-0 pointer-events-none"></iframe>
                        </div>

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-gray-900/30 group-hover:bg-gray-900/50 transition duration-300 flex items-center justify-center">
                            <span class="px-4 py-2 rounded-full font-bold text-xs bg-indigo-600 text-white shadow-lg shadow-indigo-600/40 opacity-0 group-hover:opacity-100 transition transform scale-90 group-hover:scale-100 flex items-center gap-2">
                                <i class="fas fa-eye"></i> Lihat Ukuran Penuh
                            </span>
                        </div>

                        <!-- Language Badge -->
                        <div class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-md flex items-center gap-1.5 {{ $tLang === 'en' ? 'bg-blue-600 text-white' : 'bg-red-600 text-white' }}">
                            <span>{{ $tLang === 'en' ? '🇬🇧 English' : '🇮🇩 Indonesia' }}</span>
                        </div>

                        <!-- Promo Badge -->
                        @if($template->price_normal && $template->price_normal > $template->price)
                            <div class="absolute top-3 left-3 bg-amber-500 text-white text-[11px] font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider">
                                Diskon {{ round((($template->price_normal - $template->price) / $template->price_normal) * 100) }}%
                            </div>
                        @endif
                    </div>

                    <!-- Template Info & Action -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $tLang === 'en' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $tLang === 'en' ? 'Global Standard Resume' : 'Standar Nasional Indonesia' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                {{ $template->name }}
                            </h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2">
                                {{ $template->description }}
                            </p>
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-700/80 pt-4 flex items-center justify-between">
                            <div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Biaya Unduh</div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                                        Rp{{ number_format($template->price, 0, ',', '.') }}
                                    </span>
                                    @if($template->price_normal && $template->price_normal > $template->price)
                                        <span class="text-xs text-gray-400 line-through">
                                            Rp{{ number_format($template->price_normal, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('cv.create', ['template' => $template->slug]) }}" 
                               class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/30 transition-all transform active:scale-95">
                                Gunakan <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-file-excel text-4xl mb-3"></i>
                    <p>Belum ada template yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- FULLSCREEN MODAL PREVIEW -->
    <div x-show="previewOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-2 sm:p-4" 
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 w-full max-w-5xl h-[92vh] flex flex-col overflow-hidden relative"
             @click.away="previewOpen = false">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between bg-slate-50 dark:bg-gray-950 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Pratinjau Template CV</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Format standar resolusi tinggi A4 (Siap Cetak / PDF)</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a :href="'/cv/create?template=' + previewSlug" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-700 text-white shadow-md transition">
                        <span>Pilih Template Ini</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>

                    <button @click="previewOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Iframe) -->
            <div class="flex-1 bg-slate-200 dark:bg-gray-950 p-4 sm:p-8 overflow-y-auto flex items-start justify-center">
                <div class="w-[794px] min-h-[1123px] bg-white shadow-2xl rounded-sm overflow-hidden flex-shrink-0">
                    <template x-if="previewOpen">
                        <iframe :src="'/cv/preview-example/' + previewSlug" class="w-[794px] min-h-[1123px] border-0"></iframe>
                    </template>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
