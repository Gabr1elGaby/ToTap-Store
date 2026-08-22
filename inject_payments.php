<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldParams = <<<PHP
        \$params = [
            'transaction_details' => [
                'order_id' => \$orderId,
                'gross_amount' => (int) \$product->price_sell,
            ],
            'item_details' => [
                [
                    'id' => \$product->product_code,
                    'price' => (int) \$product->price_sell,
                    'quantity' => 1,
                    'name' => substr(\$product->name, 0, 50),
                ]
            ],
            'customer_details' => [
                'first_name' => \$request->player_id,
                'last_name' => \$request->zone_id ?? '',
            ],
        ];
PHP;

$newParams = <<<PHP
        \$enabledPayments = [];
        if (\$request->payment_method === 'qris') {
            \$enabledPayments = ['gopay', 'shopeepay', 'other_qris'];
        } elseif (\$request->payment_method === 'bank_transfer') {
            \$enabledPayments = ['bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va'];
        }

        \$params = [
            'transaction_details' => [
                'order_id' => \$orderId,
                'gross_amount' => (int) \$product->price_sell,
            ],
            'item_details' => [
                [
                    'id' => \$product->product_code,
                    'price' => (int) \$product->price_sell,
                    'quantity' => 1,
                    'name' => substr(\$product->name, 0, 50),
                ]
            ],
            'customer_details' => [
                'first_name' => \$request->player_id,
                'last_name' => \$request->zone_id ?? '',
            ],
        ];
        
        if (!empty(\$enabledPayments)) {
            \$params['enabled_payments'] = \$enabledPayments;
        }
PHP;

$content = str_replace($oldParams, $newParams, $content);
file_put_contents($file, $content);
echo "Params updated.\n";
