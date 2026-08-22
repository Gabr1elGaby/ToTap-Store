<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// Find Player ID and replace it
$oldTarget1 = <<<HTML
                                        <div class="w-full">
                                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Player ID</label>
                                            <input type="text" name="player_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400" placeholder="Masukkan ID">
                                        </div>
HTML;
$newTarget1 = <<<HTML
                                        <div class="w-full">
                                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">{{ \$game->target_field_1 ?? 'Player ID' }}</label>
                                            <input type="text" name="player_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400" placeholder="Masukkan {{ \$game->target_field_1 ?? 'ID' }}">
                                        </div>
HTML;
$content = str_replace($oldTarget1, $newTarget1, $content);

// Find Zone ID and replace it
$oldTarget2 = <<<HTML
                                        @if(\$game->requires_zone_id)
                                        <div class="w-full">
                                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Zone ID</label>
                                            <input type="text" name="zone_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400" placeholder="Masukkan Zone ID">
                                        </div>
                                        @endif
HTML;
$newTarget2 = <<<HTML
                                        @if(\$game->requires_zone_id)
                                        <div class="w-full">
                                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">{{ \$game->target_field_2 ?? 'Zone ID' }}</label>
                                            <input type="text" name="zone_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400" placeholder="Masukkan {{ \$game->target_field_2 ?? 'Zone ID' }}">
                                        </div>
                                        @endif
HTML;
$content = str_replace($oldTarget2, $newTarget2, $content);

file_put_contents($file, $content);
echo "Frontend dynamic targets updated.\n";
