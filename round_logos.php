<?php

// 1. Update application-logo.blade.php
$file = 'resources/views/components/application-logo.blade.php';
$content = file_get_contents($file);
$content = preg_replace('/class="h-12 w-auto object-contain"/i', 'class="h-12 w-12 rounded-full object-cover shadow-sm ring-2 ring-white/50"', $content);
file_put_contents($file, $content);

// 2. Update welcome.blade.php
$file2 = 'resources/views/welcome.blade.php';
$content2 = file_get_contents($file2);
$content2 = preg_replace('/class="h-16 w-auto object-contain"/i', 'class="h-16 w-16 rounded-full object-cover shadow-md ring-2 ring-white/50"', $content2);
$content2 = preg_replace('/class="h-20 w-auto object-contain grayscale hover:grayscale-0 transition-all"/i', 'class="h-20 w-20 rounded-full object-cover grayscale hover:grayscale-0 transition-all shadow-md ring-2 ring-gray-600/50"', $content2);
file_put_contents($file2, $content2);

echo "Logos made circular.\n";
