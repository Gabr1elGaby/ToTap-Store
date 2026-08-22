<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldCode = <<<PHP
        \$transaction = \App\Models\Transaction::create([
            'id' => \$orderId,
PHP;

$newCode = <<<PHP
        \$transaction = \App\Models\Transaction::create([
            'id' => \$orderId,
            'user_id' => auth()->id(), // Mencatat ID Pembeli
PHP;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "User ID mapping injected.\n";
