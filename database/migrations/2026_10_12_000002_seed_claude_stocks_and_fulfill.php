<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\GameProduct;
use App\Models\GameProductStock;
use App\Models\Transaction;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $products = GameProduct::where('name', 'like', '%Claude%')->get();

        $tokens = [
            "set ANTHROPIC_BASE_URL=https://api.mwapi.dev\nset ANTHROPIC_AUTH_TOKEN=sk-f54bb1ab6c04fe032ca88ca6c795100229d3d8ac2ec44269dcef46ec4c731111\nset CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1",
            "set ANTHROPIC_BASE_URL=https://api.mwapi.dev\nset ANTHROPIC_AUTH_TOKEN=sk-7a4ed716f309ca04452c19fe9072f39b71462121d9956a4acad18704edf31993\nset CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1",
            "set ANTHROPIC_BASE_URL=https://api.mwapi.dev\nset ANTHROPIC_AUTH_TOKEN=sk-6681bd418bea7e1a1689bfa3daffc0686bd0c87381e8f101166a20fae34fc7f0\nset CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1",
            "set ANTHROPIC_BASE_URL=https://api.mwapi.dev\nset ANTHROPIC_AUTH_TOKEN=sk-c1b4407c5a7e0c8ced01cf7b5497cd97f91c2cdb8955694f5e6aed89a69a92fc\nset CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1",
        ];

        foreach ($products as $product) {
            $product->update(['provider' => 'manual', 'status' => 'available']);

            // Jika belum ada stok, masukkan 4 token Claude yang diberikan pemilik toko
            $existingCount = GameProductStock::where('game_product_id', $product->id)->count();
            if ($existingCount === 0) {
                foreach ($tokens as $token) {
                    GameProductStock::create([
                        'game_product_id' => $product->id,
                        'content' => $token,
                        'status' => 'available',
                    ]);
                }
            }

            // Otomatis penuhi pesanan yang masih pending/processing untuk produk ini
            $pendingTrxs = Transaction::where('game_product_id', $product->id)
                ->whereIn('status', ['pending', 'processing'])
                ->get();

            foreach ($pendingTrxs as $trx) {
                GameProductStock::fulfillManualStock($trx);
            }
        }

        // Pastikan transaksi yang sempat pending sebelumnya langsung dipenuhi jika ada stok
        $targetTrxs = Transaction::where(function ($q) {
            $q->where('invoice_number', 'like', '%310826%')
              ->orWhere('id', 'like', '%310826%');
        })->get();

        foreach ($targetTrxs as $t) {
            if ($t->status !== 'success') {
                GameProductStock::fulfillManualStock($t);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed
    }
};
