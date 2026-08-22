<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$pattern = '/<!-- Products Section -->.*?<\/section>/s';

// Ambil konten lama agar tidak hilang
preg_match($pattern, $content, $matches);
$oldProductsSection = $matches[0] ?? '';

// Ubah id dan judul section lama
$oldProductsSection = str_replace('id="products"', 'id="software"', $oldProductsSection);
$oldProductsSection = str_replace('Katalog Sistem', 'Software Enterprise', $oldProductsSection);

$newSection = <<<BLADE
        <!-- Kategori Section (Sesuai Referensi Wiboost) -->
        <section id="kategori" class="py-20 bg-[#F8FAFF]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-12 text-center" data-aos="fade-up">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-50 text-cyan-500 font-bold text-xs mb-4 shadow-sm border border-cyan-100">Katalog Utama</span>
                    <h2 class="text-3xl font-extrabold text-[#2B3467]">Pilih Kategori</h2>
                </div>

                <div class="flex flex-wrap justify-center gap-6 max-w-5xl mx-auto">
                    
                    <!-- CARD 1: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" class="relative w-44 h-52 bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        <!-- Ribbon -->
                        <div class="absolute top-4 -right-8 w-32 transform rotate-45 bg-[#5C6BCA] text-white text-[9px] font-bold py-1 text-center shadow-sm z-10">
                            DISKON 10%
                        </div>
                        <!-- Icon Circle -->
                        <div class="w-20 h-20 bg-white rounded-full shadow-[0_2px_15px_rgba(0,0,0,0.08)] flex items-center justify-center mb-4 group-hover:-translate-y-1 transition-transform border border-gray-50">
                            <img src="https://cdn-icons-png.flaticon.com/512/686/686589.png" alt="Game" class="w-10 h-10 object-contain opacity-80">
                        </div>
                        <h3 class="text-[#2B3467] font-bold text-sm">Top Up Game</h3>
                    </a>

                    <!-- CARD 2: KASIR POS -->
                    <a href="#software" class="relative w-44 h-52 bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="absolute top-4 -right-8 w-32 transform rotate-45 bg-[#5C6BCA] text-white text-[9px] font-bold py-1 text-center shadow-sm z-10">
                            POPULER
                        </div>
                        <div class="w-20 h-20 bg-white rounded-full shadow-[0_2px_15px_rgba(0,0,0,0.08)] flex items-center justify-center mb-4 group-hover:-translate-y-1 transition-transform border border-gray-50">
                            <img src="https://cdn-icons-png.flaticon.com/512/3024/3024503.png" alt="POS" class="w-10 h-10 object-contain opacity-80">
                        </div>
                        <h3 class="text-[#2B3467] font-bold text-sm">Kasir POS</h3>
                    </a>

                    <!-- CARD 3: PEMBUAT CV -->
                    <a href="{{ route('cv.index') }}" class="relative w-44 h-52 bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-20 h-20 bg-white rounded-full shadow-[0_2px_15px_rgba(0,0,0,0.08)] flex items-center justify-center mb-4 group-hover:-translate-y-1 transition-transform border border-gray-50">
                            <img src="https://cdn-icons-png.flaticon.com/512/942/942748.png" alt="CV" class="w-10 h-10 object-contain opacity-80">
                        </div>
                        <h3 class="text-[#2B3467] font-bold text-sm">Pembuat CV</h3>
                    </a>

                    <!-- CARD 4: JASA JOKI -->
                    <a href="#" class="relative w-44 h-52 bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all duration-300 flex flex-col items-center justify-center group overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-20 h-20 bg-white rounded-full shadow-[0_2px_15px_rgba(0,0,0,0.08)] flex items-center justify-center mb-4 group-hover:-translate-y-1 transition-transform border border-gray-50">
                            <img src="https://cdn-icons-png.flaticon.com/512/888/888873.png" alt="Joki" class="w-10 h-10 object-contain opacity-80">
                        </div>
                        <h3 class="text-[#2B3467] font-bold text-sm text-center">Jasa Joki<br>(Coming Soon)</h3>
                    </a>

                </div>
            </div>
        </section>

BLADE;

$content = preg_replace($pattern, $newSection . "\n" . $oldProductsSection, $content);
file_put_contents($file, $content);
echo "New category design applied and old products section kept.\n";
