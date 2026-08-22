<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('cv_templates')->insertOrIgnore([
    'name' => 'CV Elegant (Premium)',
    'slug' => 'elegant',
    'description' => 'Desain elegan dengan aksen warna Teal dan Emas. Cocok untuk profesional.',
    'status' => 'active',
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Inserted Elegant template.\n";
