<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

// Add ini_set just in case
$content = str_replace(
    'set_time_limit(300);',
    "set_time_limit(300);\n        ini_set('max_execution_time', '300');",
    $content
);

// Wrap API call in try catch
$oldApiCall = <<<PHP
        \$response = \$api->getGameProducts(\$request->filter_value);

        if (!isset(\$response['result']) || !\$response['result']) {
            return back()->with('error', 'Gagal menarik data dari VIP Reseller. ' . (\$response['message'] ?? ''));
        }
PHP;

$newApiCall = <<<PHP
        try {
            \$response = \$api->getGameProducts(\$request->filter_value);

            if (!isset(\$response['result']) || !\$response['result']) {
                return back()->with('error', 'Gagal menarik data dari VIP Reseller. ' . (\$response['message'] ?? ''));
            }
        } catch (\Exception \$e) {
            return back()->with('error', 'Koneksi ke VIP Reseller terputus atau sangat lambat (Time Out). Ini biasanya karena server pusat sedang kepenuhan/sibuk. Silakan coba lagi beberapa saat. Detail: ' . \$e->getMessage());
        }
PHP;

$content = str_replace($oldApiCall, $newApiCall, $content);
file_put_contents($file, $content);
echo "Added try-catch.\n";
