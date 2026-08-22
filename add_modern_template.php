<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('cv_templates')->insertOrIgnore([
    'name' => 'CV Modern (Premium)',
    'slug' => 'modern',
    'description' => 'Desain modern dengan aksen kolom gelap dan kartu informasi putih. Sangat profesional dan eye-catching.',
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Inserted Modern template.\n";
