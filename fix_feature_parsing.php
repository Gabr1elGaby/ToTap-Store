<?php
$file = 'resources/views/software/index.blade.php';
$content = file_get_contents($file);

$oldCode = <<<BLADE
                        @if(\$product->features)
                            @foreach(is_array(\$product->features) ? \$product->features : json_decode(\$product->features, true) as \$feature)
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-300">{{ \$feature }}</span>
                            </li>
                            @endforeach
                        @else
BLADE;

$newCode = <<<BLADE
                        @if(\$product->features)
                            @foreach(explode("\\n", str_replace("\\r", "", \$product->features)) as \$feature)
                                @if(trim(\$feature))
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm text-gray-300">{{ trim(\$feature) }}</span>
                                </li>
                                @endif
                            @endforeach
                        @else
BLADE;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "Fixed features parsing in software index.\n";
