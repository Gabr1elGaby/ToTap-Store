<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$g = \App\Models\Game::where('name', 'LIKE', '%Roblox%')->first();
if($g) { 
    $g->requires_zone_id = 0; 
    $g->target_field_1 = 'Username Roblox'; 
    $g->save(); 
}
echo 'Roblox updated.';
