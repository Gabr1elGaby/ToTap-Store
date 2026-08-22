<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);

// Replace the iframe wrapper with a robust inline-CSS version and click handlers
$regex = '/<div class="h-80 bg-gray-200 border-b border-gray-200 relative overflow-hidden flex items-center justify-center pointer-events-none group">.*?<\/div>\s*<\/div>/s';

$replacement = <<<HTML
<div class="bg-gray-200 border-b border-gray-200 relative overflow-hidden group cursor-pointer" style="height: 320px;" @click="previewOpen = true; previewSlug = '{{ \$template->slug }}'">
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gray-900/10 z-20 group-hover:bg-gray-900/40 transition duration-300 flex items-center justify-center">
                        <div class="bg-gray-900 text-white px-4 py-2 rounded-full font-bold text-sm opacity-0 group-hover:opacity-100 transition transform scale-95 group-hover:scale-100 flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat Detail Ukuran Penuh
                        </div>
                    </div>
                    
                    <!-- Scaled Mini CV -->
                    <div class="bg-white shadow-xl transition-transform duration-500 ease-out group-hover:-translate-y-4" 
                         style="position: absolute; top: 20px; left: 50%; width: 794px; height: 1123px; transform: translateX(-50%) scale(0.25); transform-origin: top center; z-index: 10;">
                        <iframe src="/cv/preview-example/{{ \$template->slug }}" style="width: 100%; height: 100%; border: none; pointer-events: none;" scrolling="no" tabindex="-1"></iframe>
                    </div>
                </div>
HTML;

$content = preg_replace($regex, $replacement, $content);

file_put_contents($file, $content);
echo "Updated thumbnails with inline CSS.\n";
