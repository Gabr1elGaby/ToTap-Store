<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Replace navbar logo
$oldNavbarLogo = <<<HTML
                        <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white font-bold text-lg">
                            G
                        </div>
HTML;
$newNavbarLogo = <<<HTML
                        <img src="{{ asset('images/totap-logo.png') }}" alt="TTS" class="h-10 w-auto object-contain">
                        <span class="text-xl font-bold text-white tracking-tight uppercase" style="letter-spacing: 1px;">TOTAP STORE</span>
HTML;

$content = str_replace($oldNavbarLogo, $newNavbarLogo, $content);

// Replace footer logo
$oldFooterLogo = <<<HTML
                <img src="{{ asset('images/totap-logo.png') }}" alt="ToTap Store" class="h-10 w-auto mb-4 md:mb-0 object-contain grayscale hover:grayscale-0 transition-all">
HTML;
$newFooterLogo = <<<HTML
                <div class="flex items-center gap-3 mb-4 md:mb-0">
                    <img src="{{ asset('images/totap-logo.png') }}" alt="ToTap Store" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition-all">
                    <span class="text-white font-bold text-lg uppercase tracking-wider">TOTAP STORE</span>
                </div>
HTML;

$content = str_replace($oldFooterLogo, $newFooterLogo, $content);

file_put_contents($file, $content);
echo "Fixed welcome page logo.\n";
