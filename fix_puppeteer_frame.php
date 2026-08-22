<?php
$file = 'd:/Bisnis/wa-bot/index.js';
if (!file_exists($file)) {
    echo "File not found.";
    exit;
}
$content = file_get_contents($file);

$oldArgs = <<<JS
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});
JS;

$newArgs = <<<JS
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox', 
            '--disable-setuid-sandbox', 
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--disable-gpu'
        ]
    }
});
JS;

$content = str_replace($oldArgs, $newArgs, $content);
file_put_contents($file, $content);
echo "Bot index.js Puppeteer args updated.\n";
