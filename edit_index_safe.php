<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);

$start_tag = '<div class="aspect-w-3 aspect-h-4 bg-gray-100 flex items-center justify-center p-6 border-b border-gray-100">';
$parts = explode($start_tag, $content);

if (count($parts) > 1) {
    $new_content = $parts[0];
    
    for ($i = 1; $i < count($parts); $i++) {
        // Find where the template info section starts: <div class="p-6 flex-1 flex flex-col">
        $end_pos = strpos($parts[$i], '<div class="p-6 flex-1 flex flex-col">');
        
        $iframe_html = <<<HTML
<div class="h-80 bg-gray-200 border-b border-gray-200 relative overflow-hidden flex items-center justify-center pointer-events-none group">
                    <div class="absolute inset-0 bg-gray-900/5 z-10 group-hover:bg-transparent transition duration-300"></div>
                    <div class="relative w-[794px] h-[1123px] bg-white shadow-md transform scale-[0.25] sm:scale-[0.22] md:scale-[0.2] lg:scale-[0.22] origin-center transition-transform duration-300 group-hover:scale-[0.26]">
                        <iframe src="/cv/preview-example/{{ \$template->slug }}" class="w-full h-full border-0" scrolling="no" tabindex="-1"></iframe>
                    </div>
                </div>
                
HTML;
        
        $new_content .= $iframe_html . substr($parts[$i], $end_pos);
    }
    
    file_put_contents($file, $new_content);
    echo "Successfully replaced thumbnails with iframes.\n";
} else {
    echo "Could not find start tag.\n";
}
