<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldFuncStart = 'public function syncProcess(Request $request, Game $game, VipResellerService $api)' . "\n" . '    {';
$newFuncStart = 'public function syncProcess(Request $request, Game $game, VipResellerService $api)' . "\n" . '    {' . "\n" . '        // Cegah PHP membunuh proses sebelum selesai (Beri waktu 5 menit)' . "\n" . '        set_time_limit(300);';

$content = str_replace($oldFuncStart, $newFuncStart, $content);
file_put_contents($file, $content);
echo "Time limit increased.\n";
