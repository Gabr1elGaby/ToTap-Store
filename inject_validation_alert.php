<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

if (strpos($content, '$errors->any()') === false) {
    $content = str_replace(
        '</x-guest-layout>', 
        "    @if(\$errors->any())\n    <script>alert('VALIDATION ERROR: {{ \$errors->first() }}');</script>\n    @endif\n</x-guest-layout>", 
        $content
    );
    file_put_contents($file, $content);
    echo "Validation alert injected.\n";
} else {
    echo "Validation alert already exists.\n";
}
