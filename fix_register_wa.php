<?php
$file = 'app/Http/Controllers/Auth/RegisteredUserController.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
        // Panggil server WhatsApp Bot (Node.js) untuk mengirim pesan
        try {
            \Illuminate\Support\Facades\Http::timeout(5)->post('http://127.0.0.1:3001/send-otp', [
                'phone' => \$phone,
                'otp' => \$otp
            ]);
        } catch (\Exception \$e) {
            return response()->json([
                'errors' => ['phone_number' => ['Gagal terhubung ke Server WhatsApp Bot. Pastikan server bot sedang berjalan.']]
            ], 422);
        }
PHP;

$newLogic = <<<PHP
        // Panggil server WhatsApp Bot (Node.js) untuk mengirim pesan
        try {
            \$waResponse = \Illuminate\Support\Facades\Http::timeout(5)->post('http://127.0.0.1:3001/send-otp', [
                'phone' => \$phone,
                'otp' => \$otp
            ]);
            
            if (!\$waResponse->successful()) {
                \$errorMsg = \$waResponse->json('error') ?? 'Gagal mengirim pesan WhatsApp. Pastikan nomor HP aktif.';
                return response()->json([
                    'errors' => ['phone_number' => [\$errorMsg]]
                ], 422);
            }
        } catch (\Exception \$e) {
            return response()->json([
                'errors' => ['phone_number' => ['Gagal terhubung ke Server WhatsApp Bot. Pastikan server bot sedang berjalan.']]
            ], 422);
        }
PHP;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "RegisteredUserController WA logic updated.\n";
