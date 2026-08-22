<?php

// 1. Update Welcome
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$oldCardsWrapper = <<<BLADE
                <!-- Wrapper Centered -->
                <div class="flex justify-center items-center">
                    
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-700" 
                       style="width: 180px; height: 200px; text-decoration: none; background-color: #1f2937; border-radius: 24px;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/kategori-game-4.png') }}" alt="Top Up Game" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.1));">
                        </div>
                        
                        <h3 class="text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">TOP UP GAME</h3>
                    </a>

                </div>
BLADE;

$newCardsWrapper = <<<BLADE
                <!-- Wrapper Centered -->
                <div class="flex flex-wrap justify-center items-center gap-8">
                    
                    <!-- KATEGORI: TOP UP GAME -->
                    <a href="{{ route('topup.index') }}" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-700" 
                       style="width: 180px; height: 200px; text-decoration: none; background-color: #1f2937; border-radius: 24px;"
                       data-aos="zoom-in">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/kategori-game-4.png') }}" alt="Top Up Game" class="w-full h-full object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.1));">
                        </div>
                        
                        <h3 class="text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">TOP UP GAME</h3>
                    </a>

                    <!-- KATEGORI: SOFTWARE ENTERPRISE -->
                    <a href="/software" 
                       class="relative shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col items-center justify-center group border border-gray-700" 
                       style="width: 180px; height: 200px; text-decoration: none; background-color: #1f2937; border-radius: 24px;"
                       data-aos="zoom-in" data-aos-delay="100">
                        
                        <!-- Icon -->
                        <div class="relative flex items-center justify-center mb-2 group-hover:-translate-y-2 transition-transform duration-300" style="width: 130px; height: 130px;">
                            <img src="{{ asset('images/totap-logo-circle.png') }}" alt="Software" class="w-24 h-24 object-contain" style="filter: drop-shadow(0px 8px 10px rgba(0,0,0,0.3));">
                        </div>
                        
                        <h3 class="text-white group-hover:text-blue-600 transition-colors" style="font-family: 'Orbitron', sans-serif; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">SOFTWARE</h3>
                    </a>

                </div>
BLADE;

$content = str_replace($oldCardsWrapper, $newCardsWrapper, $content);
file_put_contents($welcomeFile, $content);

// 2. Add Route
$routeFile = 'routes/web.php';
$routeContent = file_get_contents($routeFile);
if (strpos($routeContent, "Route::get('/software'") === false) {
    $routeContent .= "\nRoute::get('/software', function () { return view('software.index'); })->name('software.index');\n";
    file_put_contents($routeFile, $routeContent);
}

// 3. Create view
$viewDir = 'resources/views/software';
if (!is_dir($viewDir)) {
    mkdir($viewDir, 0755, true);
}
$viewFile = $viewDir . '/index.blade.php';

$viewTemplate = <<<BLADE
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Software Enterprise - ToTap Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
    
    @include('layouts.navigation')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-white mb-4" style="font-family: 'Orbitron', sans-serif; letter-spacing: 1px;">SOFTWARE ENTERPRISE</h1>
                <p class="text-gray-400">Solusi digital untuk meningkatkan produktivitas bisnis dan profesional Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- POS System -->
                <a href="/produk/sistem-kasir-pos" class="group bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden hover:border-blue-500 transition-all duration-300 shadow-lg hover:shadow-blue-500/20">
                    <div class="h-48 bg-gradient-to-br from-indigo-900 to-gray-800 flex items-center justify-center p-6">
                        <h2 class="text-3xl text-white font-bold text-center" style="font-family: 'Orbitron', sans-serif;">SISTEM KASIR (POS)</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-400 mb-4">Aplikasi kasir pintar untuk segala jenis usaha retail dan F&B dengan fitur laporan real-time.</p>
                        <span class="text-blue-400 font-semibold group-hover:text-blue-300 transition-colors flex items-center gap-2">
                            Lihat Produk &rarr;
                        </span>
                    </div>
                </a>

                <!-- CV Generator -->
                <a href="/cv" class="group bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden hover:border-blue-500 transition-all duration-300 shadow-lg hover:shadow-blue-500/20">
                    <div class="h-48 bg-gradient-to-br from-blue-900 to-gray-800 flex items-center justify-center p-6">
                        <h2 class="text-3xl text-white font-bold text-center" style="font-family: 'Orbitron', sans-serif;">SISTEM PEMBUAT CV</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-400 mb-4">Buat Curriculum Vitae profesional dengan berbagai template ramah ATS hanya dalam hitungan menit.</p>
                        <span class="text-blue-400 font-semibold group-hover:text-blue-300 transition-colors flex items-center gap-2">
                            Lihat Produk &rarr;
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @if (Route::has('login'))
        @auth
            <!-- Logged in, no modal needed -->
        @else
            @include('auth.login-modal')
            @include('auth.register-modal')
        @endauth
    @endif
</body>
</html>
BLADE;
file_put_contents($viewFile, $viewTemplate);

echo "Software category built.\n";
