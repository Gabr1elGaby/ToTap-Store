<?php
$fileCreate = 'resources/views/admin/games/create.blade.php';
$contentCreate = file_get_contents($fileCreate);

$jsScript = <<<HTML
    <script>
        const gameDictionary = {
            'mobile legend': { t1: 'Player ID', req2: '1', t2: 'Zone ID' },
            'free fire': { t1: 'Player ID', req2: '0', t2: 'Zone ID' },
            'pubg': { t1: 'Player ID', req2: '0', t2: 'Zone ID' },
            'valorant': { t1: 'Riot ID', req2: '1', t2: 'Tagline' },
            'genshin': { t1: 'User ID', req2: '1', t2: 'Server' },
            'roblox': { t1: 'Username', req2: '0', t2: 'Zone ID' },
            'call of duty': { t1: 'OpenID', req2: '0', t2: 'Zone ID' },
            'league of legend': { t1: 'Riot ID', req2: '1', t2: 'Tagline' },
            'honor of king': { t1: 'Player ID', req2: '0', t2: 'Zone ID' },
            'point blank': { t1: 'User ID', req2: '0', t2: 'Zone ID' },
            'arena of valor': { t1: 'OpenID', req2: '0', t2: 'Zone ID' }
        };

        document.querySelector('input[name="name"]').addEventListener('input', function(e) {
            let gameName = e.target.value.toLowerCase();
            let found = false;
            
            for (const [key, config] of Object.entries(gameDictionary)) {
                if (gameName.includes(key)) {
                    document.querySelector('input[name="target_field_1"]').value = config.t1;
                    document.querySelector('select[name="requires_zone_id"]').value = config.req2;
                    document.querySelector('input[name="target_field_2"]').value = config.t2;
                    
                    // Add a tiny hint
                    let hint = document.getElementById('auto-hint');
                    if(!hint) {
                        hint = document.createElement('span');
                        hint.id = 'auto-hint';
                        hint.className = 'text-green-500 text-xs ml-2 font-bold';
                        document.querySelector('label:contains("Label Target 1")')?.appendChild(hint) || 
                        document.querySelector('input[name="target_field_1"]').insertAdjacentElement('beforebegin', hint);
                    }
                    hint.textContent = '✨ (Otomatis dideteksi)';
                    found = true;
                    break;
                }
            }
            if(!found) {
                let hint = document.getElementById('auto-hint');
                if(hint) hint.textContent = '';
            }
        });
    </script>
</x-app-layout>
HTML;

$contentCreate = str_replace('</x-app-layout>', $jsScript, $contentCreate);
file_put_contents($fileCreate, $contentCreate);

// Same for Edit
$fileEdit = 'resources/views/admin/games/edit.blade.php';
$contentEdit = file_get_contents($fileEdit);
$contentEdit = str_replace('</x-app-layout>', $jsScript, $contentEdit);
file_put_contents($fileEdit, $contentEdit);

echo "Auto-fill script added.\n";
