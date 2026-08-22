<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

$oldHeader = <<<BLADE
        <!-- Product Header -->
        <section class="py-16 bg-gray-800 border-gray-700 border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="md:w-2/3 mx-auto text-center">
BLADE;

$newHeader = <<<BLADE
        <!-- Product Header -->
        <section class="py-16 bg-gray-800 border-gray-700 border-b relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="absolute top-0 left-4 sm:left-6 lg:left-8">
                    <a href="{{ url('/software') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-900 hover:bg-gray-700 hover:text-white rounded-lg border border-gray-700 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>
                <div class="md:w-2/3 mx-auto text-center mt-8 md:mt-0">
BLADE;

$content = str_replace($oldHeader, $newHeader, $content);
file_put_contents($file, $content);
echo "Added back button to products/show.blade.php.\n";
