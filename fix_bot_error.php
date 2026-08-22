<?php
$file = 'd:/Bisnis/wa-bot/index.js';
if (!file_exists($file)) {
    echo "File not found.";
    exit;
}
$content = file_get_contents($file);

$oldLogic = <<<JS
    try {
        await client.sendMessage(chatId, message);
        console.log(`[BERHASIL] OTP \${otp} dikirim ke \${formattedPhone}`);
        return res.json({ success: true, message: 'OTP terkirim' });
    } catch (error) {
        console.error(`[GAGAL] Gagal mengirim ke \${formattedPhone}:`, error);
        return res.status(500).json({ error: 'Gagal mengirim pesan WhatsApp' });
    }
JS;

$newLogic = <<<JS
    try {
        await client.sendMessage(chatId, message);
        console.log(`[BERHASIL] OTP \${otp} dikirim ke \${formattedPhone}`);
        return res.json({ success: true, message: 'OTP terkirim' });
    } catch (error) {
        console.error(`[GAGAL] Gagal mengirim ke \${formattedPhone}:`, error);
        // Mengirimkan error detail ke Laravel agar tampil di web
        return res.status(500).json({ error: 'Gagal: ' + (error.message || error.toString()) });
    }
JS;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "Bot index.js updated.\n";
