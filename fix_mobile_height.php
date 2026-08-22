<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$mobile_regex = '/<!-- Dynamic Scaled Wrapper -->\s*<div class="w-full flex justify-center pb-12" x-data="\{ scale: 0.45 \}" x-init="scale = Math.min\(0.9, \(window.innerWidth - 32\) \/ 794\)">\s*<!-- We need a spacer div to reserve the exact scaled height so the modal scrolls correctly -->\s*<div :style="`width: \$\{794 \* scale\}px; height: \$\{1123 \* scale\}px; position: relative;`">\s*<!-- The actual paper, absolutely positioned and scaled from top-left -->\s*<div class="w-\[794px\] h-\[1123px\] bg-white shadow-2xl absolute top-0 left-0 origin-top-left"\s*:style="`transform: scale\(\$\{scale\}\);`"\s*x-html="previewHtml">\s*<\/div>\s*<\/div>\s*<\/div>/s';

$new_mobile = <<<HTML
        <!-- Dynamic Scaled Wrapper -->
        <div class="w-full flex justify-center pb-12" x-data="{ scale: 0.45, paperHeight: 1123 }" x-init="
            scale = Math.min(0.9, (window.innerWidth - 32) / 794);
            // Observe changes in previewHtml to update height
            \$watch('previewHtml', value => {
                setTimeout(() => {
                    if (\$refs.paper) paperHeight = Math.max(1123, \$refs.paper.scrollHeight);
                }, 100);
            });
        ">
            <!-- We need a spacer div to reserve the exact scaled height so the modal scrolls correctly -->
            <div :style="`width: \${794 * scale}px; height: \${paperHeight * scale}px; position: relative; transition: height 0.3s;`">
                <!-- The actual paper, absolutely positioned and scaled from top-left -->
                <div x-ref="paper" class="w-[794px] min-h-[1123px] bg-white shadow-2xl absolute top-0 left-0 origin-top-left" 
                     :style="`transform: scale(\${scale});`" 
                     x-html="previewHtml">
                </div>
            </div>
        </div>
HTML;

$content = preg_replace($mobile_regex, $new_mobile, $content);

file_put_contents($file, $content);
echo "Added dynamic height to mobile modal.\n";
