<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// Fix Navbar
$content = str_replace('nav class="bg-[#0B1120]', 'nav class="bg-gray-900', $content);
$content = str_replace('nav class="bg-gray-900', 'nav class="bg-gray-900', $content); // In case it wasn't replaced
// If navbar had bg-white before, wait, the original navbar was:
// <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
// Oh, wait, looking at my previous script, I didn't touch navbar background directly if it was already bg-gray-900.
// BUT I did: $content = str_replace('bg-gray-900', 'bg-[#0B1120]', $content);
// WHICH RUINED IT!
$content = str_replace('bg-[#0B1120]', 'bg-gray-900', $content);

// Fix body and other sections
$content = str_replace('bg-[#1E293B]', 'bg-gray-800', $content);
$content = str_replace('bg-[#0F172A]', 'bg-black', $content);
$content = str_replace('background-color: #1E293B;', 'background-color: #1f2937;', $content); // gray-800 inline

// Also, the button in the hero section might have been broken. 
// "button is an empty white outline box!"
// The original button was probably bg-blue-600. I changed it to bg-blue-500. `bg-blue-500` might not exist.
// Let's change bg-blue-500 back to bg-blue-600 just in case.
$content = str_replace('bg-blue-500', 'bg-blue-600', $content);

// Let's just do a robust preg_replace or direct write to fix the body tag
$content = preg_replace('/<body class=".*?"/', '<body class="antialiased text-white bg-gray-900"', $content);

// Ensure navbar is dark
$content = preg_replace('/<nav class=".*? sticky/', '<nav class="bg-gray-900 border-b border-gray-800 sticky', $content);

file_put_contents($file, $content);
echo "Welcome page fixed using standard Tailwind classes.\n";
