<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Keep only these games
$allowedGames = ['Mobile Legend', 'Roblox', 'Valorant', 'Free Fire', 'PUBG'];

$deletedGames = DB::table('games')->whereNotIn('name', $allowedGames)->delete();
echo "Deleted $deletedGames games.\n";

// Delete products that don't belong to any game anymore
$deletedProducts = DB::table('game_products')->whereNotIn('game_id', function($q) {
    $q->select('id')->from('games');
})->delete();
echo "Deleted $deletedProducts orphaned products.\n";

DB::statement('SET FOREIGN_KEY_CHECKS=1;');
