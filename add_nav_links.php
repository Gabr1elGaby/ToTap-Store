<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$oldLinks = <<<BLADE
                    @else
                        <x-nav-link href="/">
                            {{ __('Beranda') }}
                        </x-nav-link>
                    @endif
BLADE;

$newLinks = <<<BLADE
                    @else
                        <x-nav-link href="/">
                            {{ __('Beranda') }}
                        </x-nav-link>
                        <x-nav-link href="/#keunggulan">
                            {{ __('Keunggulan') }}
                        </x-nav-link>
                        <x-nav-link href="/#products">
                            {{ __('Solusi Produk') }}
                        </x-nav-link>
                    @endif
BLADE;

$content = str_replace($oldLinks, $newLinks, $content);
file_put_contents($file, $content);
echo "Added missing links to main navigation.\n";
