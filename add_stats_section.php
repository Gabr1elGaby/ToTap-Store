<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$statsSection = <<<BLADE
        <!-- Stats Section -->
        <section class="border-b border-gray-800 bg-gray-900 py-8 relative z-20 shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-gray-800">
                    <div class="p-4" data-aos="fade-up" data-aos-delay="100">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">99.9K<span class="text-blue-500">+</span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Pengguna</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="200">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">50<span class="text-blue-500">+</span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Layanan</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="300">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">2.5M<span class="text-blue-500">+</span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Transaksi</p>
                    </div>
                </div>
            </div>
        </section>
BLADE;

// Find the end of the hero section
$pattern = '/(<\/section>\s*)(<section id="keunggulan")/s';
$content = preg_replace($pattern, "$1$statsSection\n$2", $content);

file_put_contents($welcomeFile, $content);
echo "Stats section added to welcome page.\n";
