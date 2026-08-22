<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$pattern = '/<!-- Kategori Utama Section -->.*?<\/section>/s';

$newSection = <<<BLADE
        <!-- Kategori Utama Section -->
        <section id="kategori" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-10 text-center" data-aos="fade-up">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pilih Kategori</h2>
                    <p class="mt-2 text-base text-gray-500 max-w-2xl mx-auto">Silakan pilih layanan yang Anda butuhkan di bawah ini.</p>
                </div>

                <!-- Wrapper Centered -->
                <div class="flex justify-center items-center">
                    
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-100" 
                       style="width: 180px; height: 200px; text-decoration: none;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-3 group-hover:-translate-y-2 transition-transform duration-300" style="width: 100px; height: 100px;">
                            <img src="{{ asset('images/kategori-game.png') }}" alt="Top Up Game" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.15));">
                        </div>
                        
                        <h3 class="text-gray-900 font-bold text-base group-hover:text-blue-600 transition-colors">Top Up Game</h3>
                    </a>

                </div>
            </div>
        </section>
BLADE;

$content = preg_replace($pattern, $newSection, $content);
file_put_contents($file, $content);
echo "Category card fixed with inline dimensions.\n";
