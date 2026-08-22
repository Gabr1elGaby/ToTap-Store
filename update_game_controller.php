<?php
$file = 'app/Http/Controllers/Admin/GameController.php';
$content = file_get_contents($file);

// Update validation rules in store
$oldStoreVal = <<<PHP
            'is_active' => 'boolean',
            'requires_zone_id' => 'boolean',
PHP;
$newStoreVal = <<<PHP
            'is_active' => 'boolean',
            'requires_zone_id' => 'boolean',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
PHP;
$content = str_replace($oldStoreVal, $newStoreVal, $content);

// Update create logic in store
$oldStoreCreate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$request->has('requires_zone_id'),
PHP;
$newStoreCreate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$request->has('requires_zone_id'),
            'target_field_1' => \$request->target_field_1 ?? 'Player ID',
            'target_field_2' => \$request->target_field_2 ?? 'Zone ID',
PHP;
$content = str_replace($oldStoreCreate, $newStoreCreate, $content);

// Update validation rules in update
$oldUpdateVal = <<<PHP
            'is_active' => 'boolean',
            'requires_zone_id' => 'boolean',
PHP;
$newUpdateVal = <<<PHP
            'is_active' => 'boolean',
            'requires_zone_id' => 'boolean',
            'target_field_1' => 'nullable|string',
            'target_field_2' => 'nullable|string',
PHP;
$content = str_replace($oldUpdateVal, $newUpdateVal, $content);

// Update update logic
$oldUpdate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$request->has('requires_zone_id'),
PHP;
$newUpdate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$request->has('requires_zone_id'),
            'target_field_1' => \$request->target_field_1 ?? 'Player ID',
            'target_field_2' => \$request->target_field_2 ?? 'Zone ID',
PHP;
$content = str_replace($oldUpdate, $newUpdate, $content);

file_put_contents($file, $content);
echo "GameController updated.\n";
