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

    <style>
        .a4-card-preview {
            width: 794px;
            height: 1123px;
            transform: scale(0.42);
            transform-origin: top center;
            pointer-events: none;
            background: white;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
            border-radius: 4px;
            overflow: hidden;
            flex-shrink: 0;
            margin-top: 10px;
        }
        .a4-modal-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 10px 0 30px 0;
            box-sizing: border-box;
        }
        .a4-modal-sheet {
            width: 794px;
            min-height: 1123px;
            background: white;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.85);
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            margin: 0 auto;
            display: block;
        }
        @media (max-width: 920px) {
            .a4-modal-sheet {
                transform: scale(0.85);
                transform-origin: top center;
                margin-bottom: -170px;
            }
        }
        @media (max-width: 768px) {
            .a4-modal-sheet {
                transform: scale(0.68);
                transform-origin: top center;
                margin-bottom: -360px;
            }
        }
        @media (max-width: 520px) {
            .a4-modal-sheet {
                transform: scale(0.46);
                transform-origin: top center;
                margin-bottom: -600px;
            }
        }

        /* Filter Tab Styles */
        .tab-btn-active {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35) !important;
        }
        .tab-btn-inactive {
            background-color: #ffffff;
            border-color: #e2e8f0;
            color: #334155;
        }
        .tab-btn-inactive:hover {
            background-color: #f1f5f9;
        }
        .dark .tab-btn-inactive {
            background-color: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }
        .dark .tab-btn-inactive:hover {
            background-color: #334155;
        }

        /* Explicit Guidance Cards Contrast (Light & Dark Mode) */
        .guide-card-indo {
            background-color: #ffffff;
            border: 1px solid #fecdd3;
            border-radius: 20px;
            padding: 26px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .dark .guide-card-indo {
            background-color: #1e293b;
            border: 1px solid #475569;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }

        .guide-card-en {
            background-color: #ffffff;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 26px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .dark .guide-card-en {
            background-color: #1e293b;
            border: 1px solid #475569;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }

        .guide-title {
            color: #0f172a;
            font-weight: 800;
            font-size: 18px;
        }
        .dark .guide-title {
            color: #ffffff;
        }

        .guide-sub {
            color: #64748b;
            font-size: 13.5px;
            margin-top: 3px;
        }
        .dark .guide-sub {
            color: #94a3b8;
        }

        .guide-list-item {
            color: #334155;
            font-size: 14px;
            line-height: 1.6;
        }
        .dark .guide-list-item {
            color: #cbd5e1;
        }
        .dark .guide-list-item strong {
            color: #0f172a;
        }
        .dark .guide-list-item strong {
            color: #f8fafc;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white font-sans antialiased min-h-screen transition-colors duration-200" 
      x-data="{ showLogin: false, showRegister: false, previewOpen: false, previewSlug: '', previewName: '', activeLang: 'all' }" 
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

        <!-- INTERACTIVE LANGUAGE FILTER TABS (UNIFIED VIBRANT BLUE ON ACTIVE) -->
        <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
            <!-- All Templates Tab -->
            <button @click="activeLang = 'all'"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center gap-2.5 shadow-sm border"
                    :class="activeLang === 'all' ? 'tab-btn-active' : 'tab-btn-inactive'">
                <i class="fas fa-layer-group text-base"></i> 
                <span>Semua Template ({{ count($templates) }})</span>
            </button>

            <!-- Indonesian Tab -->
            <button @click="activeLang = 'id'"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center gap-2.5 shadow-sm border"
                    :class="activeLang === 'id' ? 'tab-btn-active' : 'tab-btn-inactive'">
                <!-- Indonesian Vector Flag -->
                <span class="w-5 h-3.5 rounded-sm overflow-hidden shadow-sm inline-flex flex-col border border-black/10 flex-shrink-0">
                    <span class="h-1/2 bg-red-600 w-full"></span>
                    <span class="h-1/2 bg-white w-full"></span>
                </span>
                <span>Bahasa Indonesia (BUMN & Nasional)</span>
            </button>

            <!-- English Tab -->
            <button @click="activeLang = 'en'"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center gap-2.5 shadow-sm border"
                    :class="activeLang === 'en' ? 'tab-btn-active' : 'tab-btn-inactive'">
                <!-- Global / English Vector Icon -->
                <i class="fas fa-globe-americas text-base"></i>
                <span>Bahasa Inggris (International ATS)</span>
            </button>
        </div>

        <!-- EDUCATIONAL GUIDANCE CARDS (LARGER & HIGHLY LEGIBLE FONTS) -->
        <div class="mb-12 grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Indonesian CV Standards Card -->
            <div class="guide-card-indo">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div style="width: 52px; height: 52px; min-width: 52px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0;" class="bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shadow-sm">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 3px;">
                            <h3 class="guide-title">Standar CV Bahasa Indonesia</h3>
                            <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">BUMN & Lokal</span>
                        </div>
                        <p class="guide-sub">Untuk BUMN, CPNS / Instansi Pemerintah, Korporat Swasta & Startup</p>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div style="display: flex; align-items: flex-start;">
                        <i class="fas fa-check-circle text-rose-500" style="margin-right: 12px; margin-top: 4px; font-size: 15px; flex-shrink: 0;"></i>
                        <span class="guide-list-item"><strong>Data Pribadi Lengkap:</strong> Nama lengkap, kontak WhatsApp aktif, domisili (Kota/Provinsi), dan foto formal profesional.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start;">
                        <i class="fas fa-check-circle text-rose-500" style="margin-right: 12px; margin-top: 4px; font-size: 15px; flex-shrink: 0;"></i>
                        <span class="guide-list-item"><strong>Riwayat Pendidikan & IPK:</strong> Jenjang studi, jurusan, nama universitas/sekolah, dan nilai IPK/predikat kelulusan.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start;">
                        <i class="fas fa-check-circle text-rose-500" style="margin-right: 12px; margin-top: 4px; font-size: 15px; flex-shrink: 0;"></i>
                        <span class="guide-list-item"><strong>Organisasi & Sertifikasi BNSP:</strong> Sangat diutamakan kepanitiaan kampus, kegiatan kemahasiswaan, dan pelatihan profesi nasional.</span>
                    </div>
                </div>
            </div>

            <!-- English Resume Standards Card -->
            <div class="guide-card-en">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div style="width: 52px; height: 52px; min-width: 52px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-right: 18px; flex-shrink: 0;" class="bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 shadow-sm">
                        <i class="fas fa-globe-americas text-2xl"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 3px;">
                            <h3 class="guide-title">Standar Resume Bahasa Inggris</h3>
                            <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">Global & Remote</span>
                        </div>
                        <p class="guide-sub">Untuk Perusahaan Global, Startup Multinasional, Remote Work & Rekruter Luar Negeri</p>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div style="display: flex; align-items: flex-start;">
                        <i class="fas fa-check-circle text-blue-500" style="margin-right: 12px; margin-top: 4px; font-size: 15px; flex-shrink: 0;"></i>
                        <span class="guide-list-item"><strong>Quantifiable Achievements:</strong> Penjelasan pengalaman kerja dengan Action Verbs dan rumus XYZ (hasil terukur dalam angka/persentase).</span>
                    </div>
                    <div style="display: flex; align-items: flex-start;">
                        <i class="fas fa-check-circle text-blue-500" style="margin-right: 12px; margin-top: 4px; font-size: 15px; flex-shrink: 0;"></i>
                        <span class="guide-list-item"><strong>Projects & Tech Portfolio:</strong> Menampilkan portofolio proyek teknis, stack teknologi, dan tautan live demo / GitHub.</span>
                    </div>
                    <div style="display: flex; align-items: flex-start;">
                        <i class="fas fa-check-circle text-blue-500" style="margin-right: 12px; margin-top: 4px; font-size: 15px; flex-shrink: 0;"></i>
                        <span class="guide-list-item"><strong>100% Lolos ATS Global:</strong> Tanpa data sensitif (tanpa tanggal lahir/status pernikahan) agar memenuhi regulasi anti-diskriminasi internasional.</span>
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
                         @click="previewOpen = true; previewSlug = '{{ $template->slug }}'; previewName = '{{ addslashes($template->name) }}'">
                        
                        <!-- Mini Interactive Iframe / Thumbnail Centered -->
                        <div class="a4-card-preview opacity-90 group-hover:opacity-100 transition duration-300 shadow-2xl">
                            <iframe src="{{ route('cv.previewExample', $template->slug) }}" style="width: 794px; height: 1123px; border: 0;" class="pointer-events-none"></iframe>
                        </div>

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-gray-900/30 group-hover:bg-gray-900/50 transition duration-300 flex items-center justify-center">
                            <span class="px-4 py-2 rounded-full font-bold text-xs bg-indigo-600 text-white shadow-lg shadow-indigo-600/40 opacity-0 group-hover:opacity-100 transition transform scale-90 group-hover:scale-100 flex items-center gap-2">
                                <i class="fas fa-eye"></i> Lihat Ukuran Penuh
                            </span>
                        </div>

                        <!-- Promo Badge -->
                        @if($template->price_normal && $template->price_normal > $template->price)
                            <div style="position: absolute; top: 12px; left: 12px; background-color: #e11d48; color: #ffffff; font-size: 11px; font-weight: 900; padding: 4px 10px; border-radius: 8px; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.4); text-transform: uppercase; letter-spacing: 0.5px; z-index: 10;">
                                Diskon {{ round((($template->price_normal - $template->price) / $template->price_normal) * 100) }}%
                            </div>
                        @endif
                    </div>

                    <!-- Template Info & Action -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <!-- Language Category Tag -->
                            <div class="mb-2">
                                @if($tLang === 'en')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-0.5 rounded-md bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        <i class="fas fa-globe text-xs text-blue-500"></i> Bahasa Inggris • Global ATS
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-0.5 rounded-md bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        <i class="fas fa-flag text-xs text-rose-500"></i> Bahasa Indonesia • BUMN
                                    </span>
                                @endif
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

    <!-- FULLSCREEN MODAL PREVIEW (100% PERFECTLY CENTERED ON ALL SCREENS) -->
    <div x-show="previewOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(10, 15, 30, 0.85); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;" 
         x-cloak>
        
        <div style="background: #0f172a; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(0,0,0,0.9); border: 1px solid #334155; width: 100%; max-width: 900px; height: 94vh; max-height: 950px; display: flex; flex-direction: column; overflow: hidden; margin: auto; position: relative;"
             @click.away="previewOpen = false">
            
            <!-- Modal Header -->
            <div style="padding: 16px 24px; border-bottom: 1px solid #1e293b; display: flex; align-items: center; justify-content: space-between; background: #1e293b; flex-shrink: 0;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-600/30">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base" x-text="previewName || 'Pratinjau Template CV'"></h3>
                        <p class="text-xs text-slate-400">Pratinjau Dokumen Resolusi Tinggi Standar A4</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a :href="'/cv/create?template=' + previewSlug" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/40 transition transform active:scale-95">
                        <span>Pilih Template Ini</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>

                    <button @click="previewOpen = false" 
                            class="text-slate-400 hover:text-white w-9 h-9 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 transition">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Centered A4 Sheet with Zero Data Loss) -->
            <div style="flex: 1; overflow-y: auto; overflow-x: auto; background: #060913; width: 100%; box-sizing: border-box;">
                <div class="a4-modal-wrapper">
                    <div class="a4-modal-sheet">
                        <template x-if="previewOpen">
                            <iframe :src="'/cv/preview-example/' + previewSlug" 
                                    style="width: 794px; min-height: 1123px; height: 2300px; border: 0; display: block; background: white;"></iframe>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
