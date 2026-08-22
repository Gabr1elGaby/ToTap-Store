<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldMethod = <<<PHP
    public function checkNickname(Request \$request, \$slug)
    {
        \$game = Game::where('slug', \$slug)->firstOrFail();
        
        \$api = app(\App\Services\VipResellerService::class);
        \$response = \$api->checkNickname(\$game->slug, \$request->player_id, \$request->zone_id ?? '');
        
        return response()->json(\$response);
    }
PHP;

$newMethod = <<<PHP
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

$content = str_replace($oldMethod, $newMethod, $content);
file_put_contents($file, $content);
echo "Controller exception handler added.\n";
