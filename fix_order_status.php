<?php
$routeFile = 'routes/web.php';
$content = file_get_contents($routeFile);

$oldLine = "\$totalTransactions += \App\Models\Order::whereIn('status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();";
$newLine = "\$totalTransactions += \App\Models\Order::whereIn('payment_status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();";

$content = str_replace($oldLine, $newLine, $content);
file_put_contents($routeFile, $content);
echo "Fixed status column in routes/web.php\n";
