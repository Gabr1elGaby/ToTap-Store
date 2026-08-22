<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Remove button from current position
$btn_regex = '/\s*<button @click="showMobilePreview = true" class="md:hidden fixed bottom-6 right-6.*?<\/button>/s';
$content = preg_replace($btn_regex, '', $content);

// Add it before </body>
$mobile_btn = '
    <!-- Mobile Preview Floating Button -->
    <button @click="showMobilePreview = true" class="md:hidden fixed bottom-6 right-6 bg-gray-900 text-white rounded-full p-4 shadow-2xl z-50 hover:bg-gray-800 transition flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
    </button>
';
$content = str_replace('</body>', $mobile_btn . "\n</body>", $content);

file_put_contents($file, $content);
echo "Button moved.\n";
