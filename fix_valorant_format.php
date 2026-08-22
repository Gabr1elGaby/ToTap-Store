<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldMethod = <<<PHP
    public function checkNickname(Request \$request, \$slug)
    {
        try {
            \$game = Game::where('slug', \$slug)->firstOrFail();
            
            \$api = app(\App\Services\VipResellerService::class);
            \$response = \$api->checkNickname(\$game->slug, \$request->player_id, \$request->zone_id ?? '');
            
            return response()->json(\$response);
        } catch (\Exception \$e) {
            return response()->json([
                'result' => false,
                'message' => 'Gangguan Server: ' . \$e->getMessage()
            ]);
        }
    }
PHP;

$newMethod = <<<PHP
    public function checkNickname(Request \$request, \$slug)
    {
        try {
            \$game = Game::where('slug', \$slug)->firstOrFail();
            \$api = app(\App\Services\VipResellerService::class);
            
            \$target1 = \$request->player_id;
            \$target2 = \$request->zone_id ?? '';
            
            // Format khusus untuk Valorant (Digabungkan dengan #)
            if (strtolower(\$game->slug) === 'valorant') {
                // Hapus # jika user terlanjur ngetik
                \$target2 = ltrim(\$target2, '#');
                \$target1 = \$target1 . '#' . \$target2;
                \$target2 = '';
            }
            
            \$response = \$api->checkNickname(\$game->slug, \$target1, \$target2);
            
            // Decode URL Encoding dari VIP Reseller (e.g. 4Some1%20%2321104 -> 4Some1 #21104)
            if (isset(\$response['result']) && \$response['result'] === true && isset(\$response['data'])) {
                if (is_string(\$response['data'])) {
                    \$response['data'] = urldecode(\$response['data']);
                }
            }
            
            return response()->json(\$response);
        } catch (\Exception \$e) {
            return response()->json([
                'result' => false,
                'message' => 'Gangguan Server: ' . \$e->getMessage()
            ]);
        }
    }
PHP;

$content = str_replace($oldMethod, $newMethod, $content);
file_put_contents($file, $content);
echo "Valorant format fix applied.\n";
