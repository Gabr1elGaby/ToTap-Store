<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$content = str_replace('{{ url(\'/#kategori\') }}', '{{ url(\'/topup\') }}', $content);

file_put_contents($file, $content);
echo "Changed back button link to /topup.\n";
