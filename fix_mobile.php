<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Replace grid-cols-2
$content = str_replace('grid-cols-2', 'grid-cols-1 md:grid-cols-2', $content);

// Make the nav bar responsive
$old_nav = <<<HTML
    <nav class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <a href="/#products" class="text-gray-500 hover:text-gray-900 text-sm font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali
            </a>
            <div class="h-6 w-px bg-gray-300"></div>
            <h1 class="font-bold text-gray-800 hidden sm:block">Template: {{ \$template->name }}</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-sm font-bold text-gray-800">Harga: <span class="text-green-600">Rp{{ number_format(\$template->price, 0, ',', '.') }}</span></div>
            <button @click="submitCv" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm font-bold shadow hover:bg-blue-700 transition">
                Lanjut Pembayaran
            </button>
        </div>
    </nav>
HTML;

$new_nav = <<<HTML
    <nav class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-3 md:px-6 shrink-0">
        <div class="flex items-center gap-2 md:gap-4">
            <a href="/#products" class="text-gray-500 hover:text-gray-900 text-sm font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> <span class="hidden sm:inline">Kembali</span>
            </a>
            <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
            <h1 class="font-bold text-gray-800 hidden md:block">Template: {{ \$template->name }}</h1>
        </div>
        <div class="flex items-center gap-2 md:gap-4">
            <div class="text-xs md:text-sm font-bold text-gray-800">Harga: <span class="text-green-600">Rp{{ number_format(\$template->price, 0, ',', '.') }}</span></div>
            <button @click="submitCv" class="bg-blue-600 text-white px-3 md:px-4 py-1.5 rounded text-xs md:text-sm font-bold shadow hover:bg-blue-700 transition whitespace-nowrap">
                Lanjut Pembayaran
            </button>
        </div>
    </nav>
HTML;

$content = str_replace($old_nav, $new_nav, $content);

file_put_contents($file, $content);
echo "Done fixing responsive mobile design.\n";
