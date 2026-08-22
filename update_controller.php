<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldProcess = <<<PHP
    public function process(Request \$request, \$slug)
    {
        \$game = Game::where('slug', \$slug)->firstOrFail();

        \$rules = [
            'product_id' => 'required|exists:game_products,id',
            'player_id' => 'required|string|max:255',
        ];

        if (\$game->requires_zone_id) {
            \$rules['zone_id'] = 'required|string|max:255';
        }

        \$request->validate(\$rules);
        
        return back()->with('success', 'Pesanan sedang diproses! (Sistem Pembayaran akan segera diintegrasikan)');
    }
PHP;

$newProcess = <<<PHP
    public function process(Request \$request, \$slug)
    {
        \$game = Game::where('slug', \$slug)->firstOrFail();

        \$rules = [
            'product_id' => 'required|exists:game_products,id',
            'player_id' => 'required|string|max:255',
        ];

        if (\$game->requires_zone_id) {
            \$rules['zone_id'] = 'required|string|max:255';
        }

        \$request->validate(\$rules);
        
        \$product = GameProduct::findOrFail(\$request->product_id);
        
        // Buat ID Transaksi Unik (Order ID)
        \$orderId = 'TRX-' . time() . '-' . rand(100, 999);
        
        // Simpan ke Database
        \$transaction = \App\Models\Transaction::create([
            'id' => \$orderId,
            'game_id' => \$game->id,
            'game_product_id' => \$product->id,
            'target_field_1' => \$request->player_id,
            'target_field_2' => \$request->zone_id,
            'amount' => \$product->price_sell,
            'status' => 'pending',
        ]);
        
        // Konfigurasi Midtrans
        \Midtrans\Config::\$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::\$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::\$isSanitized = true;
        \Midtrans\Config::\$is3ds = true;
        
        // Buat Parameter Midtrans
        \$params = [
            'transaction_details' => [
                'order_id' => \$orderId,
                'gross_amount' => (int) \$product->price_sell,
            ],
            'item_details' => [
                [
                    'id' => \$product->product_code,
                    'price' => (int) \$product->price_sell,
                    'quantity' => 1,
                    'name' => substr(\$product->name, 0, 50),
                ]
            ],
            'customer_details' => [
                'first_name' => \$request->player_id,
                'email' => 'customer@totap.com', // Opsional, bisa minta user email nanti
            ]
        ];
        
        try {
            // Dapatkan Snap Token dari Midtrans
            \$snapToken = \Midtrans\Snap::getSnapToken(\$params);
            
            // Simpan token ke database
            \$transaction->update(['snap_token' => \$snapToken]);
            
            // Arahkan ke halaman checkout
            return redirect()->route('checkout.show', \$transaction->id);
            
        } catch (\Exception \$e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . \$e->getMessage());
        }
    }
PHP;

$content = str_replace($oldProcess, $newProcess, $content);
file_put_contents($file, $content);
echo "TopUpController updated with Midtrans logic.\n";
