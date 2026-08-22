<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldHeader = <<<BLADE
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Top Up {{ \$game->name }}
        </h2>
    </x-slot>
BLADE;

$newHeader = <<<BLADE
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ url('/#kategori') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white rounded-lg border border-gray-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight border-l-2 border-gray-600 pl-4">
                Top Up {{ \$game->name }}
            </h2>
        </div>
    </x-slot>
BLADE;

$content = str_replace($oldHeader, $newHeader, $content);
file_put_contents($file, $content);
echo "Added back button to header.\n";
