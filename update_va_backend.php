<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
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

$newLogic = <<<PHP
            } elseif (in_array(\$request->payment_method, ['bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va'])) {
                \$bank = str_replace('_va', '', \$request->payment_method);
                
                \$coreParams = [
                    'payment_type' => (\$bank === 'mandiri') ? 'echannel' : 'bank_transfer',
                    'transaction_details' => [
                        'order_id' => \$orderId,
                        'gross_amount' => (int) \$product->price_sell,
                    ]
                ];
                
                if (\$bank === 'mandiri') {
                    \$coreParams['echannel'] = [
                        'bill_info1' => 'Payment For',
                        'bill_info2' => 'Top Up Game'
                    ];
                } else {
                    \$coreParams['bank_transfer'] = [
                        'bank' => \$bank
                    ];
                }
                
                \$response = \Midtrans\CoreApi::charge(\$coreParams);
                
                \$vaNumber = '';
                if (\$bank === 'mandiri' && isset(\$response->bill_key)) {
                    \$vaNumber = \$response->biller_code . ' - ' . \$response->bill_key;
                } elseif (isset(\$response->va_numbers[0])) {
                    \$vaNumber = \$response->va_numbers[0]->va_number;
                }
                
                \$snapData = json_encode(['type' => 'va', 'bank' => strtoupper(\$bank), 'va_number' => \$vaNumber]);
                \$transaction->update(['snap_token' => \$snapData]);
            }
PHP;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "Controller updated for multiple VAs.\n";
