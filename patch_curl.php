<?php
$files = ['app/Http/Controllers/TopUpController.php', 'app/Http/Controllers/TopUpPaymentController.php'];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $oldCurl = <<<PHP
        \Midtrans\Config::\$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ];
PHP;

    $newCurl = <<<PHP
        \Midtrans\Config::\$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [], // Fix Midtrans SDK Bug
        ];
PHP;

    $content = str_replace($oldCurl, $newCurl, $content);
    file_put_contents($file, $content);
}
echo "Curl options patched.\n";
