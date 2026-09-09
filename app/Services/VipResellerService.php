<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VipResellerService
{
    protected $apiId;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiId = config('services.vip_reseller.api_id', env('NEW_API_ID', env('VIP_RESELLER_API_ID', env('VIP_API_ID', 'UEsJ21pX'))));
        $this->apiKey = config('services.vip_reseller.api_key', env('NEW_API_KEY', env('VIP_RESELLER_API_KEY', env('VIP_API_KEY', 'wTpFb8UKOona2Hm56HODEruuB7F2aAE0MQU2dXgjjRy1Q2lCUUfL7Un9mcgxtLRy'))));
        $this->baseUrl = config('services.vip_reseller.base_url', 'https://vip-reseller.co.id/api');
    }

    protected function generateSign()
    {
        return md5($this->apiId . $this->apiKey);
    }

    public function getProfile()
    {
        $response = Http::asForm()->post("{$this->baseUrl}/profile", [
            'key' => $this->apiKey,
            'sign' => $this->generateSign()
        ]);

        $data = $response->json();
        if (isset($data['result']) && $data['result'] === true && isset($data['data']['level'])) {
            \App\Models\Setting::set('vip_account_level', (string)$data['data']['level']);
        }

        return $data;
    }

    public static function getAccountPrice($priceArray)
    {
        if (!is_array($priceArray)) {
            return (float)$priceArray;
        }

        // Cek level akun yang tersimpan atau default ke Basic
        $level = strtolower(\App\Models\Setting::get('vip_account_level', 'basic'));

        if ($level === 'h2h' || $level === 'special') {
            return (float)($priceArray['special'] ?? ($priceArray['h2h'] ?? ($priceArray['premium'] ?? ($priceArray['basic'] ?? 0))));
        }

        if ($level === 'premium' || $level === 'reseller') {
            return (float)($priceArray['premium'] ?? ($priceArray['reseller'] ?? ($priceArray['basic'] ?? 0)));
        }

        // Default: Level Basic (Member) - Sesuai level akun saat ini agar tidak nombok
        return (float)($priceArray['basic'] ?? ($priceArray['member'] ?? ($priceArray['premium'] ?? ($priceArray['special'] ?? 0))));
    }

    public function getGameProducts($filterValue = '')
    {
        // For VIP Reseller, API to get services:
        // /api/game-feature
        $response = Http::connectTimeout(60)->timeout(120)->retry(3, 2000)->asForm()->post("{$this->baseUrl}/game-feature", [
            'key' => $this->apiKey,
            'sign' => $this->generateSign(),
            'type' => 'services',
            'filter_type' => 'game',
            'filter_value' => $filterValue
        ]);

        return $response->json();
    }

    public function order($serviceCode, $targetId, $targetZone = '', $customTrxId = '')
    {
        return $this->createOrder($serviceCode, $targetId, $targetZone);
    }

    public function createOrder($serviceCode, $targetId, $targetZone = '')
    {
        $payload = [
            'key' => $this->apiKey,
            'sign' => $this->generateSign(),
            'type' => 'order',
            'service' => $serviceCode,
            'data_no' => trim($targetId),
        ];

        if (!empty($targetZone)) {
            $payload['data_zone'] = trim($targetZone);
        }

        $response = Http::connectTimeout(60)->timeout(120)->retry(3, 2000)->asForm()->post("{$this->baseUrl}/game-feature", $payload);

        return $response->json();
    }

    public function checkNickname($gameCode, $target1, $target2 = '')
    {
        $response = Http::asForm()->post("{$this->baseUrl}/game-feature", [
            'key' => $this->apiKey,
            'sign' => $this->generateSign(),
            'type' => 'get-nickname',
            'code' => $gameCode,
            'target' => $target1,
            'additional_target' => $target2
        ]);
        
        return $response->json();
    }

    public function checkOrderStatus($trxId = '')
    {
        $payload = [
            'key' => $this->apiKey,
            'sign' => $this->generateSign(),
            'type' => 'status',
        ];
        if (!empty($trxId)) {
            $payload['trxid'] = trim($trxId);
        } else {
            $payload['limit'] = 50;
        }

        $response = Http::connectTimeout(30)->asForm()->post("{$this->baseUrl}/game-feature", $payload);
        return $response->json();
    }

    public function syncTransaction(\App\Models\Transaction $transaction)
    {
        try {
            // 1. Jika ada provider_trx_id, cek langsung berdasarkan trxid
            if (!empty($transaction->provider_trx_id)) {
                $statusRes = $this->checkOrderStatus($transaction->provider_trx_id);
                if (isset($statusRes['result']) && $statusRes['result'] === true && !empty($statusRes['data'])) {
                    $pData = $statusRes['data'];
                    if (is_array($pData) && isset($pData[0]) && is_array($pData[0])) {
                        $pData = $pData[0];
                    }

                    $sn = !empty($pData['sn']) ? $pData['sn'] : (!empty($pData['note']) ? $pData['note'] : (!empty($pData['info']) ? $pData['info'] : (!empty($pData['informasi']) ? $pData['informasi'] : (!empty($pData['message']) ? $pData['message'] : null))));
                    $pStatus = strtolower($pData['status'] ?? '');

                    $updateData = [];
                    if (!empty($sn)) {
                        $updateData['provider_sn'] = $sn;
                    }
                    if ($pStatus === 'success') {
                        $updateData['status'] = 'success';
                    } elseif ($pStatus === 'error' || $pStatus === 'failed') {
                        $updateData['status'] = 'failed';
                    }

                    if (!empty($updateData)) {
                        $transaction->update($updateData);
                        $transaction->refresh();
                    }
                    return true;
                }
            }

            // 2. Jika provider_trx_id kosong atau provider_sn masih kosong, tarik riwayat order terbaru dari VIP
            $recentRes = $this->checkOrderStatus('');
            if (isset($recentRes['result']) && $recentRes['result'] === true && !empty($recentRes['data']) && is_array($recentRes['data'])) {
                $target1 = trim($transaction->target_field_1 ?? '');
                $productCode = $transaction->gameProduct ? trim($transaction->gameProduct->product_code ?? '') : '';

                foreach ($recentRes['data'] as $item) {
                    $itemTarget = trim($item['data_no'] ?? ($item['target'] ?? ($item['user_id'] ?? '')));
                    $itemService = trim($item['service'] ?? ($item['code'] ?? ''));

                    $match = false;
                    if (!empty($target1) && strcasecmp($itemTarget, $target1) === 0) {
                        $match = true;
                    } elseif (!empty($productCode) && strcasecmp($itemService, $productCode) === 0 && !empty($target1) && str_contains(strtolower($itemTarget), strtolower($target1))) {
                        $match = true;
                    }

                    if ($match) {
                        $sn = !empty($item['sn']) ? $item['sn'] : (!empty($item['note']) ? $item['note'] : (!empty($item['info']) ? $item['info'] : (!empty($item['informasi']) ? $item['informasi'] : (!empty($item['message']) ? $item['message'] : null))));
                        $pStatus = strtolower($item['status'] ?? '');

                        $updateData = [
                            'provider_trx_id' => $item['trxid'] ?? $transaction->provider_trx_id,
                        ];
                        if (!empty($sn)) {
                            $updateData['provider_sn'] = $sn;
                        }
                        if ($pStatus === 'success') {
                            $updateData['status'] = 'success';
                        } elseif ($pStatus === 'error' || $pStatus === 'failed') {
                            $updateData['status'] = 'failed';
                        }

                        $transaction->update($updateData);
                        $transaction->refresh();
                        return true;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('VIP Reseller syncTransaction error: ' . $e->getMessage());
        }

        return false;
    }
}