<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Hapus Thumbnail (yang kecil)
// Menghapus blok:
// @if($game->thumbnail)
// <img src="{{ $game->thumbnail }}" alt="Thumbnail" class="w-20 h-20 rounded-2xl absolute -top-10 border-4 border-white dark:border-gray-800 shadow-md">
// <div class="h-10"></div>
// @endif
$content = preg_replace('/@if\(\$game->thumbnail\).*?<img.*?alt="Thumbnail".*?<div class="h-10"><\/div>.*?@endif/is', '', $content);

// 2. Kecilkan Cover (yang besar)
// Menghapus h-40 dan menggantinya dengan h-32 atau h-24
$content = str_replace('class="w-full h-40 object-cover"', 'class="w-full h-24 object-cover"', $content);

file_put_contents($file, $content);
echo "Cover resized and thumbnail removed.\n";
