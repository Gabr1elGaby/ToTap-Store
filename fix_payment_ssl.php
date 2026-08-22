<?php
$file = 'app/Http/Controllers/TopUpPaymentController.php';
$content = file_get_contents($file);

$oldMidtrans = <<<PHP
        \Midtrans\Config::\$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::\$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
PHP;

$newMidtrans = <<<PHP
        \Midtrans\Config::\$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::\$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::\$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ];
PHP;

$content = str_replace($oldMidtrans, $newMidtrans, $content);
file_put_contents($file, $content);
echo "SSL Disabled in PaymentController.\n";
