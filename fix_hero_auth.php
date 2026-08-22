<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$oldButtons = <<<BLADE
                    <div class="flex justify-center items-center gap-4">
                        <a href="#kategori" class="px-8 py-3 bg-blue-600 text-white rounded font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                            Pilih Kategori
                        </a>
                        <a href="/register" @click.prevent="showRegister = true" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">
                            Daftar Sekarang
                        </a>
                    </div>
BLADE;

$newButtons = <<<BLADE
                    <div class="flex justify-center items-center gap-4">
                        <a href="#kategori" class="px-8 py-3 bg-blue-600 text-white rounded font-bold text-sm uppercase tracking-wider hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                            Pilih Kategori
                        </a>
                        @auth
                            <a href="/dashboard" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">
                                Dashboard Saya
                            </a>
                        @else
                            <a href="/register" @click.prevent="showRegister = true" class="px-8 py-3 bg-transparent text-white border border-gray-600 rounded font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition">
                                Daftar Sekarang
                            </a>
                        @endauth
                    </div>
BLADE;

// Sometimes whitespaces get messed up, so let's use regex again if str_replace fails
if (strpos($content, '<a href="#kategori"') !== false) {
    // Regex replacement targeting the div containing the buttons
    $content = preg_replace(
        '/<div class="flex justify-center items-center gap-4">.*?<\/div>/s',
        $newButtons,
        $content,
        1
    );
    file_put_contents($welcomeFile, $content);
    echo "Added auth condition to hero buttons.\n";
} else {
    echo "Could not find buttons.\n";
}
