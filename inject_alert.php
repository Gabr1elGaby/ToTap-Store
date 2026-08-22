<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

if (strpos($content, "session('error')") === false) {
    $content = str_replace(
        '</x-guest-layout>', 
        "    @if(session('error'))\n    <script>alert('ERROR: {{ session('error') }}');</script>\n    @endif\n</x-guest-layout>", 
        $content
    );
    file_put_contents($file, $content);
    echo "Error alert injected.\n";
} else {
    echo "Alert already exists.\n";
}
