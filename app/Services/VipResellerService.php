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
            'service' => trim($serviceCode),
            'data_no' => trim($targetId),
        ];

        if (!empty($targetZone)) {
            $payload['data_zone'] = trim($targetZone);
        }

        Log::info('VIP Reseller Order Request:', ['service' => $serviceCode, 'targetId' => $targetId, 'targetZone' => $targetZone]);

        try {
            // 1. Coba endpoint game-feature (Games)
            $response = Http::connectTimeout(60)->timeout(120)->retry(2, 1000)->asForm()->post("{$this->baseUrl}/game-feature", $payload);
            $res = $response->json();

            if (isset($res['result']) && $res['result'] === true) {
                Log::info('VIP Reseller game-feature order success:', ['res' => $res]);
                return $res;
            }

            // 2. Fallback ke endpoint prepaid (Aplikasi Premium, Voucher, Pulsa, & Layanan Umum)
            $responsePrepaid = Http::connectTimeout(60)->timeout(120)->retry(2, 1000)->asForm()->post("{$this->baseUrl}/prepaid", $payload);
            $resPrepaid = $responsePrepaid->json();

            if (isset($resPrepaid['result']) && $resPrepaid['result'] === true) {
                Log::info('VIP Reseller prepaid order success:', ['resPrepaid' => $resPrepaid]);
                return $resPrepaid;
            }

            // Ambil pesan penolakan resmi dari provider
            $msg = $resPrepaid['message'] ?? ($res['message'] ?? 'Gagal membuat pesanan ke provider');
            Log::warning('VIP Reseller Order Rejected:', ['game_msg' => $res['message'] ?? null, 'prepaid_msg' => $resPrepaid['message'] ?? null]);

            return ['result' => false, 'message' => $msg, 'data' => null];
        } catch (\Throwable $e) {
            Log::error('VIP Reseller Order Exception:', ['error' => $e->getMessage()]);
            return ['result' => false, 'message' => $e->getMessage()];
        }
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
        if (empty($trxId)) {
            return ['result' => false, 'message' => 'TRX ID kosong'];
        }

        $payload = [
            'key' => $this->apiKey,
            'sign' => $this->generateSign(),
            'type' => 'status',
            'trxid' => trim($trxId),
        ];

        try {
            // 1. Coba endpoint game-feature
            $response = Http::connectTimeout(30)->asForm()->post("{$this->baseUrl}/game-feature", $payload);
            $res = $response->json();
            if (isset($res['result']) && $res['result'] === true && !empty($res['data'])) {
                return $res;
            }

            // 2. Fallback ke endpoint prepaid
            $responsePrepaid = Http::connectTimeout(30)->asForm()->post("{$this->baseUrl}/prepaid", $payload);
            $resPrepaid = $responsePrepaid->json();
            if (isset($resPrepaid['result']) && $resPrepaid['result'] === true && !empty($resPrepaid['data'])) {
                return $resPrepaid;
            }

            return $res ?: ($resPrepaid ?: ['result' => false]);
        } catch (\Throwable $e) {
            return ['result' => false, 'message' => $e->getMessage()];
        }
    }

    public function getOrderHistory($limit = 50)
    {
        $payload = [
            'key' => $this->apiKey,
            'sign' => $this->generateSign(),
            'type' => 'history',
            'limit' => $limit,
        ];

        try {
            // 1. Coba game-feature
            $response = Http::connectTimeout(30)->asForm()->post("{$this->baseUrl}/game-feature", $payload);
            $res = $response->json();
            if (isset($res['result']) && $res['result'] === true && !empty($res['data'])) {
                return $res;
            }

            // 2. Coba prepaid
            $responsePrepaid = Http::connectTimeout(30)->asForm()->post("{$this->baseUrl}/prepaid", $payload);
            $resPrepaid = $responsePrepaid->json();
            if (isset($resPrepaid['result']) && $resPrepaid['result'] === true && !empty($resPrepaid['data'])) {
                return $resPrepaid;
            }

            return $res ?: ($resPrepaid ?: ['result' => false]);
        } catch (\Throwable $e) {
            return ['result' => false, 'message' => $e->getMessage()];
        }
    }

    public static function isPendingSn($sn)
    {
        if (empty($sn)) return true;
        $trimmed = trim($sn);
        $lower = strtolower($trimmed);

        if (in_array($lower, ['success', 'processing', 'pending', 'error', 'failed', 'empty', 'available', 'none', '-', 'null', 'undefined', 'sukses terkirim', 'sukses', 'terkirim'])) {
            return true;
        }

        if (str_contains($lower, 'pesanan akan diproses') ||
            str_contains($lower, 'cek pesanan anda') ||
            str_contains($lower, 'sedang diproses') ||
            str_contains($lower, 'menunggu') ||
            str_contains($lower, 'dalam antrean') ||
            str_contains($lower, 'antrean proses') ||
            str_contains($lower, 'secepatnya')) {
            return true;
        }

        // Jika hanya berupa format timestamp/tanggal status tanpa kode voucher atau data akun
        // Contoh: "Sukses Terkirim - 2026-09-13 13:07:24 GMT+07:00" atau "2026-09-13 13:07:24"
        if (preg_match('/^(?:sukses\s+terkirim\s*[-–—:]\s*)?\d{4}[-\/]\d{2}[-\/]\d{2}(?:\s+\d{2}:\d{2}(?::\d{2})?(?:\s*gmt[+-]\d{2}(?::\d{2})?)?)?\s*$/i', $trimmed)) {
            return true;
        }

        // Jika hanya berupa email tunggal tanpa newline/password/link (karena ini adalah input target bukan SN/akun)
        if (filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }

    public static function extractSnFromData($data)
    {
        if (empty($data)) return null;

        if (is_string($data)) {
            $trimmed = trim($data);
            if (strlen($trimmed) > 3 && !self::isPendingSn($trimmed)) {
                return $trimmed;
            }
            return null;
        }

        if (is_array($data)) {
            if (isset($data[0])) {
                return self::extractSnFromData($data[0]);
            }

            // Cari pada kunci yang benar-benar memuat serial number, catatan akun, atau link invite
            // Kunci 'data' sengaja DIHAPUS karena pada API VIP Reseller, 'data' adalah nomor HP / email target customer
            foreach (['sn', 'note', 'catatan', 'serial_number', 'token', 'link', 'keterangan', 'message', 'info', 'informasi', 'desc'] as $key) {
                if (!empty($data[$key]) && is_string($data[$key])) {
                    $val = trim($data[$key]);
                    if (!self::isPendingSn($val)) {
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

            // 2. Jika provider_trx_id kosong atau provider_sn belum ada, cari di riwayat order VIP Reseller berdasarkan TARGET EMAIL/NO HP YANG SAMA PERSIS
            $historyRes = $this->getOrderHistory(50);
            if (isset($historyRes['result']) && $historyRes['result'] === true && !empty($historyRes['data']) && is_array($historyRes['data'])) {
                // Bersihkan target (misal: "ameliacs5758@gmail.com" vs "ameliacs5758@gmail.com -")
                $target1 = strtolower(trim(preg_replace('/[^a-zA-Z0-9@.]/', '', $transaction->target_field_1 ?? '')));

                if (!empty($target1)) {
                    foreach ($historyRes['data'] as $item) {
                        $rawItemTarget = $item['data_no'] ?? ($item['target'] ?? ($item['user_id'] ?? ($item['tujuan'] ?? ($item['data'] ?? ''))));
                        $itemTarget = strtolower(trim(preg_replace('/[^a-zA-Z0-9@.]/', '', $rawItemTarget)));
                        $itemTrxId = trim($item['trxid'] ?? ($item['id_trx'] ?? ''));

                        // HANYA COCOKKAN JIKA TARGETNYA BENAR-BENAR SAMA PERSIS DENGAN TRANSAKSI INI
                        if (!empty($itemTarget) && ($itemTarget === $target1 || str_contains($itemTarget, $target1) || str_contains($target1, $itemTarget))) {
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
            }
        } catch (\Throwable $e) {
            Log::warning('VIP Reseller syncTransaction error: ' . $e->getMessage());
        }

        return false;
    }
}