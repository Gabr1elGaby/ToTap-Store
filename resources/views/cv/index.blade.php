<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pembuat CV - ToTap Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet"></head>
<body class="bg-gray-900 text-white font-sans antialiased" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
    <!-- Navbar -->
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ previewOpen: false, previewSlug: '' }">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-white mb-4">Buat CV Profesionalmu</h2>
            <p class="text-xl text-gray-400">Pilih template, isi data, dan dapatkan CV siap digunakan.</p>
            
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($templates as $template)
            <div class="bg-gray-800 border-gray-700 rounded-xl shadow-sm hover:shadow-md transition duration-300 border border-gray-200 overflow-hidden flex flex-col">
                <div class="bg-gray-200 border-b border-gray-200 relative overflow-hidden group cursor-pointer" style="height: 320px;" @click="previewOpen = true; previewSlug = '{{ $template->slug }}'">
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gray-900/10 z-20 group-hover:bg-gray-900/40 transition duration-300 flex items-center justify-center">
                        <div class="bg-gray-900 text-white px-4 py-2 rounded-full font-bold text-sm opacity-0 group-hover:opacity-100 transition transform scale-95 group-hover:scale-100 flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat Detail Ukuran Penuh
                            
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
                        
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
                    
                    <!-- Scaled Mini CV -->
                    <div class="bg-gray-800 border-gray-700 shadow-xl transition-transform duration-500 ease-out group-hover:-translate-y-4" 
                         style="position: absolute; top: 20px; left: 50%; width: 794px; height: 1123px; transform: translateX(-50%) scale(0.25); transform-origin: top center; z-index: 10;">
                        <iframe src="/cv/preview-example/{{ $template->slug }}" style="width: 100%; height: 100%; border: none; pointer-events: none;" scrolling="no" tabindex="-1"></iframe>
                        
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
                    
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-white mb-2">{{ $template->name }}</h3>
                    <p class="text-gray-400 text-sm mb-6 flex-1">{{ $template->description ?? 'Template profesional yang rapi dan elegan.' }}</p>
                    <div class="flex items-center justify-between mt-auto">
                                                <div class="flex flex-col">
                            @if($template->price_normal > 0 && $template->price_normal > $template->price)
                                <span class="text-xs text-gray-500 line-through">Rp{{ number_format($template->price_normal, 0, ',', '.') }}</span>
                            @endif
                            <span class="text-blue-600 font-bold text-lg">Rp{{ number_format($template->price, 0, ',', '.') }}</span>
                        </div>
                        @guest
                        <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded shadow-sm text-white bg-gray-900 hover:bg-gray-800 transition">
                            Gunakan Template
                        </a>
                        @else
                        <a href="{{ route('cv.create', ['template' => $template->slug]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded shadow-sm text-white bg-gray-900 hover:bg-gray-800 transition">
                            Gunakan Template
                        </a>
                        @endauth
                        
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
                    
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
                
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
            @endforeach
            
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
        
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        $watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                            <div class="bg-gray-800 border-gray-700 shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
    <x-auth-modals />
</body>
</html>
