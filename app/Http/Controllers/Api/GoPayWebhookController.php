<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoPayWebhookController extends Controller
{
    /**
     * Handle incoming notification webhook from MacroDroid (GoPay Merchant).
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        $headerSecret = $request->header('X-Secret-Key') 
            ?? $request->header('x-secret-key') 
            ?? $request->input('secret_key') 
            ?? $request->query('secret_key');

        $expectedSecret = config('services.gopay.webhook_secret', env('GOPAY_WEBHOOK_SECRET', 'G4b-4M3l_T0T4p'));

        Log::info('GoPay Webhook Received:', [
            'payload' => $payload,
            'headerSecret' => $headerSecret ? 'PRESENT' : 'MISSING',
            'raw_content' => $request->getContent(),
        ]);

        // 1. Verify Secret Key
        if (!$headerSecret || $headerSecret !== $expectedSecret) {
            Log::warning('GoPay Webhook: Unauthorized access attempt', [
                'received' => $headerSecret,
                'ip' => $request->ip(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or missing Secret Key.',
            ], 403);
        }

        // 2. Extract Amount from Notification
        $amount = $this->extractAmount($request);

        if (!$amount || $amount <= 0) {
            Log::info('GoPay Webhook: Non-payment notification received or nominal not found.', [
                'payload' => $payload,
                'raw_content' => $request->getContent(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi diterima (Bukan pembayaran / tidak ada nominal).',
            ], 200);
        }

        Log::info("GoPay Webhook: Extracted amount Rp" . number_format($amount, 0, ',', '.'));

        // 3. Match against Pending Top Up Transactions (created in the last 24 hours)
        $transaction = Transaction::with(['game', 'gameProduct', 'user'])
            ->whereIn('status', ['pending', 'waiting', 'unpaid'])
            ->where(function ($q) use ($amount) {
                $q->where('amount', $amount)
                  ->orWhere('amount', (float)$amount)
                  ->orWhereRaw('CAST(amount AS SIGNED) = ?', [$amount]);
            })
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($transaction) {
            return $this->processTransaction($transaction, $amount, $payload);
        }

        // 4. Match against Pending Deposits (created in the last 24 hours)
        $deposit = Deposit::with('user')
            ->whereIn('status', ['pending', 'waiting', 'unpaid'])
            ->where(function ($q) use ($amount) {
                $q->where('amount', $amount)
                  ->orWhere('amount', (float)$amount)
                  ->orWhereRaw('CAST(amount AS SIGNED) = ?', [$amount]);
            })
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($deposit) {
            return $this->processDeposit($deposit, $amount, $payload);
        }

        Log::warning("GoPay Webhook: No pending transaction or deposit matching amount Rp" . number_format($amount, 0, ',', '.'));

        return response()->json([
            'success' => false,
            'message' => 'Payment received but no matching pending transaction found for amount Rp' . number_format($amount, 0, ',', '.'),
            'amount' => $amount,
        ], 200);
    }

    /**
     * Process & fulfill matched Top Up Transaction.
     */
    protected function processTransaction(Transaction $transaction, int $amount, array $payload)
    {
        DB::beginTransaction();
        try {
            // Update status to processing
            $transaction->update([
                'status' => 'processing',
                'provider_status' => 'payment_received_gopay',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GoPay Webhook: Error updating transaction status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database error'], 500);
        }

        // Call VIP Reseller API for instant fulfillment
        try {
            $product = $transaction->gameProduct;
            $game = $transaction->game;

            if ($product) {
                $vipService = app(VipResellerService::class);
                if (method_exists($vipService, 'order')) {
                    $orderRes = $vipService->order(
                        $product->product_code,
                        $transaction->target_field_1,
                        $transaction->target_field_2,
                        $transaction->id
                    );
                } else {
                    $orderRes = $vipService->createOrder(
                        $product->product_code,
                        $transaction->target_field_1,
                        $transaction->target_field_2 ?? ''
                    );
                }

                Log::info('GoPay Webhook: VIP Reseller Order Response:', ['orderRes' => $orderRes]);

                if (isset($orderRes['result']) && $orderRes['result'] === true) {
                    $pData = $orderRes['data'] ?? [];
                    $trxId = $pData['trxid'] ?? ($pData['id'] ?? null);
                    $sn = VipResellerService::extractSnFromData($pData);

                    $transaction->update([
                        'status' => 'success',
                        'provider_trx_id' => $trxId,
                        'provider_sn' => $sn,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('GoPay Webhook: VIP Reseller Fulfillment Error: ' . $e->getMessage());
        }

        Log::info("GoPay Webhook: Transaction {$transaction->id} successfully matched and processed.");

        return response()->json([
            'success' => true,
            'type' => 'transaction',
            'order_id' => $transaction->id,
            'amount' => $amount,
            'status' => $transaction->fresh()->status,
            'message' => 'Payment verified and transaction processed successfully.',
        ]);
    }

    /**
     * Process & credit matched Deposit.
     */
    protected function processDeposit(Deposit $deposit, int $amount, array $payload)
    {
        DB::beginTransaction();
        try {
            $deposit->update([
                'status' => 'success',
            ]);

            // Credit user wallet balance
            if ($deposit->user) {
                $deposit->user->increment('balance', $deposit->amount);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GoPay Webhook: Error crediting deposit: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database error'], 500);
        }

        Log::info("GoPay Webhook: Deposit {$deposit->id} successfully credited with Rp" . number_format($deposit->amount, 0, ',', '.'));

        return response()->json([
            'success' => true,
            'type' => 'deposit',
            'deposit_id' => $deposit->id,
            'amount' => $amount,
            'status' => 'success',
            'message' => 'Deposit verified and user balance credited successfully.',
        ]);
    }

    /**
     * Extract nominal integer from request body/headers/text.
     */
    protected function extractAmount(Request $request): int
    {
        // 1. Direct explicit numeric fields (amount, nominal, total, value, saldo)
        $directKeys = ['amount', 'nominal', 'total', 'value', 'saldo', 'price'];
        foreach ($directKeys as $k) {
            if ($request->filled($k)) {
                $val = (string) $request->input($k);
                // Clean any currency text e.g. "Rp 5.147,00" or "Rp.5.147"
                $val = str_replace(["\xc2\xa0", "\xa0", "&nbsp;"], ' ', $val);
                $clean = preg_replace('/[,\.](?:00|-)$/', '', trim($val));
                $clean = preg_replace('/[^0-9]/', '', $clean);
                $num = (int) $clean;
                if ($num >= 500 && $num <= 50000000) {
                    return $num;
                }
            }
        }

        // 2. Concatenate all scalar values from request parameters, headers, and raw content
        $data = array_merge(
            $request->query->all(),
            $request->request->all(),
            $request->all()
        );

        $textFragments = [];
        array_walk_recursive($data, function ($item) use (&$textFragments) {
            if (is_scalar($item)) {
                $textFragments[] = (string) $item;
            }
        });
        $textFragments[] = (string) $request->getContent();

        $fullText = implode(' ', $textFragments);

        // 3. Normalize unicode spaces & NBSP (frequently present in Android notifications)
        $fullText = str_replace(["\xc2\xa0", "\xa0", "&nbsp;"], ' ', $fullText);
        $fullText = preg_replace('/\s+/u', ' ', $fullText);

        // 4. Regex match for currency prefix: "Rp 5.147", "Rp. 5.147,00", "IDR 50.000", "sebesar Rp5.147", "dana Rp5.147"
        if (preg_match_all('/(?:rp|idr|sebesar|dana|masuk|senilai)[\s\.\:\=]*([0-9\.,]+)/iu', $fullText, $matches)) {
            foreach ($matches[1] as $rawNominal) {
                // Strip trailing cents like ,00 or .00 or ,-
                $clean = preg_replace('/[,\.](?:00|-)$/', '', trim($rawNominal));
                // Remove thousand dots and commas
                $clean = preg_replace('/[^0-9]/', '', $clean);
                $num = (int) $clean;
                if ($num >= 500 && $num <= 50000000) {
                    return $num;
                }
            }
        }

        // 5. Match dotted thousand amounts like 5.001 or 19.147 or 50.000
        if (preg_match_all('/\b(\d{1,3}(?:[\.,]\d{3})+)(?:[,\.]00)?\b/u', $fullText, $matches)) {
            foreach ($matches[1] as $rawNominal) {
                $clean = preg_replace('/[^0-9]/', '', $rawNominal);
                $num = (int) $clean;
                if ($num >= 500 && $num <= 50000000) {
                    return $num;
                }
            }
        }

        // 6. Fallback: match any standalone 4-8 digit number in the text
        if (preg_match_all('/\b(\d{4,8})\b/', $fullText, $matches)) {
            foreach ($matches[1] as $rawNum) {
                $num = (int) $rawNum;
                if ($num >= 500 && $num <= 50000000) {
                    return $num;
                }
            }
        }

        return 0;
    }
}
