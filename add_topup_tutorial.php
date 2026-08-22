<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$tutorialBox = <<<BLADE
            <!-- Panduan Top Up -->
            <div class="bg-gray-800 border-l-4 border-blue-500 rounded-xl p-5 mb-8 shadow-md" data-aos="fade-down">
                <h3 class="font-bold text-lg mb-3 text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Cara Top Up {{ \$game->name }}
                </h3>
                <ul class="space-y-2 text-sm md:text-base text-gray-300">
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">1.</span>
                        <span>Masukkan data target (Player ID / ID Pengguna) yang sesuai dengan akun game Anda.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">2.</span>
                        <span>Pilih nominal item atau layanan yang Anda inginkan dari daftar yang tersedia.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">3.</span>
                        <span>Pilih salah satu metode pembayaran yang paling memudahkan Anda.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">4.</span>
                        <span>Klik tombol <strong>Beli Sekarang</strong> dan selesaikan pembayaran. Pesanan akan masuk otomatis!</span>
                    </li>
                </ul>
            </div>
BLADE;

if (strpos($content, '<!-- Panduan Top Up -->') === false) {
    $content = str_replace(
        '<div class="flex flex-col lg:flex-row gap-6">',
        $tutorialBox . "\n" . '            <div class="flex flex-col lg:flex-row gap-6">',
        $content
    );
    file_put_contents($file, $content);
    echo "Added tutorial box to topup page.\n";
} else {
    echo "Tutorial box already exists.\n";
}
