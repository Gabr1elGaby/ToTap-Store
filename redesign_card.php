<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$pattern = '/<!-- Kategori Utama Section -->.*?<\/section>/s';

$newSection = <<<BLADE
        <!-- Kategori Utama Section -->
        <section id="kategori" class="py-20 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-14 text-center" data-aos="fade-up">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 text-blue-700 font-extrabold text-xs mb-4 uppercase tracking-wider shadow-sm">Layanan Utama</span>
                    <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Pilih Kategori</h2>
                    <p class="mt-3 text-lg text-gray-500 max-w-2xl mx-auto">Silakan pilih layanan yang Anda butuhkan di bawah ini.</p>
                </div>

                <!-- Wrapper Centered -->
                <div class="flex justify-center items-center max-w-5xl mx-auto">
                    
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" class="relative w-64 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 flex flex-col items-center justify-center group overflow-hidden border border-gray-200 pb-8 pt-10" data-aos="zoom-in" data-aos-delay="100">
                        
                        <!-- Glow effect behind icon -->
                        <div class="absolute top-10 w-32 h-32 bg-blue-500 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                        
                        <!-- Icon (Bebas tanpa lingkaran agar lega) -->
                        <div class="relative w-36 h-36 mb-6 group-hover:scale-110 transition-transform duration-500 ease-out flex items-center justify-center">
                            <img src="{{ asset('images/kategori-game.png') }}" alt="Top Up Game" class="w-full h-full object-contain drop-shadow-xl scale-125">
                        </div>
                        
                        <h3 class="text-gray-900 font-extrabold text-xl tracking-wide group-hover:text-blue-600 transition-colors duration-300">TOP UP GAME</h3>
                        <div class="w-12 h-1 bg-blue-600 mt-4 mb-3 rounded-full group-hover:w-24 transition-all duration-500 ease-out"></div>
                        <p class="text-sm text-gray-500 font-medium">Buka 24 Jam Non-Stop</p>
                    </a>

                </div>
            </div>
        </section>
BLADE;

$content = preg_replace($pattern, $newSection, $content);
file_put_contents($file, $content);
echo "Category card redesigned beautifully.\n";
