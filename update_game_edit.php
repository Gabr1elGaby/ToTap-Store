<?php
$file = 'resources/views/admin/games/edit.blade.php';
$content = file_get_contents($file);

$newFields = <<<HTML
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Label Target 1 (Contoh: Player ID, Riot ID, Username)</label>
                        <input type="text" name="target_field_1" value="{{ \$game->target_field_1 }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Butuh Target 2? (Zone ID, Server, Tagline)</label>
                        <select name="requires_zone_id" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1" {{ \$game->requires_zone_id ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ !\$game->requires_zone_id ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Label Target 2 (Contoh: Zone ID, Server, Tagline)</label>
                        <input type="text" name="target_field_2" value="{{ \$game->target_field_2 }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
HTML;

$content = preg_replace('/<div class="mb-4">\s*<label.*?Butuh Zone ID.*?<\/select>\s*<\/div>/is', $newFields, $content);
file_put_contents($file, $content);
echo "Edit blade updated.\n";
