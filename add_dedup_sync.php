<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldSyncFunc = <<<PHP
    public function syncProcess(Request \$request, Game \$game, VipResellerService \$api)
    {
        \$request->validate([
            'filter_value' => 'required|string', // The exact string to filter in VIP Reseller (e.g. "Mobile Legends")
            'markup_flat' => 'required|numeric|min:0'
        ]);

        \$response = \$api->getGameProducts(\$request->filter_value);

        if (!isset(\$response['result']) || !\$response['result']) {
            return back()->with('error', 'Gagal menarik data dari VIP Reseller. ' . (\$response['message'] ?? ''));
        }

        \$items = \$response['data'];
        \$count = 0;

        foreach (\$items as \$item) {
            // Filter strict untuk membuang produk sampah "INFO/STOK/OPEN" dari VIP Reseller
            \$nameUpper = strtoupper(\$item['name']);
            \$modal = \$item['price']['basic'] ?? 0;

            if (\$modal <= 0) continue; // Harga modal 0 atau minus = sampah
            if (str_contains(\$nameUpper, 'OPEN')) continue;
            if (str_contains(\$nameUpper, 'CLOSE')) continue;
            if (str_contains(\$nameUpper, 'INFO')) continue;
            if (str_contains(\$nameUpper, 'RATE')) continue;
            if (str_contains(\$nameUpper, 'TESTING')) continue;
            if (str_contains(\$nameUpper, 'DUMMY')) continue;

            // Only process if it matches the game exactly
            if (stripos(\$item['game'], \$request->filter_value) !== false) {
                
                // JIKA STATUS EMPTY/GANGGUAN DI PUSAT, KITA HAPUS SAJA DARI DATABASE KITA
                // AGAR TIDAK MENUH-MENUHIN HALAMAN ADMIN
                if (strtolower(\$item['status']) === 'empty' || strtolower(\$item['status']) === 'gangguan' || strtolower(\$item['status']) === 'error') {
                    GameProduct::where('game_id', \$game->id)->where('product_code', \$item['code'])->delete();
                    continue; // Skip creating it
                }

                \$jual = \$modal + \$request->markup_flat;

                GameProduct::updateOrCreate(
                    ['game_id' => \$game->id, 'product_code' => \$item['code']],
                    [
                        'name' => \$item['name'],
                        'price_modal' => \$modal,
                        'price_sell' => \$jual,
                        'status' => \$item['status']
                    ]
                );
                \$count++;
            }
        }

        return redirect()->route('admin.games.products.index', \$game)->with('success', "Berhasil menarik/memperbarui \$count produk dari VIP Reseller.");
    }
PHP;

$newSyncFunc = <<<PHP
    public function syncProcess(Request \$request, Game \$game, VipResellerService \$api)
    {
        \$request->validate([
            'filter_value' => 'required|string', 
            'markup_flat' => 'required|numeric|min:0'
        ]);

        \$response = \$api->getGameProducts(\$request->filter_value);

        if (!isset(\$response['result']) || !\$response['result']) {
            return back()->with('error', 'Gagal menarik data dari VIP Reseller. ' . (\$response['message'] ?? ''));
        }

        \$items = \$response['data'];
        \$cheapestItems = [];

        foreach (\$items as \$item) {
            \$name = trim(\$item['name']);
            \$nameUpper = strtoupper(\$name);
            \$nameLower = strtolower(\$name);
            \$modal = \$item['price']['basic'] ?? 0;

            // 1. FILTER SAMPAH
            if (\$modal <= 0) continue; 
            if (str_contains(\$nameUpper, 'OPEN') || str_contains(\$nameUpper, 'CLOSE') || str_contains(\$nameUpper, 'INFO') || str_contains(\$nameUpper, 'RATE') || str_contains(\$nameUpper, 'TESTING') || str_contains(\$nameUpper, 'DUMMY')) continue;
            
            // 2. FILTER STATUS GANGGUAN / EMPTY
            if (strtolower(\$item['status']) === 'empty' || strtolower(\$item['status']) === 'gangguan' || strtolower(\$item['status']) === 'error') {
                continue; 
            }

            // 3. FILTER NON-IDN
            if (preg_match('/\\b(global|brazil|br|my|malaysia|ph|philippines|skin|charisma|p\.ace|champion|lightborn|epic)\\b/i', \$name)) {
                continue;
            }

            if (stripos(\$item['game'], \$request->filter_value) !== false) {
                // HARI INI KITA DEDUPLIKASI!
                \$isPass = (str_contains(\$nameLower, 'pass') || str_contains(\$nameLower, 'weekly') || str_contains(\$nameLower, 'starlight') || str_contains(\$nameLower, 'twilight') || str_contains(\$nameLower, 'member') || str_contains(\$nameLower, 'bundle'));
                
                \$nameForMath = str_replace('.', '', \$nameLower);
                \$qty = 0;

                if (\$isPass) {
                    \$uniqueKey = preg_replace('/[^a-z0-9]/', '', \$nameLower);
                } else {
                    if (preg_match('/^(\d+)\s*\+\s*(\d+)\s*diamond/i', \$nameForMath, \$m)) {
                        \$qty = (int)\$m[1] + (int)\$m[2]; 
                    } elseif (preg_match('/^(\d+)\s*diamond/i', \$nameForMath, \$m)) {
                        \$qty = (int)\$m[1]; 
                    } elseif (preg_match('/(\d+)\s*\+\s*(\d+)/', \$nameForMath, \$m)) {
                        \$qty = (int)\$m[1] + (int)\$m[2];
                    } elseif (preg_match('/(\d+)/', \$nameForMath, \$m)) {
                        \$qty = (int)\$m[1];
                    }
                    if (\$qty > 0) {
                        \$uniqueKey = 'qty_' . \$qty; 
                    } else {
                        \$uniqueKey = preg_replace('/[^a-z0-9]/', '', \$nameLower);
                    }
                }

                // SIMPAN YANG PALING MURAH SAJA!
                if (!isset(\$cheapestItems[\$uniqueKey]) || \$modal < \$cheapestItems[\$uniqueKey]['modal']) {
                    \$cheapestItems[\$uniqueKey] = [
                        'code' => \$item['code'],
                        'name' => \$item['name'],
                        'modal' => \$modal,
                        'status' => \$item['status'],
                        'unique_key' => \$uniqueKey,
                    ];
                }
            }
        }

        // HAPUS SEMUA PRODUK GAME INI DI DATABASE (KITA REFRESH TOTAL)
        GameProduct::where('game_id', \$game->id)->delete();

        \$count = 0;
        foreach (\$cheapestItems as \$uniqueKey => \$cItem) {
            \$jual = \$cItem['modal'] + \$request->markup_flat;

            GameProduct::create([
                'game_id' => \$game->id, 
                'product_code' => \$cItem['code'],
                'name' => \$cItem['name'],
                'price_modal' => \$cItem['modal'],
                'price_sell' => \$jual,
                'status' => \$cItem['status']
            ]);
            \$count++;
        }

        return redirect()->route('admin.games.products.index', \$game)->with('success', "Berhasil mensinkronisasi \$count produk unik Termurah secara otomatis.");
    }
PHP;

// Find the function and replace it. Since it spans many lines, regex might be tricky. Let's use str_replace or just find the start and end of the function.
// Let's use preg_replace with a very targeted pattern.

$content = preg_replace('/public function syncProcess.*?return redirect.*?with.*?;\s*}/s', $newSyncFunc, $content);
file_put_contents($file, $content);
echo "Deduplication added to sync.\n";
