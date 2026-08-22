<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$regex = '/<!-- Dynamic Scaled Wrapper -->.*?x-html="previewHtml">\s*<\/div>\s*<\/div>\s*<\/div>/s';

$replacement = <<<HTML
        <!-- Dynamic Scaled Wrapper -->
        <div class="w-full flex justify-center pb-12" x-data="{ scale: 0.45, paperHeight: 1123 }" x-init="
            scale = Math.min(0.95, (window.innerWidth - 32) / 794);
            \$watch('previewHtml', value => {
                setTimeout(() => {
                    if (\$refs.paper) paperHeight = Math.max(1123, \$refs.paper.scrollHeight);
                }, 100);
            });
        ">
            <!-- We need a spacer div to reserve the exact scaled height so the modal scrolls correctly -->
            <div :style="`width: \${794 * scale}px; height: \${paperHeight * scale}px; position: relative; transition: height 0.3s;`">
                <!-- The actual paper, absolutely positioned and scaled from top-left -->
                <div x-ref="paper" class="bg-white shadow-2xl absolute top-0 left-0" 
                     :style="`width: 794px; min-height: 1123px; transform: scale(\${scale}); transform-origin: top left;`" 
                     x-html="previewHtml">
                </div>
            </div>
        </div>
HTML;

$content = preg_replace($regex, $replacement, $content);

file_put_contents($file, $content);
echo "Fixed mobile alignment with inline CSS.\n";
