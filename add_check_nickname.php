<?php
$file = 'app/Services/VipResellerService.php';
$content = file_get_contents($file);

$newMethod = <<<PHP
    public function checkNickname(\$gameCode, \$target1, \$target2 = '')
    {
        \$response = Http::asForm()->post("{\$this->baseUrl}/game-feature", [
            'key' => \$this->apiKey,
            'sign' => \$this->generateSign(),
            'type' => 'get-nickname',
            'code' => \$gameCode,
            'target' => \$target1,
            'additional_target' => \$target2
        ]);
        
        return \$response->json();
    }
}
PHP;

$content = preg_replace('/\}\s*$/', "\n" . $newMethod, $content);
file_put_contents($file, $content);
echo "Method checkNickname added to VipResellerService.\n";
