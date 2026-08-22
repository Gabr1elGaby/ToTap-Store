<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$oldHero = <<<BLADE
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6 tracking-tight">
                        Infrastruktur Perangkat Lunak <br>
                        <span class="text-blue-500">Untuk Skala Bisnis Anda.</span>
                    </h1>
                    <p class="text-lg text-gray-400 mb-10 leading-relaxed max-w-2xl mx-auto">
                        Sistem manajemen enterprise-grade yang dirancang khusus untuk memonitor, mengelola, dan mengotomatisasi operasional perusahaan Anda secara real-time.
                    </p>
                    <div class="flex justify-center items-center gap-4">
                        <a href="#products" class="px-8 py-3 bg-blue-600 text-white rounded font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition">
                            Lihat Solusi
                        </a>
                        <a href="#keunggulan" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">
                            Pelajari Sistem
                        </a>
                    </div>
BLADE;

$newHero = <<<BLADE
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6 tracking-tight">
                        Pusat Layanan Digital <br>
                        <span class="text-blue-500">& Top Up Terlengkap.</span>
                    </h1>
                    <p class="text-lg text-gray-400 mb-10 leading-relaxed max-w-2xl mx-auto">
                        Platform terpercaya untuk kebutuhan top up game instant dan solusi software profesional. Transaksi otomatis, harga bersahabat, dan aman 100%.
                    </p>
                    <div class="flex justify-center items-center gap-4">
                        <a href="#kategori" class="px-8 py-3 bg-blue-600 text-white rounded font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                            Pilih Kategori
                        </a>
                        <a href="/register" @click.prevent="showRegister = true" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">
                            Daftar Sekarang
                        </a>
                    </div>
BLADE;

$content = str_replace($oldHero, $newHero, $content);
file_put_contents($welcomeFile, $content);

echo "Hero section text updated.\n";
