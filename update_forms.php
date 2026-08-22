<?php

// CREATE VIEW
$createFile = 'resources/views/admin/games/create.blade.php';
$content = file_get_contents($createFile);
$content = str_replace('<form action="{{ route(\'admin.games.store\') }}" method="POST">', '<form action="{{ route(\'admin.games.store\') }}" method="POST" enctype="multipart/form-data">', $content);
$content = str_replace('<input type="text" name="thumbnail" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">', '<input type="file" name="thumbnail" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">', $content);
$content = str_replace('<input type="text" name="cover_image" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">', '<input type="file" name="cover_image" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">', $content);
$content = str_replace('URL Gambar', 'Upload Gambar', $content);
file_put_contents($createFile, $content);

// EDIT VIEW
$editFile = 'resources/views/admin/games/edit.blade.php';
$content = file_get_contents($editFile);
$content = str_replace('<form action="{{ route(\'admin.games.update\', $game) }}" method="POST">', '<form action="{{ route(\'admin.games.update\', $game) }}" method="POST" enctype="multipart/form-data">', $content);
$content = preg_replace('/<input type="text" name="thumbnail"[^>]*>/i', '<input type="file" name="thumbnail" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white"><br>@if($game->thumbnail)<img src="{{ $game->thumbnail }}" class="h-20 mt-2 rounded">@endif', $content);
$content = preg_replace('/<input type="text" name="cover_image"[^>]*>/i', '<input type="file" name="cover_image" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white"><br>@if($game->cover_image)<img src="{{ $game->cover_image }}" class="h-20 mt-2 rounded">@endif', $content);
$content = str_replace('URL Gambar', 'Upload Gambar (Biarkan kosong jika tidak ingin mengubah)', $content);
file_put_contents($editFile, $content);

echo "Forms updated to support file uploads.\n";
