<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Use preg_replace to target the entire @if($maxDiscount > 0) block
$content = preg_replace(
    '/@if\(\$maxDiscount > 0\).*?@endif/is',
    <<<HTML
@if(\$maxDiscount > 0)
                            <!-- BADGE PROMO RIBBON -->
                            <div class="absolute -right-[35px] top-[20px] w-[150px] transform rotate-45 bg-gradient-to-r from-red-600 to-rose-500 text-white text-[10px] font-extrabold py-1 text-center shadow-md z-20 animate-pulse tracking-wider">
                                DISKON {{ \$maxDiscount }}%
                            </div>
                        @endif
HTML
    ,
    $content
);

file_put_contents($file, $content);
echo "Ribbon strictly applied.\n";
