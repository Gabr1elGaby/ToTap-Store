<?php
$file = 'resources/views/admin/games/products/sync.blade.php';
$content = file_get_contents($file);

$missingFilter = <<<HTML
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Kata Kunci Pencarian (Filter)</label>
                        <p class="text-sm text-gray-500 mb-2">Sistem akan mencari game di VIP Reseller yang mengandung kata ini. Harus sama persis. Misal: <strong>Mobile Legends</strong></p>
                        <input type="text" name="filter_value" value="{{ \$game->name }}" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

HTML;

// Insert it right after @csrf
$content = str_replace('@csrf', '@csrf' . "\n" . $missingFilter, $content);

// Also add validation error display just in case
$errorDisplay = <<<HTML
                @if (\$errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach (\$errors->all() as \$error)
                                <li>{{ \$error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
HTML;

$content = str_replace('@if(session(\'error\'))', $errorDisplay . "\n                @if(session('error'))", $content);

file_put_contents($file, $content);
echo "Fixed missing filter field.\n";
