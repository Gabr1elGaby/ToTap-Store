<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// Remove the entire <section id="software"> or <section id="products"> 
// It starts with <!-- Products Section --> and ends with </section>
$content = preg_replace('/<!-- Products Section -->.*?<\/section>/s', '', $content, 1);

file_put_contents($welcomeFile, $content);
echo "Products section removed from welcome page.\n";

$navFile = 'resources/views/layouts/navigation.blade.php';
$navContent = file_get_contents($navFile);

// Remove Solusi Produk link
$navContent = preg_replace('/<x-nav-link href="#products">.*?<\/x-nav-link>/s', '', $navContent);

file_put_contents($navFile, $navContent);
echo "Solusi Produk link removed from navbar.\n";
