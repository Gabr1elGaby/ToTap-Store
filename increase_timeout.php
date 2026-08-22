<?php
$file = 'app/Services/VipResellerService.php';
$content = file_get_contents($file);

$oldCode = "Http::asForm()->post(\"{\$this->baseUrl}/game-feature\"";
$newCode = "Http::timeout(120)->asForm()->post(\"{\$this->baseUrl}/game-feature\"";

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "Timeout increased.\n";
