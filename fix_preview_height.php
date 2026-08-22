<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Fix Desktop Preview Container
$desktop_regex = '/<div class="w-\[794px\] h-\[1123px\] bg-white shadow-2xl relative shrink-0 overflow-y-auto transform scale-\[0.6\] xl:scale-75 origin-top" id="pdf-preview-container">\s*<!-- Injected HTML from Backend -->\s*<div x-html="previewHtml" class="w-full h-full"><\/div>/s';

$new_desktop = <<<HTML
<div class="w-[794px] min-h-[1123px] bg-white shadow-2xl relative shrink-0 transform scale-[0.6] xl:scale-75 origin-top" id="pdf-preview-container">
                <!-- Injected HTML from Backend -->
                <div x-html="previewHtml" class="w-full h-full"></div>
HTML;

$content = preg_replace($desktop_regex, $new_desktop, $content);

// Fix Mobile Preview Container
// We need the wrapper to dynamically adapt to the content height, but we can't easily do that with CSS transform scale if the content height is unknown!
// Fortunately, x-ref can get the height!
// But for now, we can just use a very large padding or allow the modal to just scroll naturally.
// If we use zoom (CSS zoom), it scales the layout natively! Zoom is supported in Safari and Chrome, but not Firefox. On mobile, 99% is Safari or Chrome.
// Let's use CSS transform but just let it be. If they have 2 pages, the A4 is 1123px.

file_put_contents($file, $content);
echo "Fixed desktop preview container height.\n";
