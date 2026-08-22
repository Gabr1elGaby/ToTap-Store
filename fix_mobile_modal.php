<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Revert Right Panel to just be desktop-only
$old_right_panel_regex = '/<div :class="\{\'hidden md:flex\': !showMobilePreview, \'flex fixed inset-0 z-50\': showMobilePreview\}" class="flex-1 bg-gray-800 p-4 md:p-8 overflow-y-auto flex-col items-center justify-start relative">.*?<!-- A4 Paper Container -->/s';

$desktop_right_panel = <<<HTML
        <div class="hidden md:flex flex-1 bg-gray-800 p-4 md:p-8 overflow-y-auto flex-col items-center justify-start relative">
            <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                REAL-TIME PREVIEW (Kertas A4)
            </div>
            <!-- A4 Paper Container -->
HTML;

$content = preg_replace($old_right_panel_regex, $desktop_right_panel, $content);

// Now add a dedicated Mobile Modal right before </body>
$mobile_modal = <<<HTML
    <!-- Dedicated Mobile Preview Modal -->
    <div x-cloak x-show="showMobilePreview" class="md:hidden fixed inset-0 z-[100] bg-gray-800 flex flex-col items-center overflow-y-auto">
        <!-- Close Button -->
        <button @click="showMobilePreview = false" class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition z-[110]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2 mt-6">
            REAL-TIME PREVIEW
        </div>

        <!-- A4 Paper Container for Mobile -->
        <div class="w-[794px] h-[1123px] bg-white shadow-2xl relative shrink-0 transform scale-[0.45] sm:scale-[0.55] origin-top mt-[-150px] sm:mt-[-100px]" x-html="previewHtml">
        </div>
    </div>
HTML;

$content = str_replace('<!-- Mobile Preview Floating Button -->', $mobile_modal . "\n    <!-- Mobile Preview Floating Button -->", $content);

file_put_contents($file, $content);
echo "Separated mobile modal.\n";
