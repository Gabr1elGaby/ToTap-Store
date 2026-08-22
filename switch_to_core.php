<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
        // Dapatkan Snap Token dari Midtrans
        \$snapToken = \Midtrans\Snap::getSnapToken(\$params);
        
        // Simpan token ke database
        \$transaction->update(['snap_token' => \$snapToken]);
PHP;

$newLogic = <<<PHP
        // GUNAKAN CORE API (Native QRIS / VA)
        if (\$request->payment_method === 'qris') {
            \$coreParams = [
                'payment_type' => 'gopay',
                'transaction_details' => [
                    'order_id' => \$orderId,
                    'gross_amount' => (int) \$product->price_sell,
                ]
            ];
            \$response = \Midtrans\CoreApi::charge(\$coreParams);
            
            // Cari QR Code URL di actions array
            \$qrUrl = '';
            if (isset(\$response->actions)) {
                foreach (\$response->actions as \$action) {
                    if (\$action->name === 'generate-qr-code') {
                        \$qrUrl = \$action->url;
                    }
                }
            }
            
            \$snapData = json_encode(['type' => 'qris', 'qr_url' => \$qrUrl]);
            \$transaction->update(['snap_token' => \$snapData]);
            
        } elseif (\$request->payment_method === 'bank_transfer') {
            \$coreParams = [
                'payment_type' => 'bank_transfer',
                'bank_transfer' => [
                    'bank' => 'bca'
                ],
                'transaction_details' => [
                    'order_id' => \$orderId,
                    'gross_amount' => (int) \$product->price_sell,
                ]
            ];
            \$response = \Midtrans\CoreApi::charge(\$coreParams);
            
            \$vaNumber = '';
            if (isset(\$response->va_numbers[0])) {
                \$vaNumber = \$response->va_numbers[0]->va_number;
            }
            
            \$snapData = json_encode(['type' => 'va', 'bank' => 'BCA', 'va_number' => \$vaNumber]);
            \$transaction->update(['snap_token' => \$snapData]);
        }
PHP;

$content = str_replace($oldLogic, $newLogic, $content);

// Remove the `enabled_payments` stuff I added earlier
$content = preg_replace('/\$enabledPayments = \[\];.*?if \(!empty\(\$enabledPayments\)\) \{.*?\}\s*/s', '', $content);

file_put_contents($file, $content);
echo "Controller updated to Core API.\n";
