<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);

// Add Alpine data to the main wrapper
$content = str_replace(
    '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">',
    '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ previewOpen: false, previewSlug: \'\' }">',
    $content
);

// Add Modal at the end of the wrapper
$modal_html = <<<HTML
        
        <!-- Full-Size Preview Modal -->
        <div x-cloak x-show="previewOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 p-4" style="display: none;">
            <!-- Close Area -->
            <div class="absolute inset-0 cursor-pointer" @click="previewOpen = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-gray-800 rounded-lg shadow-2xl overflow-hidden flex flex-col w-full max-w-4xl" style="height: 90vh;" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Template (Ukuran Penuh)
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-white transition bg-gray-800 hover:bg-gray-700 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Iframe Container -->
                <div class="flex-1 bg-gray-800 overflow-y-auto flex justify-center p-4 sm:p-8 relative">
                    <!-- Dynamic wrapper that scales on mobile, but stays full size on desktop -->
                    <div x-data="{ scale: 1 }" x-init="
                        \$watch('previewOpen', value => {
                            if (value) {
                                scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                            }
                        });
                        window.addEventListener('resize', () => {
                            if (previewOpen) scale = window.innerWidth < 850 ? Math.min(0.9, (window.innerWidth - 64) / 794) : 1;
                        });
                    " class="flex justify-center w-full pb-8">
                        
                        <div :style="`width: \${794 * scale}px; height: \${1123 * scale}px; position: relative;`">
                            <div class="bg-white shadow-2xl" 
                                 :style="`position: absolute; top: 0; left: 0; width: 794px; height: 1123px; transform: scale(\${scale}); transform-origin: top left;`">
                                <template x-if="previewOpen && previewSlug">
                                    <iframe :src="`/cv/preview-example/\${previewSlug}`" style="width: 100%; height: 100%; border: none;"></iframe>
                                </template>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Footer Action -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex justify-end">
                    <a :href="`/cv/create?template=\${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                </div>
            </div>
        </div>
        
    </div> <!-- Close max-w-7xl -->
HTML;

$content = str_replace(
    '    </div>', // Find the closing div of max-w-7xl
    $modal_html,
    $content
);

file_put_contents($file, $content);
echo "Added modal functionality.\n";
