<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$link = <<<HTML
                        <x-nav-link :href="route('admin.cv-templates.index')" :active="request()->routeIs('admin.cv-templates.*')">
                            {{ __('CV Templates') }}
                        </x-nav-link>
HTML;

$content = str_replace(
    "<x-nav-link :href=\"route('admin.plans.index')\" :active=\"request()->routeIs('admin.plans.*')\">\n                            {{ __('Plans') }}\n                        </x-nav-link>",
    "<x-nav-link :href=\"route('admin.plans.index')\" :active=\"request()->routeIs('admin.plans.*')\">\n                            {{ __('Plans') }}\n                        </x-nav-link>\n$link",
    $content
);

file_put_contents($file, $content);
echo "Added CV Templates to navigation.\n";
