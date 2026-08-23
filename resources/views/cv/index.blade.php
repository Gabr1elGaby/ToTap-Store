<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pembuat CV Profesional - ToTap Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Righteous&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white font-sans antialiased min-h-screen" x-data="{ showLogin: false, showRegister: false, previewOpen: false, previewSlug: '', scale: 1 }" @open-login.window="showLogin = true" @open-register.window="showRegister = true" :class="{ 'overflow-hidden': showLogin || showRegister || previewOpen }">
    
    <!-- Navbar -->
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-4">
                <i class="fas fa-file-alt"></i> CV & Resume Builder ATS-Friendly
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 tracking-tight">
                Buat CV Profesionalmu
            </h1>
            <p class="text-base text-gray-400 max-w-2xl mx-auto">
                Pilih template favorit Anda, isi formulir dengan mudah, dan unduh CV format PDF standar HRD dalam hitungan menit.
            </p>
        </div>

        <!-- Grid of CV Templates -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($templates as $template)
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden flex flex-col group hover:border-indigo-500 transition-all duration-300 transform hover:-translate-y-1">
                    
                    <!-- Preview Container -->
                    <div class="relative bg-gray-950 overflow-hidden cursor-pointer h-72 flex items-center justify-center border-b border-gray-700 group"
                         @click="previewOpen = true; previewSlug = '{{ $template->slug }}'">
                        
                        <!-- Mini Interactive Iframe / Thumbnail -->
                        <div class="w-full h-full transform scale-[0.45] origin-top pointer-events-none opacity-90 group-hover:opacity-100 group-hover:scale-[0.48] transition duration-300">
                            <iframe src="{{ route('cv.previewExample', $template->slug) }}" class="w-[794px] h-[1123px] bg-white border-0"></iframe>
                        </div>

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-gray-900/40 group-hover:bg-gray-900/60 transition duration-300 flex items-center justify-center">
                            <span class="px-4 py-2 rounded-full font-bold text-xs bg-indigo-600 text-white shadow-lg shadow-indigo-600/40 opacity-0 group-hover:opacity-100 transition transform scale-90 group-hover:scale-100 flex items-center gap-2">
                                <i class="fas fa-eye"></i> Lihat Ukuran Penuh
                            </span>
                        </div>

                        <!-- Promo Badge -->
                        @if($template->price_normal && $template->price_normal > $template->price)
                            <div class="absolute top-3 left-3 bg-red-600 text-white text-[11px] font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider">
                                Diskon {{ round((($template->price_normal - $template->price) / $template->price_normal) * 100) }}%
                            </div>
                        @endif

                        <div class="absolute top-3 right-3 bg-gray-900/80 backdrop-blur-sm text-yellow-400 text-xs font-bold px-2.5 py-1 rounded-lg border border-gray-700 flex items-center gap-1 shadow">
                            <i class="fas fa-star text-[10px]"></i> 4.9
                        </div>
                    </div>

                    <!-- Template Info & Action -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1 group-hover:text-indigo-400 transition">
                                {{ $template->name }}
                            </h3>
                            <p class="text-xs text-gray-400 leading-relaxed line-clamp-2">
                                {{ $template->description }}
                            </p>
                        </div>

                        <div class="border-t border-gray-700/80 pt-4 flex items-center justify-between">
                            <div>
                                <div class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Biaya Unduh</div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-lg font-black text-indigo-400">
                                        Rp{{ number_format($template->price, 0, ',', '.') }}
                                    </span>
                                    @if($template->price_normal && $template->price_normal > $template->price)
                                        <span class="text-xs text-gray-500 line-through">
                                            Rp{{ number_format($template->price_normal, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @guest
                                <button @click="window.dispatchEvent(new CustomEvent('open-login'))" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md shadow-indigo-600/30 flex items-center gap-1.5">
                                    Gunakan <i class="fas fa-arrow-right text-[10px]"></i>
                                </button>
                            @else
                                <a href="{{ route('cv.create', ['template' => $template->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md shadow-indigo-600/30 flex items-center gap-1.5">
                                    Gunakan <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400">
                    <i class="fas fa-folder-open text-5xl text-gray-600 mb-3"></i>
                    <p class="text-lg font-semibold">Sedang memperbarui daftar template CV...</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Full-Size Preview Modal -->
    <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/80 backdrop-blur-sm p-4" style="display: none;">
        <div class="absolute inset-0" @click="previewOpen = false"></div>
        
        <div class="relative bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl max-h-[92vh]" @click.stop>
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-850">
                <h3 class="text-white font-bold text-base flex items-center gap-2">
                    <i class="fas fa-eye text-indigo-400"></i> Preview Template CV (Ukuran Penuh)
                </h3>
                <button @click="previewOpen = false" class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Modal Body (Iframe) -->
            <div class="flex-1 bg-gray-950 overflow-y-auto flex justify-center p-4 sm:p-6">
                <div class="w-full flex justify-center" x-init="
                    $watch('previewOpen', v => {
                        if (v) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                    });
                ">
                    <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                        <div class="bg-white shadow-2xl" :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                            <template x-if="previewOpen && previewSlug">
                                <iframe :src="`/cv/preview-example/${previewSlug}`" class="w-full h-full border-0"></iframe>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-700 bg-gray-850 flex items-center justify-between">
                <button @click="previewOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-300 hover:text-white bg-gray-700 hover:bg-gray-600 transition">
                    Tutup Preview
                </button>
                @guest
                    <button @click="previewOpen = false; window.dispatchEvent(new CustomEvent('open-login'))" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                        Pilih & Gunakan Template Ini <i class="fas fa-arrow-right"></i>
                    </button>
                @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition flex items-center gap-2">
                        Pilih & Gunakan Template Ini <i class="fas fa-arrow-right"></i>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Global Auth Modals -->
    <x-auth-modals />

</body>
</html>
