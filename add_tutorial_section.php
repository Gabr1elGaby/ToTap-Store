<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$tutorialSection = <<<BLADE
        <!-- Tutorial Section -->
        <section id="tutorial" class="py-20 bg-gray-900 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center" data-aos="fade-up">
                    <h2 class="text-blue-500 font-bold uppercase tracking-widest text-xs mb-2">Cara Kerja</h2>
                    <h3 class="text-3xl font-extrabold text-white">Panduan Menggunakan ToTap Store</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                    <!-- Connector Line for Desktop -->
                    <div class="hidden md:block absolute top-12 left-1/8 right-1/8 h-0.5 bg-gray-800" style="width: 75%; left: 12.5%;"></div>

                    <!-- Step 1 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="0">
                        <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <span class="text-3xl font-black text-blue-500" style="font-family: 'Orbitron', sans-serif;">1</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Buat Akun</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Daftar dan buat akun ToTap Store Anda secara gratis untuk mempermudah transaksi.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <span class="text-3xl font-black text-blue-500" style="font-family: 'Orbitron', sans-serif;">2</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Pilih Kategori</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Pilih layanan yang Anda butuhkan: Top Up Game instant atau lisensi Software bisnis.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <span class="text-3xl font-black text-blue-500" style="font-family: 'Orbitron', sans-serif;">3</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Pembayaran</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Selesaikan pesanan Anda menggunakan metode pembayaran terenkripsi yang aman & otomatis.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <span class="text-3xl font-black text-blue-500" style="font-family: 'Orbitron', sans-serif;">4</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Selesai</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Layanan langsung aktif dan pesanan otomatis masuk ke akun Anda dalam hitungan detik!</p>
                    </div>
                </div>
            </div>
        </section>
BLADE;

// Inject right before <section id="kategori"
if (strpos($content, '<!-- Tutorial Section -->') === false) {
    $content = str_replace(
        '<!-- Kategori Utama Section -->', 
        $tutorialSection . "\n\n        <!-- Kategori Utama Section -->", 
        $content
    );
    file_put_contents($welcomeFile, $content);
    echo "Tutorial section added.\n";
} else {
    echo "Tutorial section already exists.\n";
}
