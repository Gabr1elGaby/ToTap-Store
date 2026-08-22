<?php
$file = 'resources/views/admin/games/create.blade.php';
$content = file_get_contents($file);

$newFields = <<<HTML
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Label Target 1 (Contoh: Player ID, Riot ID, Username)</label>
                        <input type="text" name="target_field_1" value="Player ID" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Butuh Target 2? (Zone ID, Server, Tagline)</label>
                        <select name="requires_zone_id" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Label Target 2 (Contoh: Zone ID, Server, Tagline)</label>
                        <input type="text" name="target_field_2" value="Zone ID" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
HTML;

$content = preg_replace('/<div class="mb-4">\s*<label.*?Butuh Zone ID.*?<\/select>\s*<\/div>/is', $newFields, $content);
file_put_contents($file, $content);
echo "Create blade updated.\n";
