<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    '</x-app-layout>', 
    "    @if(\$errors->any())\n    <script>alert('VALIDATION ERROR: {{ \$errors->first() }}');</script>\n    @endif\n    @if(session('error'))\n    <script>alert('MIDTRANS ERROR: {{ session('error') }}');</script>\n    @endif\n</x-app-layout>", 
    $content
);
file_put_contents($file, $content);
echo "Alert injected.\n";
