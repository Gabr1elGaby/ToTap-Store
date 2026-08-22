<?php
$file = 'resources/views/cv/index.blade.php';
$content = file_get_contents($file);

// 1. Ubah Body
$oldBody = '<body class="bg-gray-50 text-gray-900 font-sans antialiased">';
$newBody = '<body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" :class="{ \'overflow-hidden\': showLogin || showRegister }">';
$content = str_replace($oldBody, $newBody, $content);

// 2. Tambahkan auth modals di bawah sebelum tutup tag body
$content = str_replace('</body>', "    <x-auth-modals />\n</body>", $content);

// 3. Ubah Logo
$oldLogo = '<div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white font-bold text-lg">G</div>';
$newLogo = '<img src="{{ asset(\'images/totap-logo-circle.png\') . \'?v=\' . time() }}" alt="ToTap Store" class="h-10 w-auto object-contain">';
$content = str_replace($oldLogo, $newLogo, $content);

// 4. Ubah Tombol Gunakan Template (Card)
$oldBtn1 = <<<BLADE
                        <a href="{{ route('cv.create', ['template' => \$template->slug]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded shadow-sm text-white bg-gray-900 hover:bg-gray-800 transition">
                            Gunakan Template
                        </a>
BLADE;
$newBtn1 = <<<BLADE
                        @guest
                        <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded shadow-sm text-white bg-gray-900 hover:bg-gray-800 transition">
                            Gunakan Template
                        </a>
                        @else
                        <a href="{{ route('cv.create', ['template' => \$template->slug]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded shadow-sm text-white bg-gray-900 hover:bg-gray-800 transition">
                            Gunakan Template
                        </a>
                        @endauth
BLADE;
$content = str_replace($oldBtn1, $newBtn1, $content);

// 5. Ubah Tombol Gunakan Template Ini (Modal)
$oldBtn2 = <<<BLADE
                    <a :href="`/cv/create?template=\${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
BLADE;
$newBtn2 = <<<BLADE
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @else
                    <a :href="`/cv/create?template=\${previewSlug}`" class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        Gunakan Template Ini &rarr;
                    </a>
                    @endauth
BLADE;
$content = str_replace($oldBtn2, $newBtn2, $content);

file_put_contents($file, $content);
echo "CV Index updated.\n";
