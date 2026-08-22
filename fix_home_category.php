<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Replace the entire "Top Up Game Termurah" section with the "Pilih Kategori" section
$pattern = '/<!-- Top Up Games Section -->.*?<\/section>/s';

$newSection = <<<BLADE
        <!-- Kategori Utama Section -->
        <section id="kategori" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-12 text-center" data-aos="fade-up">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 text-blue-600 font-bold text-xs mb-4 shadow-sm">Katalog Utama</span>
                    <h2 class="text-3xl font-extrabold text-gray-900">Pilih Kategori</h2>
                </div>

                <!-- Wrapper Centered -->
                <div class="flex justify-center items-center max-w-5xl mx-auto">
                    
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" class="relative w-48 h-56 bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                        <!-- Icon Circle -->
                        <div class="w-24 h-24 bg-blue-50 rounded-full shadow-inner flex items-center justify-center mb-6 group-hover:-translate-y-2 transition-transform duration-300">
                            <!-- Gamepad Icon -->
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-gray-800 font-extrabold text-lg">Top Up Game</h3>
                        <p class="text-xs text-gray-400 mt-1">Layanan 24 Jam</p>
                    </a>

                </div>
            </div>
        </section>
BLADE;

$content = preg_replace($pattern, $newSection, $content);
file_put_contents($file, $content);
echo "Category selection injected on homepage.\n";
