<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_product_id',
        'content',
        'status',
        'transaction_id',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(GameProduct::class, 'game_product_id');
    }

    /**
     * Parse text input containing multiple stock items separated by double newlines or tokens.
     */
    public static function parseStockItems(string $input): array
    {
        $input = trim($input);
        if (empty($input)) {
            return [];
        }

        // 1. Try splitting by double newlines (or more)
        $chunks = preg_split('/(\r?\n){2,}/', $input);
        $items = [];
        foreach ($chunks as $chunk) {
            $trimmed = trim($chunk);
            if (!empty($trimmed)) {
                $items[] = $trimmed;
            }
        }

        // If chunks > 1, return them directly
        if (count($items) > 1) {
            return $items;
        }

        // 2. If single chunk but contains multiple set / $env statements, split by delimiter
        $lines = preg_split('/\r?\n/', $input);
        $tempItems = [];
        $currentBuffer = [];
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (empty($trimmedLine)) {
                if (!empty($currentBuffer)) {
                    $tempItems[] = implode("\n", $currentBuffer);
                    $currentBuffer = [];
                }
            } else {
                $currentBuffer[] = $trimmedLine;
                if (stripos($trimmedLine, 'DISABLE_NONESSENTIAL_TRAFFIC') !== false) {
                    $tempItems[] = implode("\n", $currentBuffer);
                    $currentBuffer = [];
                }
            }
        }
        if (!empty($currentBuffer)) {
            $tempItems[] = implode("\n", $currentBuffer);
        }

        if (count($tempItems) > count($items)) {
            return $tempItems;
        }

        return $items;
    }

    /**
     * Automatically fulfill a manual product order by consuming 1 available stock.
     */
    public static function fulfillManualStock(\App\Models\Transaction $transaction): bool
    {
        // Jika sudah berhasil dan sudah ada SN/script yang valid, jangan kurangi stok lagi
        if ($transaction->status === 'success' && !empty($transaction->provider_sn) && !\App\Services\VipResellerService::isPendingSn($transaction->provider_sn)) {
            return true;
        }

        // 2. KUNCI PENTING: Transaksi yang BELUM DIBAYAR ('pending') atau gagal/dibatalkan TIDAK BOLEH dialokasikan stok!
        // Hanya boleh dialokasikan stok jika statusnya sudah dikonfirmasi lunas ('processing', 'paid', atau 'success' tapi belum ada token)!
        $status = strtolower($transaction->status ?? '');
        if (!in_array($status, ['processing', 'paid', 'success'])) {
            return false;
        }

        $product = $transaction->gameProduct ?? $transaction->product;
        if (!$product && !empty($transaction->game_product_id)) {
            $product = \App\Models\GameProduct::find($transaction->game_product_id);
        }

        if (!$product) {
            return false;
        }

        $game = $transaction->game ?: ($product->game ?? null);
        $isManual = ($product->provider === 'manual') || ($game && $game->category === 'manual');
        if (!$isManual) {
            return false;
        }

        $stockItem = self::where('game_product_id', $product->id)
            ->where('status', 'available')
            ->lockForUpdate()
            ->first();

        if ($stockItem) {
            $stockItem->update([
                'status' => 'used',
                'transaction_id' => $transaction->id,
                'used_at' => now(),
            ]);
            $transaction->update([
                'status' => 'success',
                'provider_trx_id' => 'STOCK-' . $stockItem->id,
                'provider_sn' => $stockItem->content,
            ]);

            \App\Services\NotificationService::sendOrderSuccessNotification($transaction->fresh());

            if ($product->availableStocks()->count() <= 0) {
                $product->update(['status' => 'empty']);
            }
            return true;
        }

        return false;
    }
}
