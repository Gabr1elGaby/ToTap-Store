<?php
header("X-LiteSpeed-Purge: *");
if (function_exists("opcache_reset")) {
    @opcache_reset();
}

$files = [
    'app/Http/Controllers/TopUpController.php',
    'app/Http/Controllers/TopupController.php',
    'app/Http/Controllers/TopUpPaymentController.php',
    'app/Http/Controllers/Api/DuitkuCallbackController.php',
    'app/Http/Controllers/Api/TripayCallbackController.php',
    'app/Services/DuitkuService.php',
    'app/Services/TripayService.php',
    'config/services.php',
    'resources/views/topup/show.blade.php',
    'resources/views/topup/checkout.blade.php',
    'routes/web.php',
    'bootstrap/app.php',
];

$baseDir = __DIR__;
if (!file_exists($baseDir . '/app') && file_exists($baseDir . '/../app')) {
    $baseDir = $baseDir . '/..';
}

$branch = 'main';
$repo = 'Gabr1elGaby/ToTap-Store';
$updated = 0;

foreach ($files as $file) {
    $url = "https://raw.githubusercontent.com/$repo/$branch/$file?t=" . time();
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 10,
            'header'  => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ]
    ]);
    
    $content = @file_get_contents($url, false, $ctx);
    if ($content !== false && strlen($content) > 10) {
        $dest = $baseDir . '/' . $file;
        @mkdir(dirname($dest), 0777, true);
        @file_put_contents($dest, $content);
        $updated++;
    }
}

// UPDATE DATABASE
$envFile = $baseDir . '/.env';
if (file_exists($envFile)) {
    $env = file_get_contents($envFile);
    preg_match("/DB_HOST=(.*)/", $env, $mHost);
    preg_match("/DB_DATABASE=(.*)/", $env, $mDb);
    preg_match("/DB_USERNAME=(.*)/", $env, $mUser);
    preg_match("/DB_PASSWORD=(.*)/", $env, $mPass);

    $host = trim($mHost[1] ?? "127.0.0.1", " \'\r\n\"");
    $db   = trim($mDb[1] ?? "", " \'\r\n\"");
    $user = trim($mUser[1] ?? "", " \'\r\n\"");
    $pass = trim($mPass[1] ?? "", " \'\r\n\"");

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        $pdo->exec("UPDATE games SET requires_zone_id = 0, target_field_1 = 'Riot ID (Username#TAG)', target_field_2 = NULL WHERE slug = 'valorant'");
        $pdo->exec("UPDATE games SET requires_zone_id = 0, target_field_1 = 'Player ID', target_field_2 = NULL WHERE slug IN ('free-fire', 'freefire')");
        $pdo->exec("UPDATE games SET requires_zone_id = 0, target_field_1 = 'User ID', target_field_2 = NULL WHERE slug IN ('pubg-mobile', 'pubg')");
        $pdo->exec("UPDATE games SET requires_zone_id = 0, target_field_1 = 'Username Roblox', target_field_2 = NULL WHERE slug = 'roblox'");
        $pdo->exec("UPDATE games SET requires_zone_id = 1, target_field_1 = 'User ID', target_field_2 = 'Zone ID' WHERE slug IN ('mobile-legend', 'mobile-legends')");
        
        try {
            $pdo->exec("ALTER TABLE transactions ADD COLUMN payment_reference VARCHAR(255) NULL AFTER snap_token");
        } catch(Exception $ex) {}
    } catch(Exception $e) {}
}

// Clear Views Cache
$vDir = $baseDir . "/storage/framework/views";
$count = 0;
if (is_dir($vDir)) {
    $files = glob($vDir . "/*");
    foreach($files as $f) {
        if (is_file($f) && basename($f) !== '.gitignore') {
            @unlink($f);
            $count++;
        }
    }
}

if (function_exists("opcache_reset")) {
    @opcache_reset();
}

echo "<h3>Files Updated from GitHub: $updated</h3>";
echo "<h3>Views Cache Dihapus: $count file</h3>";
echo "<h1 style='color:green; font-family:sans-serif;'>🎉 SERVER DISINKRONKAN 100% SUKSES!</h1>";
