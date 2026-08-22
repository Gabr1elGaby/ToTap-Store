<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// 1. Replace the Title
$content = preg_replace(
    '/Infrastruktur Perangkat Lunak\s*<br>\s*<span class="text-blue-500">Untuk Skala Bisnis Anda\.<\/span>/i',
    'Pusat Layanan Digital <br>' . "\n" . '                        <span class="text-blue-500">& Top Up Terlengkap.</span>',
    $content
);

// 2. Replace the Description
$content = preg_replace(
    '/Sistem manajemen enterprise-grade yang dirancang khusus untuk memonitor, mengelola, dan mengotomatisasi operasional perusahaan Anda secara real-time\./i',
    'Platform terpercaya untuk kebutuhan top up game instant dan solusi software profesional. Transaksi otomatis, harga bersahabat, dan aman 100%.',
    $content
);

// 3. Replace the Buttons
$content = preg_replace(
    '/<a href="#products" class="px-8 py-3 bg-blue-600 text-white rounded font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition">\s*Lihat Solusi\s*<\/a>/i',
    '<a href="#kategori" class="px-8 py-3 bg-blue-600 text-white rounded font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">' . "\n" . '                            Pilih Kategori' . "\n" . '                        </a>',
    $content
);

$content = preg_replace(
    '/<a href="#keunggulan" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">\s*Pelajari Sistem\s*<\/a>/i',
    '<a href="/register" @click.prevent="showRegister = true" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">' . "\n" . '                            Daftar Sekarang' . "\n" . '                        </a>',
    $content
);

file_put_contents($welcomeFile, $content);
echo "Used Regex to force text replacement.\n";
