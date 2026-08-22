<?php
$file = 'app/Http/Controllers/Admin/GameProductController.php';
$content = file_get_contents($file);

$oldUpdate = <<<PHP
    public function update(Request \$request, Game \$game, GameProduct \$product)
    {
        \$request->validate([
            'name' => 'required|string',
            'price_sell' => 'required|numeric|min:0',
            'status' => 'required|in:available,empty'
        ]);

        \$product->update([
            'name' => \$request->name,
            'price_sell' => \$request->price_sell,
            'status' => \$request->status
        ]);

        return redirect()->route('admin.games.products.index', \$game)->with('success', 'Produk berhasil diupdate.');
    }
PHP;

$newUpdate = <<<PHP
    public function update(Request \$request, Game \$game, GameProduct \$product)
    {
        \$request->validate([
            'name' => 'required|string',
            'price_sell' => 'required|numeric|min:0',
            'status' => 'required|in:available,empty',
            'price_normal' => 'nullable|numeric|min:0'
        ]);

        \$product->update([
            'name' => \$request->name,
            'price_sell' => \$request->price_sell,
            'status' => \$request->status,
            'is_promo' => \$request->has('is_promo'),
            'price_normal' => \$request->price_normal
        ]);

        return redirect()->route('admin.games.products.index', \$game)->with('success', 'Produk berhasil diupdate.');
    }
PHP;

$content = str_replace($oldUpdate, $newUpdate, $content);
file_put_contents($file, $content);
echo "Controller updated.\n";
