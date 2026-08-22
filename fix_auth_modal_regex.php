<?php
$file = 'resources/views/components/auth-modals.blade.php';
$content = file_get_contents($file);

// Kita cari penutup form persis sebelum form OTP dimulai
$pattern = '/(<span x-show="!loading">Daftar<\/span>\s*<span x-show="loading">Memproses\.\.\.<\/span>\s*<\/button>\s*<\/form>)/ms';

$link = <<<HTML
<div class="mt-4 text-center text-sm text-gray-600">
                    Sudah punya lisensi? <a href="#" @click.prevent="showRegister = false; showLogin = true" class="text-blue-600 hover:underline font-bold">Masuk di sini</a>
                </div>
HTML;

$content = preg_replace($pattern, "$1\n$link", $content);
file_put_contents($file, $content);
echo "Auth modals fixed via regex.\n";
