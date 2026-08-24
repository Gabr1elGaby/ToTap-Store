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

$stmt = $pdo->query("SELECT * FROM settings");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "SETTINGS TABLE:\n";
print_r($rows);

// Test API VIP Reseller
$apiId = "UEsJ21pX";
$apiKey = "wTpFb8UKOona2Hm56HODEruuB7F2aAE0MQU2dXgjjRy1Q2lCUUfL7Un9mcgxtLRy";
$sign = md5($apiId . $apiKey);

$ch = curl_init("https://vip-reseller.co.id/api/profile");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "key" => $apiKey,
    "sign" => $sign
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

echo "\nVIP RESELLER API RESPONSE FROM LIVE SERVER:\n";
echo $res . "\n";
