<?php
$env = file_get_contents(__DIR__ . "/.env");
preg_match("/DB_HOST=(.*)/", $env, $mHost);
preg_match("/DB_DATABASE=(.*)/", $env, $mDb);
preg_match("/DB_USERNAME=(.*)/", $env, $mUser);
preg_match("/DB_PASSWORD=(.*)/", $env, $mPass);

$host = trim($mHost[1] ?? "localhost", " \'\r\n\"");
$db   = trim($mDb[1] ?? "", " \'\r\n\"");
$user = trim($mUser[1] ?? "", " \'\r\n\"");
$pass = trim($mPass[1] ?? "", " \'\r\n\"");

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$stmt = $pdo->query("SELECT id, name, price_modal, price_sell, status FROM game_products WHERE game_id IN (SELECT id FROM games WHERE slug LIKE '%mobile-legend%') ORDER BY price_sell ASC LIMIT 20");
echo "<h2>MLBB First 20 Products:</h2><pre>" . print_r($stmt->fetchAll(PDO::FETCH_ASSOC), true) . "</pre>";
