<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$oldLinks = <<<BLADE
                        <x-nav-link href="/#keunggulan">
                            {{ __('Keunggulan') }}
                        </x-nav-link>
                        <x-nav-link href="/#products">
                            {{ __('Solusi Produk') }}
                        </x-nav-link>
BLADE;

$newLinks = <<<BLADE
                        @if(request()->is('/'))
                            <x-nav-link href="#keunggulan">
                                {{ __('Keunggulan') }}
                            </x-nav-link>
                            <x-nav-link href="#products">
                                {{ __('Solusi Produk') }}
                            </x-nav-link>
                        @endif
BLADE;

$content = str_replace($oldLinks, $newLinks, $content);
file_put_contents($file, $content);
echo "Navigation links made conditional.\n";
