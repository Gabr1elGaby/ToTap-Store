<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// 1. Navbar Logo
$content = preg_replace(
    '/<img src="\{\{ asset\(\'images\/totap-logo\.png\'\).*?>\s*<span.*?TOTAP STORE<\/span>/s',
    '<img src="{{ asset(\'images/logo-totap.png\') }}" alt="ToTap Store" class="h-16 w-auto object-contain drop-shadow-[0_0_15px_rgba(59,130,246,0.5)]">',
    $content
);

// 2. Global Body
$content = str_replace('text-gray-900 bg-gray-50', 'text-white bg-[#0B1120]', $content);

// 3. Hero Section
$content = str_replace('bg-gray-900', 'bg-[#0B1120]', $content); // if any
$content = str_replace('text-gray-400', 'text-gray-400', $content); // keep
// Hero title is usually white anyway because it's a dark hero section.

// 4. Sections (Keunggulan, Kategori, Produk)
$content = str_replace('bg-white', 'bg-[#1E293B]', $content);
$content = str_replace('bg-gray-50', 'bg-[#0F172A]', $content);
$content = str_replace('text-gray-900', 'text-white', $content);
$content = str_replace('text-gray-800', 'text-gray-200', $content);
$content = str_replace('text-gray-700', 'text-gray-300', $content);
$content = str_replace('text-gray-600', 'text-gray-400', $content);
$content = str_replace('text-gray-500', 'text-gray-400', $content);
$content = str_replace('border-gray-100', 'border-gray-700', $content);
$content = str_replace('border-gray-200', 'border-gray-700', $content);

// 5. Kategori Section Card (from fix_white_card.php)
$content = str_replace('background-color: white;', 'background-color: #1E293B;', $content);
$content = str_replace('bg-blue-100 text-blue-700', 'bg-blue-900/40 text-blue-400 border border-blue-500/30', $content);
$content = str_replace('bg-blue-600', 'bg-blue-500', $content); // Brighten buttons

// Fix up the Kategori Section that we hardcoded
$content = str_replace('text-gray-900', 'text-white', $content);

file_put_contents($file, $content);
echo "Welcome page converted to dark theme.\n";
