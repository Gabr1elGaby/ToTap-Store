<?php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

// Replace Step 1
$content = preg_replace(
    '/<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900\/20">\s*<span class="text-3xl font-black text-blue-500" style="font-family: \'Orbitron\', sans-serif;">1<\/span>\s*<\/div>\s*<h4 class="text-lg font-bold text-white mb-2">Buat Akun<\/h4>/',
    '<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2"><span class="text-blue-500 mr-2">1.</span>Buat Akun</h4>',
    $content
);

// Replace Step 2
$content = preg_replace(
    '/<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900\/20">\s*<span class="text-3xl font-black text-blue-500" style="font-family: \'Orbitron\', sans-serif;">2<\/span>\s*<\/div>\s*<h4 class="text-lg font-bold text-white mb-2">Pilih Kategori<\/h4>/',
    '<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2"><span class="text-blue-500 mr-2">2.</span>Pilih Kategori</h4>',
    $content
);

// Replace Step 3
$content = preg_replace(
    '/<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900\/20">\s*<span class="text-3xl font-black text-blue-500" style="font-family: \'Orbitron\', sans-serif;">3<\/span>\s*<\/div>\s*<h4 class="text-lg font-bold text-white mb-2">Pembayaran<\/h4>/',
    '<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2"><span class="text-blue-500 mr-2">3.</span>Pembayaran</h4>',
    $content
);

// Replace Step 4
$content = preg_replace(
    '/<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900\/20">\s*<span class="text-3xl font-black text-blue-500" style="font-family: \'Orbitron\', sans-serif;">4<\/span>\s*<\/div>\s*<h4 class="text-lg font-bold text-white mb-2">Selesai<\/h4>/',
    '<div class="w-24 h-24 mx-auto bg-gray-800 border-4 border-gray-900 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-blue-900/20">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2"><span class="text-blue-500 mr-2">4.</span>Selesai</h4>',
    $content
);

file_put_contents($welcomeFile, $content);
echo "Added icons to tutorial section.\n";
