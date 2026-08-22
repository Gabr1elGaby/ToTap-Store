<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldMidtrans = <<<PHP
        // Konfigurasi Midtrans
        \Midtrans\Config::\$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::\$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::\$isSanitized = true;
        \Midtrans\Config::\$is3ds = true;
PHP;

$newMidtrans = <<<PHP
        // Konfigurasi Midtrans
        \Midtrans\Config::\$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::\$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::\$isSanitized = true;
        \Midtrans\Config::\$is3ds = true;
        
        // MATIKAN SSL VERIFICATION AGAR JALAN DI LOCALHOST WINDOWS
        \Midtrans\Config::\$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ];
PHP;

$content = str_replace($oldMidtrans, $newMidtrans, $content);
file_put_contents($file, $content);
echo "SSL Disabled for local dev.\n";
