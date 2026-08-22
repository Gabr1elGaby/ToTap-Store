<?php
$file = 'app/Http/Controllers/Admin/GameController.php';
$content = file_get_contents($file);

$dictionaryPhp = <<<PHP
        // Auto-detect targets based on game name
        \$gameName = strtolower(\$request->name);
        \$target1 = 'Player ID';
        \$reqZone = 1;
        \$target2 = 'Zone ID';

        \$dict = [
            'mobile legend' => ['Player ID', 1, 'Zone ID'],
            'free fire' => ['Player ID', 0, 'Zone ID'],
            'pubg' => ['Player ID', 0, 'Zone ID'],
            'valorant' => ['Riot ID', 1, 'Tagline'],
            'genshin' => ['User ID', 1, 'Server'],
            'roblox' => ['Username', 0, 'Zone ID'],
            'call of duty' => ['OpenID', 0, 'Zone ID'],
            'league of legend' => ['Riot ID', 1, 'Tagline'],
            'honor of king' => ['Player ID', 0, 'Zone ID'],
            'point blank' => ['User ID', 0, 'Zone ID'],
            'arena of valor' => ['OpenID', 0, 'Zone ID'],
            'ragnarok' => ['Character ID', 1, 'Server']
        ];

        foreach (\$dict as \$key => \$config) {
            if (str_contains(\$gameName, \$key)) {
                \$target1 = \$config[0];
                \$reqZone = \$config[1];
                \$target2 = \$config[2];
                break;
            }
        }
PHP;

// For store method
$oldStoreCreate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$request->has('requires_zone_id'),
            'target_field_1' => \$request->target_field_1 ?? 'Player ID',
            'target_field_2' => \$request->target_field_2 ?? 'Zone ID',
PHP;
$newStoreCreate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$reqZone,
            'target_field_1' => \$target1,
            'target_field_2' => \$target2,
PHP;
$content = str_replace($oldStoreCreate, $dictionaryPhp . "\n" . $newStoreCreate, $content);

// For update method
$oldUpdate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$request->has('requires_zone_id'),
            'target_field_1' => \$request->target_field_1 ?? 'Player ID',
            'target_field_2' => \$request->target_field_2 ?? 'Zone ID',
PHP;
$newUpdate = <<<PHP
            'is_active' => \$request->has('is_active'),
            'requires_zone_id' => \$reqZone,
            'target_field_1' => \$target1,
            'target_field_2' => \$target2,
PHP;
$content = str_replace($oldUpdate, $dictionaryPhp . "\n" . $newUpdate, $content);

file_put_contents($file, $content);
echo "Backend dictionary applied.\n";
