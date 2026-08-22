<?php
$file = 'app/Http/Controllers/Auth/RegisteredUserController.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
        // Panggil server WhatsApp Bot (Node.js) untuk mengirim pesan
        try {
            \$waResponse = \Illuminate\Support\Facades\Http::timeout(5)->post('http://127.0.0.1:3001/send-otp', [
                'phone' => \$phone,
                'otp' => \$otp
            ]);
PHP;

$newLogic = <<<PHP
        // Panggil server WhatsApp Bot (Node.js) untuk mengirim pesan
        try {
            \$waResponse = \Illuminate\Support\Facades\Http::timeout(20)->post('http://127.0.0.1:3001/send-otp', [
                'phone' => \$phone,
                'otp' => \$otp
            ]);
PHP;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "Timeout increased to 20 seconds.\n";
