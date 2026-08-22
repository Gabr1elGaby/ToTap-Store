<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldLogic = <<<BLADE
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-800" 
                       style="width: 180px; height: 200px; text-decoration: none; background-color: #050505;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px; border-radius: 50%; overflow: hidden; box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);">
                            <img src="{{ asset('images/kategori-game-2.jpg') }}" alt="Top Up Game" class="w-full h-full object-cover">
                        </div>
                        
                        <h3 class="text-white font-bold text-base group-hover:text-blue-400 transition-colors">Top Up Game</h3>
                    </a>
BLADE;

$newLogic = <<<BLADE
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-100" 
                       style="width: 180px; height: 200px; text-decoration: none; background-color: white; border-radius: 24px;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/kategori-game-3.png') }}" alt="Top Up Game" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.1));">
                        </div>
                        
                        <h3 class="text-gray-900 font-bold text-base group-hover:text-blue-600 transition-colors">Top Up Game</h3>
                    </a>
BLADE;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "Card reverted to white with rounded corners and transparent logo.\n";
