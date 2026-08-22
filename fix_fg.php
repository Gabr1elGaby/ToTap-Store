<?php
$file = 'resources/views/cv/templates/fresh-graduate.blade.php';
$content = file_get_contents($file);

$bad_regex = '/\s*<\/tr>\s*<\/table>\s*@if\(isset\(\$skill->level\)\)\s*<div style="width: 100%; background-color: #f3f4f6; height: 5px; border-radius: 3px;">\s*<div style="width: \{\{ \$skill->level \}\}%; background-color: #D4AF37; height: 100%; border-radius: 3px;"><\/div>\s*<\/div>\s*@endif\s*<\/div>\s*@endforeach\s*<\/div>\s*@endif/s';

$content = preg_replace($bad_regex, '', $content);

file_put_contents($file, $content);
echo "Fixed fresh graduate syntax.\n";
