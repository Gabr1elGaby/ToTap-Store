<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$g = \App\Models\Game::where('name', 'LIKE', '%Valorant%')->first();
if($g) { 
    $g->target_field_1 = 'Riot ID'; 
    $g->requires_zone_id = 1; 
    $g->target_field_2 = 'Tagline'; 
    $g->save(); 
}

// Also delete EUR and Gift Card products globally
\App\Models\GameProduct::where('name', 'LIKE', '%EUR%')
    ->orWhere('name', 'LIKE', '%Gift Card%')
    ->delete();

echo 'Valorant fixed and EUR deleted.';
