<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

$oldDemo = 'class="bg-indigo-100 text-indigo-700 px-6 py-2 rounded font-semibold hover:bg-indigo-200 transition"';
$newDemo = 'class="bg-blue-600/20 border border-blue-500 text-blue-400 px-8 py-3 rounded hover:bg-blue-600 hover:text-white transition shadow-[0_0_15px_rgba(59,130,246,0.3)]" style="font-family: \'Orbitron\', sans-serif; text-transform: uppercase; font-size: 14px;"';

$content = str_replace($oldDemo, $newDemo, $content);
file_put_contents($file, $content);
echo "Demo button updated.\n";
