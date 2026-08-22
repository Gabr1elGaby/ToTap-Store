<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldStep1 = <<<BLADE
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">1.</span>
                        <span>Masukkan data target (Player ID / ID Pengguna) yang sesuai dengan akun game Anda.</span>
                    </li>
BLADE;

$newStep1 = <<<BLADE
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">1.</span>
                        <div>
                            <span>Masukkan data target 
                            @if(\$game->requires_zone_id)
                                (<strong>{{ \$game->target_field_1 }}</strong> dan <strong>{{ \$game->target_field_2 ?? 'Zone ID' }}</strong>) 
                            @else
                                (<strong>{{ \$game->target_field_1 }}</strong>) 
                            @endif
                            yang sesuai dengan akun {{ \$game->name }} Anda.</span>
                            
                            @php
                                \$gameName = strtolower(\$game->name);
                            @endphp
                            
                            @if(str_contains(\$gameName, 'mobile legend'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 12345678 untuk Player ID dan 1234 untuk Zone ID.</div>
                            @elseif(str_contains(\$gameName, 'valorant'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: Jett (Riot ID) dan 1234 (Tagline tanpa #).</div>
                            @elseif(str_contains(\$gameName, 'free fire'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 1234567890 (Temukan di profil game).</div>
                            @elseif(str_contains(\$gameName, 'genshin'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 800123456 (Server Asia).</div>
                            @elseif(str_contains(\$gameName, 'pubg'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 5123456789.</div>
                            @else
                                <div class="text-xs text-gray-400 mt-1 italic">Pastikan data yang Anda masukkan valid agar pesanan tidak gagal.</div>
                            @endif
                        </div>
                    </li>
BLADE;

$content = str_replace($oldStep1, $newStep1, $content);
file_put_contents($file, $content);
echo "Updated step 1 to be dynamic.\n";
