<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldLogic = <<<BLADE
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-100" 
                       style="width: 180px; height: 200px; text-decoration: none;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-3 group-hover:-translate-y-2 transition-transform duration-300" style="width: 100px; height: 100px;">
BLADE;

$newLogic = <<<BLADE
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-100" 
                       style="width: 220px; height: 220px; text-decoration: none;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 160px; height: 160px;">
BLADE;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "Logo and card size increased.\n";
