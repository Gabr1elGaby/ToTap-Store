<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected string $apiKey;
    protected string $privateKey;
    protected string $merchantCode;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key', env('TRIPAY_API_KEY', 'CalVGyLu3IFWY2IY1kNYpoCsE7pjeW2tRmFtaUiQ'));
        $this->privateKey = config('services.tripay.private_key', env('TRIPAY_PRIVATE_KEY', 'NQwFB-5jb1M-3wmmR-RqZvH-L329i'));
        $this->merchantCode = config('services.tripay.merchant_code', env('TRIPAY_MERCHANT_CODE', 'T52431'));
        $this->isProduction = (bool) config('services.tripay.is_production', env('TRIPAY_IS_PRODUCTION', true));
        $this->baseUrl = $this->isProduction ? 'https://tripay.co.id/api/' : 'https://tripay.co.id/api-sandbox/';
    }

    /**
     * Map payment method string from UI to TriPay channel code.
     */
    public function mapMethod(string $method): string
    {
        $m = strtolower(trim($method));
        return match($m) {
            'qris', 'gopay', 'shopeepay' => 'QRIS',
            'bca_va', 'bcava', 'bca' => 'BCAVA',
            'bni_va', 'bniva', 'bni' => 'BNIVA',
            'bri_va', 'briva', 'bri' => 'BRIVA',
            'mandiri_va', 'mandiriva', 'mandiri' => 'MANDIRIVA',
            'permata_va', 'permatava', 'permata' => 'PERMATAVA',
            'cimb_va', 'cimbva', 'cimb' => 'CIMBVA',
            'bsi_va', 'bsiva', 'bsi' => 'BSIVA',
            'danamon_va', 'danamonva', 'danamon' => 'DANAMONVA',
            'alfamart', 'alfa' => 'ALFAMART',
            'indomaret', 'indo' => 'INDOMARET',
            'ovo' => 'OVO',
            default => 'QRIS',
        };
    }

    /**
     * Buat Transaksi Tertutup di TriPay
     */
    public function createTransaction(array $params): array
    {
        $orderId = $params['merchant_ref'];
        $amount = (int) $params['amount'];
        $channel = $this->mapMethod($params['method'] ?? 'qris');

        // Signature: hash_hmac('sha256', merchantCode + merchantRef + amount, privateKey)
        $signature = hash_hmac('sha256', $this->merchantCode . $orderId . $amount, $this->privateKey);

        $payload = [
            'method'         => $channel,
            'merchant_ref'   => $orderId,
            'amount'         => $amount,
            'customer_name'  => $params['customer_name'] ?? 'Pelanggan ToTap',
            'customer_email' => $params['customer_email'] ?? 'customer@totapstore.com',
            'customer_phone' => $params['customer_phone'] ?? '081234567890',
            'order_items'    => $params['order_items'] ?? [
                [
                    'sku'      => $params['sku'] ?? 'TOPUP',
                    'name'     => substr($params['product_name'] ?? 'Top Up Game', 0, 50),
                    'price'    => $amount,
                    'quantity' => 1,
                ]
            ],
            'return_url'     => $params['return_url'] ?? url('/topup/checkout/' . $orderId),
            'expired_time'   => time() + (24 * 3600), // 24 Jam
            'signature'      => $signature,
        ];

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->post($this->baseUrl . 'transaction/create', $payload);

            $result = $response->json();
            if (isset($result['success']) && $result['success'] === true && isset($result['data'])) {
                return [
                    'success' => true,
                    'data'    => $result['data'],
                ];
            }

            Log::error('TriPay createTransaction failed: ' . json_encode($result));
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Gagal membuat transaksi TriPay',
                'raw'     => $result,
            ];
        } catch (\Exception $e) {
            Log::error('TriPay Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cek Status Transaksi ke TriPay
     */
    public function getTransactionDetail(string $reference): array
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->get($this->baseUrl . 'transaction/detail', [
                    'reference' => $reference,
                ]);

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('TriPay getTransactionDetail Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
