<?php
$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

// Remove desktop link
$content = preg_replace('/<x-nav-link :href="route\(\'admin\.cv-templates\.index\'\)".*?<\/x-nav-link>/s', '', $content);
// Remove mobile link
$content = preg_replace('/<x-responsive-nav-link :href="route\(\'admin\.cv-templates\.index\'\)".*?<\/x-responsive-nav-link>/s', '', $content);

file_put_contents($file, $content);
echo "Removed from navigation.\n";
