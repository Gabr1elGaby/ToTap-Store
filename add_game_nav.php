<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$search = "                        <x-nav-link :href=\"route('admin.products.index')\" :active=\"request()->routeIs('admin.products.*')\">\n                            {{ __('Products') }}\n                        </x-nav-link>";
$replace = $search . "\n                        <x-nav-link :href=\"route('admin.games.index')\" :active=\"request()->routeIs('admin.games.*')\">\n                            {{ __('Top Up Game') }}\n                        </x-nav-link>";

if (strpos($content, $search) !== false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added desktop link.\n";
} else {
    echo "Desktop link not found.\n";
}

$searchMobile = "                <x-responsive-nav-link :href=\"route('admin.products.index')\" :active=\"request()->routeIs('admin.products.*')\">\n                    {{ __('Products') }}\n                </x-responsive-nav-link>";
$replaceMobile = $searchMobile . "\n                <x-responsive-nav-link :href=\"route('admin.games.index')\" :active=\"request()->routeIs('admin.games.*')\">\n                    {{ __('Top Up Game') }}\n                </x-responsive-nav-link>";

if (strpos($content, $searchMobile) !== false) {
    $content = str_replace($searchMobile, $replaceMobile, $content);
    file_put_contents($file, $content);
    echo "Added mobile link.\n";
} else {
    echo "Mobile link not found.\n";
}
