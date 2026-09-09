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

    public static function extractSnFromData($data)
    {
        if (empty($data)) return null;

        if (is_string($data)) {
            $trimmed = trim($data);
            if (strlen($trimmed) > 3 && !in_array(strtolower($trimmed), ['success', 'processing', 'pending', 'error', 'failed', 'empty'])) {
                return $trimmed;
            }
            return null;
        }

        if (is_array($data)) {
            if (isset($data[0])) {
                return self::extractSnFromData($data[0]);
            }

            foreach (['sn', 'data', 'note', 'info', 'informasi', 'message', 'keterangan', 'catatan', 'serial_number', 'desc', 'link'] as $key) {
                if (!empty($data[$key]) && is_string($data[$key])) {
                    $val = trim($data[$key]);
                    if (!in_array(strtolower($val), ['success', 'processing', 'pending', 'error', 'failed', 'empty', 'available'])) {
                        return $val;
                    }
                }
            }
        }

        return null;
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

                    $sn = self::extractSnFromData($pData);
                    $pStatus = is_array($pData) ? strtolower($pData['status'] ?? '') : '';

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
                    if (!empty($sn)) {
                        return true;
                    }
                }
            }

            // 2. Jika provider_trx_id kosong atau provider_sn masih kosong, tarik riwayat order terbaru dari VIP
            $recentRes = $this->checkOrderStatus('');
            if (isset($recentRes['result']) && $recentRes['result'] === true && !empty($recentRes['data']) && is_array($recentRes['data'])) {
                // Bersihkan target (misal: "ameliacs5758@gmail.com" vs "ameliacs5758@gmail.com -")
                $target1 = strtolower(trim(preg_replace('/[^a-zA-Z0-9@.]/', '', $transaction->target_field_1 ?? '')));
                $productCode = $transaction->gameProduct ? strtolower(trim($transaction->gameProduct->product_code ?? '')) : '';

                foreach ($recentRes['data'] as $item) {
                    $rawItemTarget = $item['data_no'] ?? ($item['target'] ?? ($item['user_id'] ?? ($item['tujuan'] ?? '')));
                    $itemTarget = strtolower(trim(preg_replace('/[^a-zA-Z0-9@.]/', '', $rawItemTarget)));
                    $itemService = strtolower(trim($item['service'] ?? ($item['code'] ?? ($item['layanan'] ?? ''))));
                    $itemTrxId = trim($item['trxid'] ?? ($item['id_trx'] ?? ''));

                    $match = false;
                    if (!empty($target1) && !empty($itemTarget)) {
                        if ($itemTarget === $target1 || str_contains($itemTarget, $target1) || str_contains($target1, $itemTarget)) {
                            $match = true;
                        }
                    }
                    if (!$match && !empty($productCode) && !empty($itemService) && (str_contains($itemService, $productCode) || str_contains($productCode, $itemService))) {
                        $match = true;
                    }

                    if ($match) {
                        $sn = self::extractSnFromData($item);

                        // Jika item tidak menyertakan sn lengkap di list, cek single detail by trxid
                        if (empty($sn) && !empty($itemTrxId)) {
                            $singleRes = $this->checkOrderStatus($itemTrxId);
                            if (isset($singleRes['result']) && $singleRes['result'] === true && !empty($singleRes['data'])) {
                                $sn = self::extractSnFromData($singleRes['data']);
                            }
                        }

                        $pStatus = is_array($item) ? strtolower($item['status'] ?? 'success') : 'success';

                        $updateData = [
                            'provider_trx_id' => $itemTrxId ?: $transaction->provider_trx_id,
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