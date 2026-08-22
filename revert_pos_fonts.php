<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

// Remove the Orbitron inline styles I added earlier
$content = preg_replace('/ style="font-family: \'Orbitron\', sans-serif;[^"]*"/', '', $content);

file_put_contents($file, $content);
echo "Reverted POS fonts back to default sans-serif.\n";
