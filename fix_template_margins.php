<?php
// Replace @page margins with 0, and add body padding
$files = glob('resources/views/cv/templates/*.blade.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // First, let's see what the page margin is
    if (preg_match('/@page\s*\{\s*margin:\s*(.*?);\s*\}/s', $content, $m)) {
        $margin = trim($m[1]);
        if ($margin !== '0px' && $margin !== '0' && $margin !== '0cm') {
            // It has a non-zero margin!
            // We set page margin to 0
            $content = preg_replace('/@page\s*\{\s*margin:\s*.*?;s*\}/s', '@page { margin: 0px; }', $content);
            // We set page margin to 0 for any format
            $content = preg_replace('/@page\s*\{\s*margin:\s*.*?\s*\}/s', '@page { margin: 0px; }', $content);
            
            // Now add padding to body equal to the old margin
            // If body has margin: 0; padding: 0;
            $content = preg_replace('/body\s*\{(.*?)\}/s', "body {\n            padding: $margin; $1}", $content);
            
            file_put_contents($file, $content);
            echo "Fixed $file (was $margin)\n";
        }
    }
}
