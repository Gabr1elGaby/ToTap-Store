<?php
$file = 'app/Services/VipResellerService.php';
$content = file_get_contents($file);

// Replace Http::timeout(120)->asForm() with Http::connectTimeout(30)->timeout(120)->retry(3, 2000)->asForm()
// But wait, the previous code might be just Http::timeout(120) or Http::asForm() depending on what I actually replaced.
// Let's check exactly what's there.
$oldCode = "Http::timeout(120)->asForm()->post(\"{\$this->baseUrl}/game-feature\"";
$newCode = "Http::connectTimeout(60)->timeout(120)->retry(3, 2000)->asForm()->post(\"{\$this->baseUrl}/game-feature\"";

// Just in case my previous replace failed or was slightly different, let's use a regex that handles both
$content = preg_replace(
    '/Http::(?:timeout\(\d+\)->)?asForm\(\)->post\("{\$this->baseUrl}\/game-feature"/',
    'Http::connectTimeout(60)->timeout(120)->retry(3, 2000)->asForm()->post("{$this->baseUrl}/game-feature"',
    $content
);

file_put_contents($file, $content);
echo "Added connect timeout and retries to VIP Reseller API call.\n";
