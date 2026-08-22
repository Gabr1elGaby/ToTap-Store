<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$mobileLink = <<<HTML
            <x-responsive-nav-link :href="route('admin.cv-templates.index')" :active="request()->routeIs('admin.cv-templates.*')">
                {{ __('CV Templates') }}
            </x-responsive-nav-link>
HTML;

$content = str_replace(
    "<x-responsive-nav-link :href=\"route('admin.plans.index')\" :active=\"request()->routeIs('admin.plans.*')\">\n                {{ __('Plans') }}\n            </x-responsive-nav-link>",
    "<x-responsive-nav-link :href=\"route('admin.plans.index')\" :active=\"request()->routeIs('admin.plans.*')\">\n                {{ __('Plans') }}\n            </x-responsive-nav-link>\n$mobileLink",
    $content
);

file_put_contents($file, $content);
echo "Added CV Templates to mobile navigation.\n";
