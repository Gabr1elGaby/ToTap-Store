<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected string $merchantCode;
    protected string $apiKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('services.duitku.merchant_code', env('DUITKU_MERCHANT_CODE', 'DS34557'));
        $this->apiKey = config('services.duitku.api_key', env('DUITKU_API_KEY', '4c127f4a1e4edb4a411d5d753c22762a'));
        $this->isProduction = (bool) config('services.duitku.is_production', env('DUITKU_IS_PRODUCTION', false));
        $this->baseUrl = $this->isProduction 
            ? 'https://passport.duitku.com/webapi/api/merchant/' 
            : 'https://sandbox.duitku.com/webapi/api/merchant/';
    }

    /**
     * Map method code ke Duitku Payment Method code
     */
    public function mapMethod(string $method): string
    {
        $m = strtolower(trim($method));
        return match($m) {
            'qris', 'gopay', 'shopeepay', 'sp' => 'SP', // ShopeePay QRIS
            'nobu_qris', 'nq' => 'NQ',
            'bca_va', 'bcava', 'bca', 'bc' => 'BC',
            'bni_va', 'bniva', 'bni', 'i1' => 'I1',
            'bri_va', 'briva', 'bri', 'br' => 'BR',
            'mandiri_va', 'mandiriva', 'mandiri', 'm2' => 'M2',
            'permata_va', 'permatava', 'permata', 'bt' => 'BT',
            'cimb_va', 'cimbva', 'cimb', 'b1' => 'B1',
            'bsi_va', 'bsiva', 'bsi', 'bv' => 'BV',
            'danamon_va', 'danamonva', 'danamon', 'dm' => 'DM',
            'alfamart', 'alfa', 'a1' => 'A1',
            'indomaret', 'indo', 'ir' => 'IR',
            'dana', 'da' => 'DA',
            'ovo', 'ov' => 'OV',
            default => 'SP',
        };
    }

    /**
     * Request Transaksi ke Duitku API V2 Inquiry
     */
    public function createTransaction(array $params): array
    {
        $orderId = $params['merchant_order_id'];
        $amount = (int) $params['amount'];
        $paymentMethod = $this->mapMethod($params['method'] ?? 'qris');

        // Signature: MD5(merchantCode + merchantOrderId + amount + apiKey)
        $signature = md5($this->merchantCode . $orderId . $amount . $this->apiKey);

        $payload = [
            'merchantCode'    => $this->merchantCode,
            'paymentAmount'   => $amount,
            'paymentMethod'   => $paymentMethod,
            'merchantOrderId' => $orderId,
            'productDetails'  => substr($params['product_name'] ?? 'Top Up Game', 0, 50),
            'email'           => $params['customer_email'] ?? 'customer@totapstore.com',
            'phoneNumber'     => $params['customer_phone'] ?? '081234567890',
            'customerVaName'  => $params['customer_name'] ?? 'Pelanggan ToTap',
            'callbackUrl'     => url('/api/duitku/callback'),
            'returnUrl'       => url('/topup/checkout/' . $orderId),
            'signature'       => $signature,
            'expiryPeriod'    => 1440, // 24 jam (menit)
        ];

        try {
            $response = Http::withoutVerifying()
                ->asJson()
                ->post($this->baseUrl . 'v2/inquiry', $payload);

            $result = $response->json();
            if (isset($result['statusCode']) && $result['statusCode'] == '00') {
                $isVA = isset($result['vaNumber']) && !empty($result['vaNumber']);
                $qrUrl = '';
                if (isset($result['qrString']) && !empty($result['qrString'])) {
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($result['qrString']);
                }

                return [
                    'success'       => true,
                    'reference'     => $result['reference'] ?? null,
                    'payment_url'   => $result['paymentUrl'] ?? null,
                    'type'          => $isVA ? 'va' : 'qris',
                    'va_number'     => $result['vaNumber'] ?? null,
                    'qr_string'     => $result['qrString'] ?? null,
                    'qr_url'        => $qrUrl,
                    'amount'        => $result['amount'] ?? $amount,
                    'raw'           => $result,
                ];
            }

            Log::error('Duitku createTransaction failed: ' . json_encode($result));
            return [
                'success' => false,
                'message' => $result['statusMessage'] ?? 'Gagal membuat transaksi Duitku',
                'raw'     => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Duitku Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cek Status Transaksi ke Duitku
     */
    public function checkTransaction(string $orderId): array
    {
        $signature = md5($this->merchantCode . $orderId . $this->apiKey);

        $payload = [
            'merchantCode'    => $this->merchantCode,
            'merchantOrderId' => $orderId,
            'signature'       => $signature,
        ];

        try {
            $response = Http::withoutVerifying()
                ->asJson()
                ->post($this->baseUrl . 'transactionStatus', $payload);

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Duitku checkTransaction Exception: ' . $e->getMessage());
            return ['statusCode' => '99', 'statusMessage' => $e->getMessage()];
        }
    }
}
