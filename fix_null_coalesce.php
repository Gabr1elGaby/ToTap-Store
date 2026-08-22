<?php
$files = glob('resources/views/cv/templates/*.blade.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Convert {{ $var }} to {{ $var ?? '' }} if not already
    // Be careful with objects, like {{ $org->role }} -> {{ $org->role ?? '' }}
    // We only want to target variables that we know are missing.
    // Actually, any {{ $foo->bar }} without ?? should get ?? ''
    
    // First, let's fix known problematic ones
    $content = preg_replace('/\{\{\s*(\$[a-zA-Z0-9_>-]+)\s*\}\}/', '{{ $1 ?? \'\' }}', $content);
    
    // Fix existing ones like {{ $foo ?? $bar }} to {{ $foo ?? $bar ?? '' }}
    $content = preg_replace('/\{\{\s*(\$[a-zA-Z0-9_>-]+)\s*\?\?\s*(\$[a-zA-Z0-9_>-]+)\s*\}\}/', '{{ $1 ?? $2 ?? \'\' }}', $content);
    
    // Fix ones with ?? 'DEFAULT' to make sure they are safe?
    // If it has ?? 'DEFAULT', it's already safe.
    
    file_put_contents($file, $content);
}
echo "Applied ?? '' to templates.\n";
