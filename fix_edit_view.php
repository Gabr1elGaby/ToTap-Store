<?php
$file = 'resources/views/admin/games/edit.blade.php';
$content = file_get_contents($file);

$errorBlock = <<<BLADE
            <h2 class="text-xl font-bold text-white mb-6">Edit Game</h2>
            
            @if (\$errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach (\$errors->all() as \$error)
                            <li>{{ \$error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
BLADE;

$content = str_replace('<h2 class="text-xl font-bold text-white mb-6">Edit Game</h2>', $errorBlock, $content);
file_put_contents($file, $content);
echo "Error block added to edit view.\n";
