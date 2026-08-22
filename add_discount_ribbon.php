<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$ribbonGame = <<<BLADE
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
BLADE;

$ribbonGameReplacement = <<<BLADE
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
BLADE;

// I'll use preg_replace to safely inject it inside the <a> tag
$content = preg_replace(
    '/(<!-- KATEGORI: TOP UP GAME -->\s*<a[^>]*>)/i',
    '$1' . "\n" . '                        @if(isset($maxGameDiscount) && $maxGameDiscount > 0)
                        <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-top-right-radius: 24px; border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                            Diskon s/d {{ $maxGameDiscount }}%
                        </div>
                        @endif',
    $content
);

$content = preg_replace(
    '/(<!-- KATEGORI: SOFTWARE ENTERPRISE -->\s*<a[^>]*>)/i',
    '$1' . "\n" . '                        @if(isset($maxSoftwareDiscount) && $maxSoftwareDiscount > 0)
                        <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-top-right-radius: 24px; border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                            Diskon s/d {{ $maxSoftwareDiscount }}%
                        </div>
                        @endif',
    $content
);

file_put_contents($file, $content);
echo "Added discount ribbons to category cards.\n";
