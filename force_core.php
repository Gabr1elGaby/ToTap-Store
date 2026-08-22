<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$start = strpos($content, 'try {');
$end = strpos($content, 'return redirect()->route', $start);

$newTry = <<<PHP
        try {
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
            } else {
                // Fallback to snap if needed, but we don't need it.
                \$snapToken = \Midtrans\Snap::getSnapToken(\$params);
                \$transaction->update(['snap_token' => \$snapToken]);
            }
            
            // Arahkan ke halaman checkout
            
PHP;

$content = substr_replace($content, $newTry, $start, $end - $start);

// Also we need to clean up `$params` because we don't need `enabled_payments` anymore
// But leaving `$params` there doesn't hurt.

file_put_contents($file, $content);
echo "Core API forcefully injected!\n";
