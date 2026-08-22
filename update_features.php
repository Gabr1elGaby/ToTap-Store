<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// 1. Remove "MENGAPA MEMILIH KAMI?"
$content = str_replace('<h2 class="text-blue-600 font-bold uppercase tracking-widest text-xs mb-2">MENGAPA MEMILIH KAMI?</h2>', '', $content);

// 2. Replace Feature 1
$content = str_replace(
    '<h4 class="text-lg font-bold text-white mb-2">Deploy Instan</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Tanpa proses setup manual. Segera setelah administrasi selesai, instansi aplikasi Anda langsung berjalan dan siap digunakan oleh tim.</p>',
    '<h4 class="text-lg font-bold text-white mb-2">Proses Instan 24/7</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Semua pesanan mulai dari top up game hingga lisensi software diproses secara otomatis dalam hitungan detik tanpa perlu menunggu.</p>',
    $content
);

// 3. Replace Feature 2
$content = str_replace(
    '<h4 class="text-lg font-bold text-white mb-2">Keamanan Data Tinggi</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Data perusahaan Anda terisolasi dan dienkripsi dengan standar industri. Kami menjamin privasi dan integritas database Anda.</p>',
    '<h4 class="text-lg font-bold text-white mb-2">Aman & Terpercaya</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Sistem pembayaran kami menggunakan enkripsi tingkat tinggi untuk menjamin keamanan setiap transaksi dan privasi data Anda.</p>',
    $content
);

// 4. Replace Feature 3 completely (including icon)
$oldFeature3 = <<<BLADE
<div class="w-12 h-12 bg-gray-900 rounded flex items-center justify-center text-white mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Manajemen Terpusat</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Pantau seluruh cabang dan titik penjualan (POS) Anda dari satu dashboard admin. Mempermudah proses audit dan pelaporan keuangan.</p>
BLADE;

$newFeature3 = <<<BLADE
<div class="w-12 h-12 bg-gray-900 rounded flex items-center justify-center text-white mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Harga Termurah</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Dapatkan harga paling kompetitif untuk seluruh layanan digital kami. Hemat lebih banyak untuk semua kebutuhan harian Anda.</p>
BLADE;

$content = str_replace($oldFeature3, $newFeature3, $content);
file_put_contents($welcomeFile, $content);
echo "Updated features to be general store benefits.\n";
