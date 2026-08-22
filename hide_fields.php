<?php
$views = [
    'resources/views/admin/games/create.blade.php',
    'resources/views/admin/games/edit.blade.php'
];

foreach ($views as $file) {
    $content = file_get_contents($file);
    
    // Hide the three blocks by adding "hidden"
    $content = preg_replace('/<div class="mb-4">\s*<label[^>]*>Label Target 1.*?<\/div>/is', '', $content);
    $content = preg_replace('/<div class="mb-4">\s*<label[^>]*>Butuh Target 2\?.*?<\/div>/is', '', $content);
    $content = preg_replace('/<div class="mb-4">\s*<label[^>]*>Label Target 2.*?<\/div>/is', '', $content);
    
    // Remove the JS script
    $content = preg_replace('/<script>.*?<\/script>/is', '', $content);
    
    file_put_contents($file, $content);
}

echo "Fields hidden in frontend.\n";
