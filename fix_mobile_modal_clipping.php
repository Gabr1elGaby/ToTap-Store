<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Find the mobile modal
$modal_regex = '/<!-- Dedicated Mobile Preview Modal -->\s*<div x-cloak x-show="showMobilePreview" class="md:hidden fixed inset-0 z-\[100\] bg-gray-800 flex flex-col items-center overflow-y-auto">.*?<\/div>\s*<\/div>\s*<!-- Mobile Preview Floating Button -->/s';

// We will use inline styles to calculate the perfect scale to fit the screen.
// We use a wrapper with flex justify-center. But to avoid negative overflow clipping,
// we set transform-origin: top left; on the paper, and calculate the wrapper height so it scrolls perfectly.
$new_modal = <<<'HTML'
    <!-- Dedicated Mobile Preview Modal -->
    <div x-cloak x-show="showMobilePreview" class="md:hidden fixed inset-0 z-[100] bg-gray-800 overflow-y-auto overflow-x-hidden">
        <!-- Close Button -->
        <button @click="showMobilePreview = false" class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition z-[110]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-4 flex items-center justify-center gap-2 mt-6">
            REAL-TIME PREVIEW
        </div>

        <!-- Dynamic Scaled Wrapper -->
        <div class="w-full flex justify-center pb-12" x-data="{ scale: 0.45 }" x-init="scale = Math.min(0.9, (window.innerWidth - 32) / 794)">
            <!-- We need a spacer div to reserve the exact scaled height so the modal scrolls correctly -->
            <div :style="`width: ${794 * scale}px; height: ${1123 * scale}px; position: relative;`">
                <!-- The actual paper, absolutely positioned and scaled from top-left -->
                <div class="w-[794px] h-[1123px] bg-white shadow-2xl absolute top-0 left-0 origin-top-left" 
                     :style="`transform: scale(${scale});`" 
                     x-html="previewHtml">
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Preview Floating Button -->
HTML;

$content = preg_replace($modal_regex, $new_modal, $content);

file_put_contents($file, $content);
echo "Fixed mobile modal scaling and clipping.\n";
