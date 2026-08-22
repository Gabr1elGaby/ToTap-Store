<?php
$viewFile = 'resources/views/software/index.blade.php';

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

    <div class="py-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="text-blue-500 font-bold uppercase tracking-widest text-xs mb-2">SOFTWARE ENTERPRISE</h2>
                <h3 class="text-3xl font-extrabold text-white">Solusi Berbasis Lisensi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Card 1 -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 shadow flex flex-col p-8">
                    <h3 class="text-xl font-bold text-white mb-2">Sistem Kasir (POS)</h3>
                    <p class="text-sm text-gray-400 mb-6">Aplikasi kasir pintar untuk segala jenis usaha retail dan F&B.</p>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-300">Laporan Keuangan Real-time</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-300">Sistem Manajemen Stok & Inventaris</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-300">Akses Multi User (Admin & Kasir)</span>
                        </li>
                    </ul>

                    <div class="mt-auto border-t border-gray-700 pt-6 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">LISENSI BULANAN</p>
                            <p class="text-3xl font-bold text-white">Rp 50.000</p>
                        </div>
                        <a href="/produk/sistem-kasir-pos" class="bg-gray-900 border border-gray-700 text-white hover:bg-gray-700 px-5 py-2 rounded font-semibold transition text-sm">Beli Lisensi</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 shadow flex flex-col p-8">
                    <h3 class="text-xl font-bold text-white mb-2">Sistem Pembuat CV</h3>
                    <p class="text-sm text-gray-400 mb-6">Sistem pembuatan CV profesional otomatis dengan desain modern.</p>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-300">Berbagai Template Profesional (ATS, Kreatif)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-300">Split-Screen Realtime Preview</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm text-gray-300">Download PDF Kualitas Tinggi</span>
                        </li>
                    </ul>

                    <div class="mt-auto border-t border-gray-700 pt-6 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">HARGA TEMPLATE</p>
                            <p class="text-3xl font-bold text-white">Rp 5.000</p>
                        </div>
                        <a href="/cv" class="bg-gray-900 border border-gray-700 text-white hover:bg-gray-700 px-5 py-2 rounded font-semibold transition text-sm">Buat CV</a>
                    </div>
                </div>
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
echo "Restored original software cards design to software index page.\n";
