<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Get all tables
$tables = DB::select('SHOW TABLES');
$dbName = 'Tables_in_' . DB::connection()->getDatabaseName();

$ignoredTables = ['migrations', 'users', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'personal_access_tokens', 'password_reset_tokens'];
// Also keep products, plans, features, plan_features, cv_templates, games, game_products
$keepTables = ['products', 'plans', 'features', 'plan_features', 'cv_templates', 'games', 'game_products'];

$ignoredTables = array_merge($ignoredTables, $keepTables);

foreach ($tables as $table) {
    $tableName = $table->{$dbName};
    if (!in_array($tableName, $ignoredTables)) {
        DB::table($tableName)->truncate();
        echo "Truncated: $tableName\n";
    }
}

// Keep only Super Admin
$deleted = \App\Models\User::where('role', '!=', 'superadmin')->delete();
echo "Deleted $deleted non-superadmin users.\n";

DB::statement('SET FOREIGN_KEY_CHECKS=1;');
echo "Cleanup complete!\n";
